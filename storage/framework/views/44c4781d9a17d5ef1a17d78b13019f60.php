<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <script>!function(){var t=localStorage.getItem('lms-theme')||'light';document.documentElement.setAttribute('data-theme',t);}();</script>
    <title><?php echo $__env->yieldContent('title', 'Admin Panel'); ?> - <?php echo e(config('app.name', 'Mini LMS')); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body>

<!-- ===== DESKTOP SIDEBAR (lg+) ===== -->
<div class="sidebar-container d-none d-lg-flex">
    <div>
        <div class="sidebar-logo-section">
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="d-flex align-items-center gap-2 text-decoration-none mb-1">
                <div class="d-flex align-items-center justify-content-center rounded-3 bg-danger" style="width: 38px; height: 38px; flex-shrink: 0;">
                    <i class="bi bi-shield-lock-fill text-white" style="font-size: 1.1rem;"></i>
                </div>
                <span class="fw-bold text-danger" style="font-size: 1.1rem; line-height: 1.2;">Admin LMS</span>
            </a>
            <span class="text-secondary" style="font-size: 0.75rem; padding-left: 46px;">Control Panel</span>
        </div>
        <ul class="sidebar-menu-list">
            <li><a href="<?php echo e(route('admin.dashboard')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a></li>
            <li><a href="<?php echo e(route('admin.users')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.users') ? 'active' : ''); ?>"><i class="bi bi-people-fill"></i> Kelola Pengguna</a></li>
            <li><a href="<?php echo e(route('admin.courses')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.courses') ? 'active' : ''); ?>"><i class="bi bi-journal-text"></i> Kelola Kelas</a></li>
            <li class="my-3 border-top border-secondary opacity-25"></li>
        </ul>
    </div>
    <div class="sidebar-footer">
        <form action="<?php echo e(route('logout')); ?>" method="POST">
            <?php echo csrf_field(); ?>
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
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="d-flex align-items-center gap-2 text-decoration-none mb-1">
                    <div class="d-flex align-items-center justify-content-center rounded-3 bg-danger" style="width: 38px; height: 38px; flex-shrink: 0;">
                        <i class="bi bi-shield-lock-fill text-white" style="font-size: 1.1rem;"></i>
                    </div>
                    <span class="fw-bold text-danger" style="font-size: 1.1rem; line-height: 1.2;">Admin LMS</span>
                </a>
                <span class="text-secondary" style="font-size: 0.75rem; padding-left: 46px;">Control Panel</span>
            </div>
            <!-- Nav Links -->
            <ul class="sidebar-menu-list mt-2">
                <li><a href="<?php echo e(route('admin.dashboard')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a></li>
                <li><a href="<?php echo e(route('admin.users')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.users') ? 'active' : ''); ?>"><i class="bi bi-people-fill"></i> Kelola Pengguna</a></li>
                <li><a href="<?php echo e(route('admin.courses')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.courses') ? 'active' : ''); ?>"><i class="bi bi-journal-text"></i> Kelola Kelas</a></li>
                <li class="my-3 border-top border-secondary opacity-25"></li>
            </ul>
        </div>
        <div style="padding: 1.25rem; border-top: 1px solid #e2e8f0;">
            <form action="<?php echo e(route('logout')); ?>" method="POST">
                <?php echo csrf_field(); ?>
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
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="header-brand-title text-danger">Admin Panel</a>
        </div>

        <!-- Right: action buttons + avatar -->
        <div class="header-right">
            <div class="d-flex align-items-center gap-2 d-none d-md-flex me-2">
                <button type="button" class="header-icon-btn" id="darkModeBtn" title="Toggle tema gelap">
                    <i class="bi bi-moon-stars-fill"></i>
                </button>
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" id="darkModeToggle"
                           role="switch" style="width: 2.5em; height: 1.35em; cursor: pointer;"
                           onchange="lmsToggleDark(this.checked)">
                </div>
            </div>
            <a href="<?php echo e(route('settings')); ?>">
                <?php if(Auth::user()->avatar): ?>
                    <img src="<?php echo e(asset('storage/' . Auth::user()->avatar)); ?>"
                        alt="Avatar"
                        style="width:36px;height:36px;border-radius:50%;object-fit:cover;">
                <?php else: ?>
                    <div class="d-flex align-items-center justify-content-center bg-primary text-white fw-bold"
                        style="border-radius: 50%; width: 36px; height: 36px; font-size: 0.82rem; flex-shrink: 0;">
                        <?php echo e(strtoupper(substr(Auth::user()->name, 0, 2))); ?>

                    </div>
                <?php endif; ?>
            </a>
        </div>
    </header>

    <!-- Page Content -->
    <main class="main-content">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

</div><!-- end main-wrapper -->

<script>
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
});
</script>

</body>
</html>
<?php /**PATH C:\laragon\www\mini-lms-classroom\resources\views/layouts/admin.blade.php ENDPATH**/ ?>