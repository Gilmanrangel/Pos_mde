<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\Purchase;
use App\Models\Sale;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Dashboard Admin - Fokus Inventori & Pembelian
     */
    public function dashboard()
    {
        $today = Carbon::today();

        // Statistik produk & stok
        $totalProduk      = Product::count();
        $totalSupplier    = Supplier::count();
        $stokMinimum      = 10;

        $produkStokMenipis = Product::where('stok', '<=', $stokMinimum)
            ->orderBy('stok')
            ->limit(5)
            ->get();

        $jumlahProdukStokMenipis = Product::where('stok', '<=', $stokMinimum)->count();
        $jumlahProdukStokHabis   = Product::where('stok', '<=', 0)->count();

        // Nilai total persediaan (stok * harga_beli)
        $totalNilaiStok = Product::selectRaw('SUM(stok * harga_beli) as total')
            ->value('total') ?? 0;

        // Ringkasan penjualan & pembelian (tanpa laba)
        $penjualanHariIni = Sale::whereDate('tanggal', $today)->sum('total');
        $pembelianHariIni = Purchase::whereDate('tanggal', $today)->sum('total');

        // Pembelian terbaru
        $pembelianTerakhir = Purchase::with('supplier')
            ->orderBy('tanggal', 'DESC')
            ->limit(5)
            ->get();

        return view('admin.dashboard', [
            'totalProduk'                => $totalProduk,
            'totalSupplier'              => $totalSupplier,
            'stokMinimum'                => $stokMinimum,
            'produkStokMenipis'          => $produkStokMenipis,
            'jumlahProdukStokMenipis'    => $jumlahProdukStokMenipis,
            'jumlahProdukStokHabis'      => $jumlahProdukStokHabis,
            'totalNilaiStok'             => $totalNilaiStok,
            'penjualanHariIni'           => $penjualanHariIni,
            'pembelianHariIni'           => $pembelianHariIni,
            'pembelianTerakhir'          => $pembelianTerakhir,
        ]);
    }
}
