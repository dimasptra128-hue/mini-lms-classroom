@extends('layouts.admin')

@section('title', 'Kelola Pengguna')

@section('content')

<div class="mb-4">
    <h5 class="fw-bold mb-0 text-dark">Kelola Pengguna</h5>
    <p class="text-secondary mb-0 small">Lihat dan kelola seluruh pengguna terdaftar di sistem</p>
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
                        <th>NAMA LENGKAP</th>
                        <th>EMAIL</th>
                        <th>KELAS YANG DIIKUTI</th>
                        <th>TANGGAL BERGABUNG</th>
                        <th class="text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                    <tr style="font-size: 0.88rem;">
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle overflow-hidden"
                                    style="width: 32px; height: 32px; flex-shrink:0;">

                                    @if($u->avatar)
                                        <img
                                            src="{{ asset('storage/' . $u->avatar) }}"
                                            alt="{{ $u->name }}"
                                            style="width:100%; height:100%; object-fit:cover;">
                                    @else
                                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-danger text-white fw-bold"
                                            style="width:100%; height:100%; font-size:0.75rem;">
                                            {{ strtoupper(substr($u->name, 0, 2)) }}
                                        </div>
                                    @endif

                                </div>
                                <div>
                                    <div class="fw-bold text-dark d-flex align-items-center gap-1.5 flex-wrap">
                                        {{ $u->name }}
                                        {{-- Cek status admin lewat kolom role atau is_admin --}}
                                        @if($u->role === 'admin')
                                            <span class="badge rounded-pill px-2 py-0.5"
                                                style="font-size: 0.65rem; background-color: #f3e8ff; color: #7c3aed; border: 1px solid #d8b4fe;">
                                                Admin
                                            </span>
                                        @else
                                            <span class="badge rounded-pill px-2 py-0.5"
                                                style="font-size: 0.65rem; background-color: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe;">
                                                Student
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-secondary small">ID: #{{ $u->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-dark">{{ $u->email }}</td>
                        <td>
                            <span class="badge bg-light text-dark border px-3 py-2 rounded-3 fw-bold">
                                <i class="bi bi-journal-bookmark me-1 text-danger"></i> {{ $u->courses_count ?? 0 }} Kelas
                            </span>
                        </td>
                        <td class="text-secondary">{{ $u->created_at->format('d M Y, H:i') }}</td>
                        <td class="text-center">
                            @if($u->id !== auth()->id())
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <form action="{{ route('admin.users.toggle-role', $u->id) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Ubah role user {{ $u->name }}?')">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-sm btn-outline-primary px-2.5 py-1 rounded-3 fw-semibold d-inline-flex align-items-center gap-1 text-nowrap"
                                            style="font-size: 0.8rem;">
                                            <i class="bi bi-person-gear"></i>
                                            {{ $u->role === 'admin' ? 'Jadikan Student' : 'Jadikan Admin' }}
                                        </button>
                                    </form>
                                    {{-- Button Hapus --}}
                                    <form action="{{ route('admin.users.delete', $u->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun user {{ $u->name }}? Semua kelas yang dia ajar atau ikuti akan terpengaruh.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger px-2.5 py-1 rounded-3 fw-semibold d-inline-flex align-items-center gap-1 text-nowrap" style="font-size: 0.8rem;">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            @else
                                <span class="badge rounded-pill px-3 py-2" style="background-color: #f1f5f9; color: #64748b; font-size: 0.75rem; border: 1px solid #cbd5e1;">Akun Anda</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-secondary py-4">Tidak ada data pengguna.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection