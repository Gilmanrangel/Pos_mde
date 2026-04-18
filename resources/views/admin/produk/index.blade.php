@extends('layouts.app')

@section('title', 'Daftar Produk')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <x-page-header 
        icon="bi-box-seam"
        title="Manajemen Produk"
        subtitle="Kelola data produk dengan cepat dan efisien"
        :right="'<span class=\'badge bg-light text-dark px-3 py-2\'>'.$products->total().' Produk</span>'"
    />

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- ACTION --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body d-flex justify-content-between flex-wrap gap-2">

            <a href="{{ route('produk.create') }}" class="btn-modern">
                <i class="bi bi-plus-lg"></i>
                Tambah Produk
            </a>

            <div class="d-flex gap-2">
                <a href="{{ route('produk.export') }}" class="btn-soft-success">
                    Export
                </a>

                <form action="{{ route('produk.import') }}" method="POST" enctype="multipart/form-data" class="d-flex gap-2">
                    @csrf
                    <input type="file" name="file" class="form-control form-control-sm">
                    <button class="btn-soft">
                        Import
                    </button>
                </form>
            </div>

        </div>
    </div>

    {{-- SEARCH + FILTER --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">

        <div class="row g-2">

            {{-- SEARCH --}}
            <div class="col-md-4">
                <input type="text" id="search" class="form-control"
                    placeholder="🔍 Cari produk...">
            </div>

            {{-- FILTER STOK --}}
            <div class="col-md-2">
                <select id="filterStok" class="form-select">
                    <option value="">Semua Stok</option>
                    <option value="tersedia">Tersedia</option>
                    <option value="habis">Habis</option>
                </select>
            </div>

            {{-- MIN HARGA --}}
            <div class="col-md-3">
                <input type="number" id="minHarga" class="form-control"
                    placeholder="Harga Min">
            </div>

            {{-- MAX HARGA --}}
            <div class="col-md-3">
                <input type="number" id="maxHarga" class="form-control"
                    placeholder="Harga Max">
            </div>

        </div>

    </div>
</div>

    {{-- TABLE --}}
   <table class="table table-hover align-middle mb-0">

    <thead class="table-light">
    <tr>
        <th>Kode</th>

        <th class="sortable" data-sort="nama">
            Nama <span class="sort-icon"></span>
        </th>

        <th>Satuan</th>

        <th class="sortable" data-sort="harga_beli">
            Harga Beli <span class="sort-icon"></span>
        </th>

        <th class="sortable" data-sort="harga_jual">
            Harga Jual <span class="sort-icon"></span>
        </th>

        <th class="sortable" data-sort="stok">
            Stok <span class="sort-icon"></span>
        </th>

        <th class="text-end">Aksi</th>
    </tr>
</thead>

    {{-- 🔥 partial --}}
    @include('admin.produk.partials.table')

</table>

        {{-- PAGINATION --}}
        <div id="pagination-area" class="p-3 d-flex justify-content-between align-items-center">
            <small class="text-muted">
                Menampilkan {{ $products->count() }} data
            </small>

            {{ $products->links() }}
        </div>

    </div>

</div>

@endsection