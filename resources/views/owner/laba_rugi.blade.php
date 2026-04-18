@extends('layouts.app')

@section('title', 'Laporan Laba Rugi Owner')

@section('content')
<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Laporan Laba & Rugi</h2>
            <p class="text-muted mb-0">Analisis profit berdasarkan periode penjualan.</p>
        </div>
    </div>

    {{-- FILTER --}}
    <form method="GET" class="card shadow-sm border-0 mb-4">
        <div class="card-body row g-3">

            <div class="col-md-4">
                <label class="form-label fw-semibold">Dari Tanggal</label>
                <input type="date" name="start_date" class="form-control"
                       value="{{ $start ?? '' }}">
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-control"
                       value="{{ $end ?? '' }}">
            </div>

            <div class="col-md-4 d-flex align-items-end">
                <button class="btn btn-primary w-100">Terapkan Filter</button>
            </div>

        </div>
    </form>

    {{-- STATISTIK LABA RUGI --}}
    <div class="row g-3 mb-4">

        {{-- Total Penjualan --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <span class="text-muted small">Total Penjualan</span>
                    <h4 class="fw-bold mt-1">
                        Rp {{ number_format($total_penjualan, 0, ',', '.') }}
                    </h4>
                </div>
            </div>
        </div>

        {{-- Total Modal --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <span class="text-muted small">Total Modal (HPP)</span>
                    <h4 class="fw-bold mt-1">
                        Rp {{ number_format($total_modal, 0, ',', '.') }}
                    </h4>
                </div>
            </div>
        </div>

        {{-- Laba Bersih --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <span class="text-muted small">Laba Bersih</span>
                    <h4 class="fw-bold mt-1 
                        {{ $laba_bersih < 0 ? 'text-danger' : 'text-success' }}">
                        Rp {{ number_format($laba_bersih, 0, ',', '.') }}
                    </h4>
                </div>
            </div>
        </div>

        {{-- Margin Persentase --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <span class="text-muted small">Margin Profit (%)</span>
                    <h4 class="fw-bold mt-1">
                        {{ number_format($margin, 1) }}%
                    </h4>
                </div>
            </div>
        </div>

    </div>

    {{-- Rata-rata --}}
    <div class="row g-3 mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <span class="text-muted small">Laba Rata-rata per Transaksi</span>
                    <h5 class="fw-bold mt-2">
                        Rp {{ number_format($laba_rata, 0, ',', '.') }}
                    </h5>
                </div>
            </div>
        </div>
    </div>

    {{-- DETAIL PER PRODUK --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0 d-flex justify-content-between">
            <h5 class="mb-0 fw-bold">Detail Per Produk</h5>

            <span class="text-muted small">
                Total Item: {{ count($produk_detail) }}
            </span>
        </div>

        <div class="card-body p-0">
            @if(count($produk_detail) === 0)
                <p class="text-muted small p-3 mb-0">Tidak ada data di periode ini.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th class="text-end">Qty</th>
                                <th class="text-end">H. Beli</th>
                                <th class="text-end">H. Jual</th>
                                <th class="text-end">Subtotal</th>
                                <th class="text-end">Modal</th>
                                <th class="text-end">Laba</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($produk_detail as $item)
                                <tr>
                                    <td>{{ $item['nama'] }}</td>
                                    <td class="text-end">{{ $item['qty'] }}</td>
                                    <td class="text-end">
                                        Rp {{ number_format($item['harga_beli'], 0, ',', '.') }}
                                    </td>
                                    <td class="text-end">
                                        Rp {{ number_format($item['harga_jual'], 0, ',', '.') }}
                                    </td>
                                    <td class="text-end">
                                        Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                    </td>
                                    <td class="text-end">
                                        Rp {{ number_format($item['modal'], 0, ',', '.') }}
                                    </td>
                                    <td class="text-end fw-bold
                                        {{ $item['laba'] < 0 ? 'text-danger' : 'text-success' }}">
                                        Rp {{ number_format($item['laba'], 0, ',', '.') }}
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
@endsection
