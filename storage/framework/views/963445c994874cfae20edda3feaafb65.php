<?php $__env->startSection('title', $course->name); ?>

<?php $__env->startSection('content'); ?>
 
<?php
    // Normalize materials and tasks so view works whether they're relations or JSON attributes
    $materials = isset($materialModels) ? collect($materialModels) : collect();

    $tasksRaw = $course->relationLoaded('tasks') ? $course->tasks : ($course->tasks ?? []);
    if (is_string($tasksRaw)) {
        $tasksRaw = json_decode($tasksRaw, true) ?: [];
    }
    $tasks = collect($tasksRaw)->map(function($t){ return is_object($t) ? $t : (object) $t; });
?>
<?php
    // If controller provided concrete Task models, prefer them
    if (isset($taskModels)) {
        $tasks = collect($taskModels);
    }
?>


<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 rounded-4 shadow-sm mb-4" role="alert" style="background-color: #dcfce7; color: #16a34a; font-family: var(--font-sans);">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div><?php echo session('success'); ?></div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if($errors->any()): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 rounded-4 shadow-sm mb-4" role="alert" style="background-color: #fee2e2; color: #dc2626; font-family: var(--font-sans);">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <div><?php echo e($errors->first()); ?></div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>


<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4" style="background: linear-gradient(135deg, <?php echo e($course->color); ?>f0, <?php echo e($course->color); ?>aa), url('https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=1200&auto=format&fit=crop') center/cover; color: #fff;">
    <div class="px-4 py-5 d-flex flex-column justify-content-between position-relative" style="min-height: 180px; z-index: 2; background: rgba(0,0,0,0.15);">
        <div class="d-flex align-items-start mb-3 justify-content-between w-100 flex-wrap gap-2">
            <div>
                <span class="badge rounded-pill bg-white text-dark mb-2 px-3 py-2 fw-semibold" style="font-size: 0.72rem; color: <?php echo e($course->color); ?> !important; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                    Kode Kelas: <strong><?php echo e($course->code); ?></strong>
                </span>
                <h2 class="fw-bold mb-1" style="font-size: 1.8rem; letter-spacing: -0.02em;"><?php echo e($course->name); ?></h2>
                <p class="mb-0 text-white-50 small fw-medium"><?php echo e($course->subject); ?></p>
            </div>
            <?php if($userRole === 'student'): ?>
                <form action="<?php echo e(route('courses.leave', $course->id)); ?>" method="POST" onsubmit="return confirm('Apakah kamu yakin ingin keluar dari kelas <?php echo e($course->name); ?>?')">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-sm btn-danger px-3 py-2 rounded-3 fw-bold d-inline-flex align-items-center gap-1.5" style="border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.15); font-size: 0.8rem; background-color: #dc2626;">
                        <i class="bi bi-box-arrow-right"></i> Keluar Kelas
                    </button>
                </form>
            <?php elseif($course->creator_id === auth()->id()): ?>
                <form action="<?php echo e(route('courses.destroy', $course->id)); ?>" method="POST" onsubmit="return confirm('Apakah kamu yakin ingin menghapus kelas <?php echo e($course->name); ?>? Semua materi, tugas, dan nilai di kelas ini akan dihapus permanen.')">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-sm btn-danger px-3 py-2 rounded-3 fw-bold d-inline-flex align-items-center gap-1.5" style="border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.15); font-size: 0.8rem; background-color: #dc2626;">
                        <i class="bi bi-trash"></i> Hapus Kelas
                    </button>
                </form>
            <?php endif; ?>
        </div>
        <div class="d-flex align-items-center justify-content-between mt-auto pt-3 border-top border-white border-opacity-10">
            <div class="d-flex align-items-center gap-2">

                <div class="rounded-circle overflow-hidden bg-white"
                    style="width: 32px; height: 32px; flex-shrink:0;">

                    <?php if($course->creator && $course->creator->avatar): ?>
                        <img
                            src="<?php echo e(asset('storage/' . $course->creator->avatar)); ?>"
                            alt="Teacher Avatar"
                            style="width:100%; height:100%; object-fit:cover;">
                    <?php else: ?>
                        <div class="d-flex align-items-center justify-content-center fw-bold"
                            style="width:100%; height:100%; font-size:0.75rem; color: <?php echo e($course->color); ?>;">
                            <?php echo e(strtoupper(substr($course->teacher_name, 0, 2))); ?>

                        </div>
                    <?php endif; ?>

                </div>

                <span class="small text-white fw-semibold">
                    <?php echo e($course->teacher_name); ?>

                </span>

            </div>

            <div class="text-white-50 small fw-medium">
                <?php echo e($course->level); ?>

            </div>
        </div>
    </div>
