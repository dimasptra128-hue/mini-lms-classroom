<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 rounded-4 shadow-sm mb-4" role="alert" style="background-color: #dcfce7; color: #16a34a; font-family: var(--font-sans);">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div><?php echo session('success'); ?></div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>


<div class="welcome-banner-card mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="rounded-3 overflow-hidden flex-shrink-0" style="width: 68px; height: 68px;">
            <?php if(auth()->user()->avatar): ?>
                <img
                    src="<?php echo e(asset('storage/' . auth()->user()->avatar)); ?>"
                    alt="Foto Profil"
                    style="width:100%; height:100%; object-fit:cover;">
            <?php else: ?>
                <div class="d-flex align-items-center justify-content-center bg-primary text-white fw-bold w-100 h-100"
                    style="font-size: 1.5rem;">
                    <?php echo e(strtoupper(substr(auth()->user()->name, 0, 2))); ?>

                </div>
            <?php endif; ?>
        </div>
        <h4 class="fw-bold mb-0" style="font-size: 1.35rem;">
            Selamat Datang, <?php echo e(auth()->user()->name); ?>

        </h4>
    </div>
</div>


<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background-color: #ceeaf0;">
                    <i class="bi bi-journal-bookmark text-primary fs-5"></i>
                </div>
                <span class="badge rounded-pill" style="background-color: #dcfce7; color: #16a34a; font-size: 0.75rem; font-weight: 600;">Aktif</span>
            </div>
            <div class="fw-bold fs-4 text-dark"><?php echo e($coursesCount); ?></div>
            <div class="text-secondary small">Kelas Terdaftar</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background-color: #fff7ed;">
                    <i class="bi bi-file-earmark-check text-warning fs-5"></i>
                </div>
                <?php if($tasksCount > 0): ?>
                    <span class="badge rounded-pill" style="background-color: #fee2e2; color: #dc2626; font-size: 0.75rem; font-weight: 600;"><?php echo e($tasksCount); ?> Baru</span>
                <?php else: ?>
                    <span class="badge rounded-pill" style="background-color: #dcfce7; color: #16a34a; font-size: 0.75rem; font-weight: 600;">Bebas</span>
                <?php endif; ?>
            </div>
            <div class="fw-bold fs-4 text-dark"><?php echo e($tasksCount); ?></div>
            <div class="text-secondary small">Tugas Menunggu</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background-color: #f0fdf4;">
                    <i class="bi bi-trophy text-success fs-5"></i>
                </div>
                <span class="badge rounded-pill" style="background-color: #ceeaf0; color: #1F7A8C; font-size: 0.75rem; font-weight: 600;">Nilai</span>
            </div>
            <div class="fw-bold fs-4 text-dark"><?php echo e($averageGrade ?? '-'); ?></div>
            <div class="text-secondary small">Rata-rata Nilai</div>
        </div>
    </div>
</div>


<div class="card border-0 shadow-sm rounded-4 bg-white">
    <div class="card-body p-4">
        <h6 class="fw-bold text-dark mb-3">Aktivitas Terbaru</h6>
        <div class="d-flex flex-column gap-3">
            <?php $__currentLoopData = $recentActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $act): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width: 40px; height: 40px; background-color: <?php echo e($act['color_bg']); ?>;">
                    <i class="bi <?php echo e($act['icon']); ?> <?php echo e($act['text_color'] ?? 'text-primary'); ?> fs-5"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-semibold small text-dark"><?php echo e($act['title']); ?></div>
                    <div class="text-secondary" style="font-size: 0.78rem;"><?php echo e($act['time']); ?></div>
                </div>
                <span class="badge rounded-pill" style="background-color: <?php echo e($act['badge_bg']); ?>; color: <?php echo e($act['badge_color']); ?>; font-size: 0.72rem;"><?php echo e($act['badge']); ?></span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\mini-lms-classroom\resources\views/dashboard.blade.php ENDPATH**/ ?>