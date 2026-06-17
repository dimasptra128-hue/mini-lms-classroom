<?php $__env->startSection('title', 'Admin Dashboard'); ?>

<?php $__env->startSection('content'); ?>

<div class="mb-4">
    <h5 class="fw-bold mb-0 text-dark">Admin Dashboard</h5>
    <p class="text-secondary mb-0 small">Ringkasan statistik dan aktivitas sistem Mini LMS</p>
</div>


<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background-color: #fee2e2;">
                    <i class="bi bi-people-fill text-danger fs-5"></i>
                </div>
            </div>
            <div class="fw-bold fs-4 text-dark"><?php echo e($usersCount); ?></div>
            <div class="text-secondary small">Total Pengguna</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background-color: #ceeaf0;">
                    <i class="bi bi-journal-text text-primary fs-5"></i>
                </div>
            </div>
            <div class="fw-bold fs-4 text-dark"><?php echo e($coursesCount); ?></div>
            <div class="text-secondary small">Total Kelas</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background-color: #e0f2fe;">
                    <i class="bi bi-file-earmark-text-fill text-info fs-5"></i>
                </div>
            </div>
            <div class="fw-bold fs-4 text-dark"><?php echo e($materialsCount); ?></div>
            <div class="text-secondary small">Total Materi</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background-color: #dcfce7;">
                    <i class="bi bi-clipboard-check-fill text-success fs-5"></i>
                </div>
            </div>
            <div class="fw-bold fs-4 text-dark"><?php echo e($tasksCount); ?></div>
            <div class="text-secondary small">Total Tugas</div>
        </div>
    </div>
</div>

<div class="row g-4">
    
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="fw-bold text-dark mb-0">Kelas Terbaru</h6>
                    <a href="<?php echo e(route('admin.courses')); ?>" class="small text-danger fw-bold text-decoration-none">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr class="text-secondary" style="font-size: 0.78rem;">
                                <th>NAMA KELAS</th>
                                <th>KODE</th>
                                <th>PENGAJAR</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $recentCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr style="font-size: 0.85rem;">
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-2 d-flex align-items-center justify-content-center text-white fw-bold" style="width: 32px; height: 32px; background-color: <?php echo e($course->color ?: '#1F7A8C'); ?>;">
                                            <i class="bi <?php echo e($course->icon ?: 'bi-journal-text'); ?>"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold text-dark"><?php echo e($course->name); ?></div>
                                            <div class="text-secondary small"><?php echo e($course->subject ?: 'Umum'); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><code class="bg-light px-2 py-1 rounded text-dark"><?php echo e($course->code); ?></code></td>
                                <td class="text-secondary">
                                    <?php echo e($course->teacher->name ?? ($course->teacher_name ?? 'Belum Ada Pengajar')); ?>

                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="3" class="text-center text-secondary py-3">Belum ada kelas dibuat.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="fw-bold text-dark mb-0">Pengguna Terbaru</h6>
                    <a href="<?php echo e(route('admin.users')); ?>" class="small text-danger fw-bold text-decoration-none">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr class="text-secondary" style="font-size: 0.78rem;">
                                <th>NAMA PENGGUNA</th>
                                <th>EMAIL</th>
                                <th>TERDAFTAR</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $recentUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr style="font-size: 0.85rem;">
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-danger text-white fw-bold" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                            <?php echo e(strtoupper(substr($u->name, 0, 2))); ?>

                                        </div>
                                        <span class="fw-semibold text-dark"><?php echo e($u->name); ?></span>
                                    </div>
                                </td>
                                <td class="text-secondary"><?php echo e($u->email); ?></td>
                                <td class="text-secondary"><?php echo e($u->created_at->diffForHumans()); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="3" class="text-center text-secondary py-3">Belum ada pengguna.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\mini-lms-classroom\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>