@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <x-page-header 
    icon="bi-plus-circle"
    title="Tambah Produk"
    subtitle="Masukkan data produk baru"
/>

    {{-- ERROR --}}
    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM --}}
    <div class="card shadow-sm">
        <div class="card-body">

            <form action="{{ route('produk.store') }}" method="POST">
                @csrf

                <div class="row g-3">

                    {{-- KODE --}}
                    <div class="col-md-6">
                        <label class="form-label">Kode Barang</label>
                        <input type="text" name="kode_barang"
                               value="{{ old('kode_barang') }}"
                               class="form-control"
                               placeholder="Contoh: PIP-001" required>
                    </div>

                    {{-- NAMA --}}
                    <div class="col-md-6">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" name="nama"
                               value="{{ old('nama') }}"
                               class="form-control"
                               placeholder="Nama produk" required>
                    </div>

                    {{-- SATUAN --}}
                    <div class="col-md-4">
                        <label class="form-label">Satuan</label>
                        <input type="text" name="satuan"
                               value="{{ old('satuan') }}"
                               class="form-control"
                               placeholder="pcs / kg / dus" required>
                    </div>

                    {{-- HARGA BELI --}}
                    <div class="col-md-4">
                        <label class="form-label">Harga Beli</label>
                        <input type="number" name="harga_beli"
                               value="{{ old('harga_beli') }}"
                               class="form-control"
                               required>
                    </div>

                    {{-- HARGA JUAL --}}
                    <div class="col-md-4">
                        <label class="form-label">Harga Jual</label>
                        <input type="number" name="harga_jual"
                               value="{{ old('harga_jual') }}"
                               class="form-control"
                               required>
                    </div>

                    {{-- STOK --}}
                    <div class="col-md-4">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stok"
                               value="{{ old('stok', 0) }}"
                               class="form-control"
                               required>
                    </div>

                </div>

                {{-- BUTTON --}}
                <div class="mt-4 d-flex justify-content-between">

                    {{-- KEMBALI --}}
                    <a href="{{ route('produk.index') }}"
                       class="btn-soft">
                        ← Kembali
                    </a>

                    {{-- SIMPAN --}}
                    <button type="submit" class="btn-modern">
                        <i class="bi bi-check-lg"></i>
                        Simpan Produk
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>

@endsection