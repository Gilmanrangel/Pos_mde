<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print Pembelian #{{ $purchase->id }}</title>

    <style>
        body { font-family: Arial, sans-serif; margin: 20px; font-size: 14px; }
        .title { text-align: center; font-size: 22px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table, th, td { border: 1px solid #444; }
        th, td { padding: 6px; }
        .no-border { border: none !important; }
        .text-right { text-align: right; }
        .footer { margin-top: 40px; text-align: right; }
    </style>
</head>

<body onload="window.print()">

    <div class="title">NOTA PEMBELIAN</div>

    <table class="no-border">
        <tr class="no-border">
            <td class="no-border">
                <strong>Supplier:</strong> {{ $purchase->supplier->nama }} <br>
                <strong>Petugas:</strong> {{ $purchase->user->name }} <br>
                <strong>Tanggal:</strong> {{ $purchase->tanggal }}
            </td>
            <td class="no-border text-right">
                <strong>ID Pembelian:</strong> #{{ $purchase->id }}
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($purchase->details as $d)
            <tr>
                <td>{{ $d->product->nama }}</td>
                <td>Rp {{ number_format($d->harga, 0, ',', '.') }}</td>
                <td>{{ $d->qty }}</td>
                <td class="text-right">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr>
                <th colspan="3" class="text-right">TOTAL</th>
                <th class="text-right">Rp {{ number_format($purchase->total, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->format('d M Y H:i') }}
    </div>

</body>
</html>
