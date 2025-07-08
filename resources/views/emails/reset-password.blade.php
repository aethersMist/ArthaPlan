@push('head')
    <title>Reset Kata Sandi - ArthaPlan</title>
@endpush
@component('mail::message')
# Halo {{ $user->name ?? $user->email }}!

Kami menerima permintaan untuk mengatur ulang kata sandi akun Anda.

@component('mail::button', ['url' => $url])
Reset Kata Sandi
@endcomponent

Tautan ini hanya berlaku selama 60 menit.

Jika Anda tidak meminta reset kata sandi, abaikan email ini.

Salam,<br>
**ArthaPlan**
@endcomponent