</div>


<ul class="nav nav-pills mb-4" id="courseTab" role="tablist" style="background: #fff; padding: 0.5rem; border-radius: 12px; border: 1px solid #e2e8f0; gap: 0.25rem;">
    <li class="nav-item" role="presentation">
        <button class="nav-link active px-4 py-2.5 rounded-3 fw-semibold" id="stream-tab" data-bs-toggle="tab" data-bs-target="#stream" type="button" role="tab" aria-controls="stream" aria-selected="true" style="font-size: 0.88rem; transition: all 0.2s;">
            <i class="bi bi-chat-square-text me-1.5"></i> Alur Kelas
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link px-4 py-2.5 rounded-3 fw-semibold" id="materi-tab" data-bs-toggle="tab" data-bs-target="#materi" type="button" role="tab" aria-controls="materi" aria-selected="false" style="font-size: 0.88rem; transition: all 0.2s;">
            <i class="bi bi-file-earmark-text me-1.5"></i> Materi Pelajaran
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link px-4 py-2.5 rounded-3 fw-semibold" id="tugas-tab" data-bs-toggle="tab" data-bs-target="#tugas" type="button" role="tab" aria-controls="tugas" aria-selected="false" style="font-size: 0.88rem; transition: all 0.2s;">
            <i class="bi bi-calendar-check me-1.5"></i> Tugas Kelas
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link px-4 py-2.5 rounded-3 fw-semibold" id="anggota-tab" data-bs-toggle="tab" data-bs-target="#anggota" type="button" role="tab" aria-controls="anggota" aria-selected="false" style="font-size: 0.88rem; transition: all 0.2s;">
            <i class="bi bi-people me-1.5"></i> Anggota
        </button>
    </li>
</ul>


