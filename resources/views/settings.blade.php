@extends('layouts.app')

@section('title', 'Pengaturan Akun')

@section('content')

<div class="mb-4">
    <h5 class="fw-bold mb-0 text-dark">Settings</h5>
    <p class="text-secondary mb-0 small">Kelola akun dan preferensi kamu</p>
</div>

<div class="row g-4">
    {{-- Profile Info --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold text-dark mb-4">Informasi Profil</h6>

                {{-- Avatar Display --}}
                <div class="d-flex align-items-center gap-4 mb-4">
                    <div class="rounded-3 d-flex align-items-center justify-content-center bg-primary text-white fw-bold"
                         style="width: 80px; height: 80px; font-size: 2rem; flex-shrink: 0;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div>
                        <div class="fw-semibold text-dark mb-1">Foto Profil</div>
                        <div class="text-secondary small mb-2">Unggah foto profil kamu (maks. 2MB)</div>
                        <button class="btn btn-sm btn-bikin-kelas">
                            <i class="bi bi-upload me-1"></i> Unggah Foto
                        </button>
                    </div>
                </div>

                <hr class="my-3" style="border-color: #f1f5f9;">

                {{-- Profile Form --}}
                <form>
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label-custom">Nama Lengkap</label>
                            <div class="input-group-custom">
                                <span class="input-group-icon"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control-custom" value="{{ Auth::user()->name }}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label-custom">Alamat Email</label>
                            <div class="input-group-custom">
                                <span class="input-group-icon"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control-custom" value="{{ Auth::user()->email }}">
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="button" class="btn btn-gabung-kelas mt-1">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Change Password --}}
        <div class="card border-0 shadow-sm rounded-4 bg-white">
            <div class="card-body p-4">
                <h6 class="fw-bold text-dark mb-4">Ubah Kata Sandi</h6>
                <form>
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label-custom">Kata Sandi Lama</label>
                            <div class="input-group-custom">
                                <span class="input-group-icon"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control-custom" placeholder="••••••••">
                                <button type="button" class="btn-toggle-password" tabindex="-1" style="background: none; border: none; padding: 0 1rem; color: #94a3b8; cursor: pointer; display: flex; align-items: center; outline: none; box-shadow: none;">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Kata Sandi Baru</label>
                            <div class="input-group-custom">
                                <span class="input-group-icon"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control-custom" placeholder="••••••••">
                                <button type="button" class="btn-toggle-password" tabindex="-1" style="background: none; border: none; padding: 0 1rem; color: #94a3b8; cursor: pointer; display: flex; align-items: center; outline: none; box-shadow: none;">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Konfirmasi Kata Sandi</label>
                            <div class="input-group-custom">
                                <span class="input-group-icon"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" class="form-control-custom" placeholder="••••••••">
                                <button type="button" class="btn-toggle-password" tabindex="-1" style="background: none; border: none; padding: 0 1rem; color: #94a3b8; cursor: pointer; display: flex; align-items: center; outline: none; box-shadow: none;">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="button" class="btn btn-gabung-kelas mt-1">
                                Ubah Kata Sandi
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- Right panel: Settings shortcuts --}}
    <div class="col-lg-4">
        {{-- Appearance Card --}}
        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold text-dark mb-1">Tampilan</h6>
                <p class="text-secondary mb-3" style="font-size: 0.8rem;">Sesuaikan tampilan aplikasi sesuai preferensi kamu</p>

                {{-- Dark Mode Toggle --}}
                <div class="d-flex align-items-center justify-content-between p-3 rounded-3" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center rounded-3" style="width: 40px; height: 40px; background-color: #1e293b;">
                            <i class="bi bi-moon-stars-fill" style="color: #94a3b8; font-size: 1rem;"></i>
                        </div>
                        <div>
                            <div class="fw-semibold text-dark" style="font-size: 0.88rem;">Mode Gelap</div>
                            <div class="text-secondary" style="font-size: 0.75rem;">Ubah tampilan ke tema gelap</div>
                        </div>
                    </div>
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" id="darkModeToggle"
                               role="switch" style="width: 2.5em; height: 1.35em; cursor: pointer;"
                               onchange="lmsToggleDark(this.checked)">
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 bg-white">
            <div class="card-body p-4">
                <h6 class="fw-bold text-dark mb-3">Akun & Privasi</h6>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-bell text-secondary"></i>
                            <span class="small">Notifikasi Email</span>
                        </div>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" id="notifToggle" checked>
                        </div>
                    </li>
                    <li class="list-group-item px-0 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-phone text-secondary"></i>
                            <span class="small">Notifikasi Push</span>
                        </div>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" id="pushToggle">
                        </div>
                    </li>
                    <li class="list-group-item px-0 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-eye text-secondary"></i>
                            <span class="small">Profil Publik</span>
                        </div>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" id="profileToggle" checked>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Danger Zone --}}
        <div class="card border-0 shadow-sm rounded-4 mt-4" style="border: 1px solid #fee2e2 !important; background: #fff;">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-1" style="color: #dc2626;">Bahaya</h6>
                <p class="text-secondary mb-3" style="font-size: 0.8rem;">Tindakan ini tidak dapat dibatalkan.</p>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn w-100"
                            style="background-color: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; font-weight: 600; border-radius: 8px; font-size: 0.875rem;">
                        <i class="bi bi-box-arrow-right me-1"></i> Keluar dari Akun
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
