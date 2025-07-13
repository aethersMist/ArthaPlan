<x-guest-layout>
    <div class="w-full sm:max-w-2xl mt-10 px-6 py-6 bg-white shadow-lg overflow-hidden sm:rounded-lg">
        <div class="mb-4 text-sm text-gray-700 text-center sm:text-left">
            {{ __('Terima kasih telah memverifikasi alamat email Anda! Anda sekarang dapat masuk ke akun Anda.') }}
        </div>

        <!-- Countdown Timer -->
        <div class="flex flex-col sm:flex-row justify-center items-center gap-2 rounded-xl border-2 border-primary p-4 bg-gray-50 mb-6">
            <div class="flex justify-center items-center">
                <x-secondary-button class="h-10 w-10 rounded-full">
                    <i class="fa fa-clock fa-lg"></i>
                </x-secondary-button>
            </div>
            <div class="flex flex-wrap justify-center items-center gap-1 text-center sm:text-left">
                <span>{{ __("Kami akan menutup halaman ini secara otomatis dalam") }}</span>
                <span id="countdown" class="font-bold text-2xl text-primary">20</span>
                <span>{{ __("detik.") }}</span>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <form method="POST" action="{{ route('login') }}" class="w-full sm:w-auto">
                @csrf
                <x-primary-button class="w-full">
                    {{ __('Masuk Sekarang') }}
                </x-primary-button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                @csrf
                <x-danger-button class="w-full">
                    {{ __('Keluar') }}
                </x-danger-button>
            </form>
        </div>

        <div class="text-center text-gray-500 text-sm mt-6">
            {{ __("Jika halaman ini tidak menutup secara otomatis, silakan klik salah satu tombol di atas.") }}
        </div>
    </div>

    <meta http-equiv="refresh" content="20;url={{ route('login') }}">

    <!-- Countdown Script + Auto-close fallback -->
    <script>
        let seconds = 20;
        const countdownEl = document.getElementById('countdown');

        const countdown = setInterval(() => {
            seconds--;
            countdownEl.textContent = seconds;

            if (seconds <= 0) {
                clearInterval(countdown);
            }
        }, 1000);

        setTimeout(() => {
            window.close(); 
        }, 20000);
    </script>
</x-guest-layout>