<div class="tab-content" id="courseTabContent">
    
    
    <div class="tab-pane fade show active" id="stream" role="tabpanel" aria-labelledby="stream-tab">
        <div class="row g-4">
            
            <div class="col-lg-3 d-none d-lg-block">
                <div class="card border-0 shadow-sm rounded-4 bg-white p-3 mb-3">
                    <h6 class="fw-bold text-dark mb-2" style="font-size: 0.88rem;">Tentang Kelas</h6>
                    <div class="text-secondary small mb-3">
                        Dibuat oleh <strong><?php echo e($course->teacher_name); ?></strong>. Gunakan kode kelas untuk mengajak siswa bergabung.
                    </div>
                    <div class="p-2.5 rounded-3 bg-light d-flex align-items-center justify-content-between">
                        <span class="font-monospace text-dark fw-bold text-uppercase" style="letter-spacing: 0.05em;"><?php echo e($course->code); ?></span>
                        <button class="btn btn-sm text-primary p-1 border-0 bg-transparent" onclick="copyCode('<?php echo e($course->code); ?>')" title="Salin kode">
                            <i class="bi bi-clipboard"></i>
                        </button>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 bg-white p-3">
                    <h6 class="fw-bold text-dark mb-2" style="font-size: 0.88rem;">Tugas Mendatang</h6>

                    <?php if($upcomingTasks->count() > 0): ?>
                        <div class="d-flex flex-column gap-2">
                            <?php $__currentLoopData = $upcomingTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $utask): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="p-2 rounded-3" style="background-color: #ceeaf040; border-left: 3px solid <?php echo e($course->color); ?>;">
                                <div class="fw-semibold text-dark text-truncate" style="font-size: 0.78rem;"><?php echo e($utask->title); ?></div>
                                <div class="text-secondary" style="font-size: 0.7rem;">Batas: <?php echo e($utask->due_date); ?></div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-secondary small py-2">
                            <i class="bi bi-emoji-smile me-1"></i> Tidak ada tugas mendesak!
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="col-lg-9 col-12">
                
                <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
                    <div class="d-flex gap-3 align-items-start">
                        <div class="rounded-circle overflow-hidden"
                            style="width: 40px; height: 40px; flex-shrink: 0;">

                            <?php if($course->creator && $course->creator->avatar): ?>
                                <img
                                    src="<?php echo e(asset('storage/' . $course->creator->avatar)); ?>"
                                    alt="Avatar"
                                    class="rounded-circle"
                                    style="width:100%; height:100%; object-fit:cover;">
                            <?php else: ?>
                                <div class="d-flex align-items-center justify-content-center text-white fw-bold"
                                    style="width:100%; height:100%; font-size:0.85rem; background-color: <?php echo e($course->color); ?>;">
                                    <?php echo e(strtoupper(substr($course->teacher_name, 0, 2))); ?>

                                </div>
                            <?php endif; ?>

                        </div>
                        <div class="flex-grow-1">
                            <div class="rounded-3 border px-3 py-2 text-secondary bg-light bg-opacity-50 cursor-pointer" 
                                 style="font-size: 0.88rem;" 
                                 onclick="openMaterialTab();">
                                Bagikan pengumuman atau materi baru dengan kelas...
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="d-flex flex-column gap-3">
                    

                    <?php if($feedItems->count() > 0): ?>
                        <?php $__currentLoopData = $feedItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                            <div class="d-flex align-items-start gap-3 mb-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                     style="width: 40px; height: 40px; background-color: <?php echo e($item['bg']); ?>;">
                                    <i class="bi <?php echo e($item['icon']); ?>" style="color: <?php echo e($item['color']); ?>; font-size: 1.1rem;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold text-dark small" style="font-size: 0.9rem;"><?php echo e($item['title']); ?></div>
                                    <div class="text-secondary" style="font-size: 0.75rem;"><?php echo e($item['date']->diffForHumans()); ?></div>
                                </div>
                            </div>
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.95rem;"><?php echo e($item['content']); ?></h6>
                            <?php if($item['desc']): ?>
                                <p class="text-secondary small mb-3 text-break"><?php echo e($item['desc']); ?></p>
                            <?php endif; ?>
                            <div class="d-flex justify-content-start border-top pt-2 mt-2">
                                <?php if($item['type'] === 'materi'): ?>
                                    <a href="<?php echo e(route('materials.show', [$course->id, $item['id']])); ?>"
                                       class="btn btn-sm px-3 rounded-2 fw-semibold text-decoration-none"
                                       style="color: <?php echo e($course->color); ?>; background-color: <?php echo e($course->color); ?>15; font-size: 0.78rem; border: none;">
                                        Buka Materi <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                <?php else: ?>
                                    <a href="<?php echo e(route('tasks.show', [$course->id, $item['id']])); ?>"
                                       class="btn btn-sm px-3 rounded-2 fw-semibold text-decoration-none"
                                       style="color: <?php echo e($item['color']); ?>; background-color: <?php echo e($item['color']); ?>15; font-size: 0.78rem; border: none;">
                                        Buka Tugas <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        
                        <div class="card border-0 shadow-sm rounded-4 bg-white p-5 text-center">
                            <img src="https://images.unsplash.com/photo-1546410531-bb4caa6b424d?q=80&w=400" alt="Blank Board" class="mx-auto mb-3 opacity-20" style="width: 140px; height: 100px; object-fit: contain;">
                            <h6 class="fw-bold text-dark">Alur kelas masih kosong</h6>
                            <p class="text-secondary small mb-0">Belum ada aktivitas, pengumuman, materi, atau tugas baru yang dibagikan.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
    <div class="tab-pane fade" id="materi" role="tabpanel" aria-labelledby="materi-tab">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h6 class="fw-bold mb-0 text-dark">Daftar Materi</h6>
                <p class="text-secondary mb-0 small">Bahan pembelajaran untuk diunduh dan dipelajari</p>
            </div>
            <?php if($userRole === 'teacher' || $course->creator_id === auth()->id()): ?>
                <button type="button" class="btn text-white px-3 py-2 rounded-3 d-flex align-items-center gap-1.5 fw-semibold" 
                        style="background-color: <?php echo e($course->color); ?>; font-size: 0.82rem;"
                        data-bs-toggle="modal" data-bs-target="#modalTambahMateri">
                    <i class="bi bi-plus-lg"></i> Tambah Materi
                </button>
            <?php endif; ?>
        </div>

        <?php if($materials->count() > 0): ?>
            <div class="row g-2">
                <?php $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 bg-white p-3 hover-shadow transition-all" style="border-left: 4px solid <?php echo e($course->color); ?> !important;">
                        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-sm-nowrap">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                                     style="width: 42px; height: 42px; background-color: <?php echo e($course->color); ?>10;">
                                    <i class="bi bi-file-earmark-text-fill" style="color: <?php echo e($course->color); ?>; font-size: 1.25rem;"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0.5" style="font-size: 0.92rem;"><?php echo e($mat->title); ?></h6>
                                    <span class="text-secondary" style="font-size: 0.75rem;">Diunggah <?php echo e($mat->created_at->isoFormat('D MMM YYYY')); ?></span>
                                </div>
                            </div>
                            <a href="<?php echo e(route('materials.show', [$course->id, $mat->id])); ?>" class="btn btn-sm px-3 rounded-3 fw-semibold flex-shrink-0" 
                               style="font-size: 0.8rem; background-color: <?php echo e($course->color); ?>15; color: <?php echo e($course->color); ?>; border: none; padding: 0.45rem 1rem;">
                                Buka Materi <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="card border-0 shadow-sm rounded-4 bg-white p-5 text-center">
                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 72px; height: 72px;">
                    <i class="bi bi-journal-x text-secondary fs-3"></i>
                </div>
                <h6 class="fw-bold text-dark">Belum ada materi pelajaran</h6>
                <p class="text-secondary small mb-0">Semua materi yang dibagikan oleh pengajar akan muncul di sini.</p>
            </div>
        <?php endif; ?>
    </div>

    
    <div class="tab-pane fade" id="tugas" role="tabpanel" aria-labelledby="tugas-tab">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h6 class="fw-bold mb-0 text-dark">Daftar Tugas Kelas</h6>
                <p class="text-secondary mb-0 small">Kumpulan latihan dan ujian kelas</p>
            </div>
            <?php if($userRole === 'teacher' || $course->creator_id === auth()->id()): ?>
                <button type="button" class="btn text-white px-3 py-2 rounded-3 d-flex align-items-center gap-1.5 fw-semibold" 
                        style="background-color: <?php echo e($course->color); ?>; font-size: 0.82rem;"
                        data-bs-toggle="modal" data-bs-target="#modalTambahTugas">
                    <i class="bi bi-plus-lg"></i> Tambah Tugas
                </button>
            <?php endif; ?>
        </div>

        <?php if($tasks->count() > 0): ?>
            <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
                <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="px-4 py-3 d-flex align-items-center justify-content-between gap-3 <?php echo e($i !== $tasks->count() - 1 ? 'border-bottom' : ''); ?> flex-wrap flex-sm-nowrap">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width: 40px; height: 40px; background-color: <?php echo e($course->color); ?>1a;">
                            <i class="bi bi-clipboard-check" style="color: <?php echo e($course->color); ?>; font-size: 1.1rem;"></i>
                        </div>
                        <div>
                            <div class="fw-bold small text-dark" style="font-size: 0.9rem;"><?php echo e($task->title); ?></div>
                            <div class="text-secondary" style="font-size: 0.75rem;">Tenggat: <?php echo e($task->due_date); ?></div>
                            <?php if(!empty($task->file_name)): ?>
                                <div class="text-secondary mt-1" style="font-size: 0.7rem;">
                                    <i class="bi bi-paperclip me-1"></i><?php echo e($task->file_name); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 ms-auto ms-sm-0 flex-shrink-0">
                        <?php if($task->status === 'Selesai'): ?>
                            <span class="badge rounded-pill px-2.5 py-1.5" style="background-color: #dcfce7; color: #16a34a; font-size: 0.72rem; font-weight: 600;">✓ Selesai</span>
                        <?php elseif($task->status === 'Draft'): ?>
                            <span class="badge rounded-pill px-2.5 py-1.5" style="background-color: #fff7ed; color: #b45309; font-size: 0.72rem; font-weight: 600;">Draft</span>
                        <?php else: ?>
                            <span class="badge rounded-pill px-2.5 py-1.5" style="background-color: #fff7ed; color: #b45309; font-size: 0.72rem; font-weight: 600;">Ditugaskan</span>
                        <?php endif; ?>
                        <a href="<?php echo e(route('tasks.show', [$course->id, $task->id])); ?>" class="btn btn-sm text-white px-3 py-1.5 rounded-3 fw-bold" 
                           style="font-size: 0.78rem; background-color: <?php echo e($course->color); ?>; border: none;">
                            Buka Tugas
                        </a>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="card border-0 shadow-sm rounded-4 bg-white p-5 text-center">
                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 72px; height: 72px;">
                    <i class="bi bi-file-earmark-x text-secondary fs-3"></i>
                </div>
                <h6 class="fw-bold text-dark">Tidak ada tugas saat ini</h6>
                <p class="text-secondary small mb-0">Kamu bebas dari semua tugas untuk kelas ini! Tetap pertahankan.</p>
            </div>
        <?php endif; ?>
    </div>

    
    <div class="tab-pane fade" id="anggota" role="tabpanel" aria-labelledby="anggota-tab">
        <div class="row g-4">
            
            
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                    <h5 class="fw-bold text-dark border-bottom pb-3 mb-3" style="font-size: 1.1rem; border-color: #e2e8f0 !important; color: <?php echo e($course->color); ?> !important;">
                        Pengajar
                    </h5>
                    <div class="d-flex flex-column gap-3">
                        <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle overflow-hidden"
                                style="width: 38px; height: 38px; flex-shrink: 0;">
                                <?php if(!empty($teacher->avatar)): ?>
                                    <img
                                        src="<?php echo e(asset('storage/' . $teacher->avatar)); ?>"
                                        alt="<?php echo e($teacher->name); ?>"
                                        style="width:100%; height:100%; object-fit:cover;">
                                <?php else: ?>
                                    <div class="d-flex align-items-center justify-content-center text-white fw-bold"
                                        style="width:100%; height:100%; font-size:0.8rem; background-color: <?php echo e($course->color); ?>;">
                                        <?php echo e(strtoupper(substr($teacher->name, 0, 2))); ?>

                                    </div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <div class="fw-semibold text-dark small" style="font-size: 0.9rem;"><?php echo e($teacher->name); ?></div>
                                <div class="text-secondary" style="font-size: 0.75rem;"><?php echo e($teacher->email); ?></div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>

            
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                    <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3" style="border-color: #e2e8f0 !important;">
                        <h5 class="fw-bold text-dark mb-0" style="font-size: 1.1rem; color: <?php echo e($course->color); ?> !important;">
                            Teman Sekelas
                        </h5>
                        <span class="badge rounded-pill fw-semibold" style="background-color: <?php echo e($course->color); ?>1a; color: <?php echo e($course->color); ?>; font-size: 0.78rem;">
                            <?php echo e($students->count()); ?> Anggota
                        </span>
                    </div>
                    <?php if($students->count() > 0): ?>
                        <div class="d-flex flex-column gap-3">
                            <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle overflow-hidden" style="width: 36px; height: 36px; flex-shrink:0; background-color:#f1f5f9;">
                                        <?php if($student->avatar): ?>
                                            <img
                                                src="<?php echo e(asset('storage/' . $student->avatar)); ?>"
                                                alt="Avatar"
                                                style="width:100%; height:100%; object-fit:cover;">
                                        <?php else: ?>
                                            <div class="d-flex align-items-center justify-content-center text-dark fw-semibold"
                                                style="width:100%; height:100%; font-size:0.75rem;">
                                                <?php echo e(strtoupper(substr($student->name, 0, 2))); ?>

                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark small" style="font-size: 0.85rem;"><?php echo e($student->name); ?></div>
                                        <div class="text-secondary" style="font-size: 0.72rem;"><?php echo e($student->email); ?></div>
                                    </div>
                                </div>
                                <?php if($userRole === 'teacher'): ?>
                                    <form action="<?php echo e(route('courses.kick', [$course->id, $student->id])); ?>" method="POST" onsubmit="return confirm('Keluarkan siswa <?php echo e($student->name); ?> dari kelas ini?')">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger px-2.5 py-1 rounded-3 fw-semibold d-inline-flex align-items-center gap-1" style="font-size: 0.75rem;">
                                            <i class="bi bi-person-x"></i> Keluarkan
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <p class="text-secondary small mb-0">Belum ada siswa yang bergabung di kelas ini.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>


