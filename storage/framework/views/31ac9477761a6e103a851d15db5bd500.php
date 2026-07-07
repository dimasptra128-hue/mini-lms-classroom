<?php $__env->startSection('title', 'Lupa Kata Sandi'); ?>

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
            <h3 class="fw-bold text-dark mb-1">Lupa Kata Sandi?</h3>
            <p class="text-secondary small mb-0">Masukkan email Anda untuk menerima kode verifikasi OTP</p>
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

        <!-- Status Message -->
        <?php if(session('status')): ?>
            <div class="alert alert-success border-0 rounded-3 shadow-sm mb-4 d-flex align-items-start gap-2" role="alert" style="font-size: 0.85rem;">
                <i class="bi bi-check-circle-fill fs-6 mt-1 text-success"></i>
                <div>
                    <?php echo e(session('status')); ?>

                </div>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('password.email')); ?>" method="POST" id="forgotForm" onsubmit="handleFormSubmit(event)">
            <?php echo csrf_field(); ?>
            
            <!-- Email Field -->
            <div class="mb-4">
                <label for="email" class="form-label-custom">Email</label>
                <div class="input-group-custom">
                    <span class="input-group-icon">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input type="email" class="form-control-custom" id="email" name="email" placeholder="nama@gmail.com" value="<?php echo e(old('email')); ?>" required autofocus>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-masuk w-100 mb-2" id="submitBtn">
                <span class="spinner-border spinner-border-sm d-none me-2" id="btnSpinner" role="status" aria-hidden="true"></span>
                <span id="btnText">Kirim Kode OTP</span>
            </button>
        </form>

        <!-- Back to Login -->
        <div class="text-center mt-3" style="font-size: 0.85rem;">
            <a href="<?php echo e(route('login')); ?>" class="text-link-custom">Kembali ke Halaman Login</a>
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

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dimas\mini-lms-classroom\resources\views/auth/forgot-password.blade.php ENDPATH**/ ?>