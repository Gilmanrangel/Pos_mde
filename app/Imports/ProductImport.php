<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Product([
            'kode_barang' => $row['kode_barang'],
            'nama'        => $row['nama'],
            'satuan'      => $row['satuan'],
            'harga_beli'  => $row['harga_beli'],
            'harga_jual'  => $row['harga_jual'],
            'stok'        => $row['stok'],
        ]);
    }
}
