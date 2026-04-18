@extends('layouts.app')

@section('title', 'Laporan Laba & Rugi Admin')

@section('content')
<div class="container py-4">

    <a href="{{ route('laporan.index') }}" class="btn btn-light border mb-3">← Kembali</a>

    <h3 class="fw-bold mb-4">💰 Laporan Laba & Rugi</h3>

    <div class="row g-3">

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <p class="text-muted small">Total Penjualan</p>
                    <h4 class="fw-bold">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <p class="text-muted small">Total Modal</p>
                    <h4 class="fw-bold text-primary">Rp {{ number_format($totalPembelian, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <p class="text-muted small">Laba Bersih</p>
                    <h4 class="fw-bold {{ $laba >= 0 ? 'text-success' : 'text-danger' }}">
                        Rp {{ number_format($laba, 0, ',', '.') }}
                    </h4>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
