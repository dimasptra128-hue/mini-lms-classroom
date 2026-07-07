<?php $__env->startSection('title', 'Verifikasi OTP'); ?>

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
            <h3 class="fw-bold text-dark mb-1">Verifikasi OTP</h3>
            <p class="text-secondary small mb-0">Masukkan 6 digit kode OTP yang telah dikirim ke email Anda</p>
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
                    <!-- Jika OTP kadaluarsa, tampilkan tombol Kirim Ulang di sini juga agar mempermudah user -->
                    <?php if($errors->has('otp_code') && (str_contains($errors->first('otp_code'), 'kadaluarsa') || str_contains($errors->first('otp_code'), 'expired'))): ?>
                        <div class="mt-2">
                            <button type="button" onclick="document.getElementById('resendForm').submit();" class="btn btn-sm btn-outline-danger" style="font-size: 0.75rem;">
                                Kirim Ulang Kode OTP
                            </button>
                        </div>
                    <?php endif; ?>
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

        <form action="<?php echo e(route('password.otp.verify')); ?>" method="POST" id="verifyForm" onsubmit="handleFormSubmit(event)">
            <?php echo csrf_field(); ?>
            
            <!-- OTP Code Field -->
            <div class="mb-4">
                <label for="otp_code" class="form-label-custom">Kode OTP</label>
                <div class="input-group-custom">
                    <span class="input-group-icon">
                        <i class="bi bi-shield-lock"></i>
                    </span>
                    <input type="text" class="form-control-custom text-center fw-bold" id="otp_code" name="otp_code" placeholder="123456" maxlength="6" pattern="[0-9]*" inputmode="numeric" required autofocus>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-masuk w-100 mb-2" id="submitBtn">
                <span class="spinner-border spinner-border-sm d-none me-2" id="btnSpinner" role="status" aria-hidden="true"></span>
                <span id="btnText">Verifikasi OTP</span>
            </button>
        </form>

        <!-- Hidden Resend Form -->
        <form action="<?php echo e(route('password.resend')); ?>" method="POST" id="resendForm" class="d-none">
            <?php echo csrf_field(); ?>
        </form>

        <!-- Resend and Back Links -->
        <div class="text-center mt-3" style="font-size: 0.85rem; color: #64748b;">
            Tidak menerima kode? <a href="#" onclick="event.preventDefault(); document.getElementById('resendForm').submit();" class="text-link-custom">Kirim Ulang</a>
        </div>

        <div class="text-center mt-2" style="font-size: 0.85rem;">
            <a href="<?php echo e(route('password.request')); ?>" class="text-link-custom">Ubah Email</a>
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
        btnText.innerText = 'Memverifikasi...';
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dimas\mini-lms-classroom\resources\views/auth/verify-otp.blade.php ENDPATH**/ ?>