<x-guest-layout>
    <div class="login-auth-container">
        <div class="sb-card animate-bounce-in" style="width: 100%; max-width: 400px; background: rgba(255, 255, 255, 0.95); border-radius: 20px; padding: 40px; box-shadow: 0 15px 35px rgba(0,0,0,0.2);">

            <div style="text-align: center; margin-bottom: 25px;">
                <h2 style="font-family: 'Cormorant Garamond', serif; color: #112250; font-weight: bold; font-size: 1.6rem;">Lupa Password?</h2>
                <p style="font-size: 0.8rem; color: #5A5A7A; line-height: 1.4; margin-top: 10px;">
                    {{ __('Masukkan alamat email Anda dan kami akan mengirimkan link reset password.') }}
                </p>
            </div>

            {{-- Menampilkan pesan status setelah request reset password dikirim --}}
            <x-auth-session-status class="mb-4" :status="session('status')" />

            {{-- Form ini memakai route bawaan Laravel Breeze untuk mengirim link reset password --}}
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="form-grup" style="margin-bottom: 25px;">
                    <label class="sb-label">Email</label>
                    <input id="email" type="email" name="email" :value="old('email')" required autofocus placeholder="email@gmail.com"
                           style="width: 100%; padding: 10px; border: 1px solid #D9CBC2; border-radius: 8px;">

                    {{-- Menampilkan error validasi khusus field email --}}
                    <x-input-error :messages="$errors->get('email')" class="form-err" style="margin-top: 5px;" />
                </div>

                <div style="display: flex; flex-direction: column; gap: 15px;">
                    <button type="submit" class="btn btn-primer" style="width: 100%; height: 45px; background: #112250; color: #E0C58F; font-size: 0.9rem; font-weight: bold;">
                        KIRIM LINK RESET
                    </button>

                    <a href="{{ route('login') }}" style="text-align: center; font-size: 0.85rem; color: #5A5A7A; text-decoration: none; font-weight: 600;">
                        Kembali ke Login
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>