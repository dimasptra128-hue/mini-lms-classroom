@extends('layouts.auth')

@section('title', 'Lupa Kata Sandi')

@section('content')
<div class="login-container">
    <div class="login-card">

        {{-- Logo / Brand --}}
        <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
            <div class="d-flex align-items-center justify-content-center bg-primary rounded-3" style="width: 40px; height: 40px; background-color: #1F7A8C !important;">
                <i class="bi bi-mortarboard-fill text-white fs-4"></i>
            </div>
            <span class="h4 mb-0 fw-bold" style="color: #1F7A8C;">Mini LMS</span>
        </div>

        {{-- Icon & Title --}}
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                 style="width: 64px; height: 64px; background-color: #ceeaf0;">
                <i class="bi bi-key-fill" style="color: #1F7A8C; font-size: 1.75rem;"></i>
            </div>
            <h3 class="fw-bold text-dark mb-1">Lupa Kata Sandi?</h3>
            <p class="text-secondary small mb-0">Masukkan email akunmu, kami akan bantu kamu.</p>
        </div>

        {{-- Success State --}}
        <div id="successState" class="d-none">
            <div class="text-center py-3">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                     style="width: 64px; height: 64px; background-color: #dcfce7;">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 1.75rem;"></i>
                </div>
                <h6 class="fw-bold text-dark mb-2">Email Berhasil Dikirim!</h6>
                <p class="text-secondary small mb-4">
                    Instruksi reset kata sandi telah dikirim ke <strong id="sentEmail"></strong>.
                    Periksa kotak masuk atau folder spam kamu.
                </p>
                <button onclick="resetForm()" class="btn btn-masuk w-100 mb-3">
                    <i class="bi bi-arrow-clockwise me-1"></i> Kirim Ulang
                </button>
            </div>
        </div>

        {{-- Form --}}
        <div id="formState">
            <form id="forgotForm" onsubmit="handleForgotSubmit(event)">
                @csrf
                <div class="mb-4">
                    <label for="forgot_email" class="form-label-custom">Alamat Email</label>
                    <div class="input-group-custom">
                        <span class="input-group-icon"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control-custom" id="forgot_email"
                               name="email" placeholder="nama@gmail.com" required autofocus>
                    </div>
                    <div class="mt-2 text-secondary" style="font-size: 0.78rem;">
                        <i class="bi bi-info-circle me-1"></i>
                        Masukkan email yang terdaftar pada akun Mini LMS kamu.
                    </div>
                </div>

                <button type="submit" class="btn btn-masuk w-100 mb-3" id="forgotBtn">
                    <span class="spinner-border spinner-border-sm d-none me-2" id="forgotSpinner" role="status"></span>
                    <span id="forgotBtnText"><i class="bi bi-send me-1"></i> Kirim Instruksi Reset</span>
                </button>
            </form>
        </div>

        {{-- Divider --}}
        <div class="separator"></div>

        {{-- Back to Login --}}
        <div class="text-center" style="font-size: 0.85rem;">
            <a href="{{ route('login') }}" class="d-inline-flex align-items-center gap-1 text-link-custom fw-semibold text-decoration-none">
                <i class="bi bi-arrow-left"></i> Kembali ke Halaman Login
            </a>
        </div>

    </div>
</div>

<style>
/* Step indicator dots animation */
@keyframes pulse-dot {
    0%, 100% { transform: scale(1); opacity: 0.6; }
    50% { transform: scale(1.4); opacity: 1; }
}
</style>

<script>
function handleForgotSubmit(event) {
    event.preventDefault();

    const email = document.getElementById('forgot_email').value;
    const btn   = document.getElementById('forgotBtn');
    const spinner = document.getElementById('forgotSpinner');
    const btnText = document.getElementById('forgotBtnText');

    // Show loading state
    btn.disabled = true;
    spinner.classList.remove('d-none');
    btnText.innerHTML = 'Mengirim...';

    // Simulate sending (1.5 sec) — replace with real AJAX/form POST if backend ready
    setTimeout(() => {
        document.getElementById('formState').classList.add('d-none');
        document.getElementById('sentEmail').textContent = email;
        document.getElementById('successState').classList.remove('d-none');
        btn.disabled = false;
        spinner.classList.add('d-none');
        btnText.innerHTML = '<i class="bi bi-send me-1"></i> Kirim Instruksi Reset';
    }, 1500);
}

function resetForm() {
    document.getElementById('successState').classList.add('d-none');
    document.getElementById('formState').classList.remove('d-none');
    document.getElementById('forgot_email').value = '';
    document.getElementById('forgot_email').focus();
}
</script>

@endsection
