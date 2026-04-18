<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laba Rugi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body onload="window.print()">

<div class="container py-2">

    <h4 class="mb-2">Laporan Laba Rugi</h4>

    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>Produk</th>
                <th class="text-end" style="width: 70px;">Qty</th>
                <th class="text-end">Modal</th>
                <th class="text-end">Penjualan</th>
                <th class="text-end">Laba</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produkDetail as $p)
            <tr>
                <td>{{ $p['nama'] }}</td>
                <td class="text-end">{{ $p['qty'] }}</td>
                <td class="text-end">{{ number_format($p['modal'], 0, ',', '.') }}</td>
                <td class="text-end">{{ number_format($p['jual'], 0, ',', '.') }}</td>
                <td class="text-end">{{ number_format($p['laba'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>

</body>
</html>
