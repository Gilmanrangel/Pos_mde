@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
   <x-page-header 
    icon="bi-pencil-square"
    title="Edit Produk"
    subtitle="Perbarui data produk"
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

            <form action="{{ route('produk.update', $produk->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    {{-- KODE (READONLY) --}}
                    <div class="col-md-6">
                        <label class="form-label">Kode Barang</label>
                        <input type="text" class="form-control bg-light"
                               value="{{ $produk->kode_barang }}" readonly>
                    </div>

                    {{-- NAMA --}}
                    <div class="col-md-6">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" name="nama"
                               value="{{ old('nama', $produk->nama) }}"
                               class="form-control" required>
                    </div>

                    {{-- SATUAN --}}
                    <div class="col-md-4">
                        <label class="form-label">Satuan</label>
                        <input type="text" name="satuan"
                               value="{{ old('satuan', $produk->satuan) }}"
                               class="form-control" required>
                    </div>

                    {{-- HARGA BELI --}}
                    <div class="col-md-4">
                        <label class="form-label">Harga Beli</label>
                        <input type="number" name="harga_beli"
                               value="{{ old('harga_beli', $produk->harga_beli) }}"
                               class="form-control" required>
                    </div>

                    {{-- HARGA JUAL --}}
                    <div class="col-md-4">
                        <label class="form-label">Harga Jual</label>
                        <input type="number" name="harga_jual"
                               value="{{ old('harga_jual', $produk->harga_jual) }}"
                               class="form-control" required>
                    </div>

                    {{-- STOK --}}
                    <div class="col-md-4">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stok"
                               value="{{ old('stok', $produk->stok) }}"
                               class="form-control" required>
                    </div>

                </div>

                {{-- BUTTON --}}
                <div class="mt-4 d-flex justify-content-between">

                    {{-- KEMBALI --}}
                    <a href="{{ route('produk.index') }}" class="btn-soft">
                        ← Kembali
                    </a>

                    {{-- UPDATE --}}
                    <button type="submit" class="btn-modern">
                        <i class="bi bi-check-lg"></i>
                        Update Produk
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>

@endsection