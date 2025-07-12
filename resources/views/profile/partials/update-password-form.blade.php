<section>

    @php
        $hasPassword = !is_null(Auth::user()->password);
    @endphp

    @if ($hasPassword)
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Perbarui Kata Sandi') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.') }}
            </p>
        </header>


        <form method="post" action="{{ route('password.update.custom') }}" class="mt-6 space-y-6">
            @csrf
            @method('put')

            <div>
                <x-input-label for="update_password_current_password" :value="__('Kata Sandi Saat Ini')" />
                <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="update_password_password" :value="__('Kata Sandi Baru')" />
                <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="update_password_password_confirmation" :value="__('Konfirmasi Kata Sandi')" />
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>

            <x-primary-button>{{ __('Simpan') }}</x-primary-button>

            <div class="flex items-center gap-4">
            <x-auth-session-status class="mb-4" :status="session('status')" />

                @if (session('status') === 'password-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-gray-600"
                    >{{ __('Tersimpan.') }}</p>
                @endif
            </div>
        </form>

    @else
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Buat Kata Sandi') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Anda belum memiliki kata sandi. Buat kata sandi untuk akun Anda.') }}
            </p>
        </header>

               

    <form method="POST" action="{{ route('password.set.store') }}" class="mt-4 space-y-4">
            @csrf

            <div>
                <x-input-label for="password" value="Kata Sandi Baru" />
                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password_confirmation" value="Konfirmasi Kata Sandi" />
                <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" required />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <x-primary-button type="submit">Simpan</x-primary-button>
            
            {{-- Status message --}}
            <div class="flex items-center gap-4">
            <x-auth-session-status class="mb-4" :status="session('status')" />
            @if (session('status') === 'password-set')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Kata sandi berhasil dibuat.') }}</p>
            @endif
            </div>
        </form>
@endif

</section>
