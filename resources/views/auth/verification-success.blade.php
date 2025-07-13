<x-guest-layout>
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Terima kasih telah memverifikasi alamat email Anda! Anda sekarang dapat masuk ke akun Anda.') }}
        </div>

        <div class="mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div>
                    <x-primary-button>
                        {{ __('Masuk Sekarang') }}
                    </x-primary-button>
                </div>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <x-danger-button>
                    {{ __('Keluar') }}
                </x-danger-button>
            </form>
            
             <div class="flex justify-center items-center my-4 gap-2 rounded-full bg-base border-2 border-primary p-2 ">
                <x-secondary-button class="flex justify-center items-center h-8 w-8 rounded-full">
                    <i class="fa fa-clock fa-md"></i>
                </x-secondary-button>
                <span>Anda akan dialihkan ke halaman masuk dalam </span>
                <span id="countdown" class="font-semibold text-2xl text-primary">15</span>
                <span> detik.</span>
            </div>  
        </div>
    </div>

    <meta http-equiv="refresh" content="15;url={{ route('login') }}">

    <script>
        let seconds = 15;
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
        }, 15000);
    </script>
</x-guest-layout>
