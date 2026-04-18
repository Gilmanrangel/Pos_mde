<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_barang',
        'nama',
        'satuan',
        'harga_beli',
        'harga_jual',
        'stok',
    ];

    /**
     * Relasi ke tabel purchase_details
     * (1 produk bisa muncul di banyak pembelian)
     */
    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    /**
     * Relasi ke tabel sale_details
     * (1 produk bisa muncul di banyak penjualan)
     */
    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }
}
