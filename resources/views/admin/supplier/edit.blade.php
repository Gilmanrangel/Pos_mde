@extends('layouts.app')

@section('title', 'Edit Supplier')

@section('content')

<div class="container-fluid">

    {{-- 🔥 HEADER --}}
    <x-page-header 
        icon="bi-pencil-square"
        title="Edit Supplier"
        subtitle="Perbarui data supplier"
    />

    {{-- FORM --}}
    <div class="card">
        <div class="card-body">

            <form action="{{ route('supplier.update', $supplier->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    {{-- KODE --}}
                    <div class="col-md-6">
                        <label class="form-label">Kode Supplier</label>
                        <input type="text" 
                               class="form-control"
                               value="{{ $supplier->kode_supplier }}" 
                               disabled>
                    </div>

                    {{-- NAMA --}}
                    <div class="col-md-6">
                        <label class="form-label">Nama</label>
                        <input type="text" 
                               name="nama"
                               class="form-control"
                               value="{{ $supplier->nama }}" 
                               required>
                    </div>

                    {{-- ALAMAT --}}
                    <div class="col-md-12">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" 
                                  class="form-control"
                                  rows="3">{{ $supplier->alamat }}</textarea>
                    </div>

                    {{-- TELEPON --}}
                    <div class="col-md-6">
                        <label class="form-label">Telepon</label>
                        <input type="text" 
                               name="telepon"
                               class="form-control"
                               value="{{ $supplier->telepon }}">
                    </div>

                </div>

                {{-- BUTTON --}}
                <div class="mt-4 d-flex justify-content-between">

                    <a href="{{ route('supplier.index') }}" class="btn-soft">
                        ← Kembali
                    </a>

                    <button type="submit" class="btn-modern">
                        <i class="bi bi-check-lg"></i>
                        Update
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>

@endsection