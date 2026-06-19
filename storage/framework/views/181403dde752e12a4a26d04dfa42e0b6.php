<?php
use Illuminate\Support\Str;
?>


<?php $__env->startSection('title', 'Nilai'); ?>

<?php $__env->startSection('content'); ?>


<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
        <h5 class="fw-bold mb-0 text-dark">Nilai</h5>
        <p class="text-secondary mb-0 mt-1" style="font-size: 0.875rem;">
            Rekap nilai tugas dari semua kelas yang kamu ikuti
        </p>
    </div>
    <div class="d-flex gap-2 align-items-center">
        <span class="badge rounded-pill px-3 py-2" style="background: #ceeaf0; color: #1F7A8C; font-size: 0.8rem; font-weight: 600;">
            Semester Aktif
        </span>
    </div>
</div>


<div class="row g-3 mb-4">
    
    <div class="col-6 col-md-3">
        <div class="card report-summary-card border-0 shadow-sm rounded-4 h-100 overflow-hidden interactive-card" style="position: relative;">
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #1F7A8C, #38bdf8);"></div>
            <div class="card-body p-3 pt-4">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="d-flex align-items-center justify-content-center rounded-3 tooltip-container"
                         data-tooltip="Rata-rata dari seluruh tugas yang telah dinilai"
                         style="width: 36px; height: 36px; background: #ceeaf0; flex-shrink: 0;">
                        <i class="bi bi-graph-up interactive-icon" style="color: #1F7A8C; font-size: 1rem;"></i>
                    </div>
                    <span class="text-secondary" style="font-size: 0.75rem; font-weight: 500;">Rata-rata Nilai</span>
                </div>
                <div class="fw-bold" style="font-size: 2rem; color: #1e293b; line-height: 1.1;">
                    <?php echo e($overallAvg ?? '-'); ?>

                </div>
                <?php if($overallAvg): ?>
                    <div class="mt-1">
                        <span class="badge rounded-pill px-2" style="background: <?php echo e($overallGradeColor); ?>20; color: <?php echo e($overallGradeColor); ?>; font-size: 0.75rem;">
                            Predikat <?php echo e($overallGrade); ?>

                        </span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <div class="col-6 col-md-3">
        <div class="card report-summary-card border-0 shadow-sm rounded-4 h-100 overflow-hidden interactive-card" style="position: relative;">
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #7c3aed, #a78bfa);"></div>
            <div class="card-body p-3 pt-4">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="d-flex align-items-center justify-content-center rounded-3 tooltip-container"
                         data-tooltip="Jumlah seluruh tugas yang diterbitkan guru"
                         style="width: 36px; height: 36px; background: #ede9fe; flex-shrink: 0;">
                        <i class="bi bi-clipboard-check interactive-icon" style="color: #7c3aed; font-size: 1rem;"></i>
                    </div>
                    <span class="text-secondary" style="font-size: 0.75rem; font-weight: 500;">Total Tugas</span>
                </div>
                <div class="fw-bold" style="font-size: 2rem; color: #1e293b; line-height: 1.1;"><?php echo e($totalTasks); ?></div>
                <div class="text-secondary mt-1" style="font-size: 0.75rem;">dari semua kelas</div>
            </div>
        </div>
    </div>

    
    <div class="col-6 col-md-3">
        <div class="card report-summary-card border-0 shadow-sm rounded-4 h-100 overflow-hidden interactive-card" style="position: relative;">
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #16a34a, #4ade80);"></div>
            <div class="card-body p-3 pt-4">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="d-flex align-items-center justify-content-center rounded-3 tooltip-container"
                         data-tooltip="Tugas yang telah dikerjakan & diserahkan"
                         style="width: 36px; height: 36px; background: #dcfce7; flex-shrink: 0;">
                        <i class="bi bi-check2-circle interactive-icon" style="color: #16a34a; font-size: 1rem;"></i>
                    </div>
                    <span class="text-secondary" style="font-size: 0.75rem; font-weight: 500;">Sudah Selesai</span>
                </div>
                <div class="fw-bold" style="font-size: 2rem; color: #1e293b; line-height: 1.1;"><?php echo e($totalSelesai); ?></div>
                <div class="text-secondary mt-1" style="font-size: 0.75rem;">
                    <?php echo e($totalTasks > 0 ? round(($totalSelesai / $totalTasks) * 100) : 0); ?>% dari total
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-6 col-md-3">
        <div class="card report-summary-card border-0 shadow-sm rounded-4 h-100 overflow-hidden interactive-card" style="position: relative;">
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #b45309, #fbbf24);"></div>
            <div class="card-body p-3 pt-4">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="d-flex align-items-center justify-content-center rounded-3 tooltip-container"
                         data-tooltip="Tugas yang telah diberi nilai oleh pengajar"
                         style="width: 36px; height: 36px; background: #fef3c7; flex-shrink: 0;">
                        <i class="bi bi-award interactive-icon" style="color: #b45309; font-size: 1rem;"></i>
                    </div>
                    <span class="text-secondary" style="font-size: 0.75rem; font-weight: 500;">Sudah Dinilai</span>
                </div>
                <div class="fw-bold" style="font-size: 2rem; color: #1e293b; line-height: 1.1;"><?php echo e($totalGraded); ?></div>
                <div class="text-secondary mt-1" style="font-size: 0.75rem;">tugas dengan nilai</div>
            </div>
        </div>
    </div>
