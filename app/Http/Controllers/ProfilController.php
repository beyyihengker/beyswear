<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfilController extends Controller
{
    public function index()
    {
        // Mengambil data user yang sedang login
        /** @var User $user */
        $user = Auth::user();

        // Mengirim data user ke halaman profil
        return view('profil', compact('user'));
    }

    public function update(Request $request)
    {
        // Mengambil data user yang sedang login
        /** @var User $user */
        $user = Auth::user();

        // Validasi data profil
        $request->validate([
            'name'  => 'required|string|max:100',
            'no_hp' => 'nullable|regex:/^[0-9]+$/',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ], [

            // Pesan error khusus untuk validasi profil
            'name.required'  => 'Nama lengkap tidak boleh kosong.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'email.required' => 'Alamat email harus diisi.',
            'email.email'    => 'Format email salah.',
            'email.unique'   => 'Email ini sudah terdaftar di akun lain.',
        ]);

        // Update data profil user
        $user->update([
            'name'  => $request->name,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
        ]);

        // Redirect kembali ke halaman profil dengan pesan sukses
        return redirect()->route('profil')->with('success', 'Data profil kamu berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        // Validasi password
        $request->validate([

            // Password saat ini wajib sesuai dengan password akun yang sedang login
            'current_password' => 'required|current_password',

            // Password baru wajib diisi dan harus cocok dengan konfirmasi password
            'password'         => ['required', 'confirmed', Password::defaults()],
        ], [

            // Pesan error khusus untuk validasi password
            'current_password.required' => 'Password saat ini harus diisi.',
            'current_password.current_password' => 'Password lama yang kamu masukkan salah.',
            'password.required'  => 'Password tidak boleh kosong.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'password.min'       => 'Password minimal harus 8 karakter.',
        ]);

        // Mengambil data user yang sedang login
        /** @var User $user */
        $user = Auth::user();

        // Update password user
        // Hash::make digunakan agar password tidak disimpan dalam bentuk teks asli
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Redirect kembali ke halaman profil dengan pesan sukses
        return redirect()->route('profil')->with('success', 'Password kamu berhasil diganti!');
    }
}