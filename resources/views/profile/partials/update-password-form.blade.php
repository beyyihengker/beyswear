{{--
    File bawaan Laravel Breeze untuk fitur Update Password.

    Pada aplikasi BeysWear, proses perubahan password dilakukan
    melalui ProfilController dan halaman profil custom.
    File ini dipertahankan sebagai bagian dari scaffold Breeze.
--}}
<section>

    <header>

        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>

    </header>

    {{-- Form update password bawaan Breeze --}}
    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">

        @csrf
        @method('put')

        <div>

            {{-- Password lama untuk verifikasi identitas user --}}
            <x-input-label for="update_password_current_password" :value="__('Current Password')" />

            <x-text-input
                id="update_password_current_password"
                name="current_password"
                type="password"
                class="mt-1 block w-full"
                autocomplete="current-password"
            />

            <x-input-error
                :messages="$errors->updatePassword->get('current_password')"
                class="mt-2"
            />

        </div>

        <div>

            {{-- Password baru yang akan disimpan ke database --}}
            <x-input-label for="update_password_password" :value="__('New Password')" />

            <x-text-input
                id="update_password_password"
                name="password"
                type="password"
                class="mt-1 block w-full"
                autocomplete="new-password"
            />

            <x-input-error
                :messages="$errors->updatePassword->get('password')"
                class="mt-2"
            />

        </div>

        <div>

            {{-- Konfirmasi password baru untuk menghindari kesalahan input --}}
            <x-input-label
                for="update_password_password_confirmation"
                :value="__('Confirm Password')"
            />

            <x-text-input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                class="mt-1 block w-full"
                autocomplete="new-password"
            />

            <x-input-error
                :messages="$errors->updatePassword->get('password_confirmation')"
                class="mt-2"
            />

        </div>

        <div class="flex items-center gap-4">

            <x-primary-button>
                {{ __('Save') }}
            </x-primary-button>

            {{-- Notifikasi sukses setelah password berhasil diperbarui --}}
            @if (session('status') === 'password-updated')

                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >
                    {{ __('Saved.') }}
                </p>

            @endif

        </div>

    </form>

</section>