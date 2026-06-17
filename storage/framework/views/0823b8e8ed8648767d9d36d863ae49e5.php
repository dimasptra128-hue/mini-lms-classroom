<?php $__env->startSection('title', 'Pengaturan'); ?>

<?php $__env->startSection('content'); ?>

<div class="mb-4">
    <h5 class="fw-bold mb-0 text-dark">Pengaturan</h5>
    <p class="text-secondary mb-0 small">Kelola akun dan preferensi kamu</p>
</div>

<div class="row g-4">
    
    <div class="col-lg-10">
        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold text-dark mb-4">Informasi Profil</h6>

                
                <div class="d-flex align-items-center gap-4 mb-4">
                    <div class="rounded-3 overflow-hidden"
                         style="width: 80px; height: 80px; flex-shrink: 0;">
                        <?php if(Auth::user()->avatar): ?>
                            <img src="<?php echo e(asset('storage/' . Auth::user()->avatar)); ?>" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <div class="d-flex align-items-center justify-content-center bg-primary text-white fw-bold"
                                 style="width: 80px; height: 80px; font-size: 2rem;">
                                <?php echo e(strtoupper(substr(Auth::user()->name, 0, 2))); ?>

                            </div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <div class="fw-semibold text-dark mb-1">Foto Profil</div>
                        <div class="text-secondary small mb-2">Unggah foto profil kamu (maks. 2MB)</div>
                        <button type="button" id="uploadAvatarButton" class="btn btn-sm btn-bikin-kelas">
                            <i class="bi bi-upload me-1"></i> Unggah Foto
                        </button>
                        <div class="text-secondary small mt-2" id="avatarFileName">Pilih file jika ingin mengganti avatar</div>
                        <input type="file" name="avatar" id="avatarInput" class="d-none" accept="image/*" form="profileForm">
                    </div>
                </div>

                <hr class="my-3" style="border-color: #f1f5f9;">

                
                <form id="profileForm" action="<?php echo e(route('settings.update')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php if(session('success')): ?>
                        <div class="alert alert-success small mb-3"><?php echo e(session('success')); ?></div>
                    <?php endif; ?>
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger small mb-3">
                            <ul class="mb-0">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label-custom">Nama Lengkap</label>
                            <div class="input-group-custom">
                                <span class="input-group-icon"><i class="bi bi-person"></i></span>
                                <input type="text" name="name" class="form-control-custom" value="<?php echo e(old('name', Auth::user()->name)); ?>">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label-custom">Alamat Email</label>
                            <div class="input-group-custom">
                                <span class="input-group-icon"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control-custom" value="<?php echo e(old('email', Auth::user()->email)); ?>">
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-gabung-kelas mt-1">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        
        <div class="card border-0 shadow-sm rounded-4 bg-white">
            <div class="card-body p-4">
                <h6 class="fw-bold text-dark mb-4">Ubah Kata Sandi</h6>
                <form action="<?php echo e(route('settings.password')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php if(session('password_success')): ?>
                        <div class="alert alert-success small mb-3"><?php echo e(session('password_success')); ?></div>
                    <?php endif; ?>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label-custom">Kata Sandi Lama</label>
                            <div class="input-group-custom">
                                <span class="input-group-icon"><i class="bi bi-lock"></i></span>
                                <input type="password" name="current_password" class="form-control-custom" placeholder="••••••••">
                                <button type="button" class="btn-toggle-password" tabindex="-1" style="background: none; border: none; padding: 0 1rem; color: #94a3b8; cursor: pointer; display: flex; align-items: center; outline: none; box-shadow: none;">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Kata Sandi Baru</label>
                            <div class="input-group-custom">
                                <span class="input-group-icon"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" class="form-control-custom" placeholder="••••••••">
                                <button type="button" class="btn-toggle-password" tabindex="-1" style="background: none; border: none; padding: 0 1rem; color: #94a3b8; cursor: pointer; display: flex; align-items: center; outline: none; box-shadow: none;">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Konfirmasi Kata Sandi</label>
                            <div class="input-group-custom">
                                <span class="input-group-icon"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" name="password_confirmation" class="form-control-custom" placeholder="••••••••">
                                <button type="button" class="btn-toggle-password" tabindex="-1" style="background: none; border: none; padding: 0 1rem; color: #94a3b8; cursor: pointer; display: flex; align-items: center; outline: none; box-shadow: none;">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-gabung-kelas mt-1">
                                Ubah Kata Sandi
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Toggle password visibility for all password inputs
        document.querySelectorAll('.btn-toggle-password').forEach(button => {
            button.addEventListener('click', function () {
                const passwordInput = this.previousElementSibling;
                const icon = this.querySelector('i');

                if (passwordInput && icon) {
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        icon.classList.remove('bi-eye');
                        icon.classList.add('bi-eye-slash');
                    } else {
                        passwordInput.type = 'password';
                        icon.classList.remove('bi-eye-slash');
                        icon.classList.add('bi-eye');
                    }
                }
            });
        });

        // Avatar upload button + file name preview
        const uploadButton = document.getElementById('uploadAvatarButton');
        const avatarInput = document.getElementById('avatarInput');
        const avatarFileName = document.getElementById('avatarFileName');

        if (uploadButton && avatarInput) {
            uploadButton.addEventListener('click', function () {
                avatarInput.click();
            });

            avatarInput.addEventListener('change', function () {
                if (avatarInput.files.length > 0) {
                    avatarFileName.textContent = avatarInput.files[0].name;
                } else {
                    avatarFileName.textContent = 'Pilih file jika ingin mengganti avatar';
                }
            });
        }
    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\mini-lms-classroom\resources\views/settings.blade.php ENDPATH**/ ?>