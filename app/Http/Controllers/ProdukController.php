<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil data produk beserta relasi varians
        // Relasi varians berisi ukuran, warna, dan stok produk
        $produk = Produk::with('varians')

            // Filter berdasarkan kode produk jika input kode diisi
            ->when($request->filled('kode'), function ($query) use ($request) {
                $query->where('kode', 'LIKE', '%' . $request->kode . '%');
            })

            // Filter berdasarkan nama produk jika input nama diisi
            ->when($request->filled('nama'), function ($query) use ($request) {
                $query->where('nama', 'LIKE', '%' . $request->nama . '%');
            })

            // Filter berdasarkan kategori jika kategori dipilih
            ->when($request->filled('kategori'), function ($query) use ($request) {
                $query->where('kategori', $request->kategori);
            })

            // Filter berdasarkan ukuran dari tabel produk_varians
            ->when($request->filled('ukuran'), function ($query) use ($request) {
                $query->whereHas('varians', function ($varian) use ($request) {
                    $varian->where('ukuran', $request->ukuran);
                });
            })

            // Urutkan produk dari data paling lama
            ->orderBy('created_at', 'asc')

            // Batasi 10 data per halaman
            ->paginate(10)

            // Agar filter tetap terbawa saat pindah halaman pagination
            ->withQueryString();

        // Mengirim data produk ke halaman index produk
        return view('produk.index', compact('produk'));
    }

    public function create()
    {
        // Menampilkan halaman tambah produk
        return view('produk.create');
    }

    private function generateKodeProduk()
    {
        // Mengambil produk terakhir termasuk produk yang sudah di-soft delete
        // Tujuannya agar kode produk tidak bentrok dengan data lama/terhapus
        $lastProduk = Produk::withTrashed()
            ->where('kode', 'LIKE', 'BRG%')
            ->orderBy('id', 'desc')
            ->first();

        // Jika belum ada produk sama sekali, kode pertama adalah BRG001
        if (!$lastProduk) {
            return 'BRG001';
        }

        // Mengambil angka dari kode produk terakhir
        // Contoh: BRG005 menjadi 5
        $lastNumber = (int) str_replace('BRG', '', $lastProduk->kode);

        // Menambahkan angka kode berikutnya
        $newNumber = $lastNumber + 1;

        // Mengembalikan format kode produk baru
        // Contoh: 6 menjadi BRG006
        return 'BRG' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    public function store(Request $request)
    {
        // Validasi data input tambah produk
        $validated = $request->validate([
            'nama' => 'required|min:3',
            'kategori' => 'required|in:Atasan,Bawahan,Dress,Outer / Jaket,Aksesori',
            'harga' => 'required|numeric|min:1',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        // Jika user mengupload foto produk
        if ($request->hasFile('foto')) {

            // Simpan foto ke storage/app/public/produk
            $path = $request->file('foto')
                ->store('produk', 'public');

            // Simpan path foto ke data yang akan masuk database
            $validated['foto'] = $path;
        }

        // Membuat kode produk otomatis
        $validated['kode'] = $this->generateKodeProduk();

        // Produk baru otomatis dianggap tersedia
        $validated['tersedia'] = true;

        // Menyimpan produk baru ke database
        Produk::create($validated);

        // Redirect ke halaman produk dengan pesan sukses
        return redirect()
            ->route('produk.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Produk $produk)
    {
        // Mengambil detail produk beserta variannya
        $produk->load('varians');

        // Menampilkan halaman detail produk
        return view('produk.show', compact('produk'));
    }

    public function edit(Produk $produk)
    {
        // Menampilkan halaman edit produk
        return view('produk.edit', compact('produk'));
    }

    public function update(Request $request, Produk $produk)
    {
        // Validasi data input edit produk
        $validated = $request->validate([
            'nama' => 'required|min:3',
            'kategori' => 'required|in:Atasan,Bawahan,Dress,Outer / Jaket,Aksesori',
            'harga' => 'required|numeric|min:1',
           'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        // Jika ada foto baru yang diupload
        if ($request->hasFile('foto')) {

            // Simpan foto baru ke storage
            $path = $request->file('foto')
                ->store('produk', 'public');

            // Update path foto
            $validated['foto'] = $path;
        }

        // Memperbarui data produk
        $produk->update($validated);

        // Redirect ke halaman produk dengan pesan sukses
        return redirect()
            ->route('produk.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Mencari produk berdasarkan id
        $produk = \App\Models\Produk::findOrFail($id);

        // Menghapus produk
        // Karena model Produk memakai SoftDeletes, data tidak benar-benar hilang dari database
        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk  berhasil dihapus.');
    }

    public function trash()
    {
        // Mengambil produk yang sudah di-soft delete
        $produk = Produk::onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->paginate(10);

        // Menampilkan halaman produk terhapus
        return view('produk.trash', compact('produk'));
    }

    public function restore($id)
    {
        // Mencari produk yang sudah di-soft delete
        $produk = Produk::onlyTrashed()->findOrFail($id);

        // Mengembalikan produk ke data aktif
        $produk->restore();

        return redirect()
            ->route('produk.trash')
            ->with('success', 'Produk berhasil dikembalikan.');
    }

    public function search(Request $request)
    {
        // Mengambil keyword dari request AJAX
        $keyword = $request->input('keyword');

        // Mencari produk berdasarkan nama atau kategori
        $produk = Produk::query()
            ->where('nama', 'LIKE', "%{$keyword}%")
            ->orWhere('kategori', 'LIKE', "%{$keyword}%")
            ->get();

        // Mengembalikan hasil pencarian dalam bentuk JSON
        return response()->json($produk);
    }
}