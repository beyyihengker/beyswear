<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data master produk awal.
        // Digunakan untuk menyediakan data contoh setelah proses seeding
        // sehingga aplikasi dapat langsung digunakan tanpa input produk manual.
        \App\Models\Produk::create([
            'kode' => 'BRG001',
            'nama' => 'Nevadi Ki Basic Tee',
            'kategori' => 'Atasan',
            'harga' => 110000,
            'foto' => null,
            'tersedia' => true,
        ]);

        // Produk contoh kategori bawahan.
        \App\Models\Produk::create([
            'kode' => 'BRG003',
            'nama' => 'Celana Chino',
            'kategori' => 'Bawahan',
            'harga' => 150000,
            'foto' => null,
            'tersedia' => true,
        ]);
    }
}