@extends('layouts.app')

@section('title', 'Tentang JASA KAYA')

@push('styles')
<style>
    .hero-section {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        position: relative;
        overflow: hidden;
    }
    
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
        opacity: 0.3;
    }
    
    .hero-content {
        position: relative;
        z-index: 2;
    }
    
    .floating-icon {
        animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    
    .feature-card {
        transition: all 0.3s ease;
        border: none;
        background: #fff;
        position: relative;
        overflow: hidden;
    }
    
    .feature-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(45, 90, 39, 0.1), transparent);
        transition: left 0.5s ease;
    }
    
    .feature-card:hover::before {
        left: 100%;
    }
    
    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    
    .feature-icon {
        transition: all 0.3s ease;
    }
    
    .feature-card:hover .feature-icon {
        transform: scale(1.1);
        color: var(--primary-color) !important;
    }
    
    .vision-mission-card {
        transition: all 0.3s ease;
        border: none;
        position: relative;
        overflow: hidden;
    }
    
    .vision-mission-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
    
    .process-step {
        transition: all 0.3s ease;
    }
    
    .process-step:hover {
        transform: scale(1.05);
    }
    
    .process-circle {
        transition: all 0.3s ease;
        position: relative;
    }
    
    .process-circle::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255,255,255,0.3);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: all 0.3s ease;
    }
    
    .process-step:hover .process-circle::after {
        width: 100%;
        height: 100%;
    }
    
    .stats-section {
        background: linear-gradient(135deg, #2d5a27 0%, #1a4d1a 100%);
        position: relative;
    }
    
    .stats-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><polygon points="0,100 100,0 100,100" fill="%23ffffff" opacity="0.05"/></svg>') no-repeat;
        background-size: cover;
    }
    
    .stat-item {
        position: relative;
        z-index: 2;
    }
    
    .cta-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
    
    .btn-cta {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .btn-cta::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s ease;
    }
    
    .btn-cta:hover::before {
        left: 100%;
    }
    
    .section-divider {
        height: 2px;
        background: linear-gradient(90deg, transparent, var(--primary-color), transparent);
        margin: 3rem 0;
    }
    
    .fade-in {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.6s ease;
    }
    
    .fade-in.visible {
        opacity: 1;
        transform: translateY(0);
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-section text-white py-5">
    <div class="container hero-content">
        <div class="row align-items-center min-vh-50">
            <div class="col-lg-8 fade-in">
                <div class="mb-3">
                    <span class="badge bg-success bg-opacity-20 text-white px-3 py-2 rounded-pill">
                        <i class="fas fa-leaf me-2"></i>Kemitraan Berkelanjutan
                    </span>
                </div>
                <h1 class="display-4 fw-bold mb-4 text-shadow">
                    Tentang <span class="text-warning">JASA KAYA</span>
                </h1>
                <p class="lead mb-4 text-light">
                    Sistem Kemitraan Kehutanan untuk Pembangunan Berkelanjutan yang menghubungkan 
                    Kelompok Tani Hutan Rakyat dengan Industri Pengolahan Hasil Hutan.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <span>Terverifikasi Resmi</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-shield-alt text-info me-2"></i>
                        <span>Aman & Terpercaya</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-handshake text-warning me-2"></i>
                        <span>Fasilitasi Profesional</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-center fade-in">
                <div class="position-relative">
                    <i class="fas fa-tree floating-icon" style="font-size: 8rem; color: rgba(255,255,255,0.8);"></i>
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
                        <div class="bg-white bg-opacity-10 rounded-circle p-4">
                            <i class="fas fa-seedling text-success" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="position-absolute bottom-0 start-0 w-100">
        <svg viewBox="0 0 1200 120" preserveAspectRatio="none" style="height: 60px; width: 100%;">
            <path d="M0,60 C300,120 900,0 1200,60 L1200,120 L0,120 Z" fill="#ffffff"></path>
        </svg>
    </div>
</section>

<!-- Mission & Vision -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5 fade-in">
            <h2 class="display-6 fw-bold text-primary mb-3">Visi & Misi Kami</h2>
            <p class="lead text-muted">Komitmen kami dalam membangun kemitraan kehutanan yang berkelanjutan</p>
            <div class="section-divider"></div>
        </div>
        
        <div class="row">
            <div class="col-lg-6 mb-4 fade-in">
                <div class="card vision-mission-card h-100 shadow-lg">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-eye fa-2x text-primary"></i>
                            </div>
                            <h3 class="fw-bold text-primary mb-3">Visi</h3>
                        </div>
                        <blockquote class="blockquote text-center">
                            <p class="mb-0 fst-italic">
                                "Menjadi platform digital terdepan dalam memfasilitasi kemitraan kehutanan 
                                yang berkelanjutan, transparan, dan menguntungkan semua pihak untuk 
                                mendukung pembangunan ekonomi hijau Indonesia."
                            </p>
                        </blockquote>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4 fade-in">
                <div class="card vision-mission-card h-100 shadow-lg">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-bullseye fa-2x text-success"></i>
                            </div>
                            <h3 class="fw-bold text-success mb-3">Misi</h3>
                        </div>
                        <ul class="list-unstyled">
                            <li class="mb-3 d-flex align-items-start">
                                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 24px; height: 24px;">
                                    <i class="fas fa-check text-white" style="font-size: 12px;"></i>
                                </div>
                                <span>Memfasilitasi kemitraan yang adil dan berkelanjutan</span>
                            </li>
                            <li class="mb-3 d-flex align-items-start">
                                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 24px; height: 24px;">
                                    <i class="fas fa-check text-white" style="font-size: 12px;"></i>
                                </div>
                                <span>Meningkatkan kesejahteraan petani hutan rakyat</span>
                            </li>
                            <li class="mb-3 d-flex align-items-start">
                                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 24px; height: 24px;">
                                    <i class="fas fa-check text-white" style="font-size: 12px;"></i>
                                </div>
                                <span>Mendukung industri dengan pasokan bahan baku berkualitas</span>
                            </li>
                            <li class="mb-0 d-flex align-items-start">
                                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 24px; height: 24px;">
                                    <i class="fas fa-check text-white" style="font-size: 12px;"></i>
                                </div>
                                <span>Menjaga kelestarian hutan dan lingkungan</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5 fade-in">
            <h2 class="display-6 fw-bold text-primary mb-3">Mengapa Memilih JASA KAYA?</h2>
            <p class="lead text-muted">Platform yang dirancang khusus untuk kebutuhan kemitraan kehutanan</p>
            <div class="section-divider"></div>
        </div>
        
        <div class="row">
            @foreach($features as $index => $feature)
            <div class="col-lg-3 col-md-6 mb-4 fade-in" style="animation-delay: {{ ($index * 0.1) }}s;">
                <div class="card feature-card h-100 shadow-lg text-center">
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="{{ $feature['icon'] }} fa-2x feature-icon text-primary"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold mb-3 text-dark">{{ $feature['title'] }}</h5>
                        <p class="text-muted mb-0">{{ $feature['description'] }}</p>
                    </div>
                    <div class="card-footer bg-transparent border-0 pb-4">
                        <div class="d-flex justify-content-center">
                            <div class="bg-primary" style="width: 40px; height: 3px; border-radius: 2px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5 fade-in">
            <h2 class="display-6 fw-bold text-primary mb-3">Bagaimana Cara Kerjanya?</h2>
            <p class="lead text-muted">Proses yang mudah dan transparan untuk memulai kemitraan kehutanan</p>
            <div class="section-divider"></div>
        </div>
        
        <div class="row position-relative">
            <!-- Connection Line -->
            <div class="d-none d-lg-block position-absolute top-50 start-0 w-100" style="height: 2px; background: linear-gradient(90deg, transparent 16.66%, #0d6efd 16.66%, #0d6efd 83.33%, transparent 83.33%); z-index: 1;"></div>
            
            <div class="col-lg-4 mb-4 fade-in" style="animation-delay: 0.1s;">
                <div class="text-center process-step">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3 position-relative process-circle" style="width: 80px; height: 80px; z-index: 2;">
                        <i class="fas fa-user-plus fa-2x"></i>
                    </div>
                    <h5 class="fw-bold mb-3">1. Daftar & Verifikasi</h5>
                    <p class="text-muted">Daftar sebagai KTHR atau Industri dengan dokumen lengkap. Tim kami akan memverifikasi dalam 1-3 hari kerja.</p>
                </div>
            </div>
            <div class="col-lg-4 mb-4 fade-in" style="animation-delay: 0.2s;">
                <div class="text-center process-step">
                    <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3 position-relative process-circle" style="width: 80px; height: 80px; z-index: 2;">
                        <i class="fas fa-search fa-2x"></i>
                    </div>
                    <h5 class="fw-bold mb-3">2. Cari & Ajukan</h5>
                    <p class="text-muted">Industri dapat mencari KTHR yang sesuai kebutuhan dan mengajukan permintaan kemitraan.</p>
                </div>
            </div>
            <div class="col-lg-4 mb-4 fade-in" style="animation-delay: 0.3s;">
                <div class="text-center process-step">
                    <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3 position-relative process-circle" style="width: 80px; height: 80px; z-index: 2;">
                        <i class="fas fa-handshake fa-2x"></i>
                    </div>
                    <h5 class="fw-bold mb-3">3. Fasilitasi & Kontrak</h5>
                    <p class="text-muted">CDK memfasilitasi pertemuan dan negosiasi hingga tercapai kesepakatan yang menguntungkan kedua belah pihak.</p>
                </div>
            </div>
        </div>
    </div>
</section>



<!-- Call to Action -->
<section class="py-5 bg-light position-relative overflow-hidden">
    <!-- Background Elements -->
    <div class="position-absolute top-0 end-0 opacity-5">
        <i class="fas fa-tree" style="font-size: 15rem; color: #198652;"></i>
    </div>
    <div class="position-absolute bottom-0 start-0 opacity-5">
        <i class="fas fa-leaf" style="font-size: 10rem; color: #198652;"></i>
    </div>
    
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-8 fade-in">
                <div class="mb-4">
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-3">
                        <i class="fas fa-rocket me-2"></i>Mulai Perjalanan Anda
                    </span>
                </div>
                <h3 class="display-6 fw-bold mb-3 text-dark">Siap Memulai Kemitraan Kehutanan?</h3>
                <p class="lead text-muted mb-4">Bergabunglah dengan ribuan KTHR dan industri yang telah merasakan manfaat platform JASA KAYA. Wujudkan kemitraan berkelanjutan untuk masa depan hutan Indonesia yang lebih baik.</p>
                
                <div class="d-flex flex-wrap gap-3 mb-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <span class="text-muted">Gratis untuk bergabung</span>
                    </div>
                    <!-- <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <span class="text-muted">Dukungan 24/7</span>
                    </div> -->
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <span class="text-muted">Proses cepat & mudah</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end fade-in" style="animation-delay: 0.2s;">
                <div class="d-grid gap-3">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-4 py-3 cta-button">
                        <i class="fas fa-rocket me-2"></i>Mulai Sekarang
                        <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-primary btn-lg px-4 py-3">
                        <i class="fas fa-phone me-2"></i>Hubungi Kami
                    </a>
                </div>
                
                <div class="mt-4 text-center text-lg-end">
                    <small class="text-muted">
                        <i class="fas fa-users me-1"></i>
                        Dipercaya oleh 500+ KTHR
                    </small>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
// Counter Animation
function animateCounters() {
    const counters = document.querySelectorAll('.counter');
    
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'));
        const increment = target / 100;
        let current = 0;
        
        const updateCounter = () => {
            if (current < target) {
                current += increment;
                counter.textContent = Math.floor(current) + (target >= 1000 ? '+' : '');
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = target.toLocaleString() + '+';
            }
        };
        
        updateCounter();
    });
}

// Intersection Observer for animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            
            // Trigger counter animation for statistics section
            if (entry.target.closest('.bg-primary') && entry.target.querySelector('.counter')) {
                setTimeout(animateCounters, 500);
            }
        }
    });
}, observerOptions);

// Observe all fade-in elements
document.addEventListener('DOMContentLoaded', function() {
    const fadeElements = document.querySelectorAll('.fade-in');
    fadeElements.forEach(el => observer.observe(el));
    
    // Add smooth scrolling to all anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});

// Parallax effect for hero section
window.addEventListener('scroll', () => {
    const scrolled = window.pageYOffset;
    const parallax = document.querySelector('.hero-bg');
    if (parallax) {
        const speed = scrolled * 0.5;
        parallax.style.transform = `translateY(${speed}px)`;
    }
});
</script>
@endpush
