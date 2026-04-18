@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="container-fluid py-4 fade-in">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-semibold mb-1">Laporan Penjualan</h5>
            <small class="text-muted">Ringkasan & analisis transaksi</small>
        </div>

        <a href="{{ route('dashboard.admin') }}" class="btn btn-soft">
            ← Kembali
        </a>
    </div>

    {{-- FILTER --}}
    <form method="GET" class="d-flex flex-wrap gap-2 mb-4">
        <input type="date" name="start_date" class="form-control" style="max-width:150px;">
        <input type="date" name="end_date" class="form-control" style="max-width:150px;">
        <input type="text" name="kasir" placeholder="Kasir" class="form-control" style="max-width:140px;">
        <input type="text" name="id_struk" placeholder="ID" class="form-control" style="max-width:90px;">

        <button class="btn-modern">Filter</button>
        <a href="{{ route('laporan.penjualan') }}" class="btn-soft">Reset</a>
    </form>

    {{-- STAT --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card p-3">
                <small class="text-muted">Total Penjualan</small>
                <h6 class="fw-semibold mb-0">Rp {{ number_format($totalPenjualan,0,',','.') }}</h6>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3">
                <small class="text-muted">Transaksi</small>
                <h6 class="fw-semibold mb-0">{{ $jumlahTransaksi }}</h6>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3">
                <small class="text-muted">Rata-rata</small>
                <h6 class="fw-semibold mb-0">Rp {{ number_format($rataRata,0,',','.') }}</h6>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3">
                <small class="text-muted">Tertinggi</small>
                <h6 class="fw-semibold text-success mb-0">Rp {{ number_format($tertinggi,0,',','.') }}</h6>
            </div>
        </div>
    </div>

    {{-- GRAFIK --}}
    <div class="card mb-4 p-3">
        <small class="text-muted mb-2">Tren Penjualan</small>
        <div style="height:260px;">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    {{-- TOP KASIR --}}
    <div class="card p-3 mb-4">
        <div class="d-flex justify-content-between mb-3">
            <h6 class="fw-semibold mb-0">Top Kasir</h6>
            <small class="text-muted">Berdasarkan omzet</small>
        </div>

        @forelse($topKasir as $i => $k)
            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                <div>
                    <span class="badge bg-light text-dark">#{{ $i+1 }}</span>
                    <span class="fw-semibold ms-2">{{ $k->user->name ?? '-' }}</span>
                    <br>
                    <small class="text-muted">{{ $k->transaksi }} transaksi</small>
                </div>

                <div class="fw-semibold">
                    Rp {{ number_format($k->total,0,',','.') }}
                </div>
            </div>
        @empty
            <div class="text-center text-muted">Belum ada data</div>
        @endforelse
    </div>

    {{-- TABLE --}}
    <div class="card p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="text-muted small">
                    <tr>
                        <th>Tanggal</th>
                        <th>ID</th>
                        <th>Kasir</th>
                        <th class="text-end">Total</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($sales as $sale)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($sale->tanggal)->format('d M Y H:i') }}</td>
                            <td>#{{ $sale->id }}</td>
                            <td>{{ $sale->user->name ?? '-' }}</td>
                            <td class="text-end fw-semibold">
                                Rp {{ number_format($sale->total,0,',','.') }}
                            </td>
                            <td class="text-end">
                                <a href="{{ route('kasir.struk', $sale->id) }}" class="btn-soft-primary btn-sm">
                                    Struk
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                Tidak ada data
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3">
            {{ $sales->links() }}
        </div>
    </div>

</div>

{{-- CHART --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const chartData = @json($chartData);

    new Chart(document.getElementById('salesChart'), {
        type: 'line',
        data: {
            labels: chartData.map(d => d.date),
            datasets: [{
                data: chartData.map(d => d.total),
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79,70,229,0.08)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        }
    });
</script>

@endsection