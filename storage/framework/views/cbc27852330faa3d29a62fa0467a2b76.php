<?php $__env->startSection('title', 'Kelola Kelas'); ?>

<?php $__env->startSection('content'); ?>

<div class="mb-4">
    <h5 class="fw-bold mb-0 text-dark">Kelola Kelas</h5>
    <p class="text-secondary mb-0 small">Pantau dan kelola seluruh kelas akademik yang dibuat oleh guru</p>
</div>


<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 rounded-4 shadow-sm mb-4" role="alert" style="background-color: #dcfce7; color: #16a34a; font-family: var(--font-sans);">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div><?php echo session('success'); ?></div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 rounded-4 shadow-sm mb-4" role="alert" style="background-color: #fee2e2; color: #dc2626; font-family: var(--font-sans);">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <div><?php echo session('error'); ?></div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm rounded-4 bg-white">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr class="text-secondary" style="font-size: 0.78rem;">
                        <th>KELAS & MATA PELAJARAN</th>
                        <th>KODE KELAS</th>
                        <th>NAMA GURU</th>
                        <th>ANGGOTA</th>
                        <th>TANGGAL DIBUAT</th>
                        <th class="text-center" style="width: 20%;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr style="font-size: 0.88rem;">
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center text-white fw-bold" style="width: 40px; height: 40px; background-color: <?php echo e($item->color); ?>;">
                                    <i class="bi <?php echo e($item->icon ?: 'bi-journal'); ?> fs-5"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark"><?php echo e($item->name); ?></div>
                                    <div class="text-secondary small"><?php echo e($item->subject); ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <code class="bg-light px-2 py-1 rounded-3 text-dark border font-monospace fw-bold" style="font-size: 0.85rem;"><?php echo e($item->code); ?></code>
                        </td>
                        <td class="text-dark fw-semibold"><?php echo e($item->teacher_name); ?></td>
                        <td>
                            <span class="badge bg-light text-dark border px-3 py-2 rounded-3 fw-bold">
                                <i class="bi bi-people-fill me-1 text-danger"></i> <?php echo e($item->users_count); ?> Anggota
                            </span>
                        </td>
                        <td class="text-secondary"><?php echo e($item->created_at->format('d M Y, H:i')); ?></td>
                        <td class="text-center">
                            <div class="d-inline-flex gap-1.5">
                                
                                <button type="button" class="btn btn-sm btn-outline-primary px-2.5 py-1 rounded-3 fw-semibold d-inline-flex align-items-center gap-1" style="font-size: 0.8rem;" data-bs-toggle="modal" data-bs-target="#membersModal<?php echo e($item->id); ?>">
                                    <i class="bi bi-people"></i> Anggota
                                </button>

                                
                                <form action="<?php echo e(route('admin.courses.delete', $item->id)); ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas <?php echo e($item->name); ?>? Semua materi, tugas, komentar, dan progres siswa di kelas ini akan dihapus secara permanen.')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger px-2.5 py-1 rounded-3 fw-semibold d-inline-flex align-items-center gap-1" style="font-size: 0.8rem;">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>

                            
                            <div class="modal fade text-start" id="membersModal<?php echo e($item->id); ?>" tabindex="-1" aria-labelledby="membersModalLabel<?php echo e($item->id); ?>" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                                        <div class="modal-header border-0 pb-0 px-4 pt-4">
                                            <h5 class="modal-title fw-bold text-dark" id="membersModalLabel<?php echo e($item->id); ?>">Anggota Kelas: <?php echo e($item->name); ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body px-4 py-3" style="max-height: 400px; overflow-y: auto;">
                                            
                                            <?php
                                                $usersCollection = collect($item->getRelationValue('users') ?? $item->getAttribute('users') ?? []);
                                                $teachers = $usersCollection->filter(function ($user) {
                                                    return data_get($user, 'pivot.role') === 'teacher';
                                                });
                                                $students = $usersCollection->filter(function ($user) {
                                                    return data_get($user, 'pivot.role') === 'student';
                                                });
                                            ?>
                                            <h6 class="fw-bold text-secondary mb-3 small" style="letter-spacing: 0.05em; text-transform: uppercase;">Pengajar</h6>
                                            <div class="d-flex flex-column gap-3 mb-4">
                                                <?php $__empty_1 = true; $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                    <div class="d-flex align-items-center justify-content-between gap-3">
                                                        <div class="d-flex align-items-center gap-2.5">
                                                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold bg-primary" style="width: 34px; height: 34px; font-size: 0.75rem;">
                                                                <?php echo e(strtoupper(substr($teacher->name, 0, 2))); ?>

                                                            </div>
                                                            <div>
                                                                <div class="fw-semibold text-dark small" style="font-size: 0.85rem;"><?php echo e($teacher->name); ?></div>
                                                                <div class="text-secondary" style="font-size: 0.7rem;"><?php echo e($teacher->email); ?></div>
                                                            </div>
                                                        </div>
                                                        <span class="badge bg-light text-secondary border rounded-pill px-2.5 py-1" style="font-size: 0.68rem;">Guru</span>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                    <div class="text-secondary small italic text-center py-2">Tidak ada pengajar di kelas ini.</div>
                                                <?php endif; ?>
                                            </div>

                                            
                                            <h6 class="fw-bold text-secondary mb-3 small" style="letter-spacing: 0.05em; text-transform: uppercase;">Siswa / Anggota</h6>
                                            <div class="d-flex flex-column gap-3">
                                                <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                    <div class="d-flex align-items-center justify-content-between gap-3">
                                                        <div class="d-flex align-items-center gap-2.5">
                                                            <div class="rounded-circle d-flex align-items-center justify-content-center text-dark fw-bold bg-light" style="width: 34px; height: 34px; font-size: 0.75rem;">
                                                                <?php echo e(strtoupper(substr($student->name, 0, 2))); ?>

                                                            </div>
                                                            <div>
                                                                <div class="fw-semibold text-dark small" style="font-size: 0.85rem;"><?php echo e($student->name); ?></div>
                                                                <div class="text-secondary" style="font-size: 0.7rem;"><?php echo e($student->email); ?></div>
                                                            </div>
                                                        </div>
                                                        
                                                        <form action="<?php echo e(route('admin.courses.kick', [$item->id, $student->id])); ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengeluarkan siswa <?php echo e($student->name); ?> dari kelas ini?')">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="submit" class="btn btn-sm btn-outline-danger px-2.5 py-1 rounded-3" style="font-size: 0.72rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.25rem;">
                                                                <i class="bi bi-person-x"></i> Keluarkan
                                                            </button>
                                                        </form>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                    <div class="text-secondary small italic text-center py-2">Belum ada siswa di kelas ini.</div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 px-4 pb-4">
                                            <button type="button" class="btn btn-light btn-sm rounded-3 px-3 fw-bold" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if($courses->isEmpty()): ?>
                    <tr>
                        <td colspan="6" class="text-center text-secondary py-4">Tidak ada data kelas.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\mini-lms-classroom\resources\views/admin/kelas.blade.php ENDPATH**/ ?>