<?php if($userRole === 'teacher' || $course->creator_id === auth()->id()): ?>
<div class="modal fade" id="modalTambahMateri" tabindex="-1" aria-labelledby="modalTambahMateriLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center rounded-3"
                         style="width: 44px; height: 44px; background-color: <?php echo e($course->color); ?>1a; flex-shrink: 0;">
                        <i class="bi bi-file-earmark-plus-fill" style="color: <?php echo e($course->color); ?>; font-size: 1.25rem;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0 text-dark" id="modalTambahMateriLabel">Bagikan Materi</h5>
                        <p class="text-secondary mb-0" style="font-size: 0.82rem;">Unggah modul pembelajaran baru untuk kelas ini</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="<?php echo e(route('materials.store', $course->id)); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="modal-body px-4 pt-3 pb-2">
                    <div class="mb-3">
                        <label class="form-label-custom">Judul Materi <span class="text-danger">*</span></label>
                        <div class="input-group-custom">
                            <span class="input-group-icon"><i class="bi bi-type-h1"></i></span>
                            <input type="text" class="form-control-custom" name="title" required placeholder="Contoh: Bab 3 - Fungsi Kuadrat">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label-custom">Deskripsi / Penjelasan</label>
                        <textarea class="form-control" name="description" rows="4" placeholder="Tulis instruksi atau rangkuman singkat materi di sini..." style="border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.9rem; padding: 0.65rem 0.85rem; font-family: var(--font-sans); outline: none;"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label-custom">Link Eksternal (Optional)</label>
                        <div class="input-group-custom">
                            <span class="input-group-icon"><i class="bi bi-link-45deg"></i></span>
                            <input type="url" class="form-control-custom" name="link_url" placeholder="Contoh: https://youtube.com/watch?v=...">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label-custom">Unggah File (Optional)</label>
                        <input type="file" class="form-control" name="file_upload" style="border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.88rem;">
                        <div class="form-text text-secondary" style="font-size: 0.75rem;">Mendukung PDF, Word, Excel, PPTX, Zip, Gambar. (Maks. 10MB)</div>
                    </div>
                </div>
                
                <div class="modal-footer border-0 px-4 pb-4 pt-2 gap-2">
                    <button type="button" class="btn btn-bikin-kelas" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn text-white flex-grow-1" style="background-color: <?php echo e($course->color); ?>; border: none; font-weight: 600; font-size: 0.85rem; padding: 0.55rem 1rem; border-radius: 6px;">
                        <i class="bi bi-send me-1"></i> Bagikan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>


