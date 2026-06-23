@extends('layouts.app')

@section('title', 'Dashboard')

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

{{-- Welcome Banner --}}
<div class="welcome-banner-card mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="rounded-3 overflow-hidden flex-shrink-0" style="width: 68px; height: 68px;">
            @if(auth()->user()->avatar)
                <img
                    src="{{ asset('storage/' . auth()->user()->avatar) }}"
                    alt="Foto Profil"
                    style="width:100%; height:100%; object-fit:cover;">
            @else
                <div class="d-flex align-items-center justify-content-center bg-primary text-white fw-bold w-100 h-100"
                    style="font-size: 1.5rem;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
            @endif
        </div>
        <h4 class="fw-bold mb-0" style="font-size: 1.35rem;">
            Selamat Datang, {{ auth()->user()->name }}
        </h4>
    </div>
</div>

{{-- Quick Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background-color: #ceeaf0;">
                    <i class="bi bi-journal-bookmark text-primary fs-5"></i>
                </div>
                <span class="badge rounded-pill" style="background-color: #dcfce7; color: #16a34a; font-size: 0.75rem; font-weight: 600;">Aktif</span>
            </div>
            <div class="fw-bold fs-4 text-dark">{{ $coursesCount }}</div>
            <div class="text-secondary small">Kelas Terdaftar</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background-color: #fff7ed;">
                    <i class="bi bi-file-earmark-check text-warning fs-5"></i>
                </div>
                @if($tasksCount > 0)
                    <span class="badge rounded-pill" style="background-color: #fee2e2; color: #dc2626; font-size: 0.75rem; font-weight: 600;">{{ $tasksCount }} Baru</span>
                @else
                    <span class="badge rounded-pill" style="background-color: #dcfce7; color: #16a34a; font-size: 0.75rem; font-weight: 600;">Bebas</span>
                @endif
            </div>
            <div class="fw-bold fs-4 text-dark">{{ $tasksCount }}</div>
            <div class="text-secondary small">Tugas Menunggu</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background-color: #f0fdf4;">
                    <i class="bi bi-trophy text-success fs-5"></i>
                </div>
                <span class="badge rounded-pill" style="background-color: #ceeaf0; color: #1F7A8C; font-size: 0.75rem; font-weight: 600;">Nilai</span>
            </div>
            <div class="fw-bold fs-4 text-dark">{{ $averageGrade ?? '-' }}</div>
            <div class="text-secondary small">Rata-rata Nilai</div>
        </div>
    </div>
</div>

{{-- Recent Activity --}}
<div class="card border-0 shadow-sm rounded-4 bg-white">
    <div class="card-body p-4">
        <h6 class="fw-bold text-dark mb-3">Aktivitas Terbaru</h6>
        <div class="d-flex flex-column gap-3">
            @foreach($recentActivities as $act)
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width: 40px; height: 40px; background-color: {{ $act['color_bg'] }};">
                    <i class="bi {{ $act['icon'] }} {{ $act['text_color'] ?? 'text-primary' }} fs-5"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-semibold small text-dark">{{ $act['title'] }}</div>
                    <div class="text-secondary" style="font-size: 0.78rem;">{{ $act['time'] }}</div>
                </div>
                <span class="badge rounded-pill" style="background-color: {{ $act['badge_bg'] }}; color: {{ $act['badge_color'] }}; font-size: 0.72rem;">{{ $act['badge'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
