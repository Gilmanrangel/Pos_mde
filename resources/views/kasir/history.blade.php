@extends('layouts.app')
@section('title', 'Riwayat Transaksi')

@section('content')
<h3>Riwayat Transaksi</h3>
<hr>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tanggal</th>
            <th>Total</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sales as $sale)
        <tr>
            <td>{{ $sale->id }}</td>
            <td>{{ $sale->tanggal }}</td>
            <td>Rp {{ number_format($sale->total,0,',','.') }}</td>
            <td>
                <a class="btn btn-primary btn-sm" href="{{ route('kasir.struk', $sale->id) }}">
                    Cetak Struk
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