<?php if($userRole === 'teacher' || $course->creator_id === auth()->id()): ?>
<div class="modal fade" id="modalTambahTugas" tabindex="-1" aria-labelledby="modalTambahTugasLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center rounded-3"
                         style="width: 44px; height: 44px; background-color: <?php echo e($course->color); ?>1a; flex-shrink: 0;">
                        <i class="bi bi-clipboard-plus-fill" style="color: <?php echo e($course->color); ?>; font-size: 1.25rem;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0 text-dark" id="modalTambahTugasLabel">Buat Tugas Baru</h5>
                        <p class="text-secondary mb-0" style="font-size: 0.82rem;">Tambahkan penugasan baru untuk kelas ini</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="<?php echo e(route('tasks.store', $course->id)); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="modal-body px-4 pt-3 pb-2">
                    <div class="mb-3">
                        <label class="form-label-custom">Judul Tugas <span class="text-danger">*</span></label>
                        <div class="input-group-custom">
                            <span class="input-group-icon"><i class="bi bi-type-h1"></i></span>
                            <input type="text" class="form-control-custom" name="title" required placeholder="Contoh: Latihan Soal Bab 3">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label-custom">Petunjuk / Deskripsi</label>
                        <textarea class="form-control" name="description" rows="4" placeholder="Tulis rincian tugas, petunjuk pengerjaan, atau materi terkait..." style="border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.9rem; padding: 0.65rem 0.85rem; font-family: var(--font-sans); outline: none;"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label-custom">Tenggat Waktu <span class="text-danger">*</span></label>
                        <div class="input-group-custom">
                            <span class="input-group-icon"><i class="bi bi-calendar3"></i></span>
                            <input type="datetime-local" class="form-control-custom" name="due_date" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">Unggah File Tugas (Optional)</label>
                        <input type="file" class="form-control" name="file_upload" style="border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.88rem;">
                        <div class="form-text text-secondary" style="font-size: 0.75rem;">Mendukung PDF, Word, Excel, PPTX, Zip, Gambar. (Maks. 10MB)</div>
                    </div>
                </div>
                
                <div class="modal-footer border-0 px-4 pb-4 pt-2 gap-2">
                    <button type="button" class="btn btn-bikin-kelas" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn text-white flex-grow-1" style="background-color: <?php echo e($course->color); ?>; border: none; font-weight: 600; font-size: 0.85rem; padding: 0.55rem 1rem; border-radius: 6px;">
                        <i class="bi bi-send me-1"></i> Tugaskan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<style>
