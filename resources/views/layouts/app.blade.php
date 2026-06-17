<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Apply theme immediately to avoid flash --}}
    <script>!function(){var t=localStorage.getItem('lms-theme')||'light';document.documentElement.setAttribute('data-theme',t);}();</script>
    <title>@yield('title', 'Beranda') - {{ config('app.name', 'Mini LMS') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

<!-- ===== DESKTOP SIDEBAR (lg+) ===== -->
<div class="sidebar-container d-none d-lg-flex">
    <div>
        <div class="sidebar-logo-section">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none mb-1">
                <div class="d-flex align-items-center justify-content-center rounded-3" style="width: 38px; height: 38px; background-color: #1F7A8C; flex-shrink: 0;">
                    <i class="bi bi-mortarboard-fill text-white" style="font-size: 1.1rem;"></i>
                </div>
                <span class="fw-bold" style="color: #1F7A8C; font-size: 1.1rem; line-height: 1.2;">Mini LMS</span>
            </a>
            <span class="text-secondary" style="font-size: 0.75rem; padding-left: 46px;">Tiga Serangkai University</span>
        </div>
        <ul class="sidebar-menu-list">
            <li><a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="bi bi-grid-1x2"></i> Dashboard</a></li>
            <li><a href="{{ route('courses') }}" class="sidebar-link {{ request()->routeIs('courses') ? 'active' : '' }}"><i class="bi bi-journal-bookmark"></i> Kelas Saya</a></li>
            <li><a href="{{ route('tasks') }}" class="sidebar-link {{ request()->routeIs('tasks') ? 'active' : '' }}"><i class="bi bi-calendar-event"></i> Tugas</a></li>
            <li><a href="{{ route('report') }}" class="sidebar-link {{ request()->routeIs('report') ? 'active' : '' }}"><i class="bi bi-file-earmark-bar-graph"></i> Nilai</a></li>
            <li><a href="{{ route('settings') }}" class="sidebar-link {{ request()->routeIs('settings') ? 'active' : '' }}"><i class="bi bi-gear"></i> Pengaturan</a></li>
            @if(Auth::check() && Auth::user()->isAdmin())
            <li style="margin-top: 0.5rem; padding-top: 0.5rem; border-top: 1px solid #e2e8f0;">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.*') ? 'active' : '' }}" style="color: #7c3aed;">
                    <i class="bi bi-shield-lock"></i> Admin Panel
                </a>
            </li>
            @endif
        </ul>
    </div>
    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout-sidebar">
                <i class="bi bi-box-arrow-right"></i> Keluar
            </button>
        </form>
    </div>
</div>

<!-- ===== MOBILE OFFCANVAS SIDEBAR ===== -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel" style="width: 260px; border-right: 1px solid #e2e8f0;">
    <div class="offcanvas-body d-flex flex-column justify-content-between p-0" style="background: #fff;">
        <div style="padding: 1.75rem 1.25rem;">
            <!-- Logo -->
            <div class="sidebar-logo-section">
                <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none mb-1">
                    <div class="d-flex align-items-center justify-content-center rounded-3" style="width: 38px; height: 38px; background-color: #1F7A8C; flex-shrink: 0;">
                        <i class="bi bi-mortarboard-fill text-white" style="font-size: 1.1rem;"></i>
                    </div>
                    <span class="fw-bold" style="color: #1F7A8C; font-size: 1.1rem; line-height: 1.2;">Mini LMS</span>
                </a>
                <span class="text-secondary" style="font-size: 0.75rem; padding-left: 46px;">Academic Portal</span>
            </div>
            <!-- Nav Links -->
            <ul class="sidebar-menu-list mt-2">
                <li><a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="bi bi-grid-1x2"></i> Dashboard</a></li>
                <li><a href="{{ route('courses') }}" class="sidebar-link {{ request()->routeIs('courses') ? 'active' : '' }}"><i class="bi bi-journal-bookmark"></i> Kelas Saya</a></li>
                <li><a href="{{ route('tasks') }}" class="sidebar-link {{ request()->routeIs('tasks') ? 'active' : '' }}"><i class="bi bi-calendar-event"></i> Tugas</a></li>
                <li><a href="{{ route('report') }}" class="sidebar-link {{ request()->routeIs('report') ? 'active' : '' }}"><i class="bi bi-file-earmark-bar-graph"></i> Nilai</a></li>
                <li><a href="{{ route('settings') }}" class="sidebar-link {{ request()->routeIs('settings') ? 'active' : '' }}"><i class="bi bi-gear"></i> Pengaturan</a></li>
                @if(Auth::check() && Auth::user()->isAdmin())
                <li style="margin-top: 0.5rem; padding-top: 0.5rem; border-top: 1px solid #e2e8f0;">
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.*') ? 'active' : '' }}" style="color: #7c3aed;">
                        <i class="bi bi-shield-lock"></i> Admin Panel
                    </a>
                </li>
                @endif
            </ul>
        </div>
        <div style="padding: 1.25rem; border-top: 1px solid #e2e8f0;">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout-sidebar">
                    <i class="bi bi-box-arrow-right"></i> Keluar
                </button>
            </form>
        </div>
    </div>
</div>

<!-- ===== MAIN WRAPPER ===== -->
<div class="main-wrapper">

    <!-- Top Header -->
    <header class="main-header">
        <!-- Left: hamburger (mobile) + brand + nav links (desktop) -->
        <div class="header-left">
            <!-- Hamburger (mobile only) -->
            <button class="header-icon-btn d-lg-none me-1" type="button"
                    data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar"
                    aria-controls="mobileSidebar" aria-label="Buka menu">
                <i class="bi bi-list fs-4"></i>
            </button>
            <a href="{{ route('dashboard') }}" class="header-brand-title">Mini LMS</a>
            <!-- Nav links hidden on mobile -->
            <a href="{{ route('tasks') }}" class="header-nav-link d-none d-md-inline {{ request()->routeIs('tasks') ? 'fw-semibold text-dark' : '' }}">Tugas</a>
            <a href="{{ route('report') }}" class="header-nav-link d-none d-md-inline {{ request()->routeIs('report') ? 'fw-semibold text-dark' : '' }}">Nilai</a>
        </div>

        <!-- Right: action buttons + avatar -->
        <div class="header-right">
            <!-- Dark Mode Toggle -->
            <div class="d-flex align-items-center gap-2 d-none d-md-flex">
                <button type="button" class="header-icon-btn" id="darkModeBtn" title="Toggle tema gelap">
                    <i class="bi bi-moon-stars-fill"></i>
                </button>
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" id="darkModeToggle"
                           role="switch" style="width: 2.5em; height: 1.35em; cursor: pointer;"
                           onchange="lmsToggleDark(this.checked)">
                </div>
            </div>
            <!-- Buttons hidden on small mobile to save space -->
            <button type="button" class="btn btn-gabung-kelas d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modalGabungKelas">Gabung Kelas</button>
            <button type="button" class="btn btn-bikin-kelas d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modalBikinKelas">Buat Kelas</button>
            <!-- On mobile: icon buttons for the actions -->
            <button type="button" class="header-icon-btn d-sm-none" title="Gabung Kelas" data-bs-toggle="modal" data-bs-target="#modalGabungKelas">
                <i class="bi bi-door-open"></i>
            </button>
            <button type="button" class="header-icon-btn d-sm-none" title="Buat Kelas" data-bs-toggle="modal" data-bs-target="#modalBikinKelas">
                <i class="bi bi-plus-circle"></i>
            </button>
            <a href="{{ route('settings') }}">
                <div class="d-flex align-items-center justify-content-center bg-primary text-white fw-bold"
                     style="border-radius: 50%; width: 36px; height: 36px; font-size: 0.82rem; flex-shrink: 0;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
            </a>
        </div>
    </header>

    <!-- Page Content -->
    <main class="main-content">
        @yield('content')
    </main>

</div><!-- end main-wrapper -->


<!-- ===== MODAL: GABUNG KELAS ===== -->
<div class="modal fade" id="modalGabungKelas" tabindex="-1" aria-labelledby="modalGabungKelasLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center rounded-3"
                         style="width: 44px; height: 44px; background-color: #ceeaf0; flex-shrink: 0;">
                        <i class="bi bi-door-open-fill" style="color: #1F7A8C; font-size: 1.2rem;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0 text-dark" id="modalGabungKelasLabel">Gabung Kelas</h5>
                        <p class="text-secondary mb-0" style="font-size: 0.82rem;">Masukkan kode kelas yang diberikan guru</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('courses.join') }}" method="POST">
                @csrf
                <div class="modal-body px-4 pt-3 pb-2">
                    <div class="mb-3">
                        <label class="form-label-custom">Kode Kelas</label>
                        <div class="input-group-custom">
                            <span class="input-group-icon"><i class="bi bi-key"></i></span>
                            <input type="text" class="form-control-custom" id="kodeKelas" name="kode_kelas"
                                   placeholder="Contoh: abc123"
                                   style="letter-spacing: 0.08em; text-transform: uppercase;"
                                   maxlength="10" autocomplete="off" required>
                        </div>
                        <div class="mt-2 text-secondary" style="font-size: 0.78rem;">
                            <i class="bi bi-info-circle me-1"></i>
                            Kode kelas terdiri dari 5–10 karakter huruf dan angka.
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-2 gap-2">
                    <button type="button" class="btn btn-bikin-kelas" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-gabung-kelas flex-grow-1">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Gabung
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalBikinKelas" tabindex="-1" aria-labelledby="modalBikinKelasLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center rounded-3"
                         style="width: 44px; height: 44px; background-color: #ceeaf0; flex-shrink: 0;">
                        <i class="bi bi-plus-square-fill" style="color: #1F7A8C; font-size: 1.2rem;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0 text-dark" id="modalBikinKelasLabel">Buat Kelas Baru</h5>
                        <p class="text-secondary mb-0" style="font-size: 0.82rem;">Isi detail kelas yang akan kamu buat</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('courses.store') }}" method="POST">
                @csrf
                <div class="modal-body px-4 pt-3 pb-2">
                    <div class="mb-3">
                        <label class="form-label-custom">Nama Kelas <span class="text-danger">*</span></label>
                        <div class="input-group-custom">
                            <span class="input-group-icon"><i class="bi bi-journal-bookmark"></i></span>
                            <input type="text" class="form-control-custom" id="namaKelas" name="name" placeholder="Contoh: Matematika Kelas 10A" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Mata Pelajaran</label>
                        <div class="input-group-custom">
                            <span class="input-group-icon"><i class="bi bi-book"></i></span>
                            <input type="text" class="form-control-custom" id="mataPelajaran" name="subject" placeholder="Contoh: Matematika">
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label-custom">Ruang / Kelas</label>
                            <div class="input-group-custom">
                                <span class="input-group-icon"><i class="bi bi-building"></i></span>
                                <input type="text" class="form-control-custom" name="room" placeholder="Contoh: 10A">
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="form-label-custom">Tahun Ajaran</label>
                            <div class="input-group-custom">
                                <span class="input-group-icon"><i class="bi bi-calendar3"></i></span>
                                <input type="text" class="form-control-custom" name="tahun_ajaran" placeholder="Contoh: 2025/2026">
                            </div>
                        </div>
                    </div>
                    <div class="mb-1">
                        <label class="form-label-custom d-block mb-2">Warna Tema Kelas</label>
                        <div class="d-flex gap-2 flex-wrap">
                            <label class="color-swatch-label" title="#1F7A8C">
                                <input type="radio" name="warna_tema" value="#1F7A8C" style="display:none;" checked>
                                <span class="color-swatch selected"
                                      style="background: #1F7A8C; display:inline-block; width: 28px; height: 28px; border-radius: 50%; cursor: pointer; border: 3px solid #fff; box-shadow: 0 0 0 2px #1F7A8C;"
                                      onclick="selectColor(this, '#1F7A8C')">
                                </span>
                            </label>
                            <label class="color-swatch-label" title="#16a34a">
                                <input type="radio" name="warna_tema" value="#16a34a" style="display:none;">
                                <span class="color-swatch"
                                      style="background: #16a34a; display:inline-block; width: 28px; height: 28px; border-radius: 50%; cursor: pointer; border: 3px solid #16a34a;"
                                      onclick="selectColor(this, '#16a34a')">
                                </span>
                            </label>
                            <label class="color-swatch-label" title="#7c3aed">
                                <input type="radio" name="warna_tema" value="#7c3aed" style="display:none;">
                                <span class="color-swatch"
                                      style="background: #7c3aed; display:inline-block; width: 28px; height: 28px; border-radius: 50%; cursor: pointer; border: 3px solid #7c3aed;"
                                      onclick="selectColor(this, '#7c3aed')">
                                </span>
                            </label>
                            <label class="color-swatch-label" title="#b45309">
                                <input type="radio" name="warna_tema" value="#b45309" style="display:none;">
                                <span class="color-swatch"
                                      style="background: #b45309; display:inline-block; width: 28px; height: 28px; border-radius: 50%; cursor: pointer; border: 3px solid #b45309;"
                                      onclick="selectColor(this, '#b45309')">
                                </span>
                            </label>
                            <label class="color-swatch-label" title="#dc2626">
                                <input type="radio" name="warna_tema" value="#dc2626" style="display:none;">
                                <span class="color-swatch"
                                      style="background: #dc2626; display:inline-block; width: 28px; height: 28px; border-radius: 50%; cursor: pointer; border: 3px solid #dc2626;"
                                      onclick="selectColor(this, '#dc2626')">
                                </span>
                            </label>
                            <label class="color-swatch-label" title="#0891b2">
                                <input type="radio" name="warna_tema" value="#0891b2" style="display:none;">
                                <span class="color-swatch"
                                      style="background: #0891b2; display:inline-block; width: 28px; height: 28px; border-radius: 50%; cursor: pointer; border: 3px solid #0891b2;"
                                      onclick="selectColor(this, '#0891b2')">
                                </span>
                            </label>
                            <label class="color-swatch-label" title="#be185d">
                                <input type="radio" name="warna_tema" value="#be185d" style="display:none;">
                                <span class="color-swatch"
                                      style="background: #be185d; display:inline-block; width: 28px; height: 28px; border-radius: 50%; cursor: pointer; border: 3px solid #be185d;"
                                      onclick="selectColor(this, '#be185d')">
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-3 gap-2">
                    <button type="button" class="btn btn-bikin-kelas" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-gabung-kelas flex-grow-1">
                        <i class="bi bi-plus-lg me-1"></i> Buat Kelas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.color-swatch-label input[type="radio"]:checked + .color-swatch {
    border: 3px solid #fff !important;
}
.modal-content { font-family: 'Plus Jakarta Sans', sans-serif; }
</style>

