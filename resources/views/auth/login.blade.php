<x-guest-layout>
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="relative w-full max-w-4xl bg-light min-h-[500px] rounded-2xl shadow-xl overflow-hidden flex flex-col-reverse md:flex-row">



            {{-- Login Form --}}
            <div class="w-full md:w-1/2 flex flex-col justify-center items-center px-8 py-10 text-center bg-light z-20">
                <form method="POST" action="{{ route('login') }}" class="w-full max-w-xs">
                    @csrf

                    <h2 class="text-2xl font-bold mb-2">Masuk</h2>
                    <p class="text-sm text-gray-600 mb-6">atau gunakan Email & Kata Sandi Anda</p>

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    {{-- Email --}}
                    <div class="text-start mb-4">
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" type="email" name="email" :value="old('email')" class="w-full" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    {{-- Password --}}
                    <div class="text-start mb-4">
                        <x-input-label for="password" :value="__('Kata Sandi')" />
                        <x-text-input id="password" type="password" name="password" required class="w-full" autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    {{-- Remember Me & Lupa Sandi --}}
                    <div class="flex justify-between items-center mb-4">
                        <label class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded focus:ring-accent focus:border-accent shadow-sm " name="remember" autocomplete="off">
                    <span class="ms-2 text-sm text-netral">{{ __('Ingat saya') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                <button type="button"
            data-modal-target="resetModal"
            data-modal-toggle="resetModal"
            class="underline text-sm text-accent hover:text-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent">
            {{ __('Lupa kata sandi?') }}
            </button>
            @endif
                    </div>

                    {{-- Tombol Masuk --}}
                    <x-secondary-button type="submit" class="w-full justify-center mb-4">
                        {{ __('Masuk') }}
                    </x-secondary-button>

                    {{-- Pemisah --}}
                    <div class="text-center text-gray-500 mb-4">— atau —</div>

                </form>
                {{-- Tombol Login Google --}}
                <a href="{{ url('auth/google') }}" class="flex justify-center">
                    <x-primary-button class="w-8 h-8 text-sm leading-4 font-medium rounded-full">
                        <i class="fa-brands fa-google"></i>
                    </x-primary-button>
                </a>
            </div>

            {{-- Welcome Panel --}}
            <div class="w-full md:w-1/2 h-[240px] md:h-auto bg-accent text-light flex flex-col justify-center items-center px-8 text-center z-10">
                <a href="/" class="hidden md:block">
    <x-application-logo class="w-16 h-16 fill-current text-white" />
</a>

                <h2 class="text-2xl md:text-3xl font-bold mb-2 mt-4 text-white">Hello, Teman-teman!</h2>
                <p class="text-sm mb-4 text-light">Untuk tetap terhubung, silakan masuk</p>
                <a href="{{ route('register') }}">
                   <x-secondary-button class="flex justify-center items-center">
                {{ __('Daftar') }}
                        <i class="fa fa-arrow-right ml-1" aria-hidden="true"></i>
                    </x-secondary-button>
                </a>
            </div>
        </div>
    </div>

    {{-- Modal Reset Password --}}
    <x-moddal id="resetModal" title="Reset Password" :name="'Reset Password'">
        <div class="mb-4 text-sm text-gray-600">
            {{ __('Masukkan email Anda, kami akan kirim tautan reset kata sandi.') }}
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" type="email" name="email" class="block mt-1 w-full" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="flex justify-end mt-4">
                <x-primary-button>
                    {{ __('Kirim Tautan Reset Kata Sandi') }}
                </x-primary-button>
            </div>
        </form>
    </x-moddal>
</x-guest-layout>
