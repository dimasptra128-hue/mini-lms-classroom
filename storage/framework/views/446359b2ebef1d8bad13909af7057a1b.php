<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Kata Sandi</title>
</head>
<body style="font-family: sans-serif; background-color: #f4f6fc; padding: 20px; color: #334155;">
    <div style="max-width: 500px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
        <div style="text-align: center; margin-bottom: 20px;">
            <h2 style="color: #1F7A8C; margin: 0;">Mini LMS</h2>
        </div>
        <p>Halo,</p>
        <p>Kami menerima permintaan untuk mereset kata sandi akun Mini LMS Anda.</p>
        <p>Gunakan kode OTP berikut untuk melanjutkan proses reset kata sandi Anda:</p>
        <div style="text-align: center; margin: 30px 0;">
            <span style="display: inline-block; font-size: 32px; font-weight: bold; letter-spacing: 4px; padding: 10px 30px; background-color: #ceeaf0; color: #1F7A8C; border-radius: 8px;">
                <?php echo e($otp); ?>

            </span>
        </div>
        <p>Kode OTP ini berlaku selama <strong>10 menit</strong>. Harap tidak membagikan kode ini kepada siapapun.</p>
        <p style="font-size: 0.85rem; color: #64748b; margin-top: 30px;">
            Jika Anda tidak meminta perubahan kata sandi ini, silakan abaikan email ini dengan aman.
        </p>
        <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 20px 0;">
        <p style="font-size: 0.8rem; color: #94a3b8; text-align: center; margin: 0;">
            Butuh bantuan? Silakan hubungi Administrator.
        </p>
    </div>
</body>
</html>
<?php /**PATH C:\Users\dimas\mini-lms-classroom\resources\views/emails/reset-password-otp.blade.php ENDPATH**/ ?>