/* CSS variables for active tab color */
#courseTab .nav-link {
    color: #64748b;
    background: transparent;
}
#courseTab .nav-link:hover {
    color: #0f172a;
    background-color: #f1f5f9;
}
#courseTab .nav-link.active {
    color: #fff !important;
    background-color: <?php echo e($course->color); ?> !important;
}
.cursor-pointer {
    cursor: pointer;
}
.hover-shadow:hover {
    box-shadow: 0 .5rem 1rem rgba(0,0,0,0.08)!important;
}
.transition-all {
    transition: all 0.25s ease-in-out;
}
</style>

<script>
function copyCode(code) {
    navigator.clipboard.writeText(code).then(() => {
        alert('Kode kelas ' + code + ' berhasil disalin!');
    }).catch(err => {
        console.error('Gagal menyalin: ', err);
    });
}
function openMaterialTab() {
    const triggerEl = document.querySelector('#materi-tab');
    const bsTab = bootstrap.Tab.getInstance(triggerEl);
    if (bsTab) {
        bsTab.show();
    }
    const modalEl = document.getElementById('modalTambahMateri');
    if (modalEl) {
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
    window.scrollTo({ top: triggerEl.offsetTop - 100, behavior: 'smooth' });
}
function openTasksTab() {
    const triggerEl = document.querySelector('#tugas-tab');
    bootstrap.Tab.getInstance(triggerEl).show();
    window.scrollTo({ top: triggerEl.offsetTop - 100, behavior: 'smooth' });
}
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\mini-lms-classroom\resources\views/kelas_details.blade.php ENDPATH**/ ?>