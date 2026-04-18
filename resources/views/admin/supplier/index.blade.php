@extends('layouts.app')

@section('title', 'Supplier')

@section('content')

<div class="container-fluid">

    {{-- 🔥 HEADER (COMPONENT) --}}
    <x-page-header 
        icon="bi-truck"
        title="Manajemen Supplier"
        subtitle="Kelola data supplier"
        :right="'<span class=\'badge bg-light text-dark px-3 py-2\'>'.$suppliers->count().' Supplier</span>'"
    />

    {{-- ACTION --}}
    <div class="card mb-3">
        <div class="card-body d-flex justify-content-between">

            <a href="{{ route('supplier.create') }}" class="btn-modern">
                <i class="bi bi-plus-lg"></i>
                Tambah Supplier
            </a>

        </div>
    </div>

    {{-- TABLE --}}
    <div class="card">
        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Telepon</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($suppliers as $s)
                        <tr>
                            <td>{{ $s->kode_supplier }}</td>
                            <td class="fw-semibold">{{ $s->nama }}</td>
                            <td>{{ $s->alamat }}</td>
                            <td>{{ $s->telepon }}</td>

                            <td class="text-end">
                                <a href="{{ route('supplier.edit', $s->id) }}"
                                   class="btn-soft-primary btn-sm">
                                    Edit
                                </a>

                                <form action="{{ route('supplier.destroy', $s->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Hapus supplier?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-soft-danger btn-sm">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                Belum ada data supplier
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>
    </div>

</div>

@endsection