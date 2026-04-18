@extends('layouts.app')

@section('title', 'Dashboard Owner')

@section('content')
<div class="container-fluid py-3">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Dashboard Owner</h2>
            <p class="text-muted mb-0">Ringkasan penjualan toko secara real-time.</p>
        </div>
        <div class="text-end">
            <span class="badge bg-primary-subtle text-primary px-3 py-2">
                {{ now()->format('d M Y') }}
            </span>
        </div>
    </div>

    @php
        $total7hari   = $omzet_mingguan->sum('omzet');
        $avgHariIni   = $transaksi_hari_ini > 0 ? $pendapatan_hari_ini / $transaksi_hari_ini : 0;
        $topProduct   = $barang_terlaris->first();
    @endphp

    {{-- STATISTIK BARIS 1: FOKUS HARI INI --}}
    <div class="row g-3 mb-3">

        {{-- Pendapatan Hari Ini --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted small">Pendapatan Hari Ini</span>
                        <span class="badge bg-success-subtle text-success">Rp</span>
                    </div>
                    <h3 class="fw-bold mb-1">
                        Rp {{ number_format($pendapatan_hari_ini, 0, ',', '.') }}
                    </h3>
                    <p class="text-muted small mb-0">
                        Total omzet dari semua transaksi yang tercatat hari ini.
                    </p>
                </div>
            </div>
        </div>

        {{-- Total Transaksi Hari Ini --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted small">Total Transaksi Hari Ini</span>
                        <span class="badge bg-info-subtle text-info">
                            <i class="bi bi-receipt"></i>
                        </span>
                    </div>
                    <h3 class="fw-bold mb-1">{{ $transaksi_hari_ini }}</h3>
                    <p class="text-muted small mb-0">
                        Jumlah struk penjualan yang tercatat pada tanggal {{ now()->format('d M Y') }}.
                    </p>
                </div>
            </div>
        </div>

        {{-- Rata-rata Transaksi Hari Ini --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted small">Rata-rata Nilai Transaksi</span>
                        <span class="badge bg-warning-subtle text-warning">Avg</span>
                    </div>
                    <h3 class="fw-bold mb-1">
                        Rp {{ number_format($avgHariIni, 0, ',', '.') }}
                    </h3>
                    <p class="text-muted small mb-0">
                        Pendapatan per transaksi untuk hari ini.
                    </p>
                </div>
            </div>
        </div>

    </div>

    {{-- STATISTIK BARIS 2: FOKUS 7 HARI TERAKHIR --}}
    <div class="row g-3 mb-4">

        {{-- Total Omzet 7 Hari --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted small">Total Omzet 7 Hari Terakhir</span>
                        <span class="badge bg-primary-subtle text-primary">7D</span>
                    </div>
                    <h4 class="fw-bold mb-1">
                        Rp {{ number_format($total7hari, 0, ',', '.') }}
                    </h4>
                    <p class="text-muted small mb-0">
                        Akumulasi omzet dari grafik penjualan mingguan.
                    </p>
                </div>
            </div>
        </div>

        {{-- Produk Terlaris 7 Hari --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted small">Produk Terlaris (7 Hari)</span>
                        <span class="badge bg-danger-subtle text-danger">
                            <i class="bi bi-star-fill"></i>
                        </span>
                    </div>

                    @if($topProduct)
                        <h6 class="fw-bold mb-1">{{ $topProduct->product->nama }}</h6>
                        <p class="text-muted small mb-0">
                            Terjual {{ $topProduct->total_qty }} unit dalam 7 hari terakhir.
                        </p>
                    @else
                        <p class="text-muted small mb-0">Belum ada data penjualan minggu ini.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Info Singkat Sistem --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted small">Status Sistem</span>
                        <span class="badge bg-secondary-subtle text-secondary">
                            <i class="bi bi-info-circle"></i>
                        </span>
                    </div>
                    <p class="fw-semibold mb-1">Semua modul berjalan normal.</p>
                    <p class="text-muted small mb-0">
                        Gunakan menu <strong>Laporan Penjualan</strong> atau <strong>Laba Rugi</strong>
                        untuk analisis detail berdasarkan periode tertentu.
                    </p>
                </div>
            </div>
        </div>

    </div>

    {{-- GRAFIK + TOP PRODUK --}}
    <div class="row g-3">

        {{-- GRAFIK PENJUALAN MINGGUAN --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Grafik Penjualan Mingguan</h5>
                    <span class="text-muted small">7 hari terakhir</span>
                </div>
                <div class="card-body">
                    <canvas id="chartOmzet" height="120"></canvas>

                    @if($omzet_mingguan->isEmpty())
                        <p class="text-muted small mt-3 mb-0">
                            Belum ada data penjualan dalam 7 hari terakhir.
                        </p>
                    @endif
                </div>
            </div>
        </div>

        {{-- TOP 5 PRODUK TERLARIS --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Top 5 Produk Terlaris</h5>
                    <span class="text-muted small">7 hari terakhir</span>
                </div>
                <div class="card-body p-0">
                    @if($barang_terlaris->isEmpty())
                        <p class="text-muted small p-3 mb-0">
                            Belum ada data penjualan.
                        </p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produk</th>
                                        <th class="text-end">Qty</th>
                                        <th class="text-end">Omzet (Rp)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($barang_terlaris as $item)
                                        @php
                                            $nama      = $item->product->nama ?? '-';
                                            $hargaJual = $item->product->harga_jual ?? 0;
                                            $omzet     = $hargaJual * $item->total_qty;
                                        @endphp
                                        <tr>
                                            <td>{{ $nama }}</td>
                                            <td class="text-end">{{ $item->total_qty }}</td>
                                            <td class="text-end">
                                                {{ number_format($omzet, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@section('scripts')
{{-- Chart.js (CDNJS, aman dari blokir tracking Edge) --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const labels    = {!! json_encode($omzet_mingguan->pluck('label')) !!};
    const dataOmzet = {!! json_encode($omzet_mingguan->pluck('omzet')) !!};

    if (!labels.length) return;

    const ctx = document.getElementById('chartOmzet');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Omzet (Rp)',
                data: dataOmzet,
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13,110,253,0.15)',
                borderWidth: 3,
                fill: true,
                tension: 0.35,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: true },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            const v = ctx.parsed.y || 0;
                            return 'Omzet: Rp ' + new Intl.NumberFormat('id-ID').format(v);
                        }
                    }
                }
            },
            scales: {
                y: {
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection
