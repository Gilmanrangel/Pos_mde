@extends('layouts.app')

@section('title', 'Laporan Stok Minimum')

@section('content')
<div class="container py-4">

    <a href="{{ route('laporan.index') }}" class="btn btn-light border mb-3">← Kembali</a>

    <h3 class="fw-bold mb-4">📦 Laporan Stok Minimum</h3>

    <div class="alert alert-warning">
        Produk dengan stok ≤ {{ $minimum }}
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">

            <table class="table table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Produk</th>
                        <th class="text-end">Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                        <tr>
                            <td>{{ $p->nama }}</td>
                            <td class="text-end">{{ $p->stok }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted py-3">
                                Semua stok masih aman.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

</div>
<script>
document.addEventListener("DOMContentLoaded", function () {

    Echo.channel('stocks')
        .listen('.stock.updated', (e) => {
            console.log('Realtime masuk:', e);

            // sementara kita pakai cara cepat
            location.reload();
        });

});
</script>
@endsection
