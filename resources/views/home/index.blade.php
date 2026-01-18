@extends('layouts.app')

@section('title', 'JASA KAYA - Beranda')

@push('styles')
<style>
:root {
    --primary-color: #198652;
    --secondary-color: #198652;
    --primary-color-rgb: 25, 134, 82;
}

/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-color) 100%);
    position: relative;
    overflow: hidden;
    padding: 6rem 0;
    color: #ffffff;
}

.trust-section {
    background: #ffffff;
    position: relative;
    overflow: hidden;
    padding: 4rem 0;
}

.trust-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23198652" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
}

.trust-item {
    padding: 2rem;
    transition: transform 0.3s ease;
}

.trust-item:hover {
    transform: translateY(-5px);
}

.trust-icon {
    width: 60px;
    height: 60px;
    background: rgba(25, 134, 82, 0.1);
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
    color: #198652;
}

.stats-section {
    background: #ffffff;
    padding: 5rem 0;
    position: relative;
}

.stats-card {
    background: #ffffff;
    border-radius: 20px;
    padding: 2rem;
    height: 100%;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stats-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #198652, #198652);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(25, 134, 82, 0.1);
}

.stats-card:hover::before {
    opacity: 1;
}

.stats-icon {
    width: 70px;
    height: 70px;
    background: rgba(25, 134, 82, 0.1);
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.5rem;
    color: #198652;
}

.stats-number {
    font-size: 2.5rem;
    font-weight: 700;
    background: linear-gradient(45deg, #198652, #198652);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 0.5rem;
}

.map-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    padding: 6rem 0;
    position: relative;
}

.map-section .section-title {
    background: linear-gradient(45deg, #198652, #198652);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.cta-section {
    background: linear-gradient(135deg, #198652 0%, #198652 100%);
    padding: 6rem 0;
    position: relative;
    overflow: hidden;
}

.cta-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23000000" opacity="0.05"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
}

.hero-title {
    font-size: 3.5rem;
    line-height: 1.2;
    color: #ffffff;
    margin-bottom: 1.5rem;
    font-weight: 700;
}

.hero-subtitle {
    font-size: 1.25rem;
    line-height: 1.6;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 2rem;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50px;
    color: #ffffff;
    font-weight: 500;
}

.hero-video {
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    transform: perspective(1000px) rotateY(-5deg);
    transition: transform 0.5s ease;
}

.hero-video:hover {
    transform: perspective(1000px) rotateY(0deg);
}
/* Map Section */
.map-title {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    color: var(--primary-color);
}

.map-subtitle {
    font-size: 1.1rem;
    color: #6c757d;
    max-width: 600px;
    margin: 0 auto 2rem;
}

.map-legend {
    display: inline-flex;
    gap: 1.5rem;
    padding: 1rem 2rem;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 50px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
}

.legend-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.legend-dot.kthr {
    background: #198652;
}

.legend-dot.pbphh {
    background: #3b82f6;
}

.map-container {
    position: relative;
    height: 600px;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    transform: perspective(1000px) rotateX(2deg);
    transition: transform 0.5s ease;
}

.map-container:hover {
    transform: perspective(1000px) rotateX(0deg);
}

#map {
    height: 100%;
    width: 100%;
    border-radius: 20px;
}

/* Custom Popup Styles */
.leaflet-popup-content-wrapper {
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.marker-popup {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    min-width: 280px;
}

.popup-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
    padding-bottom: 8px;
    border-bottom: 2px solid #f1f3f4;
}

.popup-header h6 {
    margin: 0;
    color: #2c3e50;
    font-weight: 600;
    flex: 1;
}

.popup-body {
    font-size: 13px;
    line-height: 1.4;
}

.popup-body p {
    margin: 0 0 6px 0;
    color: #555;
    display: flex;
    align-items: center;
}

.popup-body i {
    width: 16px;
    color: #6c757d;
    font-size: 12px;
}

.popup-body strong {
    color: #2c3e50;
    margin-right: 4px;
}

.status-badges {
    margin-top: 8px;
}

.status-badges .badge {
    font-size: 10px;
    padding: 4px 8px;
}

.leaflet-popup-tip {
    background: white;
}

.custom-popup .leaflet-popup-content {
    margin: 15px;
}


/* Process Section */
.process-section {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    padding: 6rem 0;
    position: relative;
    overflow: hidden;
}

.process-title {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    color: var(--primary-color);
}

.process-subtitle {
    font-size: 1.1rem;
    color: #6c757d;
    max-width: 600px;
    margin: 0 auto 4rem;
}

.process-step {
    position: relative;
    padding: 2rem 1rem;
    transition: transform 0.3s ease;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    height: 100%;
}