<script>
function selectColor(el, color) {
    document.querySelectorAll('.color-swatch').forEach(s => {
        s.style.border = '3px solid ' + s.style.backgroundColor;
        s.style.boxShadow = 'none';
    });
    el.style.border = '3px solid #fff';
    el.style.boxShadow = '0 0 0 2px ' + color;
}
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.form-control-custom').forEach(el => {
        el.addEventListener('input', function() { this.style.outline = ''; });
    });
});
</script>

<script>
// ===== Dark Mode Toggle =====
function lmsSetTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('lms-theme', theme);
    const toggle = document.getElementById('darkModeToggle');
    if (toggle) toggle.checked = (theme === 'dark');
}
function lmsToggleDark(checked) {
    lmsSetTheme(checked ? 'dark' : 'light');
}
document.addEventListener('DOMContentLoaded', function() {
    const saved = localStorage.getItem('lms-theme') || 'light';
    document.documentElement.setAttribute('data-theme', saved);
    const toggle = document.getElementById('darkModeToggle');
    if (toggle) toggle.checked = (saved === 'dark');
    
    // Update dark mode button icon
    const darkModeBtn = document.getElementById('darkModeBtn');
    if (darkModeBtn) {
        const updateIcon = () => {
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            const icon = darkModeBtn.querySelector('i');
            if (isDark) {
                icon.classList.remove('bi-moon-stars-fill');
                icon.classList.add('bi-sun-fill');
            } else {
                icon.classList.remove('bi-sun-fill');
                icon.classList.add('bi-moon-stars-fill');
            }
        };
        updateIcon();
        const observer = new MutationObserver(updateIcon);
        observer.observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });
    }
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
