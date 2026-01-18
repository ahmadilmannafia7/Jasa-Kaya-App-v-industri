@extends('layouts.app')

@section('title', 'Alur Proses - JASA KAYA')

@push('styles')
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
}
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-primary text-white py-5" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);">
    <div class="container">
        <div class="text-center" data-aos="fade-up">
            <h1 class="display-5 fw-bold mb-4">Alur Proses Kemitraan</h1>
            <p class="lead">Langkah demi langkah menuju kemitraan kehutanan yang berkelanjutan</p>
        </div>
    </div>
</section>

<!-- Process Steps -->
<section class="py-5">
    <div class="container">
        <div class="row">
            @foreach($processSteps as $index => $step)
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                <div class="card h-100 border-0 shadow-sm position-relative">
                    <!-- Step Number -->
                    <div class="position-absolute top-0 start-50 translate-middle">
                        <div class="bg-{{ $step['color'] }} text-white rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 60px; height: 60px;">
                            <span class="fw-bold fs-4">{{ $step['step'] }}</span>
                        </div>
                    </div>
                    
                    <div class="card-body text-center pt-5">
                        <i class="{{ $step['icon'] }} fa-3x text-{{ $step['color'] }} mb-3"></i>
                        <h5 class="fw-bold">{{ $step['title'] }}</h5>
                        <p class="text-muted">{{ $step['description'] }}</p>
                    </div>
                    
                    @if($index < count($processSteps) - 1)
                    <div class="position-absolute top-50 start-100 translate-middle d-none d-lg-block">
                        <i class="fas fa-arrow-right fa-2x text-muted"></i>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Detailed Process -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-primary">Detail Proses untuk Setiap Peran</h2>
            <p class="lead text-muted">Pahami peran dan tanggung jawab masing-masing pihak</p>
        </div>
        
        <div class="row">
            <!-- KTHR Process -->
            <div class="col-lg-6 mb-4" data-aos="fade-right">
                <div class="card border-success h-100">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-users me-2"></i>Proses untuk KTHR/Penyuluh
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item mb-3">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="fw-bold">Pendaftaran</h6>
                                    <p class="small text-muted mb-1">Upload KTP Ketua dan SK Register KTHR</p>
                                </div>
                            </div>
                            <div class="timeline-item mb-3">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <h6 class="fw-bold">Menunggu Verifikasi CDK</h6>
                                    <p class="small text-muted mb-1">CDK akan memverifikasi dokumen dalam 1-3 hari</p>
                                </div>
                            </div>
                            <div class="timeline-item mb-3">
                                <div class="timeline-marker bg-warning"></div>
                                <div class="timeline-content">
                                    <h6 class="fw-bold">Lengkapi Profil</h6>
                                    <p class="small text-muted mb-1">Isi data lengkap KTHR dan jenis tanaman</p>
                                </div>
                            </div>
                            <div class="timeline-item mb-3">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <h6 class="fw-bold">Terima Permintaan</h6>
                                    <p class="small text-muted mb-1">Evaluasi dan respon permintaan dari industri</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="fw-bold">Tanda Tangan Kontrak</h6>
                                    <p class="small text-muted mb-1">Setelah pertemuan dan negosiasi</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- PBPHH Process -->
            <div class="col-lg-6 mb-4" data-aos="fade-left">
                <div class="card border-primary h-100">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-industry me-2"></i>Proses untuk PBPHH/Industri
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item mb-3">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <h6 class="fw-bold">Pendaftaran</h6>
                                    <p class="small text-muted mb-1">Upload NIB dan SK PBPHH</p>
                                </div>
                            </div>
                            <div class="timeline-item mb-3">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <h6 class="fw-bold">Menunggu Verifikasi Dinas</h6>
                                    <p class="small text-muted mb-1">Dinas Provinsi verifikasi dalam 3-5 hari</p>
                                </div>
                            </div>
                            <div class="timeline-item mb-3">
                                <div class="timeline-marker bg-warning"></div>
                                <div class="timeline-content">
                                    <h6 class="fw-bold">Lengkapi Profil</h6>
                                    <p class="small text-muted mb-1">Isi data perusahaan dan kebutuhan bahan baku</p>
                                </div>
                            </div>
                            <div class="timeline-item mb-3">
                                <div class="timeline-marker bg-secondary"></div>
                                <div class="timeline-content">
                                    <h6 class="fw-bold">Cari & Ajukan Kemitraan</h6>
                                    <p class="small text-muted mb-1">Eksplorasi KTHR dan ajukan permintaan</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="fw-bold">Tanda Tangan Kontrak</h6>
                                    <p class="small text-muted mb-1">Setelah pertemuan dan negosiasi</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Requirements -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-primary">Persyaratan Pendaftaran</h2>
            <p class="lead text-muted">Dokumen yang diperlukan untuk bergabung</p>
        </div>
        
        <div class="row">
            <div class="col-lg-6 mb-4" data-aos="fade-right">
                <div class="card border-success">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-success">
                            <i class="fas fa-users me-2"></i>KTHR/Penyuluh
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-3"></i>
                                <div>
                                    <strong>KTP Ketua KTHR</strong>
                                    <div class="small text-muted">Format: PDF, JPG, PNG (Max: 2MB)</div>
                                </div>
                            </li>
                            <li class="list-group-item d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-3"></i>
                                <div>
                                    <strong>SK Pendaftaran KTHR</strong>
                                    <div class="small text-muted">Format: PDF (Max: 2MB)</div>
                                </div>
                            </li>
                            <li class="list-group-item d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-3"></i>
                                <div>
                                    <strong>Email Aktif</strong>
                                    <div class="small text-muted">Untuk komunikasi dan notifikasi</div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 mb-4" data-aos="fade-left">
                <div class="card border-primary">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-primary">
                            <i class="fas fa-industry me-2"></i>PBPHH/Industri
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex align-items-center">
                                <i class="fas fa-check-circle text-primary me-3"></i>
                                <div>
                                    <strong>NIB (Nomor Induk Berusaha)</strong>
                                    <div class="small text-muted">Format: PDF, JPG, PNG (Max: 2MB)</div>
                                </div>
                            </li>
                            <li class="list-group-item d-flex align-items-center">
                                <i class="fas fa-check-circle text-primary me-3"></i>
                                <div>
                                    <strong>SK PBPHH</strong>
                                    <div class="small text-muted">Format: PDF (Max: 2MB)</div>
                                </div>
                            </li>
                            <li class="list-group-item d-flex align-items-center">
                                <i class="fas fa-check-circle text-primary me-3"></i>
                                <div>
                                    <strong>Email Perusahaan</strong>
                                    <div class="small text-muted">Untuk komunikasi resmi</div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Video Tutorial -->
