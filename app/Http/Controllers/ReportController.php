<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    private function authorizeReport()
    {
        if (!in_array(Auth::user()->role, ['admin', 'owner'])) {
            abort(403, 'Anda tidak punya akses ke laporan.');
        }
    }


    /*
    |--------------------------------------------------------------------------
    | ======================= ADMIN =======================
    | DASHBOARD LAPORAN ADMIN
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $this->authorizeReport();

        $today = Sale::whereDate('tanggal', Carbon::today())->sum('total');
        $thisMonth = Sale::whereMonth('tanggal', Carbon::now()->month)
                        ->whereYear('tanggal', Carbon::now()->year)
                        ->sum('total');
        $thisYear = Sale::whereYear('tanggal', Carbon::now()->year)->sum('total');

        $totalPembelian = Purchase::sum('total');

        return view('admin.laporan.index', compact(
            'today',
            'thisMonth',
            'thisYear',
            'totalPembelian'
        ));
    }



    /*
    |--------------------------------------------------------------------------
    | LAPORAN PENJUALAN ADMIN — PREMIUM
    |--------------------------------------------------------------------------
    */
  public function penjualan(Request $request)
{
    $query = \App\Models\Sale::with('user')->latest();

    // ================= FILTER =================
    if ($request->start_date) {
        $query->whereDate('tanggal', '>=', $request->start_date);
    }

    if ($request->end_date) {
        $query->whereDate('tanggal', '<=', $request->end_date);
    }

    if ($request->kasir) {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->kasir . '%');
        });
    }

    if ($request->id_struk) {
        $query->where('id', $request->id_struk);
    }

    // ================= DATA =================
    $sales = $query->paginate(10)->withQueryString();

    // ================= STATS =================
    $totalPenjualan = (clone $query)->sum('total');
    $jumlahTransaksi = (clone $query)->count();
    $rataRata = $jumlahTransaksi ? $totalPenjualan / $jumlahTransaksi : 0;
    $tertinggi = (clone $query)->max('total');

    // ================= CHART =================
    $chartData = \App\Models\Sale::query()
        ->selectRaw('DATE(tanggal) as date, SUM(total) as total')
        ->groupBy('date')
        ->orderBy('date', 'asc')
        ->get();

    // ================= TOP KASIR =================
    $topKasir = \App\Models\Sale::selectRaw('user_id, COUNT(*) as transaksi, SUM(total) as total')
        ->with('user')
        ->groupBy('user_id')
        ->orderByDesc('total')
        ->limit(5)
        ->get();

    return view('admin.laporan.penjualan', compact(
        'sales',
        'chartData',
        'totalPenjualan',
        'jumlahTransaksi',
        'rataRata',
        'tertinggi',
        'topKasir'
    ));
}


    /*
    |--------------------------------------------------------------------------
    | LAPORAN PEMBELIAN ADMIN
    |--------------------------------------------------------------------------
    */
    public function pembelian()
    {
        $this->authorizeReport();

        $purchases = Purchase::with('supplier')->latest()->paginate(20);
        return view('admin.laporan.pembelian', compact('purchases'));
    }


    /*
    |--------------------------------------------------------------------------
    | LAPORAN STOK MINIMUM ADMIN
    |--------------------------------------------------------------------------
    */
    public function stok()
    {
        $this->authorizeReport();

        $minimum = 10;
        $products = Product::where('stok', '<=', $minimum)->get();

        return view('admin.laporan.stok', compact('products', 'minimum'));
    }


    /*
    |--------------------------------------------------------------------------
    | LABA RUGI ADMIN
    |--------------------------------------------------------------------------
    */
        public function labaRugi()
        {
            $this->authorizeReport();

            // Total Pendapatan Penjualan
            $totalPenjualan = Sale::sum('total');

            // Total modal / pembelian (HPP)
            $totalPembelian = PurchaseDetail::sum('subtotal');

            // Laba bersih
            $laba = $totalPenjualan - $totalPembelian;

            return view('admin.laporan.laba_rugi', [
                'totalPenjualan' => $totalPenjualan,
                'totalPembelian' => $totalPembelian, // ← WAJIB ADA, FIX ERROR
                'laba'           => $laba,
            ]);
        }



    /*
    |--------------------------------------------------------------------------
    | ======================= OWNER PREMIUM =======================
    | LAPORAN PENJUALAN OWNER (PREMIUM)
    |--------------------------------------------------------------------------
    */
    public function ownerPenjualan(Request $request)
    {
        $this->authorizeReport();

        $start = $request->start_date;
        $end   = $request->end_date;
        $q     = $request->q;

        $query = Sale::with('user')->withCount('details');

        if ($start && $end) {
            $query->whereBetween('tanggal', [$start, $end]);
        }

        if ($q) {
            $query->where(function ($x) use ($q) {
                $x->where('id', 'like', "%$q%")
                    ->orWhereHas('user', fn($u) =>
                        $u->where('name', 'like', "%$q%")
                    );
            });
        }

        $all = (clone $query)->get();

        $totalPenjualan  = $all->sum('total');
        $jumlahTransaksi = $all->count();
        $rataRata        = $jumlahTransaksi > 0 ? $totalPenjualan / $jumlahTransaksi : 0;
        $transaksiTertinggi = $all->max('total');

        $sales = $query->orderBy('tanggal', 'DESC')
            ->paginate(20)
            ->appends($request->query());

        return view('owner.laporan', compact(
            'sales', 'totalPenjualan', 'jumlahTransaksi',
            'rataRata', 'transaksiTertinggi',
            'start', 'end', 'q'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | EXPORT EXCEL OWNER (CSV)
    |--------------------------------------------------------------------------
    */
    public function exportOwnerPenjualanExcel(Request $request)
    {
        $this->authorizeReport();

        $query = Sale::with('user');

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('tanggal', [
                $request->start_date,
                $request->end_date
            ]);
        }

        if ($request->q) {
            $q = $request->q;
            $query->where(function ($x) use ($q) {
                $x->where('id', 'like', "%$q%")
                    ->orWhereHas('user', fn($u) =>
                        $u->where('name', 'like', "%$q%")
                    );
            });
        }

        $sales = $query->get();

        $filename = "laporan_owner_" . now()->format("Ymd_His") . ".csv";

        $callback = function () use ($sales) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Tanggal', 'ID', 'Kasir', 'Total'], ';');

            foreach ($sales as $sale) {
                fputcsv($handle, [
                    $sale->tanggal,
                    $sale->id,
                    $sale->user->name ?? '-',
                    $sale->total
                ], ';');
            }
            fclose($handle);
        };

        return response()->streamDownload($callback, $filename, [
            "Content-Type" => "text/csv"
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | PRINT OWNER
    |--------------------------------------------------------------------------
    */
    public function printOwnerPenjualan()
    {
        $this->authorizeReport();

        $sales = Sale::with('user')->get();

        return view('owner.laporan_print', [
            'sales' => $sales,
            'totalPenjualan' => $sales->sum('total'),
            'jumlahTransaksi' => $sales->count()
        ]);
    }
}