</div>


<?php $__empty_1 = true; $__currentLoopData = $reportData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
        
        <div class="card-header border-0 p-4 pb-3 card-header-toggle"
             style="background: <?php echo e($data->course->color); ?>12; cursor: pointer;"
             data-bs-toggle="collapse"
             data-bs-target="#collapseCourse<?php echo e($data->course->id); ?>"
             aria-expanded="true">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-3">
                    <div>
                        <h5 class="fw-bold mb-0 text-dark" style="font-size: 1rem;"><?php echo e($data->course->name); ?></h5>
                        <small class="text-secondary"><?php echo e($data->course->subject); ?> · <?php echo e($data->course->teacher_name); ?></small>
                    </div>
                </div>
                
                
                <div class="d-flex align-items-center gap-3">
                    <div class="text-end">
                        <?php if($data->avgScore !== null): ?>
                            <div class="fw-bold" style="font-size: 1.75rem; color: <?php echo e($data->avgScoreColor); ?>; line-height: 1;">
                                <?php echo e($data->avgScore); ?>

                            </div>
                            <small style="color: <?php echo e($data->avgScoreColor); ?>; font-weight: 600;">Predikat <?php echo e($data->avgScoreGrade); ?></small>
                        <?php else: ?>
                            <div class="fw-bold text-secondary" style="font-size: 1.4rem;">–</div>
                            <small class="text-secondary">Belum ada nilai</small>
                        <?php endif; ?>
                    </div>
                    <i class="bi bi-chevron-down collapse-chevron" style="font-size: 1.25rem; color: #64748b;"></i>
                </div>
            </div>

            
            <div class="d-flex gap-4 mt-3 flex-wrap">
                <div class="text-center">
                    <div class="fw-bold" style="font-size: 0.9rem; color: <?php echo e($data->course->color); ?>;"><?php echo e($data->allTasks); ?></div>
                    <div class="text-secondary" style="font-size: 0.72rem;">Total Tugas</div>
                </div>
                <div class="text-center">
                    <div class="fw-bold" style="font-size: 0.9rem; color: #16a34a;"><?php echo e($data->selesai); ?></div>
                    <div class="text-secondary" style="font-size: 0.72rem;">Selesai</div>
                </div>
                <?php if($data->maxScore !== null): ?>
                <div class="text-center">
                    <div class="fw-bold" style="font-size: 0.9rem; color: #b45309;"><?php echo e($data->maxScore); ?></div>
                    <div class="text-secondary" style="font-size: 0.72rem;">Tertinggi</div>
                </div>
                <div class="text-center">
                    <div class="fw-bold" style="font-size: 0.9rem; color: #dc2626;"><?php echo e($data->minScore); ?></div>
                    <div class="text-secondary" style="font-size: 0.72rem;">Terendah</div>
                </div>
                <?php endif; ?>
            </div>

            
            <div class="mt-3">
                <div class="d-flex justify-content-between mb-1">
                    <small class="text-secondary" style="font-size: 0.72rem;">Penyelesaian Tugas</small>
                    <small class="fw-semibold" style="font-size: 0.72rem; color: <?php echo e($data->course->color); ?>;"><?php echo e($data->progress); ?>%</small>
                </div>
                <div class="rounded-pill overflow-hidden" style="height: 6px; background: <?php echo e($data->course->color); ?>25;">
                    <div class="rounded-pill" style="height: 100%; width: <?php echo e($data->progress); ?>%; background: <?php echo e($data->course->color); ?>; transition: width 0.8s ease;"></div>
                </div>
            </div>
        </div>

        
        <div class="collapse show" id="collapseCourse<?php echo e($data->course->id); ?>">
            
            <div class="card-body p-0 border-top">
                <?php if($data->tasks->isEmpty()): ?>
                    <div class="text-center py-5 text-secondary">
                        <i class="bi bi-inbox" style="font-size: 2rem; opacity: 0.4;"></i>
                        <p class="mt-2 mb-0" style="font-size: 0.875rem;">Belum ada tugas di kelas ini</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table mb-0 report-table" style="font-size: 0.85rem;">
                            <thead>
                                <tr class="report-table-head">
                                    <th class="px-4 py-3 fw-semibold text-secondary" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; width: 40%;">Judul Tugas</th>
                                    <th class="px-3 py-3 fw-semibold text-secondary" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Deadline</th>
                                    <th class="px-3 py-3 fw-semibold text-secondary" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; text-align: center;">Status</th>
                                    <th class="px-3 py-3 fw-semibold text-secondary" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; text-align: center;">Nilai</th>
                                    <th class="px-3 py-3 fw-semibold text-secondary" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; text-align: center;">Predikat</th>
                                    <th class="px-4 py-3 fw-semibold text-secondary" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Catatan Guru</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $data->tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="task-row">

                                        
                                        <td class="px-4 py-3">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi bi-file-earmark-text text-secondary" style="font-size: 0.95rem;"></i>
                                                <div class="fw-semibold text-dark" style="font-size: 0.85rem;"><?php echo e($task->title); ?></div>
                                            </div>
                                            <?php if($task->description): ?>
                                                <div class="text-secondary mt-1 ps-4" style="font-size: 0.75rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 280px;">
                                                    <?php echo e(Str::limit($task->description, 60)); ?>

                                                </div>
                                            <?php endif; ?>
                                        </td>

                                        
                                        <td class="px-3 py-3">
                                            <span class="text-secondary" style="font-size: 0.8rem;">
                                                <i class="bi bi-calendar2 me-1"></i><?php echo e($task->due_date ?? '-'); ?>

                                            </span>
                                        </td>

                                        
                                        <td class="px-3 py-3 text-center">
                                            <span class="badge rounded-pill px-2.5 py-1.5 d-inline-flex align-items-center gap-1 cursor-pointer"
                                                  style="background: <?php echo e($task->status_bg); ?>; color: <?php echo e($task->status_color); ?>; font-size: 0.72rem; font-weight: 600; transition: transform 0.2s;"
                                                  onmouseover="this.style.transform='scale(1.05)'"
                                                  onmouseout="this.style.transform='scale(1)'">
                                                <i class="bi <?php echo e($task->status_icon); ?>"></i><?php echo e($task->status); ?>

                                            </span>
                                        </td>

                                        
                                        <td class="px-3 py-3 text-center">
                                            <?php if($task->score !== null): ?>
                                                <div class="fw-bold" style="font-size: 1.15rem; color: <?php echo e($task->score_color); ?>;">
                                                    <?php echo e($task->score); ?>

                                                </div>
                                            <?php else: ?>
                                                <span class="text-secondary" style="font-size: 0.8rem;">–</span>
                                            <?php endif; ?>
                                        </td>

                                        
                                        <td class="px-3 py-3 text-center">
                                            <?php if($task->score !== null): ?>
                                                <span class="fw-bold d-inline-flex align-items-center justify-content-center rounded-circle grade-badge"
                                                      style="width: 30px; height: 30px; background: <?php echo e($task->score_color); ?>18; color: <?php echo e($task->score_color); ?>; font-size: 0.8rem; cursor: help;"
                                                      title="Nilai <?php echo e($task->score); ?>">
                                                    <?php echo e(isset($task->_model) ? $task->_model->scoreGrade() : (
                                                        $task->score >= 90 ? 'A' : ($task->score >= 80 ? 'B' : ($task->score >= 70 ? 'C' : ($task->score >= 60 ? 'D' : 'E')))
                                                    )); ?>

                                                </span>
                                            <?php else: ?>
                                                <span class="text-secondary" style="font-size: 0.8rem;">–</span>
                                            <?php endif; ?>
                                        </td>

                                        
                                        <td class="px-4 py-3">
                                            <?php if($task->feedback): ?>
                                                <div class="d-flex align-items-start gap-2 tooltip-container" data-tooltip="<?php echo e($task->feedback); ?>">
                                                    <i class="bi bi-chat-left-quote-fill mt-1" style="font-size: 0.82rem; cursor: pointer; color: <?php echo e($data->course->color); ?>;"></i>
                                                    <span class="text-secondary text-truncate" style="font-size: 0.78rem; font-style: italic; max-width: 200px; display: inline-block;">
                                                        <?php echo e(Str::limit($task->feedback, 65)); ?>

                                                    </span>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-secondary" style="font-size: 0.78rem;">–</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
        <div class="mb-3">
            <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                 style="width: 72px; height: 72px; background: #ceeaf0;">
                <i class="bi bi-bar-chart" style="font-size: 2rem; color: #1F7A8C;"></i>
            </div>
        </div>
        <h5 class="fw-bold text-dark mb-2">Belum Ada Data Nilai</h5>
        <p class="text-secondary mb-3" style="font-size: 0.875rem;">
            Kamu belum terdaftar di kelas manapun, atau belum ada tugas yang dinilai.
        </p>
        <a href="<?php echo e(route('courses')); ?>" class="btn btn-gabung-kelas mx-auto" style="max-width: 180px;">
            <i class="bi bi-plus-circle me-1"></i> Gabung Kelas
        </a>
    </div>
