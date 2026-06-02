@extends('layouts.app')

@section('content')

<section class="form-box">
    <div class="tabel-header">
        <h1>Tambah Produk</h1>

        {{-- Menampilkan seluruh pesan validasi dari controller jika input gagal diproses --}}
        @if ($errors->any())
            <div class="alert alert-error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif
    </div>

        {{-- enctype multipart/form-data wajib digunakan karena form mengirim file gambar --}}
        <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data" class="form-data">

            @csrf

            <div class="form-row">

                <div class="form-grup">

                    {{-- Kode produk dibuat otomatis di controller melalui generateKodeProduk() --}}
                    <input type="text" value="Kode barang" readonly>

                </div>

                <div class="form-grup">
                    <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama produk" required>
                </div>

                <div class="form-grup">

                    {{-- Daftar kategori harus selaras dengan validasi kategori pada ProdukController --}}
                    <select name="kategori" required>
                        <option value="">Pilih Kategori</option>
                        <option value="Atasan">Atasan</option>
                        <option value="Bawahan">Bawahan</option>
                        <option value="Dress">Dress</option>
                        <option value="Outer / Jaket">Outer / Jaket</option>
                        <option value="Aksesori">Aksesori</option>
                    </select>

                </div>

                <div class="form-grup">
                    <input type="number" name="harga" value="{{ old('harga') }}" placeholder="Masukkan harga produk" required>
                </div>

                <div class="form-grup">

                    {{-- Format file yang diterima dibatasi oleh validasi di ProdukController --}}
                    <input type="file" name="foto">

                    <small style="font-size: 12px; color: #777;">
                        Format foto: JPG, JPEG, PNG, WEBP
                    </small>
                </div>

                <div class="form-action">
                    <button id="btnTambah" class="btn btn-primer">Simpan Produk</button>
                    <a href="{{ route('produk.index') }}" class="btn btn-primer"> Kembali</a>
                </div>

            </div>
        </form>

</section>

@endsection