<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD OWNER
    |--------------------------------------------------------------------------
    */
    public function dashboard()
    {
        $today = Carbon::today();

        // Pendapatan hari ini
        $pendapatan_hari_ini = Sale::whereDate('tanggal', $today)->sum('total');

        // Total transaksi hari ini
        $transaksi_hari_ini = Sale::whereDate('tanggal', $today)->count();

        // Barang terlaris
        $barang_terlaris = SaleDetail::with('product')
            ->selectRaw('product_id, SUM(qty) as total_qty')
            ->whereHas('sale', function ($q) {
                $q->where('tanggal', '>=', Carbon::now()->subDays(7));
            })
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // Omzet mingguan (tanpa chart.js)
        $raw = Sale::selectRaw('DATE(tanggal) as tgl, SUM(total) as omzet')
            ->where('tanggal', '>=', Carbon::now()->subDays(7))
            ->groupBy('tgl')
            ->orderBy('tgl')
            ->get();

        $omzet_mingguan = $raw->map(fn($row) => [
            'label' => Carbon::parse($row->tgl)->format('d M'),
            'omzet' => (int) $row->omzet
        ]);

        return view('owner.dashboard', compact(
            'pendapatan_hari_ini',
            'transaksi_hari_ini',
            'barang_terlaris',
            'omzet_mingguan'
        ));
    }



    /*
    |--------------------------------------------------------------------------
    | LAPORAN PENJUALAN OWNER (FINAL)
    |--------------------------------------------------------------------------
    */
    public function laporan(Request $request)
    {
        $start = $request->start_date ?? null;
        $end   = $request->end_date ?? null;
        $q     = $request->q ?? '';

        $query = Sale::with('user')
            ->orderBy('tanggal', 'DESC');

        // Filter tanggal
        if ($start && $end) {
            $query->whereBetween('tanggal', [$start, $end]);
        }

        // Search
        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('id', 'LIKE', "%$q%")
                    ->orWhereHas('user', fn($u) =>
                        $u->where('name', 'LIKE', "%$q%")
                    );
            });
        }

        $sales = $query->paginate(15);

        // Statistik
        $totalPenjualan     = $query->sum('total');
        $jumlahTransaksi    = $query->count();
        $rataRata           = $jumlahTransaksi > 0 ? round($totalPenjualan / $jumlahTransaksi) : 0;
        $transaksiTertinggi = $query->max('total') ?? 0;

        return view('owner.laporan', [
            'sales'               => $sales,
            'start'               => $start,
            'end'                 => $end,
            'q'                   => $q,
            'totalPenjualan'      => $totalPenjualan,
            'jumlahTransaksi'     => $jumlahTransaksi,
            'rataRata'            => $rataRata,
            'transaksiTertinggi'  => $transaksiTertinggi,
        ]);
    }



    /*
    |--------------------------------------------------------------------------
    | LABA RUGI OWNER (SUDAH FIX)
    |--------------------------------------------------------------------------
    */
    public function labaRugi(Request $request)
    {
        // tanggal filter
        $start = $request->start_date
            ? Carbon::parse($request->start_date)
            : Carbon::now()->startOfMonth();

        $end = $request->end_date
            ? Carbon::parse($request->end_date)
            : Carbon::now();

        $details = SaleDetail::with('product')
            ->whereHas('sale', fn($q) =>
                $q->whereBetween('tanggal', [$start, $end])
            )
            ->get();

        // Hitungan total
        $total_modal = $details->sum(fn($d) => $d->qty * $d->product->harga_beli);
        $total_penjualan = $details->sum(fn($d) => $d->qty * $d->product->harga_jual);
        $laba_bersih = $total_penjualan - $total_modal;

        $margin = $total_penjualan > 0
            ? ($laba_bersih / $total_penjualan) * 100
            : 0;

        $jumlah_transaksi = $details->count();
        $laba_rata = $jumlah_transaksi > 0
            ? $laba_bersih / $jumlah_transaksi
            : 0;

        // Detail produk
        $produk_detail = [];

        foreach ($details as $d) {
            $produk_detail[] = [
                'nama'        => $d->product->nama,
                'qty'         => $d->qty,
                'harga_beli'  => $d->product->harga_beli,
                'harga_jual'  => $d->product->harga_jual,
                'subtotal'    => $d->qty * $d->product->harga_jual,
                'modal'       => $d->qty * $d->product->harga_beli,
                'laba'        => ($d->qty * $d->product->harga_jual) - ($d->qty * $d->product->harga_beli),
            ];
        }

        return view('owner.laba_rugi', [
            'start'            => $start->format('Y-m-d'),
            'end'              => $end->format('Y-m-d'),
            'total_penjualan'  => $total_penjualan,
            'total_modal'      => $total_modal,
            'laba_bersih'      => $laba_bersih,
            'margin'           => $margin,
            'laba_rata'        => $laba_rata,
            'produk_detail'    => $produk_detail,
        ]);
    }
}
