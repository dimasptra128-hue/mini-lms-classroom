<?php $__env->startSection('title', 'Tugas'); ?>

<?php $__env->startSection('content'); ?>

<div class="mb-4">
    <h5 class="fw-bold mb-0 text-dark">Tugas</h5>
    <p class="text-secondary mb-0 small">Semua tugas dari kelas yang kamu ikuti</p>
</div>


<ul class="nav mb-4" style="border-bottom: 2px solid #e2e8f0; gap: 0.25rem;">
    <li class="nav-item">
        <a class="nav-link active px-4 py-2 fw-semibold"
           style="color: #1F7A8C; border-bottom: 2px solid #1F7A8C; margin-bottom: -2px; background: none; font-size: 0.9rem;"
           href="#">Semua</a>
    </li>
</ul>


<?php $__currentLoopData = $taskGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="mb-4">
    <div class="d-flex align-items-center gap-2 mb-3">
        <span class="fw-semibold small text-dark"><?php echo e($group['label']); ?></span>
        <span class="badge rounded-pill" style="background-color: <?php echo e($group['badge_bg']); ?>; color: <?php echo e($group['badge_color']); ?>; font-size: 0.72rem;">
            <?php echo e(count($group['tasks'])); ?>

        </span>
    </div>
    
    <?php if(count($group['tasks']) > 0): ?>
        <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
            <?php $__currentLoopData = $group['tasks']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="px-4 py-3 d-flex align-items-center gap-3 <?php echo e(!$loop->last ? 'border-bottom' : ''); ?>">
                <div class="flex-grow-1">
                    <div class="fw-semibold small text-dark"><?php echo e($task->title); ?></div>
                    <div class="text-secondary" style="font-size: 0.78rem;"><?php echo e($task->course->name); ?> · Batas: <?php echo e($task->due_date); ?></div>
                    <?php if($task->file_name): ?>
                        <div class="mt-1">
                            <a href="<?php echo e(asset('storage/' . $task->file_path)); ?>" download class="btn btn-light btn-sm d-inline-flex align-items-center gap-1.5 text-secondary border px-2 py-0.5 rounded-2" style="font-size: 0.72rem; text-decoration: none;">
                                <i class="bi bi-file-earmark-arrow-down-fill text-danger"></i>
                                <span class="text-dark fw-medium text-truncate" style="max-width: 180px;"><?php echo e($task->file_name); ?></span>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if($task->is_completed): ?>
                    <span class="badge rounded-pill"
                        style="background-color: #dcfce7; color: #16a34a; font-size: 0.72rem;">
                        ✓ Selesai
                    </span>
                <?php elseif($task->status === 'Draft'): ?>
                    <span class="badge rounded-pill"
                        style="background-color: #fff7ed; color: #b45309; font-size: 0.72rem;">
                        Draft
                    </span>
                <?php else: ?>
                    <a href="<?php echo e(route('courses.show', $task->course_id)); ?>"
                        class="btn btn-sm text-white"
                        style="font-size: 0.8rem; background-color: <?php echo e($task->course->color); ?>; border-radius: 6px; padding: 0.3rem 0.75rem; font-weight: 600; border: none;">
                        Kerjakan
                    </a>
                <?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 text-center">
            <span class="text-secondary small">Tidak ada tugas di kategori ini</span>
        </div>
    <?php endif; ?>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dimas\mini-lms-classroom\resources\views/tasks.blade.php ENDPATH**/ ?>