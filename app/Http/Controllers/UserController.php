<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index() {
        // Mengambil seluruh data user
        // Diurutkan dari user yang paling baru dibuat
        $users = User::query()->orderBy('created_at', 'desc')->get();

        // Mengirim data user ke halaman manajemen user
        return view('users.index', compact('users'));
    }

    public function store(Request $request) {
        // Validasi input saat menambah user baru
        $request->validate([
            'name' => ['required', 'string', 'max:255'],

            // Email wajib unik agar tidak ada akun dengan email yang sama
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],

            'no_hp' => ['required', 'regex:/^[0-9]+$/', 'max:15'],

            // Password menggunakan aturan default Laravel
            'password' => ['required', Rules\Password::defaults()],

            // Role hanya boleh admin atau kasir
            'role' => ['required', 'in:admin,kasir'],
        ]);

        // Membuat user baru
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,

            // Role user baru diset sebagai kasir
            'role' => 'kasir',

            // Password di-hash agar tidak tersimpan dalam bentuk teks asli
            'password' => bcrypt($request->password),
        ]);

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'User baru berhasil ditambahkan!');
    }

    public function update(Request $request, User $user) {
        // Validasi data user saat update
        $request->validate([
            'name' => ['required', 'string', 'max:255'],

            // Email harus unik, kecuali email milik user yang sedang diedit
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],

            // Nomor HP boleh kosong
            'no_hp' => ['required', 'regex:/^[0-9]+$/', 'max:15'],
        ]);

        // Update data utama user
        $user->update($request->only('name', 'email', 'no_hp'));

        // Jika field password diisi, password akan ikut diperbarui
        if ($request->filled('password')) {

            // Password baru di-hash sebelum disimpan
            $user->update(['password' => Hash::make($request->password)]);
        }

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Data user berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        // Mencegah user menghapus akun yang sedang dipakai login
        if ($user->id === Auth::id()) {
            return redirect()
                ->back()
                ->with('error', 'Kamu tidak bisa menghapus akunmu sendiri.');
        }

        // Mencegah akun admin dihapus
        if ($user->role === 'admin') {
            return redirect()
                ->back()
                ->with('error', 'Akun admin tidak boleh dihapus.');
        }

        // Menghapus user
        $user->delete();

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()
            ->back()
            ->with('success', 'User berhasil dihapus.');
    }
}