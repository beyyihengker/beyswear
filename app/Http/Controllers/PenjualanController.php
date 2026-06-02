<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil data transaksi beserta relasi detail transaksi
        // with('details') digunakan agar detail item otomatis ikut diambil
        $transaksi = Transaksi::with('details')

            // Filter pencarian keyword
            // Hanya dijalankan jika keyword diisi
            ->when($request->filled('keyword'), function ($query) use ($request) {

                $keyword = $request->keyword;

                $query->where(function ($q) use ($keyword) {

                    // Cari berdasarkan kode transaksi
                    $q->where('kode_transaksi', 'LIKE', "%{$keyword}%")

                        // Cari berdasarkan nama produk lama
                        ->orWhere('produk', 'LIKE', "%{$keyword}%")

                        // Cari berdasarkan detail transaksi baru
                        ->orWhereHas('details', function ($detail) use ($keyword) {

                            $detail->where('produk', 'LIKE', "%{$keyword}%")
                                ->orWhere('warna', 'LIKE', "%{$keyword}%")
                                ->orWhere('ukuran', 'LIKE', "%{$keyword}%");
                        });
                });
            })

            // Filter kategori
            // Hanya dijalankan jika kategori dipilih
            ->when($request->filled('kategori'), function ($query) use ($request) {

                $kategori = $request->kategori;

                $query->where(function ($q) use ($kategori) {

                    // Mencari kategori dari relasi produk baru
                    $q->whereHas('details.produkData', function ($produk) use ($kategori) {

                        $produk->where('kategori', $kategori);
                    })

                    // Backup pencarian kategori dari nama produk
                    ->orWhereHas('details', function ($detail) use ($kategori) {

                        $detail->where('produk', 'LIKE', "%{$kategori}%");
                    })

                    // Backup pencarian dari sistem transaksi lama
                    ->orWhereIn('produk', function ($sub) use ($kategori) {

                        $sub->select('nama')
                            ->from('produks')
                            ->where('kategori', $kategori);
                    });
                });
            })

            // Filter berdasarkan tanggal transaksi
            ->when($request->filled('tanggal'), function ($query) use ($request) {

                $query->whereDate('tanggal', $request->tanggal);
            })

            // Mengurutkan transaksi terbaru
            ->orderBy('created_at', 'desc')

            ->get();

        // Mengambil seluruh produk beserta variannya
        // Dipakai untuk dropdown transaksi
        $produkAll = Produk::with('varians')
            ->orderBy('nama')
            ->get();

        // Mengirim data ke halaman penjualan
        return view('penjualan', compact('transaksi', 'produkAll'));
    }

    public function store(Request $request)
    {
        // Validasi input transaksi
        $request->validate([

            // items wajib berupa array dan minimal ada 1 item
            'items' => 'required|array|min:1',

            // produk_id harus ada di tabel produks
            'items.*.produk_id' => 'required|exists:produks,id',

            // ukuran boleh kosong
            'items.*.ukuran' => 'nullable|string',

            // warna boleh kosong
            'items.*.warna' => 'nullable|string',

            // qty wajib angka dan minimal 1
            'items.*.qty' => 'required|integer|min:1',

            // pembayaran hanya boleh Cash atau QRIS
            'pembayaran' => 'required|in:Cash,QRIS',
        ]);

        // DB::transaction digunakan agar semua query berjalan bersamaan
        // Jika ada error, semua perubahan database akan dibatalkan
        DB::transaction(function () use ($request) {

            // Membuat kode transaksi otomatis
            // Contoh: TRX-001
            $kode = 'TRX-' . str_pad(
                Transaksi::count() + 1,
                3,
                '0',
                STR_PAD_LEFT
            );

            // Variabel total harga transaksi
            $total = 0;

            // Menyimpan detail item sementara sebelum insert database
            $detailData = [];

            // Loop semua item transaksi
            foreach ($request->items as $item) {

                // Mengambil data produk berdasarkan id
                $produk = Produk::findOrFail($item['produk_id']);

                // Mengambil relasi varian produk
                $varianQuery = $produk->varians();

                // Jika ukuran kosong cari ukuran null
                // Jika tidak kosong cari ukuran sesuai input
                empty($item['ukuran'])
                    ? $varianQuery->whereNull('ukuran')
                    : $varianQuery->where('ukuran', $item['ukuran']);

                // Jika warna kosong cari warna null
                // Jika tidak kosong cari warna sesuai input
                empty($item['warna'])
                    ? $varianQuery->whereNull('warna')
                    : $varianQuery->where('warna', $item['warna']);

                // Mengambil varian pertama yang cocok
                $varian = $varianQuery->first();

                // Jika varian tidak ditemukan
                // atau stok kurang dari qty transaksi
                if (!$varian || $varian->stok < $item['qty']) {

                    throw new \Exception('Stok varian tidak cukup.');
                }

                // Menghitung subtotal item
                $subtotal = $produk->harga * $item['qty'];

                // Menambahkan subtotal ke total transaksi
                $total += $subtotal;

                // Menyimpan detail item sementara
                $detailData[] = [

                    // Relasi produk
                    'produk_id' => $produk->id,

                    // Nama produk
                    'produk' => $produk->nama,

                    // Ukuran produk
                    'ukuran' => $item['ukuran'] ?? null,

                    // Warna produk
                    'warna' => $item['warna'] ?? null,

                    // Qty pembelian
                    'qty' => $item['qty'],

                    // Backup qty lama
                    'jumlah' => $item['qty'],

                    // Harga produk
                    'harga' => $produk->harga,

                    // Total harga item
                    'subtotal' => $subtotal,
                ];

                // Mengurangi stok varian
                $varian->decrement('stok', $item['qty']);
            }

            // Menyimpan transaksi utama
            $transaksi = Transaksi::create([

                // Kode transaksi otomatis
                'kode_transaksi' => $kode,

                // Tanggal transaksi
                'tanggal' => now(),

                // Total harga semua item
                'total_harga' => $total,

                // Metode pembayaran
                'pembayaran' => $request->pembayaran,

                // Status transaksi
                'status' => 'berhasil',
            ]);

            // Menyimpan seluruh detail item transaksi
            $transaksi->details()->createMany($detailData);
        });

        // Redirect kembali ke halaman penjualan
        return redirect()
            ->route('penjualan')
            ->with('success', 'Transaksi berhasil disimpan.');
    }

    public function struk(Transaksi $transaksi)
    {
        // Load relasi detail transaksi
        $transaksi->load('details');

        // Menampilkan halaman struk
        return view('struk', compact('transaksi'));
    }

    public function cancel(Transaksi $transaksi)
    {
        // Mencegah transaksi dibatalkan dua kali
        if ($transaksi->status === 'dibatalkan') {

            return back()->with(
                'error',
                'Transaksi sudah dibatalkan.'
            );
        }

        // Transaction database
        DB::transaction(function () use ($transaksi) {

            // Loop semua detail transaksi
            foreach ($transaksi->details as $detail) {

                // Mencari produk berdasarkan nama produk
                $produk = Produk::query()
                    ->where('nama', '=', $detail->produk)
                    ->first();

                // Jika produk tidak ditemukan skip
                if (!$produk) continue;

                // Mengambil relasi varian produk
                $varianQuery = $produk->varians();

                // Filter ukuran
                $detail->ukuran
                    ? $varianQuery->where('ukuran', $detail->ukuran)
                    : $varianQuery->whereNull('ukuran');

                // Filter warna
                $detail->warna
                    ? $varianQuery->where('warna', $detail->warna)
                    : $varianQuery->whereNull('warna');

                // Mengambil varian
                $varian = $varianQuery->first();

                // Jika varian ditemukan
                if ($varian) {

                    // Mengembalikan stok
                    $varian->increment('stok', $detail->qty);
                }
            }

            // Mengubah status transaksi menjadi dibatalkan
            $transaksi->update([
                'status' => 'dibatalkan'
            ]);
        });

        // Redirect kembali
        return back()->with(
            'success',
            'Transaksi dibatalkan dan stok dikembalikan.'
        );
    }
}