<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProductExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return Product::select(
            'kode_barang',
            'nama',
            'satuan',
            'harga_beli',
            'harga_jual',
            'stok'
        )->get();
    }

    public function headings(): array
    {
        return [
            'Kode Barang',
            'Nama Produk',
            'Satuan',
            'Harga Beli',
            'Harga Jual',
            'Stok'
        ];
    }
}