.process-step:hover {
    transform: translateY(-10px);
}

.process-step::before {
    content: '';
    position: absolute;
    top: 50px;
    right: -50%;
    width: 100%;
    height: 2px;
    background: linear-gradient(90deg, #198652, #198652);
    z-index: 0;
}

.process-step:last-child::before {
    display: none;
}

.step-number {
    width: 80px;
    height: 80px;
    background: var(--primary-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    margin: 0 auto 1.5rem;
    position: relative;
    z-index: 1;
    box-shadow: 0 10px 20px rgba(var(--primary-color-rgb), 0.2);
    flex-shrink: 0;
}

.step-icon {
    font-size: 2rem;
}

.step-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #198652;
    text-align: center;
    min-height: 2.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.step-description {
    font-size: 0.9rem;
    color: #6c757d;
    margin: 0;
    text-align: center;
    line-height: 1.4;
    flex-grow: 1;
    display: flex;
    align-items: center;
    justify-content: center;
}

@media (max-width: 991px) {
    .process-step::before {
        display: none;
    }
}

@keyframes backgroundMove {
    from { background-position: 0 0; }
    to { background-position: 100% 100%; }
}

.cta-content {
    position: relative;
    z-index: 1;
    max-width: 800px;
    margin: 0 auto;
}

.cta-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50px;
    color: rgba(255, 255, 255, 0.9);
    font-size: 0.9rem;
    margin-bottom: 2rem;
}

.cta-title {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    color: white;
}

.cta-description {
    font-size: 1.2rem;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 2.5rem;
    line-height: 1.6;
}

.cta-features {
    display: flex;
    justify-content: center;
    gap: 2rem;
    margin-bottom: 3rem;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: rgba(255, 255, 255, 0.9);
}

.feature-item i {
    color: rgba(255, 255, 255, 0.7);
}

.cta-button {
    display: inline-flex;
    align-items: center;
    padding: 1rem 2.5rem;
    background: #ffffff;
    color: var(--primary-color);
    border-radius: 50px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    gap: 0.5rem;
}

.cta-button:hover {
    background: rgba(255, 255, 255, 0.9);
    transform: translateY(-2px);
}
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <h1 class="hero-title">
                    Selamat Datang di 
                    <br> JASA KAYA
                </h1>
                <p class="hero-subtitle">
                    Platform digital yang menghubungkan Kelompok Tani Hutan Rakyat (KTHR) dengan industri pengolahan hutan atau perijinan bursa penggolahan hasil hutan (PBPHH)
                    untuk menciptakan kemitraan yang saling menguntungkan dan berkelanjutan.
                </p>
                <div class="hero-badge">
                    <i class="fas fa-shield-alt me-2"></i>
                    Terverifikasi oleh Dinas Kehutanan Provinsi
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <video class="hero-video w-100" autoplay muted loop playsinline>
                    <source src="{{ asset('vid/combined_looping_video.mp4') }}" type="video/mp4">
                </video>
            </div>
        </div>
    </div>
</section>

<section class="trust-section py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-6 col-md-4" data-aos="fade-up" data-aos-delay="0">
                <div class="trust-item">
                    <div class="trust-icon">
                        <i class="fas fa-certificate fa-2x"></i>
                    </div>
                    <h5 class="mb-2 text-dark">Terverifikasi Resmi</h5>
                    <p class="mb-0 text-muted">Terdaftar dan diawasi oleh Dinas Kehutanan</p>
                </div>
            </div>
            <div class="col-6 col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="trust-item">
                    <div class="trust-icon">
                        <i class="fas fa-shield-alt fa-2x"></i>
                    </div>
                    <h5 class="mb-2 text-dark">Data Aman</h5>
                    <p class="mb-0 text-muted">Keamanan data terjamin dengan enkripsi</p>
                </div>
            </div>
            <div class="col-12 col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="trust-item">
                    <div class="trust-icon">
                        <i class="fas fa-handshake fa-2x"></i>
                    </div>
                    <h5 class="mb-2 text-dark">Fasilitasi Profesional</h5>
                    <p class="mb-0 text-muted">Didampingi oleh tim ahli kehutanan</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Peta Interaktif -->
<section class="map-section">
    <div class="container">
        <div class="text-center" data-aos="fade-up">
            <h2 class="map-title">Sebaran Lokasi KTHR & PBPHH</h2>
            <p class="map-subtitle">Peta interaktif untuk melihat persebaran kelompok tani dan industri yang telah terdaftar di seluruh wilayah</p>
            <div class="map-legend mb-4">
                <div class="legend-item">
                    <span class="legend-dot kthr"></span>
                    <span>KTHR</span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot pbphh"></span>
                    <span>PBPHH</span>
                </div>
            </div>
        </div>
        <div class="map-container" data-aos="fade-up" data-aos-delay="200">
            <div id="map"></div>
        </div>
    </div>
</section>

<!-- Statistik -->
<section class="stats-section">
    <div class="container">
        <div class="row text-center">
            @foreach([
                ['key' => 'total_kthr', 'label' => 'KTHR Terverifikasi', 'icon' => 'users', 'desc' => 'Kelompok tani yang telah terverifikasi'],
                ['key' => 'total_pbphh', 'label' => 'Industri Bergabung', 'icon' => 'industry', 'desc' => 'Perusahaan yang aktif bermitra'],
                ['key' => 'total_partnerships', 'label' => 'Kemitraan Berhasil', 'icon' => 'handshake', 'desc' => 'Kerjasama yang telah terbentuk'],
                ['key' => 'active_requests', 'label' => 'Permintaan Aktif', 'icon' => 'clock', 'desc' => 'Proses kemitraan dalam negosiasi']
            ] as $stat)
            <div class="col-md-6 col-lg-3 mb-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-{{ $stat['icon'] }} fa-2x"></i>
                    </div>
                    <div class="stats-number" data-count="{{ isset($stats[$stat['key']]) ? $stats[$stat['key']] : 0 }}">0</div>
                    <h5 class="mb-2">{{ $stat['label'] }}</h5>
                    <p class="text-muted mb-0 small">{{ $stat['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>



<!-- Alur Proses -->
<section class="process-section">
    <div class="container">
        <div class="text-center" data-aos="fade-up">
            <h2 class="process-title">Alur Proses Kemitraan</h2>
            <p class="process-subtitle">Langkah mudah untuk memulai kemitraan yang menguntungkan dan berkelanjutan</p>
        </div>
        <div class="row">
            @foreach([
                ['step' => 'Pendaftaran', 'desc' => 'Daftar sebagai KTHR atau Industri', 'icon' => 'user-plus'],
                ['step' => 'Verifikasi', 'desc' => 'Menunggu verifikasi CDK/Provinsi', 'icon' => 'check-circle'],
                ['step' => 'Profil Lengkap', 'desc' => 'Lengkapi data profil', 'icon' => 'id-card'],
                ['step' => 'Pencarian Mitra', 'desc' => 'Cari dan ajukan mitra', 'icon' => 'search'],
                ['step' => 'Pertemuan', 'desc' => 'Fasilitasi pertemuan', 'icon' => 'users'],
                ['step' => 'Kesepakatan', 'desc' => 'Tanda tangan kontrak', 'icon' => 'handshake']
            ] as $item)
            <div class="col-6 col-md-4 col-lg-2 mb-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                <div class="process-step">
                    <div class="step-number">
                        <i class="fas fa-{{ $item['icon'] }}"></i>
                    </div>
                    <h3 class="step-title">{{ $item['step'] }}</h3>
                    <p class="step-description">{{ $item['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section text-center">
    <div class="container">
        <div class="cta-content" data-aos="fade-up">
            <div class="cta-badge">
                <i class="fas fa-star me-2"></i>
                Mulai Perjalanan Anda
            </div>
            <h2 class="cta-title">Siap Memulai Kemitraan?</h2>
            <p class="cta-description">Bergabunglah dengan ribuan KTHR dan industri yang telah merasakan manfaat kemitraan berkelanjutan dalam platform kami</p>
            <div class="cta-features">
                <div class="feature-item">
                    <i class="fas fa-check-circle"></i>
                    <span>Proses Mudah</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-shield-alt"></i>
                    <span>Terpercaya</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-sync"></i>
                    <span>Berkelanjutan</span>
                </div>
            </div>
            <a href="{{ route('register') }}" class="cta-button">
                <i class="fas fa-rocket"></i>
                Mulai Sekarang
            </a>
        </div>
    </div>
</section>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css">
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 1000,
                once: true,
                offset: 100
            });

            const animateCounter = (element) => {
                const target = parseInt(element.getAttribute('data-count'));
                const duration = 2000;
                const step = target / (duration / 16);
                let current = 0;

                const timer = setInterval(() => {
                    current += step;
                    if (current >= target) {
                        clearInterval(timer);
                        element.textContent = target;
                    } else {
                        element.textContent = Math.floor(current);
                    }
                }, 16);
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animateCounter(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            });

            document.querySelectorAll('.stats-number').forEach(counter => {
                observer.observe(counter);
            });

            // Inisialisasi peta
            const map = L.map('map').setView([-7.8, 112.5], 8);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            const markers = L.markerClusterGroup();
            const lokasi = @json(isset($lokasi) ? $lokasi : []);

            const kthrIcon = new L.Icon({
                iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            const blueIcon = new L.Icon({
                iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            console.log('Total lokasi:', lokasi.length);
            console.log('Data lokasi:', lokasi);
            
            if (Array.isArray(lokasi)) {
                lokasi.forEach(loc => {
                    if (loc && typeof loc === 'object' && loc.lat && loc.lng && loc.nama && loc.type) {
                        const icon = loc.type === 'KTHR' ? kthrIcon : blueIcon;
                        let popupContent = '';
                        
                        if (loc.type === 'KTHR') {
                            popupContent = `
                                <div class="marker-popup">
                                    <div class="popup-header">
                                        <h6 class="mb-1"><i class="fas fa-users text-success me-2"></i>${loc.nama}</h6>
                                        <span class="badge bg-success">${loc.type}</span>
                                    </div>
                                    <div class="popup-body">
                                        ${loc.nama_pendamping ? `<p class="mb-1"><i class="fas fa-user-tie me-2"></i><strong>Pendamping:</strong> ${loc.nama_pendamping}</p>` : ''}
                                        ${loc.phone ? `<p class="mb-1"><i class="fas fa-phone me-2"></i><strong>Telepon:</strong> ${loc.phone}</p>` : ''}
                                        ${loc.email ? `<p class="mb-1"><i class="fas fa-envelope me-2"></i><strong>Email:</strong> ${loc.email}</p>` : ''}
                                        ${loc.alamat ? `<p class="mb-1"><i class="fas fa-map-marker-alt me-2"></i><strong>Alamat:</strong> ${loc.alamat}</p>` : ''}
                                        ${loc.region_name ? `<p class="mb-1"><i class="fas fa-map me-2"></i><strong>Wilayah:</strong> ${loc.region_name}</p>` : ''}
                                        ${loc.luas_areal_ha ? `<p class="mb-1"><i class="fas fa-tree me-2"></i><strong>Luas Areal:</strong> ${loc.luas_areal_ha} Ha</p>` : ''}
                                        ${loc.jumlah_anggota ? `<p class="mb-1"><i class="fas fa-users me-2"></i><strong>Jumlah Anggota:</strong> ${loc.jumlah_anggota} orang</p>` : ''}
                                        <div class="status-badges mt-2">
                                            ${loc.is_siap_mitra ? '<span class="badge bg-primary me-1">Siap Mitra</span>' : ''}
                                            ${loc.is_siap_tebang ? '<span class="badge bg-warning">Siap Tebang</span>' : ''}
                                        </div>
                                    </div>
                                </div>
                            `;
                        } else if (loc.type === 'PBPHH') {
                            popupContent = `
                                <div class="marker-popup">
                                    <div class="popup-header">
                                        <h6 class="mb-1"><i class="fas fa-industry text-primary me-2"></i>${loc.nama}</h6>
                                        <span class="badge bg-primary">${loc.type}</span>
                                    </div>
                                    <div class="popup-body">
                                        ${loc.penanggung_jawab ? `<p class="mb-1"><i class="fas fa-user-tie me-2"></i><strong>Penanggung Jawab:</strong> ${loc.penanggung_jawab}</p>` : ''}
                                        ${loc.phone ? `<p class="mb-1"><i class="fas fa-phone me-2"></i><strong>Telepon:</strong> ${loc.phone}</p>` : ''}
                                        ${loc.email ? `<p class="mb-1"><i class="fas fa-envelope me-2"></i><strong>Email:</strong> ${loc.email}</p>` : ''}
                                        ${loc.alamat ? `<p class="mb-1"><i class="fas fa-map-marker-alt me-2"></i><strong>Alamat:</strong> ${loc.alamat}</p>` : ''}
                                        ${loc.kapasitas_izin_produksi_m3 ? `<p class="mb-1"><i class="fas fa-cube me-2"></i><strong>Kapasitas Izin:</strong> ${loc.kapasitas_izin_produksi_m3} m³</p>` : ''}
                                        ${loc.rencana_produksi_tahunan_m3 ? `<p class="mb-1"><i class="fas fa-chart-bar me-2"></i><strong>Rencana Produksi:</strong> ${loc.rencana_produksi_tahunan_m3} m³/tahun</p>` : ''}
                                    </div>
                                </div>
                            `;
                        }
                        
                        const marker = L.marker([loc.lat, loc.lng], { icon })
                            .bindPopup(popupContent, {
                                maxWidth: 350,
                                className: 'custom-popup'
                            });
                        markers.addLayer(marker);
                        console.log('Marker ditambahkan:', loc.nama, loc.type);
                    }
                });
            }

            map.addLayer(markers);
            if (markers.getLayers().length > 0) {
                map.fitBounds(markers.getBounds());
            }
        });
    </script>
@endpush
