<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Kata Sandi</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;">
    <div style="max-width: 600px; background: white; padding: 30px; margin: auto; border-radius: 8px;">
        <h2 style="color: #1a202c;">Halo {{ $user->name ?? 'Pengguna' }}!</h2>
        <p>Anda menerima email ini karena kami menerima permintaan reset kata sandi untuk akun Anda.</p>
        <p style="text-align: center; margin: 30px 0;">
            <a href="{{ $url }}" style="background-color: #4f46e5; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px;">
                Reset Kata Sandi
            </a>
        </p>
        <p>Tautan ini hanya berlaku selama <strong>60 menit</strong>.</p>
        <p>Jika Anda tidak meminta reset kata sandi, abaikan email ini.</p>
        <hr style="margin: 30px 0;">
        <p>Jika Anda kesulitan menekan tombol di atas, salin dan tempel URL berikut ke browser Anda:</p>
        <p><a href="{{ $url }}">{{ $url }}</a></p>
        <p style="margin-top: 40px;">Salam hangat,<br><strong>ArthaPlan</strong></p>
        <p style="font-size: 12px; color: #888;">© 2025 ArthaPlan. All rights reserved.</p>
    </div>
</body>
</html>
