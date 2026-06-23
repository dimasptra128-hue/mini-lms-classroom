<?php $__env->startSection('title', 'Masuk Sistem'); ?>

<?php $__env->startSection('content'); ?>
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
            <h3 class="fw-bold text-dark mb-1">Masuk Sistem</h3>
            <p class="text-secondary small mb-0">Akses portal akademik Mini LMS</p>
        </div>

        <!-- Validation Errors -->
        <?php if($errors->any()): ?>
            <div class="alert alert-danger border-0 rounded-3 shadow-sm mb-4 d-flex align-items-start gap-2" role="alert" style="font-size: 0.85rem;">
                <i class="bi bi-exclamation-triangle-fill fs-6 mt-1 text-danger"></i>
                <div>
                    <ul class="mb-0 ps-3">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('login')); ?>" method="POST" id="loginForm" onsubmit="handleFormSubmit(event)">
            <?php echo csrf_field(); ?>
            
            <!-- Email Field -->
            <div class="mb-3">
                <label for="email" class="form-label-custom">Email</label>
                <div class="input-group-custom">
                    <span class="input-group-icon">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input type="email" class="form-control-custom" id="email" name="email" placeholder="nama@gmail.com" value="<?php echo e(old('email')); ?>" required autofocus>
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
                    <script>document.querySelectorAll('.btn-toggle-password').forEach(button => {
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
});</script>
                    <span class="d-none"><i class="bi bi-eye-slash"></i></span>
                </div>
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="d-flex align-items-center justify-content-between mb-4" style="font-size: 0.85rem;">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>
                    <label class="form-check-label text-secondary" for="remember">
                        Ingat Saya
                    </label>
                </div>
                <!-- <a href="<?php echo e(route('password.request')); ?>" class="text-link-custom">Lupa Kata Sandi?</a> -->
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-masuk w-100 mb-2" id="submitBtn">
                <span class="spinner-border spinner-border-sm d-none me-2" id="btnSpinner" role="status" aria-hidden="true"></span>
                <span id="btnText">Masuk</span>
            </button>
        </form>

        <!-- Register Link -->
        <div class="text-center mt-3" style="font-size: 0.85rem; color: #64748b;">
            Belum punya akun? <a href="<?php echo e(route('register')); ?>" class="text-link-custom">Daftar Sekarang</a>
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
        btnText.innerText = 'Memproses...';
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\mini-lms-classroom\resources\views/auth/login.blade.php ENDPATH**/ ?>