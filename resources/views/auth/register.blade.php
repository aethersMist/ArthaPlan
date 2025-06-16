<x-guest-layout>
    <div class="flex-grow flex items-center justify-center">
        <div
          class="relative w-[768px] bg-light min-h-[500px] rounded-2xl shadow-xl overflow-hidden">
          <!-- Sign Up Form -->
          <div
            class="sign-up-form absolute w-1/2 h-full top-0 right-0 flex flex-col justify-center items-center px-8 text-center bg-light"
          >
            <form method="POST" action="{{ route('register') }}" class="w-full max-w-xs">
                <div class="flex-col justify-center items-center mb-4 text-center space-y-1">

            <h2 class="text-2xl font-bold">Buat Akun</h2>
        
        </div>
        @csrf

        <!-- Name -->
        <div class="text-start">
            <x-input-label for="name" :value="__('Nama')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4 text-start">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4 text-start">
            <x-input-label for="password" :value="__('Kata Sandi')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4 text-start">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <x-secondary-button type="submit" class="w-full flex justify-center items-center mt-4">
                {{ __('Daftar') }}
            </x-secondary-button>
    </form>
          </div>


          {{-- Toggle Panel --}}
          {{-- Sign In --}}
          <div
            class="toggle absolute w-1/2 h-full top-0 right-1/2 bg-accent text-light flex flex-col justify-center items-center px-8 text-center z-10"
          >
            
            {{-- Sign Up --}}
                       <div class="block flex flex-col items-center space-x-4">

 <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>      
                          <h2 class="text-3xl font-bold mb-2 text-primary">
                Selamat Datang!
              </h2>
<p class="text-sm mb-4">
                Daftar dengan detail pribadi Anda untuk menggunakan semua fitur kami
              </p>              <a href="{{ route('login') }}">
                  <x-secondary-button class="rounded-lg space-x-2 text-sm font-medium">
                  <i class="fa fa-arrow-left mr-1" aria-hidden="true"></i>
                {{ __('Masuk') }}
            </x-secondary-button>
             </a>
            
            </div>
          </div>
        </div>
        </div>
</x-guest-layout>
