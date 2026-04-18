@extends('layouts.app')
@section('title','Data Pembelian')

@section('content')
<div class="container-fluid">

    {{-- HEADER --}}
    <x-page-header 
        icon="bi-receipt"
        title="Data Pembelian"
        subtitle="Kelola transaksi pembelian"
    >
        <a href="{{ route('pembelian.create') }}" class="btn-modern">
            <i class="bi bi-plus-lg"></i>
            Transaksi Baru
        </a>
    </x-page-header>

    {{-- 🔍 SEARCH --}}
    <div class="card mb-3">
        <div class="card-body">
            <div class="row g-2">

                <div class="col-md-4">
                    <input type="text" 
                           id="searchInput"
                           class="form-control"
                           placeholder="Cari supplier / user...">
                </div>

                <div class="col-md-3">
                    <input type="date" id="startDate" class="form-control">
                </div>

                <div class="col-md-3">
                    <input type="date" id="endDate" class="form-control">
                </div>

            </div>
        </div>
    </div>

    {{-- STAT --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <x-stat-card 
                label="Total Transaksi"
                :value="$purchases->total()"
                desc="Semua pembelian"
                icon="bi-cart-check"
                color="bg-primary-soft"
            />
        </div>

        <div class="col-md-3">
            <x-stat-card 
                label="Total Nilai"
                :value="'Rp '.number_format($purchases->sum('total'),0,',','.')"
                desc="Nilai pembelian"
                icon="bi-cash-stack"
                color="bg-success-soft"
            />
        </div>

        <div class="col-md-3">
            <x-stat-card 
                label="Hari Ini"
                :value="$purchases->where('tanggal', today())->count()"
                desc="Transaksi hari ini"
                icon="bi-calendar-check"
                color="bg-warning-soft"
            />
        </div>

    </div>

    {{-- TABLE --}}
    <div class="card">

        {{-- 🔥 LOADING --}}
        <div id="loadingIndicator" class="text-center py-3 d-none">
            <div class="spinner-border text-primary"></div>
            <div class="mt-2 text-muted">Memuat data...</div>
        </div>

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Supplier</th>
                        <th>User</th>
                        <th class="text-end">Total</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($purchases as $row)
                        <tr>
                            <td>
                                {{ \Carbon\Carbon::parse($row->tanggal)->format('d M Y H:i') }}
                            </td>

                            <td class="fw-semibold">
                                {{ $row->supplier->nama }}
                            </td>

                            <td>
                                {{ $row->user->name }}
                            </td>

                            <td class="text-end">
                                Rp {{ number_format($row->total,0,',','.') }}
                            </td>

                            <td class="text-end">
                                <a href="{{ route('pembelian.show',$row->id) }}"
                                   class="btn-soft-primary btn-sm">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Belum ada transaksi pembelian
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>

        {{-- PAGINATION --}}
        <div class="p-3 d-flex justify-content-between align-items-center">
            <small class="text-muted">
                Menampilkan {{ $purchases->count() }} data
            </small>

            {{ $purchases->links() }}
        </div>

    </div>

</div>
@endsection