<section class="py-5 video-tutorial-section" id="video-tutorial">
    <div class="container">
        <!-- Enhanced Header -->
        <div class="text-center mb-5" data-aos="fade-down">
            <div class="video-header-icon mb-3">
                <i class="fas fa-video fa-3x text-primary"></i>
            </div>
            <h2 class="fw-bold text-primary mb-3">Video Tutorial Pendaftaran</h2>
            <p class="lead text-muted mb-4">Pelajari cara mendaftar dengan mudah melalui video panduan berikut</p>
            <div class="video-stats d-flex justify-content-center gap-4 flex-wrap">
                <span class="badge bg-primary-subtle text-primary px-3 py-2">
                    <i class="fas fa-eye me-1"></i>Tutorial Lengkap
                </span>
                <span class="badge bg-success-subtle text-success px-3 py-2">
                    <i class="fas fa-clock me-1"></i>7 Menit
                </span>
                <span class="badge bg-info-subtle text-info px-3 py-2">
                    <i class="fas fa-star me-1"></i>Mudah Dipahami
                </span>
            </div>
        </div>
        
        <!-- Optimized Video Container -->
        <div class="container-fluid px-0">
            <div class="row justify-content-center mx-0">
                <div class="col-12 col-md-11 col-lg-10 col-xl-9 col-xxl-8 px-3 px-md-4">
                    <div class="video-container-enhanced" data-aos="zoom-in" data-aos-delay="200">
                        <div class="video-main-card">

                            
                            <!-- Video Player Section -->
                            <div class="video-player-section">

                                
                                <div class="video-frame-wrapper">
                                    <div class="ratio ratio-16x9">
                                        <iframe 
                                            id="tutorialVideo"
                                            src="https://www.youtube.com/embed/jlKzQsxcETc?rel=0&modestbranding=1&showinfo=0&iv_load_policy=3" 
                                            title="Cara Pendaftaran JASA KAYA" 
                                            frameborder="0" 
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                            allowfullscreen
                                            class="video-iframe-enhanced"
                                            onload="hideVideoLoading()">
                                        </iframe>
                                    </div>
                                </div>
                                

                            </div>
                            

                        </div>
                    </div>
                </div>
            </div>
        </div>
        

    </div>
