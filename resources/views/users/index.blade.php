@extends('layouts.app')

@section('title', 'Manajemen User — BeysWear Fashion')

@section('content')

<section class="form-box">

    <h3 class="seksi-label">Tambah User Baru</h3>

    @if ($errors->any())
        <div class="alert alert-error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    {{-- Form pembuatan akun kasir baru --}}
    <form action="{{ route('users.store') }}" method="POST">

        @csrf

        <div class="form-row">

            <div class="form-grup">
                <input type="text"
                    name="name"
                    placeholder="Nama User"
                    required>
            </div>

            <div class="form-grup">
                <input type="email"
                    name="email"
                    placeholder="Email"
                    required>
            </div>

            <div class="form-grup">
                <input type="text"
                    name="no_hp"
                    placeholder="No HP"
                    inputmode="numeric"
                    pattern="[0-9]+"
                    maxlength="15"
                    required>
            </div>

            {{-- Role diset otomatis sebagai kasir --}}
            <input type="hidden"
                name="role"
                value="kasir">

            <div class="form-grup">
                <input type="password"
                    name="password"
                    placeholder="Password"
                    required>
            </div>

            <button type="submit"
                class="btn btn-primer">
                Tambah User
            </button>

        </div>

    </form>

</section>

<section class="form-box">

    <div class="tabel-header">
        <h3>Data User BeysWear</h3>
    </div>

    <div class="tabel-scroll">

        <table>

            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No HP</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                {{-- Menampilkan seluruh user yang berhasil diambil dari database --}}
                @foreach($users as $u)

                {{-- Data utama user --}}
                <tr>

                    <td>{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                    <td>{{ $u->no_hp }}</td>

                    <td>
                        <span class="badge">
                            {{ $u->role }}
                        </span>
                    </td>

                    <td class="aksi-btn">

                        {{-- Menampilkan form edit inline tanpa pindah halaman --}}
                        <button type="button"
                            class="btn btn-primer"
                            onclick="toggleEdit({{ $u->id }})">
                            Edit
                        </button>

                        {{-- Akun admin tidak boleh dihapus --}}
                        @if($u->role !== 'admin')

                            <form action="{{ route('users.destroy', $u->id) }}"
                                method="POST"
                                onsubmit="return confirm('Hapus user ini?')">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="btn btn-primer"
                                    style="background:#c0392b;">
                                    Hapus
                                </button>

                            </form>

                        @else

                            <button type="button"
                                class="btn btn-sekunder"
                                disabled>
                                Admin Tidak Bisa Dihapus
                            </button>

                        @endif

                    </td>

                </tr>

                {{-- Form edit user yang disembunyikan secara default --}}
                <tr id="edit-row-{{ $u->id }}"
                    style="display:none; background:#f8f9fb;">

                    <td colspan="5">

                        {{-- Update data user menggunakan route users.update --}}
                        <form action="{{ route('users.update', $u->id) }}"
                            method="POST">

                            @csrf
                            @method('PUT')

                            <div class="form-row">

                                <div class="form-grup">
                                    <input type="text"
                                        name="name"
                                        value="{{ $u->name }}"
                                        required>
                                </div>

                                <div class="form-grup">
                                    <input type="email"
                                        name="email"
                                        value="{{ $u->email }}"
                                        required>
                                </div>

                                <div class="form-grup">
                                    <input type="text"
                                        name="no_hp"
                                        value="{{ $u->no_hp }}"
                                        required>
                                </div>

                                <button type="submit"
                                    class="btn btn-primer">
                                    Simpan
                                </button>

                                {{-- Menutup form edit tanpa menyimpan perubahan --}}
                                <button type="button"
                                    class="btn btn-sekunder"
                                    onclick="toggleEdit({{ $u->id }})">
                                    Batal
                                </button>

                            </div>

                        </form>

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</section>

@endsection