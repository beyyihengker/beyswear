@extends('layouts.app')

@section('title', 'Detail Produk — BeysWear Fashion')

@section('content')

<section class="form-box">

    <div class="tabel-header">
        <h3>Detail Produk</h3>
    </div>

    <div class="detail-container">
        <div class="detail-kiri">

            <div class="detail-item">
                <label>Kode Produk</label>
                <input type="text" value="{{ $produk->kode }}" readonly>
            </div>

            <div class="detail-item">
                <label>Nama Produk</label>
                <input type="text" value="{{ $produk->nama }}" readonly>
            </div>

            <div class="detail-item">
                <label>Kategori</label>
                <input type="text" value="{{ $produk->kategori }}" readonly>
            </div>

            <div class="detail-item">
                <label>Harga Produk</label>
                <input type="text" value="Rp {{ number_format($produk->harga, 0, ',', '.') }}"readonly>
            </div>

            <div class="detail-item">
                <label>Total Stok</label>

                {{-- Total stok dihitung dari akumulasi seluruh stok varian produk --}}
                <input type="text" value="{{ $produk->varians->sum('stok') }} pcs" readonly>
            </div>

            <div class="detail-item">
                <label>Total Varian</label>

                {{-- Jumlah kombinasi ukuran/warna yang dimiliki produk --}}
                <input type="text" value="{{ $produk->varians->count() }} Varian" readonly>
            </div>

            <div class="detail-item">
                <label>Ukuran Tersedia</label>

                {{-- Menampilkan daftar ukuran unik dari seluruh varian --}}
                <textarea rows="3" readonly>{{ $produk->varians->pluck('ukuran')->filter()->unique()->implode(', ') ?: '-' }}</textarea>
            </div>

            <div class="detail-item">
                <label>Warna Tersedia</label>

                {{-- Menampilkan daftar warna unik dari seluruh varian --}}
                <textarea rows="3" readonly>{{ $produk->varians->pluck('warna')->filter()->unique()->implode(', ') ?: '-' }}</textarea>
            </div>

        </div>

        <div class="detail-kanan">

            @if($produk->foto)

                {{-- Foto produk diambil dari storage/public --}}
                <img src="{{ asset('storage/' . $produk->foto) }}" class="detail-foto">

            @else

                <div class="foto-kosong">
                    Tidak ada foto
                </div>

            @endif

            <a href="{{ route('produk.index') }}" class="btn btn-primer">Kembali</a>

        </div>

    </div>

    <div class="form-varian">

        <div class="tabel-header">
            <h3>Tambah Varian Produk</h3>
        </div>

        {{-- Error khusus validasi varian dari ProdukVarianController --}}
        @error('varian')
            <div class="alert alert-error">
                {{ $message }}
            </div>
        @enderror

        {{-- Form penambahan ukuran/warna/stok produk --}}
        <form action="{{ route('varian.store') }}" method="POST">

            @csrf

            {{-- Produk tujuan pengisian varian --}}
            <input type="hidden" name="produk_id" value="{{ $produk->id }}">

            <div class="form-row">

                <div class="form-grup">
                    <select name="ukuran">
                        <option value="">Ukuran (Opsional)</option>
                        <option value="S">S</option>
                        <option value="M">M</option>
                        <option value="L">L</option>
                        <option value="XL">XL</option>
                    </select>
                </div>

                <div class="form-grup">
                    <input type="text" name="warna" placeholder="Masukkan warna (opsional)">
                </div>

                <div class="form-grup">
                    <input type="number" name="stok" placeholder="Masukkan stok" min="1" required>
                </div>

                <div class="form-action">
                    <button type="submit" class="btn btn-primer">Tambah Varian</button>
                </div>

            </div>

        </form>

    </div>

    <br>

    <div class="tabel-box">
        <div class="tabel-header">
            <h3>Stok Tiap Varian</h3>

            {{-- Jumlah total record varian yang dimiliki produk --}}
            <span class="chip">{{ $produk->varians->count() }} varian</span>
        </div>

        <div class="tabel-scroll">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Ukuran</th>
                        <th>Warna</th>
                        <th>Stok</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    {{-- Menampilkan seluruh varian yang berelasi dengan produk --}}
                    @forelse($produk->varians as $varian)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $varian->ukuran ?: '-' }}</td>
                            <td>{{ $varian->warna ?: '-' }}</td>
                            <td>{{ $varian->stok }} pcs</td>

                            <td class="status-cell">

                                {{-- Klasifikasi status stok untuk monitoring cepat --}}
                                @if($varian->stok == 0)
                                    <span class="chip status-badge" style="color:#c0392b;">Habis</span>

                                @elseif($varian->stok < 5)
                                    <span class="chip status-badge" style="color:#d35400;">Menipis</span>

                                @else
                                    <span class="chip status-badge" style="color:#1e8449;">Aman</span>

                                @endif

                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" style="text-align:center;">
                                Belum ada varian produk.
                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>
        </div>
    </div>

</section>

@endsection