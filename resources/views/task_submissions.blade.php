@extends('layouts.app')

@section('title', 'Kelola Penilaian - ' . $task->title)

@section('content')

{{-- Alert Messages --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 rounded-4 shadow-sm mb-4" role="alert" style="background-color: #dcfce7; color: #16a34a; font-family: var(--font-sans);">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div>{!! session('success') !!}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Back to Task Link --}}
<div class="mb-4">
    <a href="{{ route('tasks.show', [$course->id, $task->id]) }}" class="d-inline-flex align-items-center gap-1 text-decoration-none fw-semibold text-secondary transition-all" style="font-size: 0.88rem;">
        <i class="bi bi-arrow-left"></i> Kembali ke Tugas: {{ $task->title }}
    </a>
</div>

<div class="task-submissions-page">

{{-- Task Header --}}
<div class="card border-0 shadow-sm rounded-4 bg-white p-4 p-md-5 mb-4">
    <div class="d-flex align-items-start gap-3 justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                 style="width: 52px; height: 52px; background-color: {{ $course->color }}15;">
                <i class="bi bi-file-earmark-check-fill" style="color: {{ $course->color }}; font-size: 1.6rem;"></i>
            </div>
            <div>
                <h4 class="fw-bold text-dark mb-2" style="font-size: 1.4rem;">{{ $task->title }}</h4>
                <div class="text-secondary" style="font-size: 0.88rem;">
                    Kelas: <strong>{{ $course->name }}</strong> | Tenggat: <strong>{{ $task->due_date }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Summary Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-people text-primary fs-5"></i>
                <span class="text-secondary" style="font-size: 0.85rem;">Total Siswa</span>
            </div>
            <div class="fw-bold fs-4 text-dark">{{ $totalStudents }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-check2-circle text-success fs-5"></i>
                <span class="text-secondary" style="font-size: 0.85rem;">Sudah Dikumpulkan</span>
            </div>
            <div class="fw-bold fs-4 text-dark">{{ $submittedCount }}/{{ $totalStudents }}</div>
            <div class="text-secondary mt-1" style="font-size: 0.75rem;">
                {{ $totalStudents > 0 ? round(($submittedCount / $totalStudents) * 100) : 0 }}%
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-star text-warning fs-5"></i>
                <span class="text-secondary" style="font-size: 0.85rem;">Sudah Dinilai</span>
            </div>
            <div class="fw-bold fs-4 text-dark">{{ $gradedCount }}/{{ $submittedCount }}</div>
            <div class="text-secondary mt-1" style="font-size: 0.75rem;">
                {{ $submittedCount > 0 ? round(($gradedCount / $submittedCount) * 100) : 0 }}%
            </div>
        </div>
    </div>
</div>

{{-- Students Submissions Table --}}
<div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead style="background-color: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                <tr>
                    <th style="padding: 1rem; font-weight: 600; font-size: 0.85rem; color: #475569;">No</th>
                    <th style="padding: 1rem; font-weight: 600; font-size: 0.85rem; color: #475569;">Nama Siswa</th>
                    <th style="padding: 1rem; font-weight: 600; font-size: 0.85rem; color: #475569;">Status</th>
                    <th style="padding: 1rem; font-weight: 600; font-size: 0.85rem; color: #475569;">Dikumpulkan</th>
                    <th style="padding: 1rem; font-weight: 600; font-size: 0.85rem; color: #475569;">File</th>
                    <th style="padding: 1rem; font-weight: 600; font-size: 0.85rem; color: #475569;">Nilai</th>
                    <th style="padding: 1rem; font-weight: 600; font-size: 0.85rem; color: #475569;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $index => $student)
                    @php
                        $submission = $submissions[$student->id] ?? null;
                        $hasSubmitted = $submission && data_get($submission, 'status') === 'Selesai';
                        $score = data_get($submission, 'score');
                        $feedback = data_get($submission, 'feedback');
                    @endphp
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 1rem; font-size: 0.9rem;">{{ $index + 1 }}</td>
                        <td style="padding: 1rem; font-size: 0.9rem;">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
                                     style="width: 32px; height: 32px; font-size: 0.7rem; background-color: {{ $course->color }};">
                                    {{ strtoupper(substr($student->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="fw-medium text-dark">{{ $student->name }}</div>
                                    <div class="text-secondary" style="font-size: 0.75rem;">{{ $student->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 1rem; font-size: 0.9rem;">
                            @if($hasSubmitted)
                                <span class="badge rounded-pill px-2 py-1" style="background-color: #dcfce7; color: #16a34a; font-size: 0.75rem;">
                                    <i class="bi bi-check-circle me-1"></i> Dikumpulkan
                                </span>
                            @else
                                <span class="badge rounded-pill px-2 py-1" style="background-color: #fee2e2; color: #dc2626; font-size: 0.75rem;">
                                    <i class="bi bi-x-circle me-1"></i> Belum
                                </span>
                            @endif
                        </td>
                        <td style="padding: 1rem; font-size: 0.9rem;">
                            @if($hasSubmitted)
                                <span class="text-secondary" style="font-size: 0.85rem;">
                                    {{ \Carbon\Carbon::parse(data_get($submission, 'submitted_at'))->format('d M Y H:i') }}
                                </span>
                            @else
                                <span class="text-secondary" style="font-size: 0.85rem;">-</span>
                            @endif
                        </td>
                        <td style="padding: 1rem; font-size: 0.9rem;">
                            @if($hasSubmitted && data_get($submission, 'file_path'))
                                <a href="{{ asset('storage/' . data_get($submission, 'file_path')) }}" 
                                   download 
                                   class="btn btn-sm btn-outline-secondary rounded-3"
                                   style="font-size: 0.75rem; padding: 0.3rem 0.6rem;">
                                    <i class="bi bi-download"></i> Unduh
                                </a>
                            @else
                                <span class="text-secondary" style="font-size: 0.85rem;">-</span>
                            @endif
                        </td>
                        <td style="padding: 1rem; font-size: 0.9rem;">
                            @if($score !== null)
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold" style="font-size: 1.1rem; color: {{ $score >= 80 ? '#16a34a' : ($score >= 70 ? '#b45309' : '#dc2626') }};">
                                        {{ $score }}
                                    </span>
                                    <span class="badge rounded-pill" 
                                          style="background-color: {{ $score >= 90 ? '#dcfce7; color: #16a34a' : ($score >= 80 ? '#fef3c7; color: #b45309' : '#fee2e2; color: #dc2626') }}; font-size: 0.7rem;">
                                        {{ $score >= 90 ? 'A' : ($score >= 80 ? 'B' : ($score >= 70 ? 'C' : ($score >= 60 ? 'D' : 'E'))) }}
                                    </span>
                                </div>
                            @else
                                <span class="text-secondary" style="font-size: 0.85rem;">-</span>
                            @endif
                        </td>
                        <td style="padding: 1rem; font-size: 0.9rem;">
                            <button class="btn btn-sm btn-outline-primary rounded-3" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#gradeModal"
                                    onclick="openGradeModal({{ $student->id }}, '{{ $student->name }}', {{ $score ?? 'null' }}, '{{ addslashes($feedback ?? '') }}')"
                                    style="font-size: 0.75rem;">
                                <i class="bi bi-pencil me-1"></i> {{ $score !== null ? 'Edit' : 'Nilai' }}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding: 2rem; text-align: center;">
                            <div class="text-center py-3">
                                <i class="bi bi-inbox text-secondary fs-4 d-block mb-2"></i>
                                <p class="text-secondary small mb-0">Tidak ada siswa dalam kelas ini</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Grade Modal --}}
<div class="modal fade" id="gradeModal" tabindex="-1" aria-labelledby="gradeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="gradeModalLabel" style="font-size: 1.15rem;">Berikan Nilai</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="gradeForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="studentName" class="form-label fw-semibold" style="font-size: 0.9rem;">Siswa</label>
                        <input type="text" class="form-control rounded-3" id="studentName" readonly 
                               style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                    </div>
                    <div class="mb-3">
                        <label for="scoreInput" class="form-label fw-semibold" style="font-size: 0.9rem;">
                            Nilai (0-100)
                        </label>
                        <div class="input-group">
                            <input type="number" class="form-control rounded-3" id="scoreInput" name="score" 
                                   min="0" max="100" step="1" placeholder="Masukkan nilai"
                                   style="border: 1px solid #e2e8f0; font-size: 0.9rem;">
                            <span class="input-group-text rounded-3" style="border: 1px solid #e2e8f0; background-color: transparent;">
                                <i class="bi bi-percent"></i>
                            </span>
                        </div>
                        <small class="d-block mt-2" style="color: #475569;">
                            <i class="bi bi-info-circle"></i>
                            Nilai A: 90-100 | B: 80-89 | C: 70-79 | D: 60-69 | E: <60
                        </small>
                    </div>
                    <div class="mb-3">
                        <label for="feedbackInput" class="form-label fw-semibold" style="font-size: 0.9rem;">
                            Catatan/Feedback (Opsional)
                        </label>
                        <textarea class="form-control rounded-3" id="feedbackInput" name="feedback" 
                                  rows="3" placeholder="Tulis catatan untuk siswa..."
                                  style="border: 1px solid #e2e8f0; font-size: 0.9rem; resize: none;"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary rounded-3" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn text-white rounded-3" style="background-color: {{ $course->color }}; border: none;">
                        <i class="bi bi-check me-1"></i> Simpan Nilai
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .transition-all {
        transition: all 0.2s ease-in-out;
    }
</style>

<script>
    let currentStudentId = null;

    function openGradeModal(studentId, studentName, score, feedback) {
        currentStudentId = studentId;
        document.getElementById('studentName').value = studentName;
        document.getElementById('scoreInput').value = score || '';
        document.getElementById('feedbackInput').value = feedback || '';
        
        // Update form action URL
        const form = document.getElementById('gradeForm');
        form.action = `/kelas/{{ $course->id }}/tasks/{{ $task->id }}/grade/${studentId}`;
    }

    document.getElementById('gradeForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const score = document.getElementById('scoreInput').value;
        
        if (!score || score < 0 || score > 100) {
            alert('Nilai harus antara 0-100');
            return;
        }

        this.submit();
    });
</script>
</div>

<style>
    .transition-all {
        transition: all 0.2s ease-in-out;
    }

    @media (prefers-color-scheme: dark) {
        /* Ensure the page changes when dark mode is active */
    }

    [data-theme="dark"] .task-submissions-page .card,
    [data-theme="dark"] .task-submissions-page .card.bg-white,
    [data-theme="dark"] .task-submissions-page .table,
    [data-theme="dark"] .task-submissions-page .modal-content,
    [data-theme="dark"] .task-submissions-page .modal-header,
    [data-theme="dark"] .task-submissions-page .modal-body,
    [data-theme="dark"] .task-submissions-page .modal-footer,
    [data-theme="dark"] .task-submissions-page .form-control,
    [data-theme="dark"] .task-submissions-page .input-group-text,
    [data-theme="dark"] .task-submissions-page .btn-outline-secondary,
    [data-theme="dark"] .task-submissions-page .badge {
        background-color: var(--dm-surface-2) !important;
        border-color: var(--dm-border) !important;
        color: var(--dm-text) !important;
    }

    [data-theme="dark"] .task-submissions-page thead,
    [data-theme="dark"] .task-submissions-page thead th {
        background-color: var(--dm-surface-2) !important;
        border-color: var(--dm-border) !important;
        color: var(--dm-text) !important;
    }

    [data-theme="dark"] .task-submissions-page th,
    [data-theme="dark"] .task-submissions-page td,
    [data-theme="dark"] .task-submissions-page .text-dark,
    [data-theme="dark"] .task-submissions-page .text-secondary,
    [data-theme="dark"] .task-submissions-page .fw-medium,
    [data-theme="dark"] .task-submissions-page .form-label,
    [data-theme="dark"] .task-submissions-page small,
    [data-theme="dark"] .task-submissions-page .badge {
        color: var(--dm-text) !important;
    }

    [data-theme="dark"] .task-submissions-page .table tbody tr {
        border-color: var(--dm-border) !important;
    }

    [data-theme="dark"] .task-submissions-page .btn-outline-secondary:hover {
        background-color: rgba(226, 232, 240, 0.08) !important;
    }

    [data-theme="dark"] .task-submissions-page .rounded-circle {
        border-color: var(--dm-border) !important;
    }
</style>
@endsection
