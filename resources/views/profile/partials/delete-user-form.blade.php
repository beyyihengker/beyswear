<section class="space-y-6">

    <header>

        {{-- Informasi kepada user mengenai konsekuensi penghapusan akun --}}
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>

    </header>

    {{-- Tombol hanya membuka modal konfirmasi, belum menghapus akun --}}
    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >
        {{ __('Delete Account') }}
    </x-danger-button>

    {{-- Modal konfirmasi penghapusan akun --}}
    <x-modal
        name="confirm-user-deletion"
        :show="$errors->userDeletion->isNotEmpty()"
        focusable
    >

        {{-- Form akan mengirim request DELETE ke route profile.destroy --}}
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">

            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">

                {{-- Password digunakan sebagai validasi tambahan sebelum akun dihapus --}}
                <x-input-label
                    for="password"
                    value="{{ __('Password') }}"
                    class="sr-only"
                />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('Password') }}"
                />

                {{-- Menampilkan error validasi password jika gagal --}}
                <x-input-error
                    :messages="$errors->userDeletion->get('password')"
                    class="mt-2"
                />

            </div>

            <div class="mt-6 flex justify-end">

                {{-- Menutup modal tanpa melakukan perubahan data --}}
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                {{-- Tombol submit untuk menjalankan proses hapus akun --}}
                <x-danger-button class="ms-3">
                    {{ __('Delete Account') }}
                </x-danger-button>

            </div>

        </form>

    </x-modal>

</section>