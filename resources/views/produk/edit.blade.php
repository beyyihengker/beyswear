@extends('layouts.app')

@section('title', 'Edit Produk — BeysWear Fashion')

@section('content')

<section class="form-box">

    <div class="tabel-header">
        <h1>Edit Produk</h1>
    </div>

    {{-- enctype multipart/form-data diperlukan agar foto baru dapat diupload --}}
    <form action="{{ route('produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data" class="form-data">

        @csrf

        {{-- Laravel hanya mendukung GET dan POST secara langsung,
             sehingga PUT disimulasikan menggunakan method spoofing --}}
        @method('PUT')

        <div class="form-row">

            {{-- old() digunakan agar input tetap terisi jika validasi gagal --}}
            <div class="form-grup">
                <input type="text" name="kode" value="{{ old('kode', $produk->kode) }}" placeholder="Masukkan kode produk">
            </div>

            <div class="form-grup">
                <input type="text" name="nama" value="{{ old('nama', $produk->nama) }}" placeholder="Masukkan nama produk">
            </div>

            <div class="form-grup">

                {{-- Daftar kategori harus konsisten dengan validasi kategori pada ProdukController --}}
                <select name="kategori">
                    <option value="">Pilih Kategori</option>
                    <option value="Atasan" {{ old('kategori', $produk->kategori) == 'Atasan' ? 'selected' : '' }}>Atasan</option>
                    <option value="Bawahan" {{ old('kategori', $produk->kategori) == 'Bawahan' ? 'selected' : '' }}>Bawahan</option>
                    <option value="Dress" {{ old('kategori', $produk->kategori) == 'Dress' ? 'selected' : '' }}>Dress</option>
                    <option value="Outer / Jaket" {{ old('kategori', $produk->kategori) == 'Outer / Jaket' ? 'selected' : '' }}>Outer / Jaket</option>
                    <option value="Aksesori" {{ old('kategori', $produk->kategori) == 'Aksesori' ? 'selected' : '' }}>Aksesori</option>
                </select>

            </div>

            <div class="form-grup">
                <input type="number" name="harga" value="{{ old('harga', $produk->harga) }}" placeholder="Masukkan harga produk">
            </div>

            <div class="form-grup">

                {{-- Jika tidak memilih file baru, foto lama tetap digunakan oleh controller --}}
                <input type="file" name="foto">

            </div>

            <div class="aksi-btn">
                <button type="submit" class="btn btn-primer">Update Produk</button>
                <a href="{{ route('produk.index') }}" class="btn btn-primer">Kembali</a>
            </div>

        </div>

    </form>

</section>

@endsection