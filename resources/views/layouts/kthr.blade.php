<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard KTHR - JASA KAYA')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link href="{{ asset('css/kthr-dashboard.css') }}" rel="stylesheet">
    
    <style>
        /* KTHR Navigation Styles */
        .navbar-dark .navbar-nav .nav-link {
            color: rgba(255,255,255,0.9);
            transition: all 0.3s ease;
        }
        
        .navbar-dark .navbar-nav .nav-link:hover,
        .navbar-dark .navbar-nav .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
            border-radius: 5px;
        }
        
        .brand-icon {
            width: 50px;
            height: 50px;
            background: rgba(255,255,255,0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            position: relative;
            overflow: hidden;
        }
        
        .brand-icon::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transform: rotate(45deg);
            animation: shimmer 3s infinite;
        }
        
        @keyframes shimmer {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
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
    @stack('styles')
</head>
<body>
<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <!-- Brand -->
    <div class="sidebar-brand">
        <div class="brand-icon">
            <i class="fas fa-users"></i>
        </div>
        <div class="brand-text">
            <span class="fw-bold">Dashboard KTHR</span>
            <small class="d-block">{{ Str::limit(Auth::user()->kthr->kthr_name, 20) }}</small>
        </div>
    </div>
    
    <!-- Navigation Menu -->
    <nav class="sidebar-nav">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('kthr.dashboard') ? 'active' : '' }}" 
                   href="{{ route('kthr.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('kthr.profile*') ? 'active' : '' }}" 
                   href="{{ route('kthr.profile') }}">
                    <i class="fas fa-user-edit"></i>
                    <span>Profil KTHR</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('kthr.requests') ? 'active' : '' }}" 
                   href="{{ route('kthr.requests') }}">
                    <i class="fas fa-inbox"></i>
                    <span>Permintaan Masuk</span>
                    @php
                        $pendingCount = \App\Models\PermintaanKerjasama::where('kthr_id', Auth::user()->kthr->kthr_id)
                            ->where('status', 'Terkirim')->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="badge bg-warning text-dark ms-auto">{{ $pendingCount }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('kthr.partnerships') ? 'active' : '' }}" 
                   href="{{ route('kthr.partnerships') }}">
                    <i class="fas fa-handshake"></i>
                    <span>Kemitraan</span>
                    @php
                        $activeCount = \App\Models\PermintaanKerjasama::where('kthr_id', Auth::user()->kthr->kthr_id)
                            ->whereIn('status', ['Disetujui', 'Menunggu Jadwal', 'Dijadwalkan', 'Menunggu Tanda Tangan', 'Selesai'])->count();
                    @endphp
                    @if($activeCount > 0)
                        <span class="badge bg-success ms-auto">{{ $activeCount }}</span>
                    @endif
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
                    <small class="user-role">KTHR/Penyuluh</small>
                </div>
                <i class="fas fa-chevron-up ms-auto"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-up">
                <li><a class="dropdown-item" href="{{ route('kthr.profile') }}"><i class="fas fa-user me-2"></i>Profil</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
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

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('dashboard-content')
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Sidebar Toggle Function
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('show');
    }
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        const sidebar = document.getElementById('sidebar');
        const toggle = document.querySelector('.mobile-toggle');
        
        if (window.innerWidth <= 991.98 && 
            !sidebar.contains(event.target) && 
            !toggle.contains(event.target) && 
            sidebar.classList.contains('show')) {
            sidebar.classList.remove('show');
        }
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
        const sidebar = document.getElementById('sidebar');
        if (window.innerWidth > 991.98) {
            sidebar.classList.remove('show');
        }
    });
</script>

@stack('scripts')
</body>
</html>
