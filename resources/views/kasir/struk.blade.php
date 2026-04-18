<!DOCTYPE html>
<html>
<head>
    <title>Struk Penjualan</title>
    <style>
        body { font-family: monospace; }
        .center { text-align: center; }
        .line { border-top: 1px dashed #000; margin: 10px 0; }
    </style>
</head>
<body>

<div class="center">
    <h3>TOKO MATERIAL</h3>
    <p>Jl. Contoh No. 123</p>
</div>

<div class="line"></div>

Tanggal : {{ $sale->tanggal }} <br>
Kasir : {{ $sale->user->name }} <br>

<div class="line"></div>

<table width="100%">
    @foreach($sale->details as $d)
    <tr>
        <td>{{ $d->product->nama }} (x{{ $d->qty }})</td>
        <td align="right">Rp {{ number_format($d->subtotal,0,',','.') }}</td>
    </tr>
    @endforeach
</table>

<div class="line"></div>

<h3>Total: Rp {{ number_format($sale->total,0,',','.') }}</h3>

<div class="center">
    <p>Terima kasih!</p>
    <script>window.print();</script>
</div>

</body>
</html>
