<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProdukVarian;

class ProdukVarianController extends Controller
{
    public function store(Request $request)
    {
        // Mengambil produk_id dari request
        // produk_id digunakan untuk menentukan varian ini milik produk yang mana
        $produkId = $request->produk_id;

        // Mengubah input kosong menjadi null
        // Tujuannya agar data kosong konsisten disimpan sebagai null, bukan string kosong
        $request->merge([
            'ukuran' => $request->ukuran ?: null,
            'warna'  => $request->warna ?: null,
        ]);

        // Validasi manual
        // Minimal salah satu dari ukuran atau warna harus diisi
        if (!$request->ukuran && !$request->warna) {
            return redirect()
                ->route('produk.show', $produkId)
                ->withInput()
                ->withErrors([
                    'varian' => 'Ukuran atau warna minimal salah satu harus diisi.'
                ]);
        }

        // Validasi data varian produk
        $validated = $request->validate([

            // produk_id wajib ada dan harus terdaftar di tabel produks
            'produk_id' => 'required|exists:produks,id',

            // ukuran boleh kosong
            'ukuran' => 'nullable|string',

            // warna boleh kosong
            'warna' => 'nullable|string',

            // stok wajib angka dan minimal 0
            'stok' => 'required|integer|min:0',
        ]);

        // Mengecek apakah varian dengan kombinasi produk, ukuran, dan warna yang sama sudah ada
        $existing = ProdukVarian::where('produk_id', $validated['produk_id'])

            // Mengecek ukuran
            ->where(function ($query) use ($validated) {

                // Jika ukuran null, cari data yang ukuran-nya juga null
                $validated['ukuran'] === null
                    ? $query->whereNull('ukuran')

                    // Jika ukuran tidak null, cari ukuran yang sama
                    : $query->where('ukuran', $validated['ukuran']);
            })

            // Mengecek warna
            ->where(function ($query) use ($validated) {

                // Jika warna null, cari data yang warna-nya juga null
                $validated['warna'] === null
                    ? $query->whereNull('warna')

                    // Jika warna tidak null, cari warna yang sama
                    : $query->where('warna', $validated['warna']);
            })

            // Mengambil data pertama yang cocok
            ->first();

        // Jika varian sudah ada, stok tidak membuat data baru
        // tetapi ditambahkan ke stok varian yang sudah ada
        if ($existing) {

            // Menambahkan stok varian lama
            $existing->increment('stok', $validated['stok']);

            return redirect()
                ->route('produk.show', $validated['produk_id'])
                ->with('success', 'Stok varian berhasil ditambahkan.');
        }

        // Jika varian belum ada, buat data varian baru
        ProdukVarian::create($validated);

        return redirect()
            ->route('produk.show', $validated['produk_id'])
            ->with('success', 'Varian berhasil ditambahkan');
    }
}