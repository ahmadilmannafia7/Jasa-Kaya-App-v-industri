@extends('layouts.app')

@section('title', 'Kontak - JASA KAYA')

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
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="pattern" width="50" height="50" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23pattern)"/></svg>') repeat;
    animation: patternMove 30s linear infinite;
}

@keyframes patternMove {
    from { background-position: 0 0; }
    to { background-position: 100% 100%; }
}

.contact-card {
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.contact-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0,0,0,0.1);
}

.contact-icon {
    transition: all 0.3s ease;
}

.contact-card:hover .contact-icon {
    transform: scale(1.1);
    color: #198652;
}

.form-control:focus, .form-select:focus {
    border-color: #198652;
    box-shadow: 0 0 0 0.25rem rgba(76, 187, 125, 0.25);
}

.btn-primary {
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.operating-hours-card {
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.operating-hours-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0,0,0,0.1);
}
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-section text-white py-5">
    <div class="container">
        <div class="text-center">
            <h1 class="display-5 fw-bold mb-4">Hubungi Kami</h1>
            <p class="lead">Tim kami siap membantu Anda dalam proses kemitraan kehutanan</p>
        </div>
    </div>
</section>

<!-- Contact Information -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Dinas Kehutanan Provinsi -->
            <div class="col-12">
                <div class="card h-100 contact-card">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-tree fa-3x text-primary mb-3 contact-icon"></i>
                        <h5 class="fw-bold">Dinas Kehutanan Provinsi Jawa Timur</h5>
                        <div class="mb-3">
                            <i class="fas fa-map-marker-alt text-muted me-2"></i>
                            <span class="text-muted">Jl. Raya By pass Juanda No.5, Manyar, Sedati Agung, Kec. Sedati, Kabupaten Sidoarjo, Jawa Timur 61253</span>
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-phone text-muted me-2"></i>
                            <a href="tel:08158333630" class="text-decoration-none">08158333630</a>
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-envelope text-muted me-2"></i>
                            <a href="mailto:dishut@jatimprov.go.id" class="text-decoration-none">dishut@jatimprov.go.id</a>
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-globe text-muted me-2"></i>
                            <a href="https://dishut.jatimprov.go.id" target="_blank" class="text-decoration-none">dishut.jatimprov.go.id</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cabang Dinas Kehutanan -->
            <div class="col-12 text-center mb-4">
                <h3 class="fw-bold">Cabang Dinas Kehutanan</h3>
                <p class="text-muted">Temukan Cabang Dinas Kehutanan terdekat di wilayah Anda</p>
            </div>

            <!-- Pacitan -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 contact-card" data-aos="fade-up">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-building fa-2x text-primary mb-3 contact-icon"></i>
                        <h5 class="fw-bold">Wilayah Pacitan</h5>
                        <div class="mb-3">
                            <i class="fas fa-map-marker-alt text-muted me-2"></i>
                            <span class="text-muted">Jl. Achmad Yani No.2, Pacitan</span>
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-phone text-muted me-2"></i>
                            <a href="tel:(0357) 881711" class="text-decoration-none">(0357) 881711</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Madiun -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 contact-card">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-building fa-2x text-primary mb-3 contact-icon"></i>
                        <h5 class="fw-bold">Wilayah Madiun</h5>
                        <div class="mb-3">
                            <i class="fas fa-map-marker-alt text-muted me-2"></i>
                            <span class="text-muted">Jl. Rimba Karya No.6, Madiun</span>
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-phone text-muted me-2"></i>
                            <a href="tel:(0351) 462261" class="text-decoration-none">(0351) 462261</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trenggalek -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 contact-card">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-building fa-2x text-primary mb-3 contact-icon"></i>
                        <h5 class="fw-bold">Wilayah Trenggalek</h5>
                        <div class="mb-3">
                            <i class="fas fa-map-marker-alt text-muted me-2"></i>
                            <span class="text-muted">Jl. Soekarno-Hatta No.335, Trenggalek</span>
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-phone text-muted me-2"></i>
                            <a href="tel:(0355) 796482" class="text-decoration-none">(0355) 796482</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Malang -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 contact-card" data-aos="fade-up">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-building fa-2x text-primary mb-3 contact-icon"></i>
                        <h5 class="fw-bold">Wilayah Malang</h5>
                        <div class="mb-3">
                            <i class="fas fa-map-marker-alt text-muted me-2"></i>
                            <span class="text-muted">Jl. Raya Langsep No.55, Malang</span>
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-phone text-muted me-2"></i>
                            <a href="tel:(0341) 571154" class="text-decoration-none">(0341) 571154</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Nganjuk -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 contact-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-building fa-2x text-primary mb-3 contact-icon"></i>
                        <h5 class="fw-bold">Wilayah Nganjuk</h5>
                        <div class="mb-3">
                            <i class="fas fa-map-marker-alt text-muted me-2"></i>
                            <span class="text-muted">Jl. Veteran No.17, Nganjuk</span>
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-phone text-muted me-2"></i>
                            <a href="tel:(0358) 321746" class="text-decoration-none">(0358) 321746</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bojonegoro -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 contact-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-building fa-2x text-primary mb-3 contact-icon"></i>
                        <h5 class="fw-bold">Wilayah Bojonegoro</h5>
                        <div class="mb-3">
                            <i class="fas fa-map-marker-alt text-muted me-2"></i>
                            <span class="text-muted">Jl. Teuku Umar No.17, Bojonegoro</span>
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-phone text-muted me-2"></i>
                            <a href="tel:(0353) 881255" class="text-decoration-none">(0353) 881255</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lumajang -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 contact-card" data-aos="fade-up">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-building fa-2x text-primary mb-3 contact-icon"></i>
                        <h5 class="fw-bold">Wilayah Lumajang</h5>
                        <div class="mb-3">
                            <i class="fas fa-map-marker-alt text-muted me-2"></i>
                            <span class="text-muted">Jl. Pisang Agung No.55, Lumajang</span>
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-phone text-muted me-2"></i>
                            <a href="tel:(0334) 881426" class="text-decoration-none">(0334) 881426</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Jember -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 contact-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-building fa-2x text-primary mb-3 contact-icon"></i>
                        <h5 class="fw-bold">Wilayah Jember</h5>
                        <div class="mb-3">
                            <i class="fas fa-map-marker-alt text-muted me-2"></i>
                            <span class="text-muted">Jl. Letjen Panjaitan No.44, Jember</span>
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-phone text-muted me-2"></i>
                            <a href="tel:(0331) 321746" class="text-decoration-none">(0331) 321746</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Banyuwangi -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 contact-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-building fa-2x text-primary mb-3 contact-icon"></i>
                        <h5 class="fw-bold">Wilayah Banyuwangi</h5>
                        <div class="mb-3">
                            <i class="fas fa-map-marker-alt text-muted me-2"></i>
                            <span class="text-muted">Jl. Adi Sucipto No.25, Banyuwangi</span>
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-phone text-muted me-2"></i>
                            <a href="tel:(0333) 421746" class="text-decoration-none">(0333) 421746</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sumenep -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 contact-card" data-aos="fade-up">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-building fa-2x text-primary mb-3 contact-icon"></i>
                        <h5 class="fw-bold">Wilayah Sumenep</h5>
                        <div class="mb-3">
                            <i class="fas fa-map-marker-alt text-muted me-2"></i>
                            <span class="text-muted">Jl. Trunojoyo No.134, Sumenep</span>
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-phone text-muted me-2"></i>
                            <a href="tel:(0328) 662746" class="text-decoration-none">(0328) 662746</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>




@endsection
