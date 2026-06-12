@extends('layouts.app')

@section('title', $material->title)

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

{{-- Material Detail Content --}}
<div class="row g-4">
    <div class="col-lg-8 col-12">
        {{-- Material Info Card --}}
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 p-md-5 mb-4">
            {{-- Material Header --}}
            <div class="d-flex align-items-center gap-3 border-bottom pb-4 mb-4">
                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width: 52px; height: 52px; background-color: {{ $course->color }}15;">
                    <i class="bi bi-file-earmark-text-fill" style="color: {{ $course->color }}; font-size: 1.6rem;"></i>
                </div>
                <div>
                    <span class="text-secondary small fw-medium" style="font-size: 0.8rem;">Materi Pelajaran</span>
                    <h4 class="fw-bold text-dark mb-1" style="font-size: 1.4rem; letter-spacing: -0.01em;">{{ $material->title }}</h4>
                    <div class="d-flex align-items-center gap-2 text-secondary" style="font-size: 0.78rem;">
                        <span>Oleh: <strong>{{ $course->teacher_name }}</strong></span>
                        <span>·</span>
                        <span>Diunggah {{ $material->created_at->isoFormat('D MMMM YYYY, HH:mm') }}</span>
                    </div>
                </div>
            </div>

            {{-- Description --}}
            <div class="mb-4">
                <h6 class="fw-bold text-dark mb-3" style="font-size: 0.95rem;">Deskripsi / Petunjuk:</h6>
                @if($material->description)
                    <div class="text-secondary" style="font-size: 0.92rem; line-height: 1.7; white-space: pre-line;">
                        {{ $material->description }}
                    </div>
                @else
                    <p class="text-secondary small italic mb-0">Tidak ada deskripsi tertulis untuk materi ini.</p>
                @endif
            </div>

            {{-- Attachments if any --}}
            @if($material->file_name || $material->link_url)
                <div class="border-top pt-4 mt-4">
                    <h6 class="fw-bold text-dark mb-3" style="font-size: 0.95rem;">Lampiran:</h6>
                    <div class="d-flex flex-column gap-2">
                        @if($material->file_name)
                            <div class="p-3 rounded-4 border d-flex align-items-center justify-content-between hover-shadow transition-all bg-light bg-opacity-30">
                                <div class="d-flex align-items-center gap-3 min-width-0">
                                    <div class="rounded-3 d-flex align-items-center justify-content-center bg-white border" style="width: 42px; height: 42px; flex-shrink: 0;">
                                        <i class="bi bi-file-earmark-pdf-fill text-danger fs-5"></i>
                                    </div>
                                    <div class="min-width-0">
                                        <div class="fw-semibold text-dark text-truncate small" style="max-width: 320px;">{{ $material->file_name }}</div>
                                        <div class="text-secondary" style="font-size: 0.72rem;">Dokumen Lampiran</div>
                                    </div>
                                </div>
                                @if($material->file_path)
                                    <a href="{{ asset('storage/' . $material->file_path) }}" download class="btn btn-sm btn-outline-secondary rounded-3 px-3 d-flex align-items-center gap-1" style="font-size: 0.78rem;">
                                        <i class="bi bi-download"></i> Unduh
                                    </a>
                                @endif
                            </div>
                        @endif

                        @if($material->link_url)
                            <div class="p-3 rounded-4 border d-flex align-items-center justify-content-between hover-shadow transition-all bg-light bg-opacity-30">
                                <div class="d-flex align-items-center gap-3 min-width-0">
                                    <div class="rounded-3 d-flex align-items-center justify-content-center bg-white border" style="width: 42px; height: 42px; flex-shrink: 0;">
                                        <i class="bi bi-link-45deg text-primary fs-5"></i>
                                    </div>
                                    <div class="min-width-0">
                                        <div class="fw-semibold text-dark text-truncate small" style="max-width: 320px;">Link Pembelajaran</div>
                                        <div class="text-secondary text-truncate" style="font-size: 0.72rem; max-width: 250px;">{{ $material->link_url }}</div>
                                    </div>
                                </div>
                                <a href="{{ $material->link_url }}" target="_blank" class="btn btn-sm text-white rounded-3 px-3 d-flex align-items-center gap-1" style="font-size: 0.78rem; background-color: {{ $course->color }}; border: none;">
                                    <i class="bi bi-box-arrow-up-right"></i> Buka
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- ======= COMMENT SECTION ======= --}}
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 p-md-5" id="comments">
            <h6 class="fw-bold text-dark mb-4 d-flex align-items-center gap-2" style="font-size: 1rem;">
                <i class="bi bi-chat-dots-fill" style="color: {{ $course->color }};"></i>
                Komentar &amp; Pertanyaan
                <span class="badge rounded-pill fw-semibold ms-1" style="background-color: {{ $course->color }}15; color: {{ $course->color }}; font-size: 0.72rem;">
                    {{ $material->comments->count() }}
                </span>
            </h6>

            {{-- Comment Form --}}
            <form action="{{ route('comments.store', [$course->id, 'materials', $material->id]) }}" method="POST" class="mb-4">
                @csrf
                <div class="d-flex gap-3 align-items-start">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
                         style="width: 38px; height: 38px; font-size: 0.8rem; background-color: {{ $course->color }};">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div class="flex-grow-1">
                        <div class="comment-input-wrapper position-relative">
                            <textarea class="form-control comment-textarea" name="body" rows="2"
                                      placeholder="Tulis pertanyaan atau komentar tentang materi ini..."
                                      style="border-radius: 12px; border: 1px solid #e2e8f0; font-size: 0.88rem; padding: 0.7rem 1rem; resize: none; font-family: var(--font-sans); transition: border-color 0.2s, box-shadow 0.2s;"
                                      onfocus="this.style.borderColor='{{ $course->color }}'; this.style.boxShadow='0 0 0 3px {{ $course->color }}20';"
                                      onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';" required></textarea>
                        </div>
                        <div class="d-flex justify-content-end mt-2">
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
                @forelse($material->comments as $comment)
                    <div class="d-flex gap-3 align-items-start comment-item">
                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                             style="width: 36px; height: 36px; font-size: 0.75rem;
                                    background-color: {{ $comment->user_id === $course->creator_id ? $course->color : '#f1f5f9' }};
                                    color: {{ $comment->user_id === $course->creator_id ? '#fff' : '#475569' }};">
                            {{ strtoupper(substr($comment->user->name, 0, 2)) }}
                        </div>
                        <div class="flex-grow-1">
                            <div class="p-3 rounded-4" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                                <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                    <span class="fw-bold text-dark" style="font-size: 0.85rem;">{{ $comment->user->name }}</span>
                                    @if($comment->user_id === $course->creator_id)
                                        <span class="badge rounded-pill" style="background-color: {{ $course->color }}15; color: {{ $course->color }}; font-size: 0.68rem; font-weight: 600;">Pengajar</span>
                                    @endif
                                    <span class="text-secondary" style="font-size: 0.73rem;">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-dark mb-0" style="font-size: 0.88rem; line-height: 1.6; white-space: pre-line;">{{ $comment->body }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 56px; height: 56px;">
                            <i class="bi bi-chat-square text-secondary fs-4"></i>
                        </div>
                        <p class="text-secondary small mb-0">Belum ada komentar. Jadilah yang pertama bertanya!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Side Class Info card (Desktop only) --}}
    <div class="col-lg-4 col-12">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
            <h6 class="fw-bold text-dark border-bottom pb-3 mb-3" style="font-size: 0.95rem; color: {{ $course->color }} !important;">
                Detail Kelas
            </h6>
            <div class="mb-4">
                <h6 class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">{{ $course->name }}</h6>
                <span class="text-secondary small">{{ $course->level }}</span>
            </div>
            <div class="d-flex flex-column gap-2 small text-secondary border-top pt-3">
                <div class="d-flex justify-content-between">
                    <span>Mata Pelajaran:</span>
                    <span class="text-dark fw-medium">{{ $course->subject ?: '-' }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Ruang Kelas:</span>
                    <span class="text-dark fw-medium">{{ $course->room ?: '-' }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Kode Kelas:</span>
                    <span class="text-dark font-monospace fw-bold text-uppercase">{{ $course->code }}</span>
                </div>
            </div>
        </div>


        @if($otherMaterials->count() > 0)
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
            <h6 class="fw-bold text-dark border-bottom pb-3 mb-3" style="font-size: 0.88rem;">
                Materi Lainnya
            </h6>
            <div class="d-flex flex-column gap-2">
                @foreach($otherMaterials as $otherMat)
                    <a href="{{ route('materials.show', [$course->id, $otherMat->id]) }}" 
                       class="d-flex align-items-center gap-2 text-decoration-none p-2 rounded-3 hover-bg-light transition-all">
                        <i class="bi bi-file-earmark-text" style="color: {{ $course->color }};"></i>
                        <span class="text-dark small fw-medium text-truncate" style="font-size: 0.82rem;">{{ $otherMat->title }}</span>
                    </a>
                @endforeach
            </div>
        </div>
        @endif
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
.comment-item {
    animation: fadeIn 0.3s ease-in-out;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(6px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

@endsection
