@extends('layouts.app')

@section('title', 'My Courses')

@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 rounded-4 shadow-sm mb-4" role="alert" style="background-color: #dcfce7; color: #16a34a; font-family: var(--font-sans);">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div>{!! session('success') !!}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('info'))
    <div class="alert alert-info alert-dismissible fade show border-0 rounded-4 shadow-sm mb-4" role="alert" style="background-color: #ceeaf0; color: #1F7A8C; font-family: var(--font-sans);">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-info-circle-fill fs-5"></i>
            <div>{{ session('info') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="fw-bold mb-0 text-dark">My Courses</h5>
        <p class="text-secondary mb-0 small">Semua kelas yang sedang kamu ikuti atau ajar</p>
    </div>
    <a href="#" class="btn btn-gabung-kelas" data-bs-toggle="modal" data-bs-target="#modalGabungKelas">
        <i class="bi bi-plus-lg me-1"></i> Gabung Kelas
    </a>
</div>

@if($courses->count() > 0)
<div class="row g-3">
    @foreach ($courses as $course)
    <div class="col-md-6 col-lg-6">
        <a href="{{ route('courses.show', $course->id) }}" class="card-course-link text-decoration-none" style="display: block; color: inherit; height: 100%;">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 hover-shadow transition-all" style="background: #fff;">
                {{-- Course Header Banner --}}
                <div class="px-4 py-4 text-white"
                     style="background: {{ $course->color }};">
                    <div class="fw-bold fs-5 mb-1 text-truncate">{{ $course->name }}</div>
                    <div style="font-size: 0.8rem; opacity: 0.85;">{{ $course->level }}</div>
                </div>
                
                {{-- Course Body --}}
                <div class="card-body px-4 py-3">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                             style="width: 28px; height: 28px; font-size: 0.7rem; background: {{ $course->color }};">
                            {{ strtoupper(substr($course->teacher_name, 0, 2)) }}
                        </div>
                        <span class="text-secondary small">{{ $course->teacher_name }}</span>
                    </div>

                    
                    <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top">
                        @if ($course->tasks_count > 0)
                            <span class="badge rounded-pill"
                                  style="background-color: #fee2e2; color: #dc2626; font-size: 0.75rem;">
                                {{ $course->tasks_count }} Tugas Baru
                            </span>
                        @else
                            <span class="badge rounded-pill"
                                  style="background-color: #dcfce7; color: #16a34a; font-size: 0.75rem;">
                                Tidak ada tugas
                            </span>
                        @endif
                        <span class="small fw-bold" style="color: {{ $course->color }};">
                            Masuk Kelas <i class="bi bi-arrow-right ms-1"></i>
                        </span>
                    </div>
                </div>
            </div>
        </a>
    </div>
    @endforeach
</div>
@else
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
@endif

<style>
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

@endsection
