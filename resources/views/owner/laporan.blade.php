@extends('layouts.app')

@section('title', 'Laporan Penjualan Owner')

@section('content')
<div class="container-fluid py-3">

    <h2 class="fw-bold mb-3">Laporan Penjualan (Owner)</h2>

    {{-- FILTER --}}
    <form method="GET" class="card shadow-sm border-0 mb-3">
        <div class="card-body row g-3 align-items-end">

            <div class="col-md-3">
                <label class="form-label small text-muted">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control"
                       value="{{ $start }}">
            </div>

            <div class="col-md-3">
                <label class="form-label small text-muted">Tanggal Akhir</label>
                <input type="date" name="end_date" class="form-control"
                       value="{{ $end }}">
            </div>

            <div class="col-md-3">
                <label class="form-label small text-muted">Cari (ID Struk / Kasir)</label>
                <input type="text" name="q" class="form-control"
                       placeholder="Nama kasir atau ID"
                       value="{{ $q }}">
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary flex-fill">Filter</button>
                <a href="{{ route('owner.laporan') }}" class="btn btn-secondary">Reset</a>
            </div>

        </div>
    </form>

    {{-- STATISTIK --}}
    <div class="row g-3 mb-3">

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <p class="text-muted small mb-1">Total Omzet</p>
                    <h4 class="fw-bold mb-0">Rp {{ number_format($totalPenjualan,0,',','.') }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <p class="text-muted small mb-1">Jumlah Transaksi</p>
                    <h4 class="fw-bold mb-0">{{ $jumlahTransaksi }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <p class="text-muted small mb-1">Rata-rata / Transaksi</p>
                    <h4 class="fw-bold mb-0">Rp {{ number_format($rataRata,0,',','.') }}</h4>
                    <p class="text-muted small m-0">
                        Transaksi tertinggi:
                        <strong>Rp {{ number_format($transaksiTertinggi,0,',','.') }}</strong>
                    </p>
                </div>
            </div>
        </div>

    </div>

    {{-- TABEL --}}
    <div class="card shadow-sm border-0">
        <div class="table-responsive p-0">
            <table class="table table-hover table-striped mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>ID Struk</th>
                        <th>Kasir</th>
                        <th class="text-end">Total</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                @forelse ($sales as $sale)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($sale->tanggal)->format('d M Y H:i') }}</td>
                        <td>#{{ $sale->id }}</td>
                        <td>{{ $sale->user->name ?? '-' }}</td>
                        <td class="text-end">
                            Rp {{ number_format($sale->total,0,',','.') }}
                        </td>
                        <td class="text-center">
                            <a href="{{ route('kasir.struk',$sale->id) }}" 
                               class="btn btn-outline-primary btn-sm">Struk</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-3">
                            Tidak ada data.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($sales->hasPages())
            <div class="p-3">
                {{ $sales->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
