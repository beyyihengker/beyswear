{{--
    Halaman profil bawaan Laravel Breeze.

    Halaman ini berfungsi sebagai container yang memanggil
    beberapa partial:
    - update-profile-information-form
    - update-password-form
    - delete-user-form

    Pada aplikasi BeysWear, fitur profil utama menggunakan
    halaman profil custom dan ProfilController.
--}}
<x-app-layout>

    {{-- Header halaman --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">

                <div class="max-w-xl">

                    {{-- Form update informasi profil user --}}
                    @include('profile.partials.update-profile-information-form')

                </div>

            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">

                <div class="max-w-xl">

                    {{-- Form update password user --}}
                    @include('profile.partials.update-password-form')

                </div>

            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">

                <div class="max-w-xl">

                    {{-- Form penghapusan akun user --}}
                    @include('profile.partials.delete-user-form')

                </div>

            </div>

        </div>

    </div>

</x-app-layout>