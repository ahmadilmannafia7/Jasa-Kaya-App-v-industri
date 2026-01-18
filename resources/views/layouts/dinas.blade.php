<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Dinas - JASA KAYA')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link href="{{ asset('css/dinas-dashboard.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <!-- Brand -->
    <div class="sidebar-brand">
        <div class="brand-icon">
            <i class="fas fa-tree"></i>
        </div>
        <div class="brand-text">
            <span class="fw-bold">Dinas Kehutanan</span>
            <small class="d-block">Dashboard Eksekutif</small>
        </div>
    </div>
    
    <!-- Navigation Menu -->
    <nav class="sidebar-nav">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dinas.dashboard') ? 'active' : '' }}" 
                   href="{{ route('dinas.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard Eksekutif</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dinas.approvals') ? 'active' : '' }}" 
                   href="{{ route('dinas.approvals') }}">
                    <i class="fas fa-user-check"></i>
                    <span>Approval PBPHH</span>
                    @if(isset($pendingApprovals) && $pendingApprovals > 0)
                        <span class="badge bg-warning ms-2">{{ $pendingApprovals }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dinas.user-management') ? 'active' : '' }}" 
                   href="{{ route('dinas.user-management') }}">
                    <i class="fas fa-users-cog"></i>
                    <span>Manajemen Pengguna</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dinas.region-management') ? 'active' : '' }}" 
                   href="{{ route('dinas.region-management') }}">
                    <i class="fas fa-map-marked-alt"></i>
                    <span>Manajemen Wilayah</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dinas.monitoring') ? 'active' : '' }}" 
                   href="{{ route('dinas.monitoring') }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Monitoring Provinsi</span>
                </a>
            </li>
        </ul>
    </nav>
    
    <!-- User Profile -->
    <div class="sidebar-user">
        <div class="dropdown">
            <a class="user-profile d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                <div class="user-avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="user-info">
                    <div class="user-name">{{ Str::limit(Auth::user()->email, 15) }}</div>
                    <small class="user-role">Dinas Provinsi</small>
                </div>
                <i class="fas fa-chevron-up ms-auto"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-up">
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="fas fa-sign-out-alt me-2"></i>Keluar
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- Mobile Toggle -->
<button class="mobile-toggle d-lg-none" type="button" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
</button>

<!-- Main Content -->
<div class="main-content">
    <div class="content-wrapper">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('dashboard-content')
    </div>
</div>

<style>
/* Alert Styling */
.alert {
    border: none;
    border-radius: 12px;
    backdrop-filter: blur(10px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.alert-success {
    background: linear-gradient(135deg, rgba(25, 134, 82, 0.1), rgba(34, 197, 94, 0.1));
    border-left: 4px solid #198652;
}

.alert-danger {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(220, 38, 38, 0.1));
    border-left: 4px solid #ef4444;
}

/* Ultra-Aggressive Modal Backdrop Fix */
.modal-backdrop,
.modal-backdrop.fade,
.modal-backdrop.show,
.modal-backdrop.fade.show,
div[class*="modal-backdrop"],
*[class*="modal-backdrop"] {
    display: none !important;
    opacity: 0 !important;
    visibility: hidden !important;
    pointer-events: none !important;
    z-index: -9999 !important;
    position: absolute !important;
    top: -9999px !important;
    left: -9999px !important;
    width: 0 !important;
    height: 0 !important;
}

/* Ensure modals are clickable */
.modal,
.modal-dialog,
.modal-content,
.modal-header,
.modal-body,
.modal-footer {
    pointer-events: auto !important;
    z-index: 9999 !important;
}

/* Force all form controls in modals to be interactive */
.modal input,
.modal select,
.modal textarea,
.modal button,
.modal .btn,
.modal .form-control,
.modal .form-select {
    pointer-events: auto !important;
    z-index: 10000 !important;
}
</style>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('show');
}

// Close sidebar when clicking outside on mobile
document.addEventListener('click', function(event) {
    const sidebar = document.getElementById('sidebar');
    const toggle = document.querySelector('.mobile-toggle');
    
    if (window.innerWidth <= 1024) {
        if (!sidebar.contains(event.target) && !toggle.contains(event.target)) {
            sidebar.classList.remove('show');
        }
    }
});

// Handle window resize
window.addEventListener('resize', function() {
    const sidebar = document.getElementById('sidebar');
    if (window.innerWidth > 1024) {
        sidebar.classList.remove('show');
    }
});
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
