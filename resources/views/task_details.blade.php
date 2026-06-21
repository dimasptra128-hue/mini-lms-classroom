@extends('layouts.app')

@section('title', $task->title)

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

{{-- Back to Class Link --}}
<div class="mb-4">
    <a href="{{ route('courses.show', $course->id) }}" class="d-inline-flex align-items-center gap-1 text-decoration-none fw-semibold text-secondary transition-all" style="font-size: 0.88rem;">
        <i class="bi bi-arrow-left"></i> Kembali ke Kelas {{ $course->name }}
    </a>
</div>

{{-- Task Detail Content --}}
<div class="row g-4 task-details-page">
@php
    // Normalize comments to a Collection of objects. Support DB relation, JSON attribute, or null.
    $commentsRaw = null;
    if (isset($task) && (isset($task->comments))) {
        $commentsRaw = $task->comments;
    }
    if ($commentsRaw instanceof \Illuminate\Support\Collection) {
        $comments = $commentsRaw;
    } else {
        if (is_string($commentsRaw)) {
            $commentsRaw = json_decode($commentsRaw, true) ?: [];
        }
        $comments = collect($commentsRaw ?: [])->map(function($c) {
            $obj = is_object($c) ? $c : (object) $c;
            if (!isset($obj->user) && isset($obj->user_id)) {
                $obj->user = \App\Models\User::find($obj->user_id);
            }
            return $obj;
        });
    }