</section>

<!-- FAQ -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-primary">Pertanyaan Umum</h2>
            <p class="lead text-muted">Jawaban untuk pertanyaan yang sering diajukan</p>
        </div>
        
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item" data-aos="fade-up" data-aos-delay="100">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                Berapa lama proses verifikasi?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Proses verifikasi untuk KTHR memakan waktu 1-3 hari kerja oleh CDK, sedangkan untuk industri PBPHH memakan waktu 3-5 hari kerja oleh Dinas Provinsi.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Apakah ada biaya untuk menggunakan platform ini?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Platform JASA KAYA sepenuhnya gratis untuk digunakan. Tidak ada biaya pendaftaran, verifikasi, atau penggunaan fitur-fitur yang tersedia.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Bagaimana jika dokumen saya ditolak?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Jika dokumen ditolak, Anda akan menerima notifikasi dengan alasan penolakan yang jelas. Anda dapat memperbaiki dokumen dan mendaftar ulang.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="fw-bold mb-4">Siap Memulai Proses Kemitraan?</h2>
        <p class="lead mb-4">Bergabunglah dengan ribuan KTHR dan industri yang telah merasakan manfaat kemitraan berkelanjutan</p>
        <a href="{{ route('register') }}" class="btn btn-light btn-lg">
            <i class="fas fa-rocket me-2"></i>Mulai Pendaftaran
        </a>
    </div>
</section>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
@endpush

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        AOS.init({
            duration: 1000,
            once: true,
            offset: 50
        });
        
        // Auto-play video when it comes into view
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Video is in view
                    entry.target.style.transform = 'scale(1.02)';
                } else {
                    entry.target.style.transform = 'scale(1)';
                }
            });
        }, { threshold: 0.5 });
        
        const videoContainer = document.querySelector('.ratio');
        if (videoContainer) {
            observer.observe(videoContainer);
        }
    });
    
    // Scroll to video function
    function scrollToVideo() {
        const videoSection = document.querySelector('.video-tutorial-section');
        if (videoSection) {
            videoSection.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'center' 
            });
            
            // Add a subtle highlight effect
            const videoCard = videoSection.querySelector('.card');
            if (videoCard) {
                videoCard.style.transform = 'scale(1.02)';
                videoCard.style.boxShadow = '0 20px 40px rgba(0,123,255,0.2)';
                
                setTimeout(() => {
                    videoCard.style.transform = 'scale(1)';
                    videoCard.style.boxShadow = '0 10px 30px rgba(0,0,0,0.1)';
                }, 1000);
            }
        }
    }
    
    // Hide video loading overlay
    function hideVideoLoading() {
        const loadingOverlay = document.getElementById('videoLoading');
        if (loadingOverlay) {
            loadingOverlay.style.display = 'none';
        }
    }
    
    // Open video in fullscreen
    function openVideoFullscreen() {
        const iframe = document.getElementById('tutorialVideo');
        if (iframe.requestFullscreen) {
            iframe.requestFullscreen();
        } else if (iframe.webkitRequestFullscreen) {
            iframe.webkitRequestFullscreen();
        } else if (iframe.msRequestFullscreen) {
            iframe.msRequestFullscreen();
        }
    }
    
    // Share video function
    function shareVideo() {
        if (navigator.share) {
            navigator.share({
                title: 'Tutorial Pendaftaran JASA KAYA',
                text: 'Pelajari cara mendaftar di platform JASA KAYA',
                url: 'https://youtu.be/jlKzQsxcETc'
            });
        } else {
            // Fallback: copy to clipboard
            navigator.clipboard.writeText('https://youtu.be/jlKzQsxcETc').then(() => {
                alert('Link video telah disalin ke clipboard!');
            });
        }
    }
