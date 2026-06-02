@extends('layouts.app')

@section('title', 'Penjualan — BeysWear Fashion')

@section('content')

<section class="form-box">
    <h3 class="seksi-label" id="lbl-form">Cari Data Transaksi</h3>

    {{-- Filter transaksi menggunakan method GET agar parameter pencarian tetap muncul di URL --}}
    <form action="{{ route('penjualan') }}" method="GET">
        <div class="form-row">

            <div class="form-grup">
                <input type="text"
                    name="keyword"
                    value="{{ request('keyword') }}"
                    placeholder="cth. Basic Tee / TRX-001">
            </div>

            <div class="form-grup">
                <select name="kategori">
                    <option value="">Semua Kategori</option>
                    <option value="Atasan" {{ request('kategori') == 'Atasan' ? 'selected' : '' }}>Atasan</option>
                    <option value="Bawahan" {{ request('kategori') == 'Bawahan' ? 'selected' : '' }}>Bawahan</option>
                    <option value="Dress" {{ request('kategori') == 'Dress' ? 'selected' : '' }}>Dress</option>
                    <option value="Outer / Jaket" {{ request('kategori') == 'Outer / Jaket' ? 'selected' : '' }}>Outer / Jaket</option>
                    <option value="Aksesori" {{ request('kategori') == 'Aksesori' ? 'selected' : '' }}>Aksesori</option>
                </select>
            </div>

            <div class="form-grup">
                <input type="date"
                    name="tanggal"
                    value="{{ request('tanggal') }}">
            </div>

            <button class="btn btn-primer" type="submit">
                Cari
            </button>

            {{-- Menghapus seluruh parameter filter yang sedang aktif --}}
            <a href="{{ route('penjualan') }}" class="btn btn-sekunder">
                Reset
            </a>
        </div>
    </form>
</section>

<section class="form-box">
    <h3 class="seksi-label">Tambah Transaksi</h3>

    {{-- Area pemilihan item sebelum dimasukkan ke keranjang transaksi --}}
    <div class="form-row">
        <div class="form-grup">
            <input type="text" id="kode" placeholder="Kode Barang" readonly>
        </div>

        <div class="form-grup">
            <select id="produkSelect">
                <option value="">Pilih Produk</option>
                @foreach($produkAll as $p)
                    <option value="{{ $p->id }}">{{ $p->nama }}</option>
                @endforeach
            </select>
            <span id="stokInfo" style="font-size:.75rem;margin-top:4px;"></span>
        </div>

        <div class="form-grup">
            <select id="ukuranSelect">
                <option value="">Pilih Ukuran</option>
            </select>
        </div>

        <div class="form-grup">
            <select id="warnaSelect">
                <option value="">Pilih Warna</option>
            </select>
        </div>

        <div class="form-grup">
            <input type="number" id="qty" placeholder="Qty" min="1">
            <span id="qtyError" style="font-size:.75rem;color:#c0392b;"></span>
        </div>

        <div class="form-grup" style="max-width:150px;">
            <button type="button" id="btnAddCart" class="btn btn-primer">
                Tambah Item
            </button>
        </div>
    </div>

    {{-- Form ini mengirim seluruh item yang sudah masuk cart ke PenjualanController --}}
    <form action="{{ route('penjualan.store') }}" method="POST" style="margin-top:20px;">
        @csrf

        {{-- Hidden input cart akan dibuat melalui JavaScript agar data item terkirim ke server --}}
        <div id="cartInputs"></div>

        <div class="form-row">
            <div class="form-grup">
                <select name="pembayaran" required>
                    <option value="">Pilih Pembayaran</option>
                    <option value="Cash">Cash</option>
                    <option value="QRIS">QRIS</option>
                </select>
            </div>

            <div class="form-grup" style="max-width:180px;">
                <button type="submit" class="btn btn-primer">
                    Simpan Transaksi
                </button>
            </div>
        </div>

        <div class="tabel-scroll" style="margin-top:18px;">
            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Ukuran</th>
                        <th>Warna</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                {{-- Isi cart transaksi ditampilkan melalui JavaScript --}}
                <tbody id="cartTable">
                    <tr>
                        <td colspan="7" style="text-align:center;">
                            Belum ada item.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </form>
</section>

<section class="form-box">
    <div class="tabel-header">
        <h3>Data Seluruh Transaksi</h3>
    </div>

    <div class="tabel-scroll">

        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Produk</th>
                    <th>Ukuran</th>
                    <th>Warna</th>
                    <th>Qty</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Pembayaran</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach($transaksi as $t)
                <tr>
                    <td>{{ $t->kode_transaksi }}</td>

                    <td>
                        {{-- Mendukung struktur transaksi baru multi-item dari detail_transaksi --}}
                        @if($t->details->count() > 0)
                            @foreach($t->details as $d)
                                <div>{{ $d->produk }}</div>
                            @endforeach
                        @else
                            {{-- Fallback untuk data transaksi lama --}}
                            {{ $t->produk ?? '-' }}
                        @endif
                    </td>

                    <td>
                        @if($t->details->count() > 0)
                            @foreach($t->details as $d)
                                <div>{{ $d->ukuran ?? '-' }}</div>
                            @endforeach
                        @else
                            {{ $t->ukuran ?? '-' }}
                        @endif
                    </td>

                    <td>
                        @if($t->details->count() > 0)
                            @foreach($t->details as $d)
                                <div>{{ $d->warna ?? '-' }}</div>
                            @endforeach
                        @else
                            {{ $t->warna ?? '-' }}
                        @endif
                    </td>

                    <td>
                        @if($t->details->count() > 0)
                            @foreach($t->details as $d)
                                <div>{{ $d->qty ?? $d->jumlah }}</div>
                            @endforeach
                        @else
                            {{ $t->qty ?? '-' }}
                        @endif
                    </td>

                    <td>{{ \Carbon\Carbon::parse($t->tanggal)->format('d/m/Y') }}</td>
                    <td>Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
                    <td>{{ $t->pembayaran }}</td>

                    <td>
                        {{-- Transaksi dibatalkan tidak dihapus agar riwayat tetap tersimpan --}}
                        @if($t->status === 'dibatalkan')
                            <span class="badge" style="background:#fdecea;color:#c0392b;">Dibatalkan</span>
                        @else
                            <span class="badge" style="background:#eaf4ea;color:#1e8449;">Berhasil</span>
                        @endif
                    </td>

                    <td class="aksi-btn">
                        <a href="{{ route('penjualan.struk', $t->id) }}"
                            target="_blank"
                            class="btn btn-primer">
                            Struk
                        </a>

                        {{-- Pembatalan transaksi akan mengubah status dan mengembalikan stok --}}
                        @if($t->status !== 'dibatalkan')
                            <form action="{{ route('penjualan.cancel', $t->id) }}" method="POST"
                                onsubmit="return confirm('Batalkan transaksi ini dan kembalikan stok?')">
                                @csrf
                                @method('PATCH')

                                <button type="submit" class="btn btn-primer" style="background:#c0392b;">
                                    Batalkan
                                </button>
                            </form>
                        @else
                            <button class="btn btn-sekunder" disabled>
                                Selesai
                            </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

@push('scripts')

<script>
    // Data produk dan varian dikirim ke JavaScript untuk dropdown ukuran, warna, stok, dan cart.
    window.produkData = @json($produkAll);
</script>

@endpush

@endsection