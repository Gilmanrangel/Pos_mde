<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PurchaseController extends Controller
{
    // ================= INDEX =================
    public function index(Request $request)
    {
        $query = Purchase::with('supplier', 'user')->latest();

        // 🔍 SEARCH TEXT
        if ($request->search) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->whereHas('supplier', function ($q2) use ($search) {
                    $q2->where('nama', 'like', "%$search%");
                })
                ->orWhereHas('user', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%$search%");
                });
            });
        }

        // 📅 FILTER TANGGAL (FIX FORMAT)
        if ($request->startDate && $request->endDate) {

            $start = Carbon::parse($request->startDate)->format('Y-m-d');
            $end   = Carbon::parse($request->endDate)->format('Y-m-d');

            $query->whereBetween('tanggal', [$start, $end]);
        }

        $purchases = $query->paginate(10)->withQueryString();

        return view('pembelian.index', compact('purchases'));
    }


    // ================= AJAX SEARCH =================
   public function search(Request $request)
{
    $query = Purchase::with('supplier', 'user')->latest();

    // 🔍 keyword
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->whereHas('supplier', function ($q2) use ($search) {
                $q2->where('nama', 'like', "%$search%");
            })
            ->orWhereHas('user', function ($q2) use ($search) {
                $q2->where('name', 'like', "%$search%");
            });
        });
    }

    // 📅 tanggal (LANGSUNG PAKAI VALUE DARI INPUT DATE)
    if ($request->filled('startDate') && $request->filled('endDate')) {
        $query->whereDate('tanggal', '>=', $request->startDate)
              ->whereDate('tanggal', '<=', $request->endDate);
    }

    $purchases = $query->limit(10)->get();

    return response()->json($purchases);
}


    // ================= CREATE =================
    public function create()
    {
        $suppliers = Supplier::all();
        $products  = Product::all();

        return view('pembelian.create', compact('suppliers', 'products'));
    }


    // ================= STORE =================
   public function store(Request $request)
{
    DB::beginTransaction();

    try {

        // 🔥 VALIDASI MINIMAL
        if (!$request->produk_id || count($request->produk_id) == 0) {
            return back()->with('error', 'Tidak ada produk dipilih!');
        }

        // 🔥 SIMPAN HEADER
        $purchase = Purchase::create([
            'supplier_id' => $request->supplier_id,
            'user_id'     => Auth::id(),
            'tanggal'     => now(),
            'total'       => 0,
        ]);

        $total = 0;

        $produk_ids = $request->produk_id;
        $qtys       = $request->qty;
        $hargas     = $request->harga;

        // 🔥 LOOP DATA DARI FORM
        for ($i = 0; $i < count($produk_ids); $i++) {

            // 🔥 NORMALISASI HARGA (hapus titik)
            $harga = str_replace('.', '', $hargas[$i]);

            $qty = (int) $qtys[$i];

            if ($qty <= 0) continue;

            $subtotal = $qty * $harga;

            // 🔥 SIMPAN DETAIL
            PurchaseDetail::create([
                'purchase_id' => $purchase->id,
                'product_id'  => $produk_ids[$i],
                'qty'         => $qty,
                'harga'       => $harga,
                'subtotal'    => $subtotal,
            ]);

            // 🔥 UPDATE STOK
            $product = Product::find($produk_ids[$i]);
            if ($product) {
                $product->stok += $qty;
                $product->save();
            }

            $total += $subtotal;
        }

        // 🔥 UPDATE TOTAL
        $purchase->update(['total' => $total]);

        DB::commit();

        return redirect()->route('pembelian.index')
            ->with('success', 'Pembelian berhasil disimpan.');

    } catch (\Exception $e) {

        DB::rollBack();

        // 🔥 DEBUG BIAR KELIHATAN ERROR NYA
        dd($e->getMessage());
    }
}


    // ================= SHOW =================
    public function show($id)
    {
        $purchase = Purchase::with('supplier', 'details.product', 'user')
            ->findOrFail($id);

        return view('pembelian.show', compact('purchase'));
    }


    // ================= PRINT =================
    public function print($id)
    {
        $purchase = Purchase::with('supplier', 'details.product', 'user')
            ->findOrFail($id);

        return view('pembelian.print', compact('purchase'));
    }
}