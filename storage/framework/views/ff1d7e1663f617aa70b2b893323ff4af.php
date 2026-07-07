<?php $__env->startSection('title', 'Kelas'); ?>

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

<?php if(session('info')): ?>
    <div class="alert alert-info alert-dismissible fade show border-0 rounded-4 shadow-sm mb-4" role="alert" style="background-color: #ceeaf0; color: #1F7A8C; font-family: var(--font-sans);">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-info-circle-fill fs-5"></i>
            <div><?php echo e(session('info')); ?></div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="fw-bold mb-0 text-dark">Kelas</h5>
        <p class="text-secondary mb-0 small">Semua kelas yang sedang kamu ikuti atau ajar</p>
    </div>
    <a href="#" class="btn btn-gabung-kelas" data-bs-toggle="modal" data-bs-target="#modalGabungKelas">
        <i class="bi bi-plus-lg me-1"></i> Gabung Kelas
    </a>
</div>

<ul class="nav nav-pills mb-4" id="kelasTab" role="tablist" style="background: #fff; padding: 0.5rem; border-radius: 12px; border: 1px solid #e2e8f0; gap: 0.25rem; width: fit-content; font-family: var(--font-sans);">
    <li class="nav-item" role="presentation">
        <button class="nav-link active px-4 py-2.5 rounded-3 fw-semibold" id="active-tab" data-bs-toggle="tab" data-bs-target="#active-classes" type="button" role="tab" aria-controls="active-classes" aria-selected="true" style="font-size: 0.88rem; transition: all 0.2s;">
            Kelas Aktif (<?php echo e($activeCourses->count()); ?>)
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link px-4 py-2.5 rounded-3 fw-semibold" id="archived-tab" data-bs-toggle="tab" data-bs-target="#archived-classes" type="button" role="tab" aria-controls="archived-classes" aria-selected="false" style="font-size: 0.88rem; transition: all 0.2s;">
            Kelas Diarsipkan (<?php echo e($archivedCourses->count()); ?>)
        </button>
    </li>
</ul>

