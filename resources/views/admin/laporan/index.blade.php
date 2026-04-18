@extends('layouts.app')

@section('title', 'Laporan Admin')

@section('content')
<div class="container-fluid py-4">


    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                📊 Ringkasan Laporan Admin
            </h2>
            <p class="text-muted mb-0">Monitoring cepat seluruh aktivitas penjualan & pembelian.</p>
        </div>
    </div>

    {{-- KARTU STATISTIK --}}
    <div class="row g-3 mb-4">

        {{-- Pendapatan Hari Ini --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <span class="text-muted small">Pendapatan Hari Ini</span>
                    <h4 class="fw-bold mt-2">Rp {{ number_format($today, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>

        {{-- Penjualan Bulan Ini --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <span class="text-muted small">Penjualan Bulan Ini</span>
                    <h4 class="fw-bold mt-2 text-primary">Rp {{ number_format($thisMonth, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>

        {{-- Penjualan Tahun Ini --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <span class="text-muted small">Penjualan Tahun Ini</span>
                    <h4 class="fw-bold mt-2">Rp {{ number_format($thisYear, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>

        {{-- Total Pembelian --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <span class="text-muted small">Total Pembelian</span>
                    <h4 class="fw-bold mt-2 text-success">
                        Rp {{ number_format($totalPembelian, 0, ',', '.') }}
                    </h4>
                </div>
            </div>
        </div>

    </div>

    {{-- MENU LAPORAN --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <h5 class="fw-bold mb-3">📂 Menu Laporan Lengkap</h5>

            <div class="row g-3">

                <div class="col-md-3">
                    <a href="{{ route('laporan.penjualan') }}" class="text-decoration-none">
                        <div class="p-3 border rounded shadow-sm bg-light report-menu">
                            <h6 class="fw-bold mb-1">🛒 Laporan Penjualan</h6>
                            <p class="text-muted small mb-0">Detail transaksi per struk.</p>
                        </div>
                    </a>
                </div>

                <div class="col-md-3">
                    <a href="{{ route('laporan.pembelian') }}" class="text-decoration-none">
                        <div class="p-3 border rounded shadow-sm bg-light report-menu">
                            <h6 class="fw-bold mb-1">📦 Laporan Pembelian</h6>
                            <p class="text-muted small mb-0">Semua pembelian barang masuk.</p>
                        </div>
                    </a>
                </div>

                <div class="col-md-3">
                    <a href="{{ route('laporan.stok') }}" class="text-decoration-none">
                        <div class="p-3 border rounded shadow-sm bg-light report-menu">
                            <h6 class="fw-bold mb-1">📉 Stok Minimum</h6>
                            <p class="text-muted small mb-0">Pantau barang hampir habis.</p>
                        </div>
                    </a>
                </div>

                <div class="col-md-3">
                    <a href="{{ route('laporan.laba_rugi') }}" class="text-decoration-none">
                        <div class="p-3 border rounded shadow-sm bg-light report-menu">
                            <h6 class="fw-bold mb-1">💰 Laba & Rugi</h6>
                            <p class="text-muted small mb-0">Ringkasan profit perusahaan.</p>
                        </div>
                    </a>
                </div>

            </div>

        </div>
    </div>

</div>

<style>
    .report-menu:hover {
        background: #eef4ff !important;
        transform: translateY(-3px);
        transition: 0.2s;
        cursor: pointer;
    }
</style>
@endsection
