@extends('layouts.app')

@section('title', 'Detail Pembelian')

@section('content')

<div class="container-fluid">

    {{-- 🔥 HEADER (SUDAH CUKUP, JANGAN DUPLIKAT) --}}
    <x-page-header 
    icon="bi-receipt"
    title="Detail Pembelian"
    subtitle="Informasi lengkap transaksi pembelian"
>

    <a href="{{ route('pembelian.index') }}" class="btn-soft me-2">
        ← Kembali
    </a>

    <a href="{{ route('pembelian.print', $purchase->id) }}" 
       target="_blank"
       class="btn-modern">
        <i class="bi bi-printer"></i> Print
    </a>

</x-page-header>

    {{-- 🔥 INFO TRANSAKSI --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <x-stat-card 
                label="Tanggal"
                :value="\Carbon\Carbon::parse($purchase->tanggal)->format('d M Y H:i')"
                desc="Waktu transaksi"
                icon="bi-calendar-event"
                color="bg-primary-soft"
            />
        </div>

        <div class="col-md-4">
            <x-stat-card 
                label="Supplier"
                :value="$purchase->supplier->nama ?? '-'"
                desc="Sumber barang"
                icon="bi-truck"
                color="bg-warning-soft"
            />
        </div>

        <div class="col-md-4">
            <x-stat-card 
                label="User"
                :value="$purchase->user->name ?? '-'"
                desc="Input oleh"
                icon="bi-person"
                color="bg-success-soft"
            />
        </div>

    </div>

    {{-- 🔥 TABLE --}}
    <div class="card">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table align-middle mb-0">

                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th class="text-end">Harga</th>
                            <th class="text-end">Qty</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($purchase->details as $detail)
                            <tr>
                                <td class="fw-semibold">
                                    {{ $detail->product->nama ?? '-' }}
                                </td>

                                <td class="text-end">
                                    Rp {{ number_format($detail->harga, 0, ',', '.') }}
                                </td>

                                <td class="text-end">
                                    {{ $detail->qty }}
                                </td>

                                <td class="text-end fw-semibold">
                                    Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

        </div>

        {{-- 🔥 TOTAL --}}
        <div class="p-4 border-top d-flex justify-content-end">
            <div class="text-end">
                <small class="text-muted">Total Pembelian</small>
                <h3 class="fw-bold text-primary">
                    Rp {{ number_format($purchase->total, 0, ',', '.') }}
                </h3>
            </div>
        </div>

    </div>

</div>

@endsection