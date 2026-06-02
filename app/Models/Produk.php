<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ProdukVarian;

class Produk extends Model
{
    // SoftDeletes digunakan agar data produk tidak benar-benar terhapus
    // Saat delete(), data hanya diberi deleted_at
    use SoftDeletes;

    // Menentukan nama tabel
    // Laravel default akan mencari tabel "produks"
    // tetapi tetap ditulis agar lebih jelas
    protected $table = 'produks';

    // Field yang boleh diisi menggunakan create() atau update()
    protected $fillable = [

        // Kode produk otomatis
        // Contoh: BRG001
        'kode',

        // Nama produk
        'nama',

        // Kategori produk
        // Contoh: Atasan, Dress, Aksesori
        'kategori',

        // Harga produk
        'harga',

        // Path/lokasi foto produk
        'foto',

        // Status apakah produk tersedia atau tidak
        'tersedia'
    ];

    // Mengubah tipe data otomatis saat diambil dari database
    protected $casts = [

        // tersedianya produk otomatis menjadi true/false
        'tersedia' => 'boolean',

        // Harga otomatis menjadi decimal dengan 2 angka di belakang koma
        'harga' => 'decimal:2',
    ];

    public function scopeTersedia($query)
    {
        // Local scope
        // Digunakan untuk mengambil produk yang tersedia saja

        // Contoh penggunaan:
        // Produk::tersedia()->get();

        return $query->where('tersedia', true);
    }

    public function transaksis()
    {
        return $this->belongsToMany(
            Transaksi::class,
            'detail_transaksi'
        );
    }

    public function varians()
    {
        return $this->hasMany(ProdukVarian::class);
    }
}