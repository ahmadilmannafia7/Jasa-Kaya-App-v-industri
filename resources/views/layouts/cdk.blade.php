<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard CDK - JASA KAYA')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link href="{{ asset('css/cdk-dashboard.css') }}" rel="stylesheet">
    
    <style>
        /* CDK Navigation Styles */
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
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
        
        /* Ultra-aggressive modal backdrop fixes */
        .modal-backdrop,
        .modal-backdrop.fade,
        .modal-backdrop.show,
        .modal-backdrop.fade.show {
            display: none !important;
            opacity: 0 !important;
            pointer-events: none !important;
            visibility: hidden !important;
        }
        
        /* Ensure modal elements are clickable */
        .modal {
            z-index: 1055 !important;
            pointer-events: auto !important;
        }
        
        .modal.show {
            z-index: 1055 !important;
            display: block !important;
        }
        
        .modal-dialog {
            z-index: 1056 !important;
            pointer-events: auto !important;
        }
        
        .modal-content {
            z-index: 1057 !important;
            pointer-events: auto !important;
        }
        
        .modal-body,
        .modal-header,
        .modal-footer {
            pointer-events: auto !important;
        }
        
        /* Force all modal elements to be interactive */
        .modal * {
            pointer-events: auto !important;
        }
        
        /* Ensure form controls are clickable */
        .form-control,
        .form-select,
        .btn {
            pointer-events: auto !important;
        }
        
        .modal input,
        .modal select,
        .modal button,
        .modal textarea {
            pointer-events: auto !important;
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
            <i class="fas fa-seedling"></i>
        </div>
        <div class="brand-text">
            <span class="fw-bold">Dashboard CDK</span>
            <small class="d-block">{{ Auth::user()->region->name }}</small>
        </div>
    </div>
    
    <!-- Navigation Menu -->
    <nav class="sidebar-nav">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('cdk.dashboard') ? 'active' : '' }}" 
                   href="{{ route('cdk.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('cdk.approvals*') ? 'active' : '' }}" 
                   href="{{ route('cdk.approvals') }}">
                    <i class="fas fa-user-check"></i>
                    <span>Approval Akun</span>
                    @php
                        $pendingCount = \App\Models\User::where('region_id', Auth::user()->region_id)
                            ->where('role', 'KTHR_PENYULUH')
                            ->where('approval_status', 'Pending')
                            ->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="badge bg-warning ms-auto">{{ $pendingCount }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('cdk.meetings*') ? 'active' : '' }}" 
                   href="{{ route('cdk.meetings') }}">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Fasilitasi Pertemuan</span>
                    @php
                        $facilitationCount = \App\Models\PermintaanKerjasama::whereHas('kthr.user', function($query) {
                            $query->where('region_id', Auth::user()->region_id);
                        })->where('status', 'Disetujui')->count();
                    @endphp
                    @if($facilitationCount > 0)
                        <span class="badge bg-info ms-auto">{{ $facilitationCount }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('cdk.monitoring*') ? 'active' : '' }}" 
                   href="{{ route('cdk.monitoring') }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Monitoring Wilayah</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('cdk.reports*') ? 'active' : '' }}" 
                   href="{{ route('cdk.reports') }}">
                    <i class="fas fa-file-alt"></i>
                    <span>Laporan</span>
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
                    <small class="user-role">CDK</small>
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
