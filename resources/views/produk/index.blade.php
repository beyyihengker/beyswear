@extends('layouts.app')

@section('content')

<section class="form-box">
    <h3 class="seksi-label" id="lbl-form">Cari Data Produk</h3>

    {{-- Form filter produk menggunakan method GET agar parameter pencarian muncul di URL --}}
    <form action="{{ route('produk.index') }}" method="GET">
        <div class="form-row">

            <div class="form-grup">
                <input type="text"
                    name="kode"
                    value="{{ request('kode') }}"
                    placeholder="cth. BRG001">
            </div>

            <div class="form-grup">
                <input type="text"
                    name="nama"
                    value="{{ request('nama') }}"
                    placeholder="cth. Nevadi Ki">
            </div>

            <div class="form-grup">

                {{-- Filter ukuran akan memanfaatkan relasi produk -> varians di controller --}}
                <select name="ukuran">
                    <option value="">Ukuran</option>
                    <option value="S" {{ request('ukuran') == 'S' ? 'selected' : '' }}>S</option>
                    <option value="M" {{ request('ukuran') == 'M' ? 'selected' : '' }}>M</option>
                    <option value="L" {{ request('ukuran') == 'L' ? 'selected' : '' }}>L</option>
                    <option value="XL" {{ request('ukuran') == 'XL' ? 'selected' : '' }}>XL</option>
                </select>

            </div>

            <div class="form-grup">

                {{-- Daftar kategori harus konsisten dengan data master produk --}}
                <select name="kategori">
                    <option value="">Kategori</option>
                    <option value="Atasan" {{ request('kategori') == 'Atasan' ? 'selected' : '' }}>Atasan</option>
                    <option value="Bawahan" {{ request('kategori') == 'Bawahan' ? 'selected' : '' }}>Bawahan</option>
                    <option value="Dress" {{ request('kategori') == 'Dress' ? 'selected' : '' }}>Dress</option>
                    <option value="Outer / Jaket" {{ request('kategori') == 'Outer / Jaket' ? 'selected' : '' }}>Outer / Jaket</option>
                    <option value="Aksesori" {{ request('kategori') == 'Aksesori' ? 'selected' : '' }}>Aksesori</option>
                </select>

            </div>

            <button class="btn btn-primer" type="submit">
                Cari
            </button>

            {{-- Menghapus seluruh parameter filter yang sedang aktif --}}
            <a href="{{ route('produk.index') }}" class="btn btn-sekunder">
                Reset
            </a>
        </div>
    </form>
</section>

<section class="form-box">
    <div class="tabel-header">
        <h1>Data Produk</h1>

        <div class="header-actions">

            {{-- Menampilkan data produk yang terkena soft delete --}}
            <a href="{{ route('produk.trash') }}" class="header-link">
                Produk Terhapus
            </a>

            <span class="header-divider">|</span>

            <a href="{{ route('produk.create') }}" class="header-link">
                + Tambah Produk
            </a>
        </div>
    </div>

    <div class="tabel-scroll">

        <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    {{-- Data berasal dari pagination ProdukController --}}
                    @foreach($produk as $item)

                    <tr>

                        <td>{{ $loop->iteration }}</td>

                        <td>

                            @if($item->foto)

                                {{-- File gambar disimpan pada storage/public --}}
                                <img src="{{ asset('storage/' . $item->foto) }}" width="80" class="foto-produk">

                            @else

                                <span class="text-muted">
                                    Tidak ada foto
                                </span>

                            @endif

                        </td>

                        <td>{{ $item->kode }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->kategori }}</td>
                        <td>Rp {{ number_format($item->harga) }}</td>

                        <td>

                            {{-- Aksi CRUD produk --}}
                            <div class="aksi-btn">

                                <a href="{{ route('produk.show', $item->id) }}" class="btn btn-primer">Detail</a>

                                <a href="{{ route('produk.edit', $item->id) }}" class="btn btn-primer">Edit</a>

                                <form action="{{ route('produk.destroy', $item->id) }}" method="POST" style="display:inline;">

                                    @csrf
                                    @method('DELETE')

                                    {{-- Penghapusan menggunakan soft delete, bukan menghapus permanen dari database --}}
                                    <button type="submit" class="btn btn-primer" style="background:#c0392b;" onclick="return confirm('Yakin hapus produk?')">Hapus</button>

                                </form>

                            </div>

                        </td>

                    </tr>

                    @endforeach

                </tbody>

        </table>

    </div>

        {{-- Pagination mempertahankan parameter filter melalui withQueryString() di controller --}}
        <div class="pagination-box">
            {{ $produk->links() }}
        </div>

</section>

@endsection