@endphp
    {{-- Left Column: Task Instructions --}}
    <div class="col-lg-8 col-12">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 p-md-5 mb-4">
            {{-- Task Header --}}
            <div class="d-flex align-items-start gap-3 border-bottom pb-4 mb-4 justify-content-between flex-wrap flex-md-nowrap">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width: 52px; height: 52px; background-color: {{ $course->color }}15;">
                        <i class="bi bi-clipboard-check-fill" style="color: {{ $course->color }}; font-size: 1.6rem;"></i>
                    </div>
                    <div>
                        <span class="text-secondary small fw-medium" style="font-size: 0.8rem;">Tugas Kelas</span>
                        <h4 class="fw-bold text-dark mb-1" style="font-size: 1.4rem; letter-spacing: -0.01em;">{{ $task->title }}</h4>
                        <div class="d-flex align-items-center gap-2 text-secondary" style="font-size: 0.78rem;">
                            <span>Oleh: <strong>{{ $course->teacher_name }}</strong></span>
                            <span>·</span>
                            <span>Diposting {{ $task->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                <div class="text-md-end text-start mt-2 mt-md-0 flex-shrink-0">
                    <div class="fw-semibold text-danger" style="font-size: 0.88rem;">Tenggat: {{ $task->due_date }}</div>
                    <span class="text-secondary small">100 Poin</span>
                    @if($userRole === 'teacher')
                        <div class="d-flex gap-2 mt-3">
                            <a href="{{ route('tasks.showSubmissions', [$course->id, $task->id]) }}" class="btn btn-sm btn-outline-primary rounded-3 px-3 fw-semibold" style="font-size: 0.78rem;">
                                <i class="bi bi-file-earmark-check me-1"></i> Kelola Penilaian
                            </a>
                            <form action="{{ route('tasks.delete', [$course->id, $task->id]) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-3 px-3 fw-semibold" style="font-size: 0.78rem;">
                                    <i class="bi bi-trash me-1"></i> Hapus Tugas
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Instructions --}}
            <div class="mb-4">
                <h6 class="fw-bold text-dark mb-3" style="font-size: 0.95rem;">Petunjuk Pengerjaan:</h6>
                @if($task->description)
                    <div class="text-secondary" style="font-size: 0.92rem; line-height: 1.7; white-space: pre-line;">
                        {{ $task->description }}
                    </div>
                @else
                    <p class="text-secondary small italic mb-0">Tidak ada instruksi khusus untuk tugas ini.</p>
                @endif
            </div>

            {{-- Task File Attachment --}}
            @if($task->file_name)
                <div class="border-top pt-4 mt-4">
                    <h6 class="fw-bold text-dark mb-3" style="font-size: 0.95rem;">File Lampiran Soal:</h6>
                    <div class="p-3 rounded-4 border d-flex align-items-center justify-content-between hover-shadow transition-all bg-light bg-opacity-30">
                        <div class="d-flex align-items-center gap-3 min-width-0">
                            <div class="rounded-3 d-flex align-items-center justify-content-center bg-white border" style="width: 42px; height: 42px; flex-shrink: 0;">
                                <i class="bi bi-file-earmark-arrow-down-fill text-danger fs-5"></i>
                            </div>
                            <div class="min-width-0">
                                <div class="fw-semibold text-dark text-truncate small" style="max-width: 320px;">{{ $task->file_name }}</div>
                                <div class="text-secondary" style="font-size: 0.72rem;">Lampiran Tugas</div>
                            </div>
                        </div>
                        @if($task->file_path)
                            {{-- Modifikasi di bagian atribut download --}}
                            <a href="{{ asset('storage/' . $task->file_path) }}" 
                            download="{{ $task->file_name }}" 
                            class="btn btn-sm btn-outline-secondary rounded-3 px-3 d-flex align-items-center gap-1 download-btn" 
                            style="font-size: 0.78rem;">
                                <i class="bi bi-download download-icon"></i> <span class="download-text">Unduh</span>
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- ======= COMMENT SECTION ======= --}}
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 p-md-5" id="comments">
            <h6 class="fw-bold text-dark mb-4 d-flex align-items-center gap-2" style="font-size: 1rem;">
                <i class="bi bi-chat-dots-fill" style="color: {{ $course->color }};"></i>
                Komentar &amp; Jawaban
                <span class="badge rounded-pill fw-semibold ms-1" style="background-color: {{ $course->color }}15; color: {{ $course->color }}; font-size: 0.72rem;">
                    {{ $comments->count() }}
                </span>
            </h6>

            {{-- Comment / Answer Form --}}
            <form action="{{ route('comments.store', [$course->id, 'tasks', $task->id]) }}" method="POST" class="mb-4">
                @csrf
                <input type="hidden" name="reply_to" id="replyToInput" value="">
                <div id="replyContext" class="d-none mb-3 rounded-3 p-3 reply-context-box">
                    <div class="d-flex align-items-center justify-content-between gap-3">
                        <div class="text-secondary small">Membalas komentar <span id="replyTargetName" class="fw-semibold text-dark"></span></div>
                        <button type="button" class="btn btn-sm btn-light btn-outline-secondary rounded-3" onclick="cancelReply()">Batal</button>
                    </div>
                </div>
                <div class="d-flex gap-3 align-items-start">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
                         style="width: 38px; height: 38px; font-size: 0.8rem; background-color: {{ $course->color }};">
                        {{ strtoupper(substr(optional(auth()->user())->name ?? 'U', 0, 2)) }}
                    </div>
                    <div class="flex-grow-1">
                        <textarea class="form-control" name="body" rows="2"
                                  placeholder="{{ $userRole === 'teacher' ? 'Tulis balasan atau klarifikasi untuk tugas ini...' : 'Tulis jawaban singkat atau pertanyaan terkait tugas ini...' }}"
                                  style="border-radius: 12px; border: 1px solid #e2e8f0; font-size: 0.88rem; padding: 0.7rem 1rem; resize: none; font-family: var(--font-sans); transition: border-color 0.2s, box-shadow 0.2s; outline: none;"
                                  onfocus="this.style.borderColor='{{ $course->color }}'; this.style.boxShadow='0 0 0 3px {{ $course->color }}20';"
                                  onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';" required></textarea>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <span class="text-secondary" style="font-size: 0.72rem;">
                                @if($userRole !== 'teacher')
                                    <i class="bi bi-info-circle me-1"></i> Jawaban lengkap dapat diunggah di kotak "Tugas Anda"
                                @endif
                            </span>
                            <button type="submit" class="btn btn-sm text-white px-4 py-2 rounded-3 fw-semibold"
                                    style="background-color: {{ $course->color }}; border: none; font-size: 0.82rem;">
                                <i class="bi bi-send me-1"></i> Kirim
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            {{-- Comments List --}}
            <div class="d-flex flex-column gap-3">
                @php
                    $commentsById = $comments->keyBy('id');
                @endphp
                @forelse($comments as $comment)
                    <div class="d-flex gap-3 align-items-start comment-item">
                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0 {{ $comment->user_id === $course->creator_id ? 'creator-avatar' : 'comment-avatar-alt' }}"
                             style="width: 36px; height: 36px; font-size: 0.75rem; @if($comment->user_id === $course->creator_id) background-color: {{ $course->color }}; color: #fff; @endif">
                                    {{ strtoupper(substr(optional($comment->user)->name ?? ($comment->name ?? 'U'), 0, 2)) }}
                        </div>
                        <div class="flex-grow-1">
                            <div class="p-3 rounded-4 comment-bubble">
                                <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                    <span class="fw-bold text-dark" style="font-size: 0.85rem;">{{ optional($comment->user)->name ?? ($comment->name ?? 'Pengguna') }}</span>
                                    @if($comment->user_id === $course->creator_id)
                                        <span class="badge rounded-pill" style="background-color: {{ $course->color }}15; color: {{ $course->color }}; font-size: 0.68rem; font-weight: 600;">Pengajar</span>
                                    @endif
                                    <span class="text-secondary" style="font-size: 0.73rem;">{{ isset($comment->created_at) ? \Carbon\Carbon::parse($comment->created_at)->diffForHumans() : '' }}</span>
                                </div>
                                @if(!empty($comment->reply_to) && isset($commentsById[$comment->reply_to]))
                                    <div class="mb-2 rounded-3 py-2 px-3 comment-reply-indicator">
                                        <span class="text-secondary small">Balasan untuk <strong>{{ optional($commentsById[$comment->reply_to]->user)->name ?? ($commentsById[$comment->reply_to]->name ?? 'Komentar') }}</strong></span>
                                    </div>
                                @endif
                                <p class="text-dark mb-0 comment-body" style="font-size: 0.88rem; line-height: 1.6; white-space: pre-line;">{{ $comment->body ?? '' }}</p>
                                <div class="mt-3 d-flex justify-content-end">
                                    <button type="button" class="btn btn-sm btn-link text-secondary" onclick="setReply({{ $comment->id }}, '{{ addslashes(optional($comment->user)->name ?? ($comment->name ?? 'Pengguna')) }}')">
                                        <i class="bi bi-reply"></i> Balas
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 56px; height: 56px;">
                            <i class="bi bi-chat-square text-secondary fs-4"></i>
                        </div>
                        <p class="text-secondary small mb-0">Belum ada komentar. Silakan ajukan pertanyaan jika ada yang kurang jelas.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Right Column: Your Work Box --}}
    <div class="col-lg-4 col-12">
        @if($userRole !== 'teacher')
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                <h6 class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">Tugas Anda</h6>
                @php
                    $submissionsRaw = $task->submissions ?? [];
                    if (is_string($submissionsRaw)) {
                        $submissionsRaw = json_decode($submissionsRaw, true) ?: [];
                    }
                    $userSubmission = $submissionsRaw[auth()->id()] ?? null;
                @endphp
                @if($userSubmission && data_get($userSubmission, 'status') === 'Selesai')
                    <span class="badge rounded-pill text-success" style="background-color: #dcfce7; font-size: 0.75rem; font-weight: 600;">Diserahkan</span>
                @else
                    <span class="badge rounded-pill text-warning" style="background-color: #fff7ed; font-size: 0.75rem; font-weight: 600;">Ditugaskan</span>
                @endif
            </div>

            @if($userSubmission && data_get($userSubmission, 'status') === 'Selesai')
                <div class="py-3 text-center">
                    <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 54px; height: 54px;">
                        <i class="bi bi-check-circle-fill text-success fs-3"></i>
                    </div>
                    <p class="text-dark fw-semibold small mb-1">Pekerjaan Anda telah selesai</p>
                    <p class="text-secondary mb-3" style="font-size: 0.75rem;">Diserahkan pada {{ \Carbon\Carbon::parse(data_get($userSubmission, 'submitted_at', now()))->isoFormat('D MMM YYYY') }}</p>
                    @if(data_get($userSubmission,'file_path'))
                        <div class="mb-2">
                            <a href="{{ asset('storage/' . data_get($userSubmission,'file_path')) }}" download class="btn btn-sm btn-outline-secondary w-100 rounded-3 small mb-2">Unduh Kiriman</a>
                        </div>
                    @endif

                    {{-- Score Display --}}
                    @php
                        $score = data_get($userSubmission, 'score');
                        $feedback = data_get($userSubmission, 'feedback');
                    @endphp
                    @if($score !== null)
                        <div class="border-top pt-3 mt-3">
                            <div class="mb-3">
                                <span class="text-secondary" style="font-size: 0.75rem;">Nilai dari Guru</span>
                                <div class="d-flex align-items-center justify-content-center gap-2 mt-2">
                                    <span class="fw-bold" style="font-size: 1.8rem; color: {{ $score >= 80 ? '#16a34a' : ($score >= 70 ? '#b45309' : '#dc2626') }};">
                                        {{ $score }}
                                    </span>
                                    <span class="badge rounded-pill py-2" 
                                          style="background-color: {{ $score >= 90 ? '#dcfce7; color: #16a34a' : ($score >= 80 ? '#fef3c7; color: #b45309' : '#fee2e2; color: #dc2626') }}; font-size: 0.8rem; font-weight: 600;">
                                        Grade {{ $score >= 90 ? 'A' : ($score >= 80 ? 'B' : ($score >= 70 ? 'C' : ($score >= 60 ? 'D' : 'E'))) }}
                                    </span>
                                </div>
                            </div>
                            @if($feedback)
                                <div class="rounded-3 p-3 mb-0 feedback-box">
                                    <div class="text-secondary" style="font-size: 0.75rem; font-weight: 600; margin-bottom: 0.5rem;">
                                        <i class="bi bi-chat-dots me-1"></i> Catatan dari Guru
                                    </div>
                                    <p class="text-dark small mb-0" style="font-size: 0.85rem; white-space: pre-wrap;">{{ $feedback }}</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="border-top pt-3 mt-3">
                            <p class="text-secondary small mb-0">
                                <i class="bi bi-hourglass-split me-1"></i> Menunggu penilaian dari guru
                            </p>
                        </div>
                    @endif

                    <form action="{{ route('tasks.cancelSubmission', [$course->id, $task->id]) }}" method="POST" class="mt-3">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-secondary w-100 rounded-3 small" style="font-size: 0.85rem; font-weight: 600;">Batalkan Pengiriman</button>
                    </form>
                </div>
            @else
                <div class="py-2">
                    <form id="submitForm" action="{{ route('tasks.submit', [$course->id, $task->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <div class="p-3 border rounded-3 text-center bg-light bg-opacity-30 cursor-pointer file-input-box" 
                                 onclick="document.getElementById('taskSubmitFile').click()"
                                 style="border-style: dashed !important;">
                                <i class="bi bi-plus-lg fs-5 text-secondary d-block mb-1"></i>
                                <span class="small fw-semibold text-secondary">Tambah atau buat file jawaban</span>
                                <input type="file" id="taskSubmitFile" name="submission_file" style="display:none;" onchange="fileSelected(this)">
                            </div>
                            <div id="fileSelectedDisplay" class="d-none p-2 border rounded-3 align-items-center justify-content-between mt-2 bg-light">
                                <span class="small text-truncate text-dark fw-medium" style="max-width: 200px;" id="selectedFileName">file.pdf</span>
                                <button type="button" class="btn-close" style="font-size:0.75rem;" onclick="removeSelectedFile()"></button>
                            </div>
                        </div>
                        <button id="submitBtn" type="submit" class="btn text-white w-100 rounded-3" style="background-color: {{ $course->color }}; font-weight: 600; font-size: 0.85rem; border: none; padding: 0.6rem;">
                            Tandai sebagai Selesai
                        </button>
                    </form>
                </div>
            @endif
        </div>
        @endif

        {{-- Class Info Sidebar Card --}}
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
            <h6 class="fw-bold text-dark border-bottom pb-3 mb-3" style="font-size: 0.95rem; color: {{ $course->color }} !important;">
                Info Kelas
            </h6>
            <div class="mb-3">
                <h6 class="fw-bold text-dark mb-0" style="font-size: 0.9rem;">{{ $course->name }}</h6>
                <span class="text-secondary" style="font-size: 0.72rem;">{{ $course->level }}</span>
            </div>
            <div class="d-flex flex-column gap-2 small text-secondary border-top pt-3">
                <div class="d-flex justify-content-between">
                    <span>Pengajar:</span>
                    <span class="text-dark fw-medium">{{ $course->teacher_name }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Kode Kelas:</span>
                    <span class="text-dark font-monospace fw-bold text-uppercase">{{ $course->code }}</span>
                </div>
            </div>


            @if($otherTasks->count() > 0)
            <div class="border-top mt-3 pt-3">
                <div class="fw-bold text-dark mb-2" style="font-size: 0.82rem;">Tugas Lainnya</div>
                <div class="d-flex flex-column gap-1">
                    @foreach($otherTasks as $otherTask)
                        <a href="{{ route('tasks.show', [$course->id, $otherTask->id]) }}"
                           class="d-flex align-items-center gap-2 text-decoration-none p-1 rounded-3 hover-bg-light transition-all">
                            <i class="bi bi-clipboard-check" style="color: {{ $course->color }}; font-size: 0.85rem;"></i>
                            <span class="text-dark small text-truncate" style="font-size: 0.8rem;">{{ $otherTask->title }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.hover-shadow:hover {
    box-shadow: 0 .4rem .8rem rgba(0,0,0,0.05)!important;
}
.transition-all {
    transition: all 0.2s ease-in-out;
}
.hover-text-dark:hover {
    color: #0f172a !important;
}
.hover-bg-light:hover {
    background-color: #f8fafc !important;
}
.cursor-pointer {
    cursor: pointer;
}
.comment-item {
    animation: fadeIn 0.3s ease-in-out;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(6px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<script>
function fileSelected(el) {
    if (el.files && el.files[0]) {
        document.getElementById('selectedFileName').innerText = el.files[0].name;
        document.getElementById('fileSelectedDisplay').classList.remove('d-none');
        document.getElementById('fileSelectedDisplay').classList.add('d-flex');
        var btn = document.getElementById('submitBtn');
        if (btn) btn.innerText = 'Serahkan Tugas';
    }
}
function removeSelectedFile() {
    document.getElementById('taskSubmitFile').value = '';
    document.getElementById('fileSelectedDisplay').classList.remove('d-flex');
    document.getElementById('fileSelectedDisplay').classList.add('d-none');
    var btn = document.getElementById('submitBtn');
    if (btn) btn.innerText = 'Tandai sebagai Selesai';
}
// The actual submission is handled via the submit form (`#submitForm`).
function setReply(commentId, commenterName) {
    document.getElementById('replyToInput').value = commentId;
    document.getElementById('replyTargetName').innerText = commenterName;
    document.getElementById('replyContext').classList.remove('d-none');
    document.getElementById('replyContext').classList.add('d-flex');
    document.querySelector('textarea[name="body"]').focus();
}
function cancelReply() {
    document.getElementById('replyToInput').value = '';
    document.getElementById('replyContext').classList.add('d-none');
    document.getElementById('replyContext').classList.remove('d-flex');
}
</script>

<style>
.reply-context-box {
    background-color: #f8fafc;
    border: 1px solid #cbd5e1;
}

.comment-bubble {
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
}

.comment-reply-indicator {
    background-color: rgba(15, 23, 42, 0.04);
    border: 1px solid #e2e8f0;
}

.file-input-box {
    border-color: #cbd5e1 !important;
    background-color: #f8fafc;
}

.comment-avatar-alt {
    color: #475569;
}

[data-theme="dark"] .task-details-page .reply-context-box,
[data-theme="dark"] .task-details-page .comment-bubble,
[data-theme="dark"] .task-details-page .comment-reply-indicator,
[data-theme="dark"] .task-details-page .file-input-box,
[data-theme="dark"] .task-details-page .feedback-box,
[data-theme="dark"] .task-details-page .bg-light,
[data-theme="dark"] .task-details-page .bg-light.bg-opacity-30,
[data-theme="dark"] .task-details-page .bg-white,
[data-theme="dark"] .task-details-page .card.bg-white,
[data-theme="dark"] .task-details-page .rounded-circle.bg-success.bg-opacity-10,
[data-theme="dark"] .task-details-page .rounded-circle.bg-light {
    background-color: var(--dm-surface-2) !important;
    border-color: var(--dm-border) !important;
}

[data-theme="dark"] .task-details-page .reply-context-box .text-secondary,
[data-theme="dark"] .task-details-page .comment-bubble .text-secondary,
[data-theme="dark"] .task-details-page .comment-reply-indicator .text-secondary,
[data-theme="dark"] .task-details-page .file-input-box .text-secondary,
[data-theme="dark"] .task-details-page .feedback-box .text-secondary,
[data-theme="dark"] .task-details-page .comment-bubble .text-dark,
[data-theme="dark"] .task-details-page .reply-context-box .text-dark,
[data-theme="dark"] .task-details-page .comment-reply-indicator .text-dark,
[data-theme="dark"] .task-details-page .file-input-box .text-dark,
[data-theme="dark"] .task-details-page .feedback-box .text-dark,
[data-theme="dark"] .task-details-page .text-dark,
[data-theme="dark"] .task-details-page .text-secondary {
    color: var(--dm-text) !important;
}

[data-theme="dark"] .comment-avatar-alt,
[data-theme="dark"] .creator-avatar {
    color: #94a3b8 !important;
}

[data-theme="dark"] .task-details-page .creator-avatar {
    color: #ffffff !important;
}

[data-theme="dark"] .task-details-page .comment-avatar-alt {
    background-color: #334155 !important;
    color: #cbd5e1 !important;
    border-color: var(--dm-border) !important;
}

[data-theme="dark"] .task-details-page .comment-body,
[data-theme="dark"] .task-details-page .feedback-box p,
[data-theme="dark"] .task-details-page .comment-reply-indicator,
[data-theme="dark"] .task-details-page .file-input-box,
[data-theme="dark"] .task-details-page .file-input-box:hover,
[data-theme="dark"] .task-details-page .file-input-box .text-secondary,
[data-theme="dark"] .task-details-page .d-flex.bg-light,
[data-theme="dark"] .task-details-page .d-flex.bg-light .text-secondary,
[data-theme="dark"] .task-details-page .card.border-0.shadow-sm.rounded-4 {
    background-color: var(--dm-surface-2) !important;
    border-color: var(--dm-border) !important;
}

[data-theme="dark"] .task-details-page .reply-context-box,
[data-theme="dark"] .task-details-page .comment-bubble,
[data-theme="dark"] .task-details-page .comment-reply-indicator,
[data-theme="dark"] .task-details-page .file-input-box,
[data-theme="dark"] .task-details-page .feedback-box,
[data-theme="dark"] .task-details-page .bg-light,
[data-theme="dark"] .task-details-page .bg-light.bg-opacity-30,
[data-theme="dark"] .task-details-page .bg-white,
[data-theme="dark"] .task-details-page .card.bg-white,
[data-theme="dark"] .task-details-page .rounded-circle.bg-success.bg-opacity-10,
[data-theme="dark"] .task-details-page .rounded-circle.bg-light {
    background-color: var(--dm-surface-2) !important;
    border-color: var(--dm-border) !important;
}

[data-theme="dark"] .task-details-page .reply-context-box .text-secondary,
[data-theme="dark"] .task-details-page .comment-bubble .text-secondary,
[data-theme="dark"] .task-details-page .comment-reply-indicator .text-secondary,
[data-theme="dark"] .task-details-page .file-input-box .text-secondary,
[data-theme="dark"] .task-details-page .feedback-box .text-secondary,
[data-theme="dark"] .task-details-page .comment-bubble .text-dark,
[data-theme="dark"] .task-details-page .reply-context-box .text-dark,
[data-theme="dark"] .task-details-page .comment-reply-indicator .text-dark,
[data-theme="dark"] .task-details-page .file-input-box .text-dark,
[data-theme="dark"] .task-details-page .feedback-box .text-dark,
[data-theme="dark"] .task-details-page .text-dark,
[data-theme="dark"] .task-details-page .text-secondary,
[data-theme="dark"] .task-details-page .comment-body {
    color: var(--dm-text) !important;
}
</style>

@endsection
