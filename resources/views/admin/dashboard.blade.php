@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid py-4">

    {{-- 🔥 HEADER MODERN --}}
    <x-page-header 
    icon="bi-speedometer2"
    title="Dashboard Admin"
    subtitle="Ringkasan inventori & aktivitas sistem"
    :right="'<span class=\'badge bg-light text-dark px-3 py-2\'>'.now()->format('d M Y').'</span>'"
/>

    {{-- 🔥 STATISTIK --}}
    <div class="row g-3 mb-4">

        {{-- TOTAL PRODUK --}}
        <div class="col-md-3">
            <div class="card card-stat h-100">
                <div class="card-body d-flex justify-content-between align-items-center">

                    <div>
                        <p class="text-muted small mb-1">Total Produk</p>
                        <h3 class="fw-bold mb-0">{{ $totalProduk }}</h3>
                        <small class="text-muted">Item terdaftar</small>
                    </div>

                    <div class="icon-stat bg-primary-soft">
                        <i class="bi bi-box-seam"></i>
                    </div>

                </div>
            </div>
        </div>

        {{-- SUPPLIER --}}
        <div class="col-md-3">
            <div class="card card-stat h-100">
                <div class="card-body d-flex justify-content-between align-items-center">

                    <div>
                        <p class="text-muted small mb-1">Total Supplier</p>
                        <h3 class="fw-bold mb-0">{{ $totalSupplier }}</h3>
                        <small class="text-muted">Rekan bisnis</small>
                    </div>

                    <div class="icon-stat bg-success-soft">
                        <i class="bi bi-truck"></i>
                    </div>

                </div>
            </div>
        </div>

        {{-- NILAI STOK --}}
        <div class="col-md-3">
            <div class="card card-stat h-100">
                <div class="card-body d-flex justify-content-between align-items-center">

                    <div>
                        <p class="text-muted small mb-1">Nilai Persediaan</p>
                        <h5 class="fw-bold mb-0">
                            Rp {{ number_format($totalNilaiStok, 0, ',', '.') }}
                        </h5>
                        <small class="text-muted">Total nilai stok</small>
                    </div>

                    <div class="icon-stat bg-warning-soft">
                        <i class="bi bi-cash-stack"></i>
                    </div>

                </div>
            </div>
        </div>

        {{-- STATUS STOK --}}
        <div class="col-md-3">
            <div class="card card-stat h-100">
                <div class="card-body">

                    <p class="text-muted small mb-2">Status Stok</p>

                    <div class="mb-1">
                        <span class="badge bg-warning-soft">
                            Menipis: {{ $jumlahProdukStokMenipis }}
                        </span>
                    </div>

                    <div>
                        <span class="badge bg-danger-soft">
                            Habis: {{ $jumlahProdukStokHabis }}
                        </span>
                    </div>

                    <small class="text-muted d-block mt-2">
                        Batas ≤ {{ $stokMinimum }}
                    </small>

                </div>
            </div>
        </div>

    </div>

    {{-- 🔥 PENJUALAN & PEMBELIAN --}}
    <div class="row g-3 mb-4">

        <div class="col-md-6">
            <div class="card card-stat h-100">
                <div class="card-body">

                    <p class="text-muted small mb-1">Penjualan Hari Ini</p>
                    <h4 class="fw-bold">
                        Rp {{ number_format($penjualanHariIni, 0, ',', '.') }}
                    </h4>
                    <small class="text-muted">
                        Total transaksi kasir hari ini
                    </small>

                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-stat h-100">
                <div class="card-body">

                    <p class="text-muted small mb-1">Pembelian Hari Ini</p>
                    <h4 class="fw-bold">
                        Rp {{ number_format($pembelianHariIni, 0, ',', '.') }}
                    </h4>
                    <small class="text-muted">
                        Total pembelian supplier
                    </small>

                </div>
            </div>
        </div>

    </div>

    {{-- 🔥 TABEL --}}
    <div class="row g-3">

        {{-- STOK MENIPIS --}}
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">

                <div class="card-header bg-white border-0 d-flex justify-content-between">
                    <h6 class="mb-0 fw-semibold">Produk Stok Menipis</h6>
                    <small class="text-muted">≤ {{ $stokMinimum }}</small>
                </div>

                <div class="card-body p-0">
                    @if($produkStokMenipis->isEmpty())
                        <p class="p-3 text-muted">Tidak ada data</p>
                    @else
                        <table class="table table-sm mb-0">
                            <tbody>
                                @foreach($produkStokMenipis as $p)
                                <tr>
                                    <td>{{ $p->nama }}</td>
                                    <td class="text-end">{{ $p->stok }}</td>
                                    <td class="text-end">
                                        Rp {{ number_format($p->harga_beli, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

            </div>
        </div>

        {{-- PEMBELIAN --}}
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">

                <div class="card-header bg-white border-0 d-flex justify-content-between">
                    <h6 class="mb-0 fw-semibold">Pembelian Terakhir</h6>
                    <small class="text-muted">5 data</small>
                </div>

                <div class="card-body p-0">
                    @if($pembelianTerakhir->isEmpty())
                        <p class="p-3 text-muted">Belum ada data</p>
                    @else
                        <table class="table table-sm mb-0">
                            <tbody>
                                @foreach($pembelianTerakhir as $pb)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($pb->tanggal)->format('d M Y') }}</td>
                                    <td>{{ $pb->supplier->nama ?? '-' }}</td>
                                    <td class="text-end">
                                        Rp {{ number_format($pb->total, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

            </div>
        </div>

    </div>

</div>
@endsection