<?php endif; ?>


<div class="card border-0 rounded-4 mt-2 mb-4" style="background: #f8fafc;">
    <div class="card-body py-3 px-4">
        <div class="d-flex align-items-center flex-wrap gap-3">
            <small class="text-secondary fw-semibold" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Keterangan Predikat:</small>
            <?php $__currentLoopData = [['A','≥ 90','#16a34a','Sangat Baik'],['B','80–89','#1F7A8C','Baik'],['C','70–79','#b45309','Cukup'],['D','60–69','#dc2626','Kurang / Perlu Perbaikan'],['E','< 60','#7f1d1d','Sangat Kurang']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$g,$r,$c,$d]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="d-flex align-items-center gap-1 tooltip-container" data-tooltip="<?php echo e($d); ?>" style="cursor: help;">
                <span class="fw-bold d-inline-flex align-items-center justify-content-center rounded-circle grade-badge"
                      style="width: 22px; height: 22px; background: <?php echo e($c); ?>18; color: <?php echo e($c); ?>; font-size: 0.7rem;"><?php echo e($g); ?></span>
                <small style="color: #64748b; font-size: 0.75rem;"><?php echo e($r); ?></small>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>


<style>
/* Card Hover effect */
.interactive-card {
    transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease;
}
.interactive-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px -8px rgba(0,0,0,0.08) !important;
}

