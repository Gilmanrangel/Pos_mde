@extends('layouts.app')

@section('title', 'Tambah Supplier')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <x-page-header 
        icon="bi-plus-circle"
        title="Tambah Supplier"
        subtitle="Masukkan data supplier baru"
    />

    {{-- FORM --}}
    <div class="card">
        <div class="card-body">

            <form action="{{ route('supplier.store') }}" method="POST">
                @csrf

                <div class="row g-3">

                    {{-- KODE --}}
                    <div class="col-md-6">
                        <label class="form-label">Kode Supplier</label>
                        <input type="text" name="kode_supplier"
                               class="form-control"
                               placeholder="Contoh: SUP-001"
                               required>
                    </div>

                    {{-- NAMA --}}
                    <div class="col-md-6">
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama"
                               class="form-control"
                               required>
                    </div>

                    {{-- ALAMAT --}}
                    <div class="col-md-12">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat"
                                  class="form-control"
                                  rows="3"></textarea>
                    </div>

                    {{-- TELEPON --}}
                    <div class="col-md-6">
                        <label class="form-label">Telepon</label>
                        <input type="text" name="telepon"
                               class="form-control">
                    </div>

                </div>

                {{-- BUTTON --}}
                <div class="mt-4 d-flex justify-content-between">

                    <a href="{{ route('supplier.index') }}" class="btn-soft">
                        ← Kembali
                    </a>

                    <button type="submit" class="btn-modern">
                        <i class="bi bi-check-lg"></i>
                        Simpan
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>

@endsection