<div class="tab-content" id="kelasTabContent">
    
    <div class="tab-pane fade show active" id="active-classes" role="tabpanel" aria-labelledby="active-tab">
        <?php if($activeCourses->count() > 0): ?>
        <div class="row g-3">
            <?php $__currentLoopData = $activeCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-6 col-lg-6">
                <a href="<?php echo e(route('courses.show', $course->id)); ?>" class="card-course-link text-decoration-none" style="display: block; color: inherit; height: 100%;">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 hover-shadow transition-all" style="background: #fff;">
                        
                        <div class="px-4 py-4 text-white" style="background: <?php echo e($course->color); ?>;">
                            <div class="fw-bold fs-5 mb-1 text-truncate"><?php echo e($course->name); ?></div>
                            <div style="font-size: 0.8rem; opacity: 0.85;"><?php echo e($course->level); ?></div>
                        </div>
                        
                        
                        <div class="card-body px-4 py-3">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <?php if($course->creator?->avatar): ?>
                                    <img src="<?php echo e(asset('storage/' . $course->creator->avatar)); ?>" alt="<?php echo e($course->teacher_name); ?>" class="rounded-circle" style="width:28px;height:28px;object-fit:cover;">
                                <?php else: ?>
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width:28px;height:28px;font-size:0.7rem;background:<?php echo e($course->color); ?>;">
                                        <?php echo e(strtoupper(substr($course->teacher_name, 0, 2))); ?>

                                    </div>
                                <?php endif; ?>
                                <span class="text-secondary small"><?php echo e($course->teacher_name); ?></span>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top">
                                <?php if($course->pending_tasks_count > 0): ?>
                                    <span class="badge rounded-pill" style="background-color: #fee2e2; color: #dc2626; font-size: 0.75rem;">
                                        <?php echo e($course->pending_tasks_count); ?> Tugas Baru
                                    </span>
                                <?php else: ?>
                                    <span class="badge rounded-pill" style="background-color: #dcfce7; color: #16a34a; font-size: 0.75rem;">
                                        Tidak ada tugas
                                    </span>
                                <?php endif; ?>
                                <span class="small fw-bold" style="color: <?php echo e($course->color); ?>;">
                                    Masuk Kelas <i class="bi bi-arrow-right ms-1"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php else: ?>
        <div class="card border-0 shadow-sm rounded-4 bg-white p-5 text-center">
            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 72px; height: 72px;">
                <i class="bi bi-journal-x text-secondary fs-3"></i>
            </div>
            <h6 class="fw-bold text-dark">Belum ada kelas diikuti</h6>
            <p class="text-secondary small mb-4">Gabung dengan kode kelas dari guru kamu atau buat kelas baru sendiri.</p>
            <div class="d-flex gap-2 justify-content-center">
                <button class="btn btn-gabung-kelas" data-bs-toggle="modal" data-bs-target="#modalGabungKelas">
                    <i class="bi bi-door-open-fill me-1"></i> Gabung Kelas
                </button>
                <button class="btn btn-bikin-kelas" data-bs-toggle="modal" data-bs-target="#modalBikinKelas">
                    <i class="bi bi-plus-lg me-1"></i> Buat Kelas
                </button>
            </div>
        </div>
        <?php endif; ?>
    </div>

    
    <div class="tab-pane fade" id="archived-classes" role="tabpanel" aria-labelledby="archived-tab">
        <?php if($archivedCourses->count() > 0): ?>
        <div class="row g-3">
            <?php $__currentLoopData = $archivedCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-6 col-lg-6">
                <a href="<?php echo e(route('courses.show', $course->id)); ?>" class="card-course-link text-decoration-none" style="display: block; color: inherit; height: 100%; opacity: 0.85;">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 hover-shadow transition-all" style="background: #fff;">
                        
                        <div class="px-4 py-4 text-white d-flex justify-content-between align-items-start" style="background: #64748b;">
                            <div>
                                <div class="fw-bold fs-5 mb-1 text-truncate text-white-50" style="text-decoration: line-through;"><?php echo e($course->name); ?></div>
                                <div style="font-size: 0.8rem; opacity: 0.85;"><?php echo e($course->level); ?></div>
                            </div>
                            <span class="badge rounded-pill bg-white text-dark py-1.5 px-2.5 fw-bold" style="font-size: 0.68rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                <i class="bi bi-archive-fill me-1 text-secondary"></i> Diarsipkan
                            </span>
                        </div>
                        
                        
                        <div class="card-body px-4 py-3">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <?php if($course->creator?->avatar): ?>
                                    <img src="<?php echo e(asset('storage/' . $course->creator->avatar)); ?>" alt="<?php echo e($course->teacher_name); ?>" class="rounded-circle" style="width:28px;height:28px;object-fit:cover;">
                                <?php else: ?>
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width:28px;height:28px;font-size:0.7rem;background:#64748b;">
                                        <?php echo e(strtoupper(substr($course->teacher_name, 0, 2))); ?>

                                    </div>
                                <?php endif; ?>
                                <span class="text-secondary small"><?php echo e($course->teacher_name); ?></span>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top">
                                <span class="badge rounded-pill" style="background-color: #f1f5f9; color: #64748b; font-size: 0.75rem;">
                                    Kelas Selesai
                                </span>
                                <span class="small fw-bold text-secondary">
                                    Buka Materi <i class="bi bi-arrow-right ms-1"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php else: ?>
        <div class="card border-0 shadow-sm rounded-4 bg-white p-5 text-center">
            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 72px; height: 72px;">
                <i class="bi bi-archive text-secondary fs-3"></i>
            </div>
            <h6 class="fw-bold text-dark">Tidak ada kelas diarsipkan</h6>
            <p class="text-secondary small mb-0">Kelas yang diarsipkan oleh pengajar akan muncul di sini.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
#kelasTab .nav-link {
    color: #64748b;
    background: transparent;
}
#kelasTab .nav-link:hover {
    color: #0f172a;
    background-color: #f1f5f9;
}
#kelasTab .nav-link.active {
    color: #fff !important;
    background-color: #1F7A8C !important;
}
.hover-shadow:hover {
    box-shadow: 0 .5rem 1.5rem rgba(0,0,0,0.08)!important;
    transform: translateY(-2px);
}
.transition-all {
    transition: all 0.2s ease-in-out;
}
.card-course-link {
    display: block;
    transition: transform 0.2s;
}
</style>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dimas\mini-lms-classroom\resources\views/kelas.blade.php ENDPATH**/ ?>