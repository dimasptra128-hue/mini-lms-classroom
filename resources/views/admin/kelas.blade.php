@extends('layouts.admin')

@section('title', 'Kelola Kelas')

@section('content')

<div class="mb-4">
    <h5 class="fw-bold mb-0 text-dark">Kelola Kelas</h5>
    <p class="text-secondary mb-0 small">Pantau dan kelola seluruh kelas akademik yang dibuat oleh guru</p>
</div>

{{-- Notifications / Alerts --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 rounded-4 shadow-sm mb-4" role="alert" style="background-color: #dcfce7; color: #16a34a; font-family: var(--font-sans);">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div>{!! session('success') !!}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 rounded-4 shadow-sm mb-4" role="alert" style="background-color: #fee2e2; color: #dc2626; font-family: var(--font-sans);">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <div>{!! session('error') !!}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card border-0 shadow-sm rounded-4 bg-white">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr class="text-secondary" style="font-size: 0.78rem;">
                        <th>KELAS & MATA PELAJARAN</th>
                        <th>KODE KELAS</th>
                        <th>NAMA GURU</th>
                        <th>ANGGOTA</th>
                        <th>TANGGAL DIBUAT</th>
                        <th class="text-center" style="width: 20%;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($courses as $item)
                    <tr style="font-size: 0.88rem;">
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center text-white fw-bold" style="width: 40px; height: 40px; background-color: {{ $item->color }};">
                                    <i class="bi {{ $item->icon ?: 'bi-journal' }} fs-5"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $item->name }}</div>
                                    <div class="text-secondary small">{{ $item->subject }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <code class="bg-light px-2 py-1 rounded-3 text-dark border font-monospace fw-bold" style="font-size: 0.85rem;">{{ $item->code }}</code>
                        </td>
                        <td class="text-dark fw-semibold">{{ $item->teacher_name }}</td>
                        <td>
                            <span class="badge bg-light text-dark border px-3 py-2 rounded-3 fw-bold">
                                <i class="bi bi-people-fill me-1 text-danger"></i> {{ $item->users_count }} Anggota
                            </span>
                        </td>
                        <td class="text-secondary">{{ $item->created_at->format('d M Y, H:i') }}</td>
                        <td class="text-center">
                            <div class="d-inline-flex gap-1.5">
                                {{-- Button Anggota --}}
                                <button type="button" class="btn btn-sm btn-outline-primary px-2.5 py-1 rounded-3 fw-semibold d-inline-flex align-items-center gap-1" style="font-size: 0.8rem;" data-bs-toggle="modal" data-bs-target="#membersModal{{ $item->id }}">
                                    <i class="bi bi-people"></i> Anggota
                                </button>

                                {{-- Button Hapus --}}
                                <form action="{{ route('admin.courses.delete', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas {{ $item->name }}? Semua materi, tugas, komentar, dan progres siswa di kelas ini akan dihapus secara permanen.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger px-2.5 py-1 rounded-3 fw-semibold d-inline-flex align-items-center gap-1" style="font-size: 0.8rem;">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>

                            {{-- Modal Anggota Kelas --}}
                            <div class="modal fade text-start" id="membersModal{{ $item->id }}" tabindex="-1" aria-labelledby="membersModalLabel{{ $item->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                                        <div class="modal-header border-0 pb-0 px-4 pt-4">
                                            <h5 class="modal-title fw-bold text-dark" id="membersModalLabel{{ $item->id }}">Anggota Kelas: {{ $item->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body px-4 py-3" style="max-height: 400px; overflow-y: auto;">
                                            {{-- List Guru --}}
                                            @php
                                                $usersCollection = collect($item->getRelationValue('users') ?? $item->getAttribute('users') ?? []);
                                                $teachers = $usersCollection->filter(function ($user) {
                                                    return data_get($user, 'pivot.role') === 'teacher';
                                                });
                                                $students = $usersCollection->filter(function ($user) {
                                                    return data_get($user, 'pivot.role') === 'student';
                                                });
                                            @endphp
                                            <h6 class="fw-bold text-secondary mb-3 small" style="letter-spacing: 0.05em; text-transform: uppercase;">Pengajar</h6>
                                            <div class="d-flex flex-column gap-3 mb-4">
                                                @forelse($teachers as $teacher)
                                                    <div class="d-flex align-items-center justify-content-between gap-3">
                                                        <div class="d-flex align-items-center gap-2.5">
                                                            <div class="rounded-circle overflow-hidden" style="width: 34px; height: 34px; flex-shrink:0;">
                                                                @if($teacher->avatar)
                                                                    <img
                                                                        src="{{ asset('storage/' . $teacher->avatar) }}"
                                                                        alt="{{ $teacher->name }}"
                                                                        style="width:100%; height:100%; object-fit:cover;">
                                                                @else
                                                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold bg-primary"
                                                                        style="width:100%; height:100%; font-size:0.75rem;">
                                                                        {{ strtoupper(substr($teacher->name, 0, 2)) }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div>
                                                                <div class="fw-semibold text-dark small" style="font-size: 0.85rem;">{{ $teacher->name }}</div>
                                                                <div class="text-secondary" style="font-size: 0.7rem;">{{ $teacher->email }}</div>
                                                            </div>
                                                        </div>
                                                        <span class="badge bg-light text-secondary border rounded-pill px-2.5 py-1" style="font-size: 0.68rem;">Guru</span>
                                                    </div>
                                                @empty
                                                    <div class="text-secondary small italic text-center py-2">Tidak ada pengajar di kelas ini.</div>
                                                @endforelse
                                            </div>

                                            {{-- List Siswa --}}
                                            <h6 class="fw-bold text-secondary mb-3 small" style="letter-spacing: 0.05em; text-transform: uppercase;">Siswa / Anggota</h6>
                                            <div class="d-flex flex-column gap-3">
                                                @forelse($students as $student)
                                                    <div class="d-flex align-items-center justify-content-between gap-3">
                                                        <div class="d-flex align-items-center gap-2.5">
                                                            <div class="rounded-circle overflow-hidden" style="width: 34px; height: 34px; flex-shrink:0;">
                                                                @if($student->avatar)
                                                                    <img
                                                                        src="{{ asset('storage/' . $student->avatar) }}"
                                                                        alt="{{ $student->name }}"
                                                                        style="width:100%; height:100%; object-fit:cover;">
                                                                @else
                                                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-dark fw-bold bg-light"
                                                                        style="width:100%; height:100%; font-size:0.75rem;">
                                                                        {{ strtoupper(substr($student->name, 0, 2)) }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div>
                                                                <div class="fw-semibold text-dark small" style="font-size: 0.85rem;">{{ $student->name }}</div>
                                                                <div class="text-secondary" style="font-size: 0.7rem;">{{ $student->email }}</div>
                                                            </div>
                                                        </div>
                                                        
                                                        <form action="{{ route('admin.courses.kick', [$item->id, $student->id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengeluarkan siswa {{ $student->name }} dari kelas ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger px-2.5 py-1 rounded-3" style="font-size: 0.72rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.25rem;">
                                                                <i class="bi bi-person-x"></i> Keluarkan
                                                            </button>
                                                        </form>
                                                    </div>
                                                @empty
                                                    <div class="text-secondary small italic text-center py-2">Belum ada siswa di kelas ini.</div>
                                                @endforelse
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 px-4 pb-4">
                                            <button type="button" class="btn btn-light btn-sm rounded-3 px-3 fw-bold" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @if($courses->isEmpty())
                    <tr>
                        <td colspan="6" class="text-center text-secondary py-4">Tidak ada data kelas.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
