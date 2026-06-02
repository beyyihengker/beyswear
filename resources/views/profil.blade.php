@extends('layouts.app')

@section('content')

<div class="container-profil">

    {{-- Flash message dari controller setelah update profil atau password --}}
    @if(session('success'))
        <div class="alert alert-success animate-bounce-in">
            {{ session('success') }}
            <button onclick="this.parentElement.remove()">×</button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error animate-bounce-in">
            {{ session('error') }}
            <button onclick="this.parentElement.remove()">×</button>
        </div>
    @endif

    {{-- Ringkasan informasi akun pengguna yang sedang login --}}
    <div class="card-profil">

        <div class="profil-header">

            <div class="avatar-big">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>

            <div>
                <h2 style="color: #112250;">{{ $user->name }}</h2>

                {{-- Jabatan menggunakan accessor pada model User --}}
                <span class="badge">
                    {{ $user->jabatan ?? $user->role }}
                </span>
            </div>

        </div>

        <div class="form-grid">

            <div class="info-group">
                <label>Email</label>
                <p>{{ $user->email }}</p>
            </div>

            <div class="info-group">
                <label>Nomor Telepon</label>
                <p>{{ $user->no_hp ?? '-' }}</p>
            </div>

        </div>

        {{-- Menampilkan form edit profil dan ganti password --}}
        <button type="button"
            class="btn-edit"
            onclick="toggleEditProfil()">
            Edit Profil
        </button>

    </div>

    {{-- Form edit profil dan password disembunyikan secara default --}}
    <div id="editProfilBox" style="display: none; margin-top: 20px;">

        <div class="sb-card">

            <p class="sb-title">UPDATE INFORMASI</p>

            {{-- Mengirim data ke ProfilController@update --}}
            <form action="{{ route('profile.update') }}" method="POST">

                @csrf
                @method('PATCH')

                <div class="form-grid">

                    <div class="input-box">
                        <label class="sb-label">Nama Lengkap</label>

                        <input type="text"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            required>

                        @error('name')
                            <p class="form-err">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="input-box">
                        <label class="sb-label">No. HP</label>

                        <input type="text"
                            name="no_hp"
                            value="{{ old('no_hp', $user->no_hp) }}"
                            inputmode="numeric"
                            pattern="[0-9]+"
                            maxlength="15">

                        @error('no_hp')
                            <p class="form-err">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div class="input-box" style="margin-top:15px;">

                    <label class="sb-label">Email</label>

                    <input type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        required>

                    @error('email')
                        <p class="form-err">{{ $message }}</p>
                    @enderror

                </div>

                <button type="submit" class="btn-save">
                    Simpan Perubahan
                </button>

            </form>

        </div>

        <div class="sb-card" style="margin-top: 20px;">

            <p class="sb-title">GANTI PASSWORD</p>

            {{-- Mengirim data ke ProfilController@updatePassword --}}
            <form action="{{ route('profile.password') }}" method="POST">

                @csrf
                @method('PATCH')

                <div class="input-box">

                    <label class="sb-label">
                        Password Saat Ini
                    </label>

                    <input type="password"
                        name="current_password">

                    @error('current_password')
                        <p class="form-err">{{ $message }}</p>
                    @enderror

                </div>

                {{-- Redirect ke fitur reset password Laravel --}}
                <div style="margin-top: 10px; margin-bottom: 10px;">

                    @if (Route::has('password.request'))

                        <a href="{{ route('password.request') }}"
                            style="font-size: 0.8rem; color: #3C507D; font-weight: 600; text-decoration: none;">
                            Lupa Password?
                        </a>

                    @endif

                </div>

                <div class="form-grid" style="margin-top:15px;">

                    <div class="input-box">

                        <label class="sb-label">
                            Password Baru
                        </label>

                        <input type="password"
                            name="password">

                    </div>

                    <div class="input-box">

                        <label class="sb-label">
                            Konfirmasi Password
                        </label>

                        <input type="password"
                            name="password_confirmation">

                    </div>

                </div>

                @error('password')
                    <p class="form-err">{{ $message }}</p>
                @enderror

                <button type="submit" class="btn-save">
                    Update Password
                </button>

            </form>

        </div>

    </div>

</div>

<script>

    // Menampilkan atau menyembunyikan panel edit profil
    function toggleEditProfil() {

        const box =
            document.getElementById('editProfilBox');

        box.style.display =
            box.style.display === 'none' ||
            box.style.display === ''
                ? 'block'
                : 'none';

        // Scroll otomatis ke form ketika panel dibuka
        if (box.style.display === 'block') {

            box.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });

        }
    }

</script>

@endsection