<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * LIST PRODUK + SEARCH + FILTER + PAGINATION
     */
public function index(Request $request)
{
    $query = Product::query();

    // 🔍 SEARCH
    if ($request->search) {
        $search = strtolower($request->search);

        $query->where(function ($q) use ($search) {
            $q->whereRaw("LOWER(nama) LIKE ?", ["%$search%"])
              ->orWhereRaw("LOWER(kode_barang) LIKE ?", ["%$search%"]);
        });
    }

    // 📊 FILTER STOK
    if ($request->stok == 'habis') {
        $query->where('stok', 0);
    } elseif ($request->stok == 'tersedia') {
        $query->where('stok', '>', 0);
    }

    // 💰 FILTER HARGA
    if ($request->min_harga) {
        $query->where('harga_jual', '>=', $request->min_harga);
    }

    if ($request->max_harga) {
        $query->where('harga_jual', '<=', $request->max_harga);
    }

    // 🔃 SORTING
    if ($request->sort_by) {
        $query->orderBy($request->sort_by, $request->sort_dir ?? 'asc');
    } else {
        $query->orderBy('nama');
    }

    // 📄 PAGINATION
    $products = $query->paginate(10)->withQueryString();

    // 🔥 INI BAGIAN PENTING (AJAX DETECT)
   if ($request->ajax()) {
    return view('admin.produk.index', compact('products'))->render();
}

    return view('admin.produk.index', compact('products'));
}

    /**
     * 🔥 AJAX SEARCH (REALTIME) — FIX TOTAL
     */
    public function ajaxSearch(Request $request)
    {
        $search = strtolower(trim($request->search));

        if (!$search) {
            return response()->json([]);
        }

        $products = Product::where(function ($q) use ($search) {
            $q->whereRaw("LOWER(nama) LIKE ?", ["%$search%"])
              ->orWhereRaw("LOWER(kode_barang) LIKE ?", ["%$search%"]);
        })
        ->orderBy('nama') // 🔥 biar urut
        ->limit(10)
        ->get();

        return response()->json($products);
    }

    /**
     * FORM TAMBAH PRODUK
     */
    public function create()
    {
        return view('admin.produk.create');
    }

    /**
     * SIMPAN PRODUK
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|unique:products',
            'nama'        => 'required',
            'satuan'      => 'required',
            'harga_beli'  => 'required|numeric',
            'harga_jual'  => 'required|numeric',
            'stok'        => 'required|integer',
        ]);

        Product::create($validated);

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * FORM EDIT PRODUK
     */
    public function edit(Product $produk)
    {
        return view('admin.produk.edit', compact('produk'));
    }

    /**
     * UPDATE PRODUK
     */
    public function update(Request $request, Product $produk)
    {
        $validated = $request->validate([
            'nama'        => 'required',
            'satuan'      => 'required',
            'harga_beli'  => 'required|numeric',
            'harga_jual'  => 'required|numeric',
            'stok'        => 'required|integer',
        ]);

        $produk->update($validated);

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * HAPUS PRODUK
     */
    public function destroy(Product $produk)
    {
        $produk->delete();

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    /* ============================================================
     |  EXPORT PRODUK KE CSV
     |============================================================ */

    public function export()
    {
        $fileName = 'produk_export_' . date('Ymd_His') . '.csv';

        $products = Product::all();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'kode_barang', 'nama', 'satuan',
                'harga_beli', 'harga_jual', 'stok'
            ]);

            foreach ($products as $p) {
                fputcsv($file, [
                    $p->kode_barang,
                    $p->nama,
                    $p->satuan,
                    $p->harga_beli,
                    $p->harga_jual,
                    $p->stok
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /* ============================================================
     |  IMPORT PRODUK DARI CSV
     |============================================================ */

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);

        $file = fopen($request->file('file'), 'r');

        $firstRow = true;

        while (($data = fgetcsv($file, 1000, ',')) !== false) {

            if ($firstRow) {
                $firstRow = false;
                continue;
            }

            if (count($data) < 6) {
                continue;
            }

            Product::updateOrCreate(
                ['kode_barang' => $data[0]],
                [
                    'nama'        => $data[1],
                    'satuan'      => $data[2],
                    'harga_beli'  => $data[3],
                    'harga_jual'  => $data[4],
                    'stok'        => $data[5],
                ]
            );
        }

        fclose($file);

        return redirect()->route('produk.index')
            ->with('success', 'Import produk berhasil.');
    }
}