/* Icon scale effect on hover */
.interactive-card:hover .interactive-icon {
    transform: scale(1.15) rotate(4deg);
}
.interactive-icon {
    transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    display: inline-block;
}

/* Collapsible header styling */
.card-header-toggle {
    transition: background-color 0.2s ease-in-out, border-radius 0.25s ease;
    border-radius: 1rem 1rem 0 0 !important;
}
.card-header-toggle:hover {
    filter: brightness(0.97);
}
.card-header-toggle.collapsed {
    border-radius: 1rem !important;
}

/* Chevron arrow rotation */
.collapse-chevron {
    transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.card-header-toggle.collapsed .collapse-chevron {
    transform: rotate(-90deg);
}

/* Table row transition */
.task-row {
    transition: background-color 0.15s ease;
}
.task-row:hover {
    background-color: #f8fafc !important;
}

/* Grade circle animation */
.grade-badge {
    transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.2s ease;
}
.grade-badge:hover {
    transform: scale(1.2);
    box-shadow: 0 4px 6px rgba(0,0,0,0.06);
}

.report-table thead tr.report-table-head {
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}

.report-table tbody tr.task-row {
    border-bottom: 1px solid #f1f5f9;
}

.report-table tbody tr.task-row:hover {
    background-color: #f8fafc !important;
}

[data-theme="dark"] .report-table thead tr.report-table-head {
    background: rgba(148,163,184,0.1);
    border-bottom-color: #334155;
}

[data-theme="dark"] .report-table tbody tr.task-row {
    border-bottom-color: #334155;
}

[data-theme="dark"] .report-table tbody tr.task-row:hover {
    background-color: rgba(148,163,184,0.12) !important;
}

.report-table,
.report-table thead,
.report-table tbody,
.report-table tr,
.report-table th,
.report-table td {
    background-color: transparent !important;
}

[data-theme="dark"] .report-table,
[data-theme="dark"] .report-table thead,
[data-theme="dark"] .report-table tbody,
[data-theme="dark"] .report-table tr,
[data-theme="dark"] .report-table th,
[data-theme="dark"] .report-table td {
    background-color: transparent !important;
}

[data-theme="dark"] .report-table td,
[data-theme="dark"] .report-table th {
    color: var(--dm-text) !important;
}

[data-theme="dark"] .report-table .text-secondary {
    color: var(--dm-text-muted) !important;
}

/* Pure CSS tooltips */
.tooltip-container {
    position: relative;
}
.tooltip-container::after {
    content: attr(data-tooltip);
    position: absolute;
    bottom: 125%;
    left: 50%;
    transform: translateX(-50%) translateY(8px);
    background-color: #0f172a;
    color: #ffffff;
    padding: 0.35rem 0.7rem;
    border-radius: 6px;
    font-size: 0.72rem;
    white-space: nowrap;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.2s ease, transform 0.2s ease, visibility 0.2s ease;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    z-index: 100;
    pointer-events: none;
    font-weight: 500;
}
.tooltip-container:hover::after {
    opacity: 1;
    visibility: visible;
    transform: translateX(-50%) translateY(0);
}
</style>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\mini-lms-classroom\resources\views/report.blade.php ENDPATH**/ ?>