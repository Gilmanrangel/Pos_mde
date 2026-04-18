<tbody id="product-table">
@forelse($products as $p)
<tr>
    <td>{{ $p->kode_barang }}</td>

    <td class="fw-semibold">
        {{ $p->nama }}
    </td>

    <td>{{ $p->satuan }}</td>

    <td>
        Rp {{ number_format($p->harga_beli, 0, ',', '.') }}
    </td>

    <td>
        Rp {{ number_format($p->harga_jual, 0, ',', '.') }}
    </td>

    <td id="stok-{{ $p->id }}">
        <span class="badge bg-light text-dark">
            {{ $p->stok }}
        </span>
    </td>

    <td class="text-end">

        <a href="{{ route('produk.edit', $p->id) }}"
           class="btn-soft-primary btn-sm">
            Edit
        </a>

        <form action="{{ route('produk.destroy', $p->id) }}"
              method="POST"
              class="d-inline"
              onsubmit="return confirm('Hapus produk?')">

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
    <td colspan="7" class="text-center text-muted py-3">
        Tidak ada data ditemukan
    </td>
</tr>
@endforelse
</tbody>