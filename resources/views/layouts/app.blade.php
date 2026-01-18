<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'JASA KAYA - Sistem Kemitraan Kehutanan')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #198652;
            --primary-color-rgb: 25, 134, 82;
            --secondary-color: #198652;
            --accent-color: #198652;
            --light-green: #e8f5ee;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }
        
        .navbar-brand {
            font-weight: bold;
            color: var(--primary-color) !important;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .card-header {
            background-color: var(--light-green);
            border-bottom: 2px solid var(--accent-color);
        }
        
        .sidebar {
            background-color: var(--primary-color);
            min-height: 100vh;
        }
        
        .sidebar .nav-link {
            color: white;
            padding: 12px 20px;
            border-radius: 5px;
            margin: 2px 10px;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .stats-card {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 10px;
        }
        
        /* Footer Styles */
        footer {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%) !important;
        }
        
        footer a:hover {
            color: var(--accent-color) !important;
            transition: color 0.3s ease;
        }
        
        footer .social-links a {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        
        footer .social-links a:hover {
            background: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(45, 90, 39, 0.3);
        }
        
        footer .footer-link {
            transition: all 0.3s ease;
            padding: 2px 0;
        }
        
        footer .footer-link:hover {
            padding-left: 5px;
            color: var(--accent-color) !important;
        }
        
        /* Global Sidebar Consistency */
        .sidebar, .sidebar-modern {
            width: 280px !important;
            max-width: 280px !important;
            flex: 0 0 280px !important;
        }
        
        @media (max-width: 768px) {
            .sidebar, .sidebar-modern {
                width: 100% !important;
                max-width: 100% !important;
                flex: 1 1 auto !important;
            }
        }
        
        /* Modal Fix - Ensure modal elements are clickable */
        .modal {
            z-index: 1055 !important;
        }
        
        .modal-backdrop {
            z-index: 1050 !important;
        }
        
        .modal-dialog {
            z-index: 1056 !important;
            pointer-events: auto !important;
        }
        
        .modal-content {
            pointer-events: auto !important;
            position: relative !important;
            z-index: 1057 !important;
        }
        
        .modal-body,
        .modal-header,
        .modal-footer {
            pointer-events: auto !important;
        }
        
        .modal-body input,
        .modal-body select,
        .modal-body button,
        .modal-body textarea {
            pointer-events: auto !important;
            z-index: auto !important;
        }
        
        /* Ensure form elements are interactive */
        .form-control,
        .form-select,
        .btn {
            pointer-events: auto !important;
        }
        
        /* Force all modal elements to be interactive */
        .modal * {
            pointer-events: auto !important;
        }
        
        /* Remove any potential blocking overlays */
        .modal-backdrop,
        .modal-backdrop.fade,
        .modal-backdrop.show,
        .modal-backdrop.fade.show {
            display: none !important;
            opacity: 0 !important;
            pointer-events: none !important;
            visibility: hidden !important;
        }
        
        /* Ensure modal is always on top */
        .modal.show {
            display: block !important;
            z-index: 1055 !important;
        }
        
        /* Force clickable state for all interactive elements */
        input, select, button, textarea, .btn, .form-control, .form-select {
            pointer-events: auto !important;
            user-select: auto !important;
            -webkit-user-select: auto !important;
            -moz-user-select: auto !important;
            -ms-user-select: auto !important;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('images/logoDishut.png') }}" alt="Logo Dinas Kehutanan" class="me-2" style="height: 50px; width: auto;">
                JASA KAYA
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">Tentang</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('process') ? 'active' : '' }}" href="{{ route('process') }}">Alur Proses</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Kontak</a>
                        </li>
                    @endguest
                </ul>
                
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Masuk</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary ms-2" href="{{ route('register') }}">Daftar</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i>{{ Auth::user()->email }}
                            </a>
                            <ul class="dropdown-menu">
                                @if(Auth::user()->role === 'KTHR_PENYULUH')
                                    <li><a class="dropdown-item" href="{{ route('kthr.profile') }}">Profil</a></li>
                                    <li><a class="dropdown-item" href="{{ route('kthr.dashboard') }}">Dashboard</a></li>
                                @elseif(Auth::user()->role === 'PBPHH')
                                    <li><a class="dropdown-item" href="{{ route('pbphh.profile') }}">Profil</a></li>
                                    <li><a class="dropdown-item" href="{{ route('pbphh.dashboard') }}">Dashboard</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Keluar</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5 mt-5">
        <div class="container">
            <div class="row g-4">
                <!-- Brand & Description -->
                <div class="col-lg-4 col-md-6">
                    <div class="mb-3">
                        <h4 class="mb-0 fw-bold">JASA KAYA</h4>
                    </div>
                    <p class="text-light mb-3">Sistem Kemitraan Kehutanan untuk Pembangunan Berkelanjutan dan Pengelolaan Hutan Lestari di Jawa Timur.</p>
                    <div class="d-flex gap-3 social-links">
                        <a href="#" class="text-light" title="Facebook"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-light" title="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-light" title="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-light" title="YouTube"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div class="col-lg-2 col-md-6">
                    <h6 class="fw-bold mb-3 text-success">Menu Utama</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('home') }}" class="text-light text-decoration-none footer-link"><i class="fas fa-home me-2"></i>Beranda</a></li>
                        <li class="mb-2"><a href="{{ route('about') }}" class="text-light text-decoration-none footer-link"><i class="fas fa-info-circle me-2"></i>Tentang</a></li>
                        <li class="mb-2"><a href="{{ route('process') }}" class="text-light text-decoration-none footer-link"><i class="fas fa-route me-2"></i>Alur Proses</a></li>
                        <li class="mb-2"><a href="{{ route('contact') }}" class="text-light text-decoration-none footer-link"><i class="fas fa-envelope me-2"></i>Kontak</a></li>
                    </ul>
                </div>
                
                <!-- Services -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-bold mb-3 text-success">Layanan Kami</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('register') }}" class="text-light text-decoration-none footer-link"><i class="fas fa-user-plus me-2"></i>Pendaftaran KTHR</a></li>
                        <li class="mb-2"><a href="{{ route('register') }}" class="text-light text-decoration-none footer-link"><i class="fas fa-industry me-2"></i>Pendaftaran PBPHH</a></li>
                        <li class="mb-2"><a href="#" class="text-light text-decoration-none footer-link"><i class="fas fa-handshake me-2"></i>Fasilitasi Kemitraan</a></li>
                        <li class="mb-2"><a href="#" class="text-light text-decoration-none footer-link"><i class="fas fa-chart-line me-2"></i>Monitoring & Evaluasi</a></li>
                    </ul>
                </div>
                
                <!-- Contact Info -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-bold mb-3 text-success">Kontak Kami</h6>
                    <div class="mb-2">
                        <i class="fas fa-map-marker-alt me-2 text-success"></i>
                        <span class="text-light">Dinas Kehutanan Provinsi Jawa Timur<br>Jl. Raya By pass Juanda No.5, Manyar, Sedati Agung, Kec. Sedati, Kabupaten Sidoarjo, Jawa Timur 61253</span>
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-phone me-2 text-success"></i>
                        <a href="tel:08158333630" class="text-light text-decoration-none">08158333630</a>
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-envelope me-2 text-success"></i>
                        <a href="mailto:dishut@jatimprov.go.id" class="text-light text-decoration-none">dishut@jatimprov.go.id</a>
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-globe me-2 text-success"></i>
                        <a href="https://dishut.jatimprov.go.id" class="text-light text-decoration-none" target="_blank">dishut.jatimprov.go.id</a>
                    </div>
                </div>
            </div>
            
            <!-- Bottom Bar -->
            <hr class="my-4 border-secondary">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0 text-light">&copy; 2024 Dinas Kehutanan Provinsi Jawa Timur. Semua hak dilindungi.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <small class="text-muted">
                        <a href="#" class="text-muted text-decoration-none me-3">Kebijakan Privasi</a>
                        <a href="#" class="text-muted text-decoration-none me-3">Syarat & Ketentuan</a>
                        <a href="#" class="text-muted text-decoration-none">Bantuan</a>
                    </small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    

    
    @stack('scripts')
</body>
</html>
