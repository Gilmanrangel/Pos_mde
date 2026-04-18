<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-size: 12px;
        }
        .table th, .table td {
            padding: 4px 6px;
        }
    </style>
</head>
<body onload="window.print()">

<div class="container-fluid py-2">

    <h4 class="mb-0">Laporan Penjualan</h4>
    <p class="text-muted small mb-2">
        Periode:
        @if($start && $end)
            {{ \Carbon\Carbon::parse($start)->format('d M Y') }}
            s/d
            {{ \Carbon\Carbon::parse($end)->format('d M Y') }}
        @else
            Semua tanggal
        @endif
    </p>

    <p class="small mb-1">
        Total transaksi: <strong>{{ $jumlahTransaksi }}</strong><br>
        Total omzet: <strong>Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</strong>
    </p>

    <hr class="my-2">

    <table class="table table-bordered table-sm mb-0">
        <thead>
            <tr>
                <th style="width: 140px;">Tanggal</th>
                <th style="width: 80px;">ID Struk</th>
                <th>Kasir</th>
                <th style="width: 140px;" class="text-end">Total (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sales as $sale)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($sale->tanggal)->format('d-m-Y H:i') }}</td>
                    <td>#{{ $sale->id }}</td>
                    <td>{{ $sale->user->name ?? '-' }}</td>
                    <td class="text-end">
                        {{ number_format($sale->total, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">
                        Tidak ada data.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>

</body>
</html>
