@extends('layouts.app')
@section('title', 'Transaksi Kasir')

@section('content')
<div class="container">
    <h3 class="mb-4">Transaksi Penjualan (Kasir)</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- ✅ TAMPILKAN ERROR VALIDASI LARAVEL --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="transaksi-form" action="{{ route('kasir.transaksi.store') }}" method="POST">
        @csrf

        {{-- INPUT BARIS ATAS --}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-3">
                        <label class="form-label">Kode Barang</label>
                        <input type="text" id="kode_barang" class="form-control" placeholder="Masukkan kode barang">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" id="nama_barang" class="form-control" readonly>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Harga Jual</label>
                        <input type="text" id="harga_jual" class="form-control" readonly>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Stok</label>
                        <input type="text" id="stok" class="form-control" readonly>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Qty</label>
                        <input type="number" id="qty" class="form-control" min="1" value="1">
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                        <button type="button" class="btn btn-primary w-100" onclick="addToCart()">
                            + Tambah ke Keranjang
                        </button>
                    </div>

                </div>
            </div>
        </div>

        {{-- TABEL KERANJANG --}}
        <div class="card mb-4">
            <div class="card-body">
                <h5>Keranjang</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="cart-body">
                        <tr>
                            <td colspan="6" class="text-center text-muted">Keranjang masih kosong.</td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-end">
                    <h5>Total: Rp <span id="total-display">0</span></h5>
                </div>
            </div>
        </div>

        {{-- BAYAR --}}
        <div class="card mb-4">
            <div class="card-body row g-3">
                <div class="col-md-4">
                    <label>Bayar</label>
                    {{-- tetap text agar bisa format rupiah --}}
                    <input type="text" id="bayar" name="bayar" class="form-control" value="0" oninput="formatBayarInput()">
                </div>
                <div class="col-md-4">
                    <label>Kembalian</label>
                    <input type="text" id="kembalian" class="form-control" readonly>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    {{-- ✅ jangan biarkan submit tanpa beforeSubmit --}}
                    <button type="submit" class="btn btn-success w-100" onclick="beforeSubmit(event)">
                        Simpan Transaksi
                    </button>
                </div>
            </div>
        </div>

        <input type="hidden" id="items" name="items">
        <input type="hidden" id="total" name="total" value="0">
    </form>
</div>

<script>
    let cart = [];

    function formatRupiah(x) {
        return new Intl.NumberFormat('id-ID').format(x);
    }

    // "10.000" -> 10000
    function toNumber(str) {
        return Number(String(str).replace(/[^0-9]/g, ''));
    }

    function formatBayarInput() {
        const bayar = document.getElementById('bayar');
        bayar.value = formatRupiah(toNumber(bayar.value));
        hitungKembalian();
    }

    function renderCart() {
        const tbody = document.getElementById('cart-body');
        tbody.innerHTML = '';

        if (cart.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" class="text-center text-muted">Keranjang masih kosong.</td></tr>`;
            document.getElementById('total-display').textContent = '0';
            document.getElementById('total').value = 0;
            hitungKembalian();
            return;
        }

        let total = 0;
        cart.forEach((item, i) => {
            total += item.subtotal;
            tbody.innerHTML += `
                <tr>
                    <td>${item.kode_barang}</td>
                    <td>${item.nama}</td>
                    <td>Rp ${formatRupiah(item.harga_jual)}</td>
                    <td>${item.qty}</td>
                    <td>Rp ${formatRupiah(item.subtotal)}</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeItem(${i})">Hapus</button>
                    </td>
                </tr>`;
        });

        document.getElementById('total-display').textContent = formatRupiah(total);
        document.getElementById('total').value = total;
        hitungKembalian();
    }

    function removeItem(i) {
        cart.splice(i, 1);
        renderCart();
    }

    function addToCart() {
        const kode = document.getElementById('kode_barang').value.trim();
        const qty = parseInt(document.getElementById('qty').value, 10);

        if (!kode) {
            Swal.fire('Peringatan', 'Kode barang harus diisi.', 'warning');
            return;
        }

        if (!Number.isFinite(qty) || qty <= 0) {
            Swal.fire('Peringatan', 'Qty harus lebih dari 0.', 'warning');
            return;
        }

        fetch(`{{ url('/kasir/get-produk') }}/${encodeURIComponent(kode)}`)
            .then(res => res.json())
            .then(data => {
                if (data.message) {
                    Swal.fire('Peringatan', data.message, 'warning');
                    return;
                }

                // tampilkan data produk di input atas (biar user yakin)
                document.getElementById('nama_barang').value = data.nama ?? '';
                document.getElementById('harga_jual').value = data.harga_jual ?? '';
                document.getElementById('stok').value = data.stok ?? '';

                if (qty > data.stok) {
                    Swal.fire('Peringatan', `Stok tersedia: ${data.stok}`, 'warning');
                    return;
                }

                cart.push({
                    product_id: data.id,
                    kode_barang: data.kode_barang,
                    nama: data.nama,
                    harga_jual: Number(data.harga_jual),
                    qty: qty,
                    subtotal: Number(data.harga_jual) * qty
                });

                renderCart();
            })
            .catch(() => {
                Swal.fire('Error', 'Gagal terhubung ke server.', 'error');
            });
    }

    function hitungKembalian() {
        const total = toNumber(document.getElementById('total').value);
        const bayar = toNumber(document.getElementById('bayar').value);
        const kembali = bayar - total;

        document.getElementById('kembalian').value =
            kembali >= 0 ? 'Rp ' + formatRupiah(kembali) : 'Belum cukup';
    }

    // ✅ FIX UTAMA: kirim bayar sebagai angka murni agar lolos validasi integer di backend
    function beforeSubmit(e) {
        if (cart.length === 0) {
            e.preventDefault();
            Swal.fire('Peringatan', 'Keranjang masih kosong.', 'warning');
            return;
        }

        document.getElementById('items').value = JSON.stringify(cart);

        // ubah bayar yang terformat (10.000) menjadi 10000 sebelum dikirim
        const bayarEl = document.getElementById('bayar');
        bayarEl.value = toNumber(bayarEl.value);

        // pastikan total juga angka
        const totalEl = document.getElementById('total');
        totalEl.value = toNumber(totalEl.value);

        const bayar = Number(bayarEl.value || 0);
        const total = Number(totalEl.value || 0);

        if (bayar < total) {
            e.preventDefault();
            Swal.fire('Peringatan', 'Pembayaran belum cukup.', 'warning');
        }
    }
</script>
@endsection