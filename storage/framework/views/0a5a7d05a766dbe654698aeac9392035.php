<?php $__env->startSection('title', 'Kelola Pengguna'); ?>

<?php $__env->startSection('content'); ?>

<div class="mb-4">
    <h5 class="fw-bold mb-0 text-dark">Kelola Pengguna</h5>
    <p class="text-secondary mb-0 small">Lihat dan kelola seluruh pengguna terdaftar di sistem</p>
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
                        <th>NAMA LENGKAP</th>
                        <th>EMAIL</th>
                        <th>KELAS YANG DIIKUTI</th>
                        <th>TANGGAL BERGABUNG</th>
                        <th class="text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr style="font-size: 0.88rem;">
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle overflow-hidden"
                                    style="width: 32px; height: 32px; flex-shrink:0;">

                                    <?php if($u->avatar): ?>
                                        <img
                                            src="<?php echo e(asset('storage/' . $u->avatar)); ?>"
                                            alt="<?php echo e($u->name); ?>"
                                            style="width:100%; height:100%; object-fit:cover;">
                                    <?php else: ?>
                                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-danger text-white fw-bold"
                                            style="width:100%; height:100%; font-size:0.75rem;">
                                            <?php echo e(strtoupper(substr($u->name, 0, 2))); ?>

                                        </div>
                                    <?php endif; ?>

                                </div>
                                <div>
                                    <div class="fw-bold text-dark d-flex align-items-center gap-1.5 flex-wrap">
                                        <?php echo e($u->name); ?>

                                        
                                        <?php if($u->role === 'admin'): ?>
                                            <span class="badge rounded-pill px-2 py-0.5"
                                                style="font-size: 0.65rem; background-color: #f3e8ff; color: #7c3aed; border: 1px solid #d8b4fe;">
                                                Admin
                                            </span>
                                        <?php else: ?>
                                            <span class="badge rounded-pill px-2 py-0.5"
                                                style="font-size: 0.65rem; background-color: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe;">
                                                Student
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-secondary small">ID: #<?php echo e($u->id); ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="text-dark"><?php echo e($u->email); ?></td>
                        <td>
                            <span class="badge bg-light text-dark border px-3 py-2 rounded-3 fw-bold">
                                <i class="bi bi-journal-bookmark me-1 text-danger"></i> <?php echo e($u->courses_count ?? 0); ?> Kelas
                            </span>
                        </td>
                        <td class="text-secondary"><?php echo e($u->created_at->format('d M Y, H:i')); ?></td>
                        <td class="text-center">
                            <?php if($u->id !== auth()->id()): ?>
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <form action="<?php echo e(route('admin.users.toggle-role', $u->id)); ?>"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Ubah role user <?php echo e($u->name); ?>?')">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit"
                                            class="btn btn-sm btn-outline-primary px-2.5 py-1 rounded-3 fw-semibold d-inline-flex align-items-center gap-1 text-nowrap"
                                            style="font-size: 0.8rem;">
                                            <i class="bi bi-person-gear"></i>
                                            <?php echo e($u->role === 'admin' ? 'Jadikan Student' : 'Jadikan Admin'); ?>

                                        </button>
                                    </form>
                                    
                                    <form action="<?php echo e(route('admin.users.delete', $u->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun user <?php echo e($u->name); ?>? Semua kelas yang dia ajar atau ikuti akan terpengaruh.')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger px-2.5 py-1 rounded-3 fw-semibold d-inline-flex align-items-center gap-1 text-nowrap" style="font-size: 0.8rem;">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            <?php else: ?>
                                <span class="badge rounded-pill px-3 py-2" style="background-color: #f1f5f9; color: #64748b; font-size: 0.75rem; border: 1px solid #cbd5e1;">Akun Anda</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center text-secondary py-4">Tidak ada data pengguna.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\mini-lms-classroom\resources\views/admin/users.blade.php ENDPATH**/ ?>