<x-guest-layout>
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="relative w-full max-w-4xl bg-light min-h-[500px] rounded-2xl shadow-xl overflow-hidden flex flex-col-reverse md:flex-row">

            {{-- Welcome Panel --}}
            <div class="w-full md:w-1/2 h-[240px] md:h-auto bg-accent text-light flex flex-col justify-center items-center px-8 text-center z-10">
                <a href="/" class="hidden md:block">
                    <x-application-logo class="w-20 h-20 fill-current text-white" />
                </a>
                <h2 class="text-2xl md:text-3xl font-bold mb-2 mt-4 text-white">Selamat Datang!</h2>
                <p class="text-sm mb-4 text-light">Daftar dengan detail pribadi Anda untuk menggunakan semua fitur kami</p>
                <a href="{{ route('login') }}">
                    <x-secondary-button class="rounded-lg space-x-2 text-sm font-medium">
                  <i class="fa fa-arrow-left mr-1" aria-hidden="true"></i>
                {{ __('Masuk') }}
                    </x-secondary-button>
                </a>
            </div>

            {{-- Register Form --}}
            <div class="w-full md:w-1/2 flex flex-col justify-center items-center px-8 py-10 text-center bg-light z-20">
                <form method="POST" action="{{ route('register') }}" class="w-full max-w-xs">
                    @csrf

                    <h2 class="text-2xl font-bold mb-2">Daftar</h2>

                    {{-- Name --}}
                    <div class="text-start mb-4">
                        <x-input-label for="name" :value="__('Nama')" />
                        <x-text-input id="name" type="text" name="name" :value="old('name')" class="w-full" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    {{-- Email --}}
                    <div class="text-start mb-4">
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" type="email" name="email" :value="old('email')" class="w-full" required autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    {{-- Password --}}
                    <div class="text-start mb-4">
                        <x-input-label for="password" :value="__('Kata Sandi')" />
                        <x-text-input id="password" type="password" name="password" class="w-full" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="text-start mb-4">
                        <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi')" />
                        <x-text-input id="password_confirmation" type="password" name="password_confirmation" class="w-full" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <x-secondary-button type="submit" class="w-full justify-center mb-4">
                        {{ __('Daftar') }}
                    </x-secondary-button>

                    {{-- Pemisah --}}
                    <div class="text-center text-gray-500 mb-4">— atau —</div>

                </form>
                {{-- Login Google --}}
                <a href="{{ url('auth/google') }}" class="flex justify-center">
                    <x-primary-button class="w-8 h-8 text-sm leading-4 font-medium rounded-full">
                        <i class="fa-brands fa-google"></i>
                    </x-primary-button>
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
