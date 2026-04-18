@extends('layouts.app')

@section('title', 'Laporan Pembelian Admin')

@section('content')
<div class="container py-4">

    <a href="{{ route('laporan.index') }}" class="btn btn-light border mb-3">← Kembali</a>

    <h3 class="fw-bold mb-4">📄 Laporan Pembelian (Admin)</h3>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">

            <table class="table table-striped table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Supplier</th>
                        <th class="text-end">Total (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchases as $p)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y H:i') }}</td>
                            <td>{{ $p->supplier->nama ?? '-' }}</td>
                            <td class="text-end">{{ number_format($p->total, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-3">Tidak ada pembelian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="p-3">
                {{ $purchases->links() }}
            </div>

        </div>
    </div>

</div>
@endsection
