<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProdukVarian;

class ProdukVarianSeeder extends Seeder
{
    public function run(): void
    {
        // Data contoh varian untuk produk dengan id = 1.
        // Produk terkait harus sudah dibuat terlebih dahulu
        // melalui ProdukSeeder karena menggunakan foreign key produk_id.

        ProdukVarian::create([
            'produk_id' => 1,
            'ukuran' => 'M',
            'warna' => 'Hitam',
            'stok' => 10,
        ]);

        // Kombinasi ukuran dan warna yang berbeda
        // disimpan sebagai record terpisah agar stok
        // dapat dikelola per varian.
        ProdukVarian::create([
            'produk_id' => 1,
            'ukuran' => 'L',
            'warna' => 'Hitam',
            'stok' => 5,
        ]);

        ProdukVarian::create([
            'produk_id' => 1,
            'ukuran' => 'M',
            'warna' => 'Putih',
            'stok' => 8,
        ]);
    }
}