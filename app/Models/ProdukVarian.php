<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdukVarian extends Model
{
    // Field yang boleh diisi menggunakan create() atau update()
    protected $fillable = [

        // Foreign key yang menghubungkan varian ke produk
        'produk_id',

        // Ukuran produk
        // Contoh: S, M, L, XL
        'ukuran',

        // Warna produk
        // Contoh: Hitam, Putih, Navy
        'warna',

        // Jumlah stok varian
        'stok',
    ];

    public function produk()
    {
        // Relasi many-to-one
        // Banyak varian dimiliki oleh satu produk

        // Contoh:
        // Produk "Kaos Oversize"
        // memiliki banyak varian ukuran dan warna

        return $this->belongsTo(Produk::class);
    }
}