</script>
<style>
.timeline {
    position: relative;
}

.timeline-item {
    position: relative;
    padding-left: 30px;
    transition: all 0.3s ease;
}

.timeline-item:hover {
    transform: translateX(10px);
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: 7px;
    top: 20px;
    bottom: -15px;
    width: 2px;
    background: linear-gradient(to bottom, #198652, #198652);
}

.timeline-marker {
    position: absolute;
    left: 0;
    top: 5px;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.timeline-item:hover .timeline-marker {
    transform: scale(1.2);
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #198652, #198652);
    position: relative;
    overflow: hidden;
}

.bg-gradient-primary::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="pattern" width="50" height="50" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23pattern)"/></svg>') repeat;
    animation: patternMove 30s linear infinite;
}

@keyframes patternMove {
    from { background-position: 0 0; }
    to { background-position: 100% 100%; }
}

.card {
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0,0,0,0.1);
}

.card-header {
    border-bottom: none;
    background: transparent;
    position: relative;
    overflow: hidden;
}

.card-header::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(to right, #198652, #198652);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.card:hover .card-header::after {
    transform: scaleX(1);
}

.accordion-button:not(.collapsed) {
    background: linear-gradient(135deg, #19865220, #19865220);
    color: #198652;
}

.accordion-button:focus {
    box-shadow: 0 0 0 0.25rem rgba(76, 187, 125, 0.25);
}

.btn-light:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

/* Video Tutorial Styles */
.bg-gradient {
    background: linear-gradient(135deg, #198652, #198652);
}

.ratio iframe {
    transition: all 0.3s ease;
}

.ratio:hover iframe {
    transform: scale(1.02);
}

.card-footer {
    background: rgba(248, 249, 250, 0.8) !important;
    backdrop-filter: blur(10px);
}

.video-info-icon {
    transition: all 0.3s ease;
}

.video-info-icon:hover {
    transform: scale(1.1) rotate(5deg);
    box-shadow: 0 8px 16px rgba(0,0,0,0.15);
}

/* Enhanced Video Info Styling */
.video-info-icon-enhanced {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.video-info-icon-enhanced::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left 0.6s;
}

.video-info-icon-enhanced:hover::before {
    left: 100%;
}

.video-info-icon-enhanced:hover {
    transform: scale(1.15) rotate(5deg);
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
}

.bg-success-gradient {
    background: linear-gradient(135deg, #28a745, #20c997) !important;
}

.bg-primary-gradient {
    background: linear-gradient(135deg, #007bff, #6610f2) !important;
}

.bg-warning-gradient {
    background: linear-gradient(135deg, #ffc107, #fd7e14) !important;
}

.hover-lift {
    transition: all 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
}

.info-card {
    position: relative;
}

.info-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #007bff, #28a745, #ffc107);
    border-radius: 0.375rem 0.375rem 0 0;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.info-card:hover::before {
    opacity: 1;
}

.rating-stars {
    animation: sparkle 2s ease-in-out infinite;
}

@keyframes sparkle {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.video-cta button {
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.video-cta button::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.video-cta button:hover::before {
    width: 300px;
    height: 300px;
}

.video-cta button:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 25px rgba(0,123,255,0.3);
}

.bg-light-subtle {
    background-color: #f8f9fa !important;
    border: 1px solid #e9ecef;
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

.progress {
            background-color: rgba(40, 167, 69, 0.1);
        }
        
        /* Enhanced Video Container Styling */
        .video-container-enhanced {
            margin: 2rem 0;
        }
        
        .video-main-card {
            background: #ffffff;
            border: 1px solid #f3f4f6;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }
        
        .video-main-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 30px 80px rgba(0,0,0,0.15);
        }
        
        /* Enhanced Header Section */
        .video-header-section {
            background: #f8fafc;
            padding: 2rem 2.5rem;
            position: relative;
            overflow: hidden;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .header-background-pattern {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255,255,255,0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 80%, rgba(255,255,255,0.1) 0%, transparent 50%);
        }
        
        .header-content {
            position: relative;
            z-index: 2;
        }
        
        .video-header-section h4 {
            color: #1f2937;
        }
        
        .video-header-section p {
            color: #6b7280;
        }
        
        .video-icon-wrapper {
            width: 60px;
            height: 60px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255,255,255,0.3);
        }
        
        .video-icon-wrapper .pulse-icon {
            font-size: 1.8rem;
            color: #fff;
            animation: pulse-glow 2s ease-in-out infinite;
        }
        
        @keyframes pulse-glow {
            0%, 100% {
                transform: scale(1);
                filter: drop-shadow(0 0 5px rgba(255,255,255,0.5));
            }
            50% {
                transform: scale(1.1);
                filter: drop-shadow(0 0 15px rgba(255,255,255,0.8));
            }
        }
        
        /* Video Player Section */
        .video-player-section {
            position: relative;
            background: #000;
        }
        
        .video-loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            transition: opacity 0.5s ease;
        }
        
        .loading-content {
            text-align: center;
            padding: 2rem;
        }
        
        .loading-spinner-enhanced {
            margin-bottom: 1rem;
        }
        
        .loading-progress {
            width: 200px;
            height: 4px;
            background: rgba(0,123,255,0.2);
            border-radius: 2px;
            overflow: hidden;
            margin: 1rem auto 0;
        }
        
        .progress-bar-animated {
            height: 100%;
            background: linear-gradient(90deg, #007bff, #6610f2);
            border-radius: 2px;
            animation: loading-progress 2s ease-in-out infinite;
        }
        
        @keyframes loading-progress {
            0% { width: 0%; }
            50% { width: 70%; }
            100% { width: 100%; }
        }
        
        .video-frame-wrapper {
            position: relative;
        }
        
        .video-iframe-enhanced {
            border: none;
            transition: all 0.3s ease;
        }
        
        .video-iframe-enhanced:hover {
            filter: brightness(1.05);
        }
        
        /* Enhanced Video Controls */
        .video-controls-enhanced {
            position: absolute;
            top: 15px;
            left: 15px;
            right: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 5;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .video-player-section:hover .video-controls-enhanced {
            opacity: 1;
        }
        
        .video-quality-badge {
            background: rgba(0,0,0,0.7);
            color: #fff;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }
        
        .control-btn {
            background: rgba(0,0,0,0.7);
            border: none;
            color: #fff;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 0.5rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .control-btn:hover {
            background: rgba(0,123,255,0.8);
            transform: scale(1.1);
            color: #fff;
        }
        
        /* Enhanced Footer Section */
        .video-footer-section {
            padding: 2rem 2.5rem;
            background: #ffffff;
            border-top: 1px solid #e5e7eb;
        }
        
        .video-description {
            margin-bottom: 1rem;
        }
        
        .video-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.75rem;
        }
        
        .tag-item {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: #fff;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .tag-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,123,255,0.3);
        }
        
        .btn-youtube {
            background: linear-gradient(135deg, #ff0000, #cc0000);
            border: none;
            color: #fff;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }
        
        .btn-youtube:hover {
            background: linear-gradient(135deg, #cc0000, #990000);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255,0,0,0.3);
            color: #fff;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .video-header-section {
                padding: 1.5rem 1rem;
            }
            
            .video-footer-section {
                padding: 1.5rem 1rem;
            }
            
            .video-icon-wrapper {
                width: 50px;
                height: 50px;
                margin-bottom: 1rem;
            }
            
            .video-icon-wrapper .pulse-icon {
                font-size: 1.5rem;
            }
            
            .d-flex.align-items-center.justify-content-center {
                flex-direction: column;
                text-align: center;
            }
            
            .video-tags {
                justify-content: center;
            }
            
            .video-controls-enhanced {
                opacity: 1;
            }
        }
        
        @media (max-width: 576px) {
            .video-main-card {
                border-radius: 15px;
                margin: 1rem 0;
            }
            
            .video-header-section {
                padding: 1rem;
            }
            
            .video-footer-section {
                padding: 1rem;
            }
        }
</style>
@endpush
@endsection
