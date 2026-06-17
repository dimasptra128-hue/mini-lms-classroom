@extends('layouts.app')

@section('title', 'Tugas')

@section('content')

<div class="mb-4">
    <h5 class="fw-bold mb-0 text-dark">Tugas</h5>
    <p class="text-secondary mb-0 small">Semua tugas dari kelas yang kamu ikuti</p>
</div>

{{-- Tab Navigation --}}
<ul class="nav mb-4" style="border-bottom: 2px solid #e2e8f0; gap: 0.25rem;">
    <li class="nav-item">
        <a class="nav-link active px-4 py-2 fw-semibold"
           style="color: #1F7A8C; border-bottom: 2px solid #1F7A8C; margin-bottom: -2px; background: none; font-size: 0.9rem;"
           href="#">Semua</a>
    </li>
</ul>

{{-- Task Groups --}}
@foreach ($taskGroups as $group)
<div class="mb-4">
    <div class="d-flex align-items-center gap-2 mb-3">
        <span class="fw-semibold small text-dark">{{ $group['label'] }}</span>
        <span class="badge rounded-pill" style="background-color: {{ $group['badge_bg'] }}; color: {{ $group['badge_color'] }}; font-size: 0.72rem;">
            {{ count($group['tasks']) }}
        </span>
    </div>
    
    @if(count($group['tasks']) > 0)
        <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
            @foreach ($group['tasks'] as $task)
            <div class="px-4 py-3 d-flex align-items-center gap-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                <div class="flex-grow-1">
                    <div class="fw-semibold small text-dark">{{ $task->title }}</div>
                    <div class="text-secondary" style="font-size: 0.78rem;">{{ $task->course->name }} · Batas: {{ $task->due_date }}</div>
                    @if($task->file_name)
                        <div class="mt-1">
                            <a href="{{ asset('storage/' . $task->file_path) }}" download class="btn btn-light btn-sm d-inline-flex align-items-center gap-1.5 text-secondary border px-2 py-0.5 rounded-2" style="font-size: 0.72rem; text-decoration: none;">
                                <i class="bi bi-file-earmark-arrow-down-fill text-danger"></i>
                                <span class="text-dark fw-medium text-truncate" style="max-width: 180px;">{{ $task->file_name }}</span>
                            </a>
                        </div>
                    @endif
                </div>
                @if ($task->status === 'Selesai')
                    <span class="badge rounded-pill" style="background-color: #dcfce7; color: #16a34a; font-size: 0.72rem;">✓ Selesai</span>
                @elseif ($task->status === 'Draft')
                    <span class="badge rounded-pill" style="background-color: #fff7ed; color: #b45309; font-size: 0.72rem;">Draft</span>
                @else
                    <a href="{{ route('courses.show', $task->course_id) }}" class="btn btn-sm text-white" style="font-size: 0.8rem; background-color: {{ $task->course->color }}; border-radius: 6px; padding: 0.3rem 0.75rem; font-weight: 600; border: none;">
                        Kerjakan
                    </a>
                @endif
            </div>
            @endforeach
        </div>
    @else
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 text-center">
            <span class="text-secondary small">Tidak ada tugas di kategori ini</span>
        </div>
    @endif
</div>
@endforeach

@endsection
