@extends('layouts.app')

@section('title', 'Transaksi Pembelian')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <x-page-header 
        icon="bi-cart-plus"
        title="Transaksi Pembelian"
        subtitle="Input pembelian barang dari supplier"
    >
        <a href="{{ route('pembelian.index') }}" class="btn-soft">
            ← Kembali
        </a>
    </x-page-header>

    <form action="{{ route('pembelian.store') }}" method="POST" id="purchaseForm">
        @csrf

        {{-- SUPPLIER --}}
        <div class="card mb-4">
            <div class="card-body">

                <label class="form-label fw-semibold">Pilih Supplier</label>

                <select name="supplier_id" class="form-select" required>
                    <option value="">-- Pilih Supplier --</option>
                    @foreach($suppliers as $sup)
                        <option value="{{ $sup->id }}">{{ $sup->nama }}</option>
                    @endforeach
                </select>

            </div>
        </div>

        {{-- DETAIL --}}
        <div class="card">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Detail Pembelian</h5>

                    <button type="button" class="btn-modern" id="addRow">
                        <i class="bi bi-plus-lg"></i> Tambah Barang
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle mb-0" id="itemTable">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th class="text-end">Harga</th>
                                <th class="text-end">Qty</th>
                                <th class="text-end">Subtotal</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>

                        <tbody id="itemBody">
                            <tr class="text-center text-muted">
                                <td colspan="5">
                                    Belum ada item. Klik "Tambah Barang"
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <hr>

                {{-- TOTAL --}}
                <div class="d-flex justify-content-end">
                    <div class="text-end">
                        <small class="text-muted">Total Pembelian</small>
                        <h3 class="fw-bold" id="totalDisplay">Rp 0</h3>
                        <input type="hidden" name="total" id="totalInput" value="0">
                    </div>
                </div>

                <button type="submit" class="btn-modern w-100 mt-3 py-2">
                    <i class="bi bi-check-lg"></i>
                    Simpan Pembelian
                </button>

            </div>
        </div>

    </form>

</div>

@endsection


{{-- ================= KIRIM DATA KE JS (WAJIB DI SINI) ================= --}}
@push('scripts')
<script>
    window.products = @json($products);
</script>
@endpush