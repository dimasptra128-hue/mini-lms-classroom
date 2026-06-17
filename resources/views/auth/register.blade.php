@extends('layouts.auth')

@section('title', 'Daftar Akun Baru')

@section('content')
<div class="login-container">
    <div class="login-card">
        <!-- Logo / Brand Header -->
        <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
            <div class="d-flex align-items-center justify-content-center bg-primary rounded-3" style="width: 40px; height: 40px; background-color: #1F7A8C !important;">
                <i class="bi bi-mortarboard-fill text-white fs-4"></i>
            </div>
            <span class="h4 mb-0 fw-bold" style="color: #1F7A8C;">Mini LMS</span>
        </div>

        <!-- Card Title -->
        <div class="text-center mb-4">
            <h3 class="fw-bold text-dark mb-1">Daftar Akun Baru</h3>
            <p class="text-secondary small mb-0">Mulai perjalanan belajar di portal Mini LMS</p>
        </div>

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="alert alert-danger border-0 rounded-3 shadow-sm mb-4 d-flex align-items-start gap-2" role="alert" style="font-size: 0.85rem;">
                <i class="bi bi-exclamation-triangle-fill fs-6 mt-1 text-danger"></i>
                <div>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" id="registerForm" onsubmit="handleFormSubmit(event)">
            @csrf
            
            <!-- Name Field -->
            <div class="mb-3">
                <label for="name" class="form-label-custom">Nama Lengkap</label>
                <div class="input-group-custom">
                    <span class="input-group-icon">
                        <i class="bi bi-person"></i>
                    </span>
                    <input type="text" class="form-control-custom" id="name" name="name" placeholder="Nama Lengkap Anda" value="{{ old('name') }}" required autofocus>
                </div>
            </div>

            <!-- Email Field -->
            <div class="mb-3">
                <label for="email" class="form-label-custom">Email</label>
                <div class="input-group-custom">
                    <span class="input-group-icon">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input type="email" class="form-control-custom" id="email" name="email" placeholder="nama@gmail.com" value="{{ old('email') }}" required>
                </div>
            </div>

            <!-- Password Field -->
            <div class="mb-3">
                <label for="password" class="form-label-custom">Kata Sandi</label>
                <div class="input-group-custom">
                    <span class="input-group-icon">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input type="password" class="form-control-custom" id="password" name="password" placeholder="*********" required>
                    <button type="button" class="btn-toggle-password" tabindex="-1" style="background: none; border: none; padding: 0 1rem; color: #94a3b8; cursor: pointer; display: flex; align-items: center; outline: none; box-shadow: none;">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Confirm Password Field -->
            <div class="mb-4">
                <label for="password_confirmation" class="form-label-custom">Konfirmasi Kata Sandi</label>
                <div class="input-group-custom">
                    <span class="input-group-icon">
                        <i class="bi bi-lock-fill"></i>
                    </span>
                    <input type="password" class="form-control-custom" id="password_confirmation" name="password_confirmation" placeholder="*********" required>
                    <button type="button" class="btn-toggle-password" tabindex="-1" style="background: none; border: none; padding: 0 1rem; color: #94a3b8; cursor: pointer; display: flex; align-items: center; outline: none; box-shadow: none;">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-masuk w-100 mb-2" id="submitBtn">
                <span class="spinner-border spinner-border-sm d-none me-2" id="btnSpinner" role="status" aria-hidden="true"></span>
                <span id="btnText">Daftar Akun</span>
            </button>
        </form>

        <!-- Login Link -->
        <div class="text-center mt-3" style="font-size: 0.85rem; color: #64748b;">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-link-custom">Masuk Sekarang</a>
        </div>

        <!-- Divider Line -->
        <div class="separator"></div>

        <!-- Card Footer Help Link -->
        <div class="text-center" style="font-size: 0.8rem; color: #64748b;">
            Butuh bantuan? <a href="#" class="text-link-custom">Hubungi Administrator</a>
        </div>
    </div>
</div>

<script>
    function handleFormSubmit(event) {
        const submitBtn = document.getElementById('submitBtn');
        const spinner = document.getElementById('btnSpinner');
        const btnText = document.getElementById('btnText');
        
        submitBtn.disabled = true;
        spinner.classList.remove('d-none');
        btnText.innerText = 'Mendaftarkan...';
    }
</script>
<script>
    document.querySelectorAll('.btn-toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            // Ambil element input password yang satu grup dengan tombol mata ini
            const passwordInput = this.previousElementSibling;
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    });
</script>
@endsection
