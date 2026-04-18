<?php

namespace App\Http\Controllers;

use App\Events\StockUpdated;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    // Halaman utama transaksi kasir
    public function create()
    {
        return view('kasir.transaksi');
    }

    // AJAX: cari produk berdasarkan kode_barang
    public function getProductByKode($kode)
    {
        $product = Product::where('kode_barang', $kode)->first();

        if (! $product) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        return response()
            ->json([
                'id'          => $product->id,
                'kode_barang' => $product->kode_barang,
                'nama'        => $product->nama,
                'harga_jual'  => $product->harga_jual,
                'stok'        => $product->stok,
            ])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    // Simpan transaksi
    public function store(Request $request)
    {
        // Validasi dasar
        $request->validate([
            'items' => 'required|string',
            'bayar' => 'required|numeric|min:0',
        ]);

        $items = json_decode($request->input('items', '[]'), true);

        if (! is_array($items) || count($items) === 0) {
            return back()
                ->with('error', 'Keranjang kosong. Tidak ada barang yang dijual.')
                ->withInput();
        }

        // Normalisasi bayar: "10.000" -> 10000 (defense-in-depth)
        $bayarRaw = (string) $request->input('bayar', '0');
        $bayar    = (int) preg_replace('/[^0-9]/', '', $bayarRaw);

        try {
            $sale = DB::transaction(function () use ($items, $bayar) {

                $productIds = collect($items)
                    ->pluck('product_id')
                    ->filter()
                    ->unique()
                    ->values();

                if ($productIds->isEmpty()) {
                    throw new \Exception('Tidak ada produk valid di keranjang.');
                }

                // Lock row produk (aman untuk 2 kasir)
                $products = Product::whereIn('id', $productIds)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                $totalServer = 0;

                // Validasi stok + hitung total di server
                foreach ($items as $item) {
                    if (! isset($item['product_id'], $item['qty'])) {
                        throw new \Exception('Data item tidak lengkap.');
                    }

                    $productId = (int) $item['product_id'];
                    $qty       = (int) $item['qty'];

                    if (! isset($products[$productId])) {
                        throw new \Exception("Produk dengan ID {$productId} tidak ditemukan.");
                    }

                    $product = $products[$productId];

                    if ($qty <= 0) {
                        throw new \Exception("Qty untuk produk {$product->nama} tidak valid.");
                    }

                    if ($product->stok < $qty) {
                        throw new \Exception(
                            "Stok produk '{$product->nama}' tidak cukup. " .
                            "Tersedia: {$product->stok}, diminta: {$qty}."
                        );
                    }

                    $hargaJual = (int) $product->harga_jual;
                    $subtotal  = $hargaJual * $qty;
                    $totalServer += $subtotal;
                }

                if ($bayar < $totalServer) {
                    throw new \Exception('Pembayaran kurang dari total transaksi.');
                }

                // Simpan header penjualan
                $sale = Sale::create([
                    'user_id'   => Auth::id(),
                    'tanggal'   => now(),
                    'total'     => $totalServer,
                    'bayar'     => $bayar,
                    'kembalian' => $bayar - $totalServer,
                ]);

                // Simpan detail + update stok + broadcast realtime
                foreach ($items as $item) {
                    $product   = $products[(int) $item['product_id']];
                    $qty       = (int) $item['qty'];
                    $hargaJual = (int) $product->harga_jual;
                    $subtotal  = $hargaJual * $qty;

                    SaleDetail::create([
                        'sale_id'    => $sale->id,
                        'product_id' => $product->id,
                        'qty'        => $qty,
                        'harga_jual' => $hargaJual,
                        'subtotal'   => $subtotal,
                    ]);

                    // ✅ Kurangi stok (atomic)
                    Product::where('id', $product->id)->decrement('stok', $qty);

                    // ✅ Ambil stok terbaru
                    $latestStok = (int) Product::where('id', $product->id)->value('stok');

                    // ✅ Broadcast realtime ke admin
                    broadcast(new StockUpdated($product->id, $latestStok));
                }

                return $sale;
            }, 3);

        } catch (\Throwable $e) {
            return back()
                ->with('error', $e->getMessage())
                ->withInput();
        }

        return redirect()
    ->route('kasir.transaksi')
    ->with('success', 'Transaksi berhasil disimpan.');
    }

    // History transaksi per kasir
    public function history()
    {
        $sales = Sale::where('user_id', Auth::id())
            ->orderBy('id', 'DESC')
            ->get();

        return view('kasir.history', compact('sales'));
    }

    // Struk
    public function struk($id)
    {
        $sale = Sale::with('details.product')->findOrFail($id);

        return view('kasir.struk', compact('sale'));
    }
}