@extends('layouts.kthr')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/kthr-dashboard.css') }}">
<style>
/* Enhanced Profile Form Styles */
.profile-form-container {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-radius: 20px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.08);
    overflow: hidden;
    position: relative;
}

.profile-form-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #007bff, #28a745, #ffc107);
}

.section-card {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 1.5rem;
    border: 1px solid #e3e6f0;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}

.section-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.section-title {
    color: #2c3e50;
    font-weight: 600;
    font-size: 1.25rem;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #e9ecef;
    display: flex;
    align-items: center;
}

.section-title i {
    color: #007bff;
    margin-right: 0.75rem;
    font-size: 1.1rem;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.form-control, .form-select {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
    font-size: 0.95rem;
}

.form-control:focus, .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.15);
    transform: translateY(-1px);
}

.plant-item {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border: 2px solid #e9ecef;
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
    position: relative;
}

.plant-item:hover {
    border-color: #007bff;
    box-shadow: 0 8px 25px rgba(0,123,255,0.1);
}



.plant-header {
    background: rgba(0,123,255,0.05);
    margin: -1.5rem -1.5rem 1.5rem -1.5rem;
    padding: 1rem 1.5rem;
    border-radius: 16px 16px 0 0;
    border-bottom: 1px solid #e9ecef;
}

.plant-title {
    color: #007bff;
    font-weight: 600;
    margin: 0;
    font-size: 1.1rem;
}

.btn-add-plant {
    background: linear-gradient(135deg, #007bff, #0056b3);
    border: none;
    border-radius: 12px;
    padding: 0.75rem 1.5rem;
    color: white;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0,123,255,0.2);
}

.btn-add-plant:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,123,255,0.3);
    color: white;
}

.btn-remove-plant {
    background: linear-gradient(135deg, #dc3545, #c82333);
    border: none;
    border-radius: 8px;
    color: white;
    transition: all 0.3s ease;
}

.btn-remove-plant:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 15px rgba(220,53,69,0.3);
}

.progress-container {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    border: 1px solid #e3e6f0;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.progress {
    height: 8px;
    border-radius: 10px;
    background: #e9ecef;
    overflow: hidden;
}

.progress-bar {
    background: linear-gradient(90deg, #007bff, #28a745);
    border-radius: 10px;
    transition: all 0.5s ease;
}

.form-actions {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    border: 1px solid #e3e6f0;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    margin-top: 2rem;
}

.btn-submit {
    background: linear-gradient(135deg, #28a745, #20c997);
    border: none;
    border-radius: 12px;
    padding: 1rem 2rem;
    color: white;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(40,167,69,0.2);
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(40,167,69,0.3);
    color: white;
}

/* Enhanced Plant Item Styles */
.plant-content {
    padding: 1rem 0;
}

.form-floating {
    position: relative;
}

.form-floating > .form-control,
.form-floating > .form-select {
    height: calc(3.5rem + 2px);
    line-height: 1.25;
    border-radius: 12px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-floating > .form-control:focus,
.form-floating > .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.15);
    transform: translateY(-1px);
}

.form-floating > label {
    padding: 1rem 0.75rem;
    font-weight: 600;
    color: #6c757d;
    transition: all 0.3s ease;
}

.form-floating > .form-control:focus ~ label,
.form-floating > .form-control:not(:placeholder-shown) ~ label,
.form-floating > .form-select:focus ~ label,
.form-floating > .form-select:not([value=""]) ~ label {
    opacity: 0.65;
    transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
    color: #007bff;
}

/* Card Sections */
.plant-content .card {
    transition: all 0.3s ease;
    border-radius: 12px;
}

.plant-content .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.card-title {
    font-size: 0.95rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

/* Upload Area Enhancements */
.upload-area {
    position: relative;
}

.upload-area .form-control[type="file"] {
    border: 2px dashed #007bff;
    background: rgba(0,123,255,0.05);
    border-radius: 12px;
    padding: 1rem;
    transition: all 0.3s ease;
}

.upload-area .form-control[type="file"]:hover {
    border-color: #0056b3;
    background: rgba(0,123,255,0.1);
    transform: translateY(-1px);
}

.upload-area .form-control[type="file"]:focus {
    border-color: #0056b3;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.15);
}

/* Badge Enhancements */
.badge {
    font-size: 0.75rem;
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    font-weight: 500;
}

.bg-success-subtle {
    background-color: rgba(25, 135, 84, 0.1) !important;
}

.text-success {
    color: #198754 !important;
}

.border-success-subtle {
    border-color: rgba(25, 135, 84, 0.3) !important;
}

/* Icon Colors */
.text-success { color: #28a745 !important; }
.text-primary { color: #007bff !important; }
.text-info { color: #17a2b8 !important; }
.text-warning { color: #ffc107 !important; }

/* Responsive Improvements */
@media (max-width: 768px) {
    .plant-item {
        padding: 1rem;
    }
    
    .plant-header {
        margin: -1rem -1rem 1rem -1rem;
        padding: 0.75rem 1rem;
    }
    
    .form-floating > .form-control,
    .form-floating > .form-select {
        height: calc(3rem + 2px);
    }
    
    .card-body {
        padding: 1rem !important;
    }
}

/* Animation for new plant items */
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.plant-item {
     animation: slideInUp 0.5s ease-out;
 }

.alert {
    border-radius: 12px;
    border: none;
    padding: 1rem 1.5rem;
    margin-bottom: 1.5rem;
}

.alert-danger {
    background: linear-gradient(135deg, #f8d7da, #f5c6cb);
    color: #721c24;
}

.alert-success {
    background: linear-gradient(135deg, #d4edda, #c3e6cb);
    color: #155724;
}

@media (max-width: 768px) {
    .section-card {
        padding: 1.5rem;
    }
    
    .plant-item {
        padding: 1rem;
    }
    
    .plant-header {
        margin: -1rem -1rem 1rem -1rem;
        padding: 0.75rem 1rem;
    }
}
</style>
@endpush

@section('title', 'Lengkapi Profil KTHR - JASA KAYA')

@section('dashboard-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-primary mb-1">Lengkapi Profil KTHR</h2>
        <p class="text-muted mb-0">Lengkapi informasi profil KTHR untuk dapat bermitra dengan industri</p>
    </div>
</div>


<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="profile-form-container">
            <div class="p-4">
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form id="completeProfileForm" action="{{ route('kthr.profile.complete.submit') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Enhanced Progress Indicator -->
                    <div class="progress-container">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-chart-line text-primary me-2"></i>
                                <span class="fw-bold text-dark">Progress Pengisian</span>
                            </div>
                            <span class="badge bg-primary fs-6 px-3 py-2" id="progressText">0%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 0%" id="progressBar"></div>
                        </div>
                        <small class="text-muted mt-2 d-block">
                            <i class="fas fa-info-circle me-1"></i>
                            Lengkapi semua field yang diperlukan untuk menyelesaikan profil
                        </small>
                    </div>
                    
                    <!-- Informasi Dasar -->
                    <div class="section-card">
                        <h5 class="section-title">
                            <i class="fas fa-user"></i>Informasi Dasar
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nama_pendamping" class="form-label">Nama Pendamping <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('nama_pendamping') is-invalid @enderror" id="nama_pendamping" name="nama_pendamping"
                                            value="{{ old('nama_pendamping', $kthr->nama_pendamping ?? '') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Nomor Telepon <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="phone" name="phone"
                                            value="{{ old('phone') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="jumlah_anggota" class="form-label">Jumlah Anggota <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('jumlah_anggota') is-invalid @enderror" id="jumlah_anggota" name="jumlah_anggota"
                                            value="{{ old('jumlah_anggota') }}" min="1" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="alamat_sekretariat" class="form-label">Alamat Sekretariat <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control" id="alamat_sekretariat" name="alamat_sekretariat"
                                            rows="3" required>{{ old('alamat_sekretariat') }}</textarea>
                                    </div>
                                </div>
                        </div>
                    </div>

                    <!-- Informasi Lokasi -->
                    <div class="section-card">
                        <h5 class="section-title">
                            <i class="fas fa-map-marker-alt"></i>Informasi Lokasi
                        </h5>
                        <div class="row">
                            <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="coordinate_lat" class="form-label">Latitude <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('coordinate_lat') is-invalid @enderror" id="coordinate_lat" name="coordinate_lat"
                                            value="{{ old('coordinate_lat') }}" step="0.00000001" min="-90" max="90"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="coordinate_lng" class="form-label">Longitude <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="coordinate_lng" name="coordinate_lng"
                                            value="{{ old('coordinate_lng') }}" step="0.00000001" min="-180" max="180"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="luas_areal_ha" class="form-label">Luas Areal (Ha) <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="luas_areal_ha" name="luas_areal_ha"
                                            value="{{ old('luas_areal_ha') }}" step="0.01" min="0" required>
                                    </div>
                                </div>
                        </div>
                    </div>

                    <!-- Informasi Kegiatan -->
                    <div class="section-card">
                        <h5 class="section-title">
                            <i class="fas fa-calendar-alt"></i>Informasi Kegiatan
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="jumlah_pertemuan_tahunan" class="form-label">Jumlah Pertemuan KTH
                                            <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="jumlah_pertemuan_tahunan"
                                            name="jumlah_pertemuan_tahunan" value="{{ old('jumlah_pertemuan_tahunan') }}"
                                            min="0" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="shp_file" class="form-label">File SHP (Opsional)</label>
                                        <input type="file" class="form-control @error('shp_file') is-invalid @enderror" id="shp_file" name="shp_file"
                                            accept=".zip,.shp">
                                        <small class="text-muted">Format: ZIP atau SHP (Max: 10MB)</small>
                                    </div>
                                </div>
                        </div>
                    </div>

                            @push('scripts')
                            <script>
                            // Progress indicator
                            function updateProgress() {
                                const form = document.getElementById('completeProfileForm');
                                const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
                                let filledInputs = 0;
                                
                                inputs.forEach(input => {
                                    if (input.value.trim() !== '') {
                                        filledInputs++;
                                    }
                                });
                                
                                const progress = Math.round((filledInputs / inputs.length) * 100);
                document.getElementById('progressBar').style.width = progress + '%';
                document.getElementById('progressText').textContent = progress + '%';
                
                // Update progress bar color based on completion
                const progressBar = document.getElementById('progressBar');
                if (progress < 30) {
                    progressBar.style.background = 'linear-gradient(90deg, #dc3545, #c82333)';
                } else if (progress < 70) {
                    progressBar.style.background = 'linear-gradient(90deg, #ffc107, #fd7e14)';
                } else {
                    progressBar.style.background = 'linear-gradient(90deg, #007bff, #28a745)';
                }
                            }

                            // File validation function
                            function validateImageFile(input) {
                                const file = input.files[0];
                                const maxSize = 5 * 1024 * 1024; // 5MB
                                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                                
                                if (file) {
                                    if (!allowedTypes.includes(file.type)) {
                                        alert('Format file tidak didukung. Gunakan format: JPG, JPEG, PNG, GIF, atau WEBP');
                                        input.value = '';
                                        return false;
                                    }
                                    
                                    if (file.size > maxSize) {
                                        alert('Ukuran file terlalu besar. Maksimal 5MB');
                                        input.value = '';
                                        return false;
                                    }
                                }
                                return true;
                            }

                            // Add event listeners to all form inputs
                            document.addEventListener('DOMContentLoaded', function() {
                                const form = document.getElementById('completeProfileForm');
                                const inputs = form.querySelectorAll('input, select, textarea');
                                
                                inputs.forEach(input => {
                                    input.addEventListener('input', updateProgress);
                                    input.addEventListener('change', updateProgress);
                                    
                                    // Add file validation for image inputs
                                    if (input.type === 'file' && input.accept.includes('image')) {
                                        input.addEventListener('change', function() {
                                            validateImageFile(this);
                                        });
                                    }
                                });
                                
                                // Initial progress calculation
                                updateProgress();
                            });

                            document.getElementById('completeProfileForm').addEventListener('submit', function(e) {
                                e.preventDefault();
                                
                                // Validasi field wajib
                                const requiredFields = [
                                    'nama_pendamping',
                                    'phone',
                                    'alamat_sekretariat',
                                    'coordinate_lat',
                                    'coordinate_lng',
                                    'luas_areal_ha',
                                    'jumlah_anggota',
                                    'jumlah_pertemuan_tahunan'
                                ];

                                let isValid = true;
                                let firstError = null;

                                requiredFields.forEach(field => {
                                    const input = document.getElementById(field);
                                    if (!input.value.trim()) {
                                        input.classList.add('is-invalid');
                                        isValid = false;
                                        if (!firstError) firstError = input;
                                    } else {
                                        input.classList.remove('is-invalid');
                                    }
                                });

                                // Validasi data tanaman
                                const plantItems = document.querySelectorAll('.plant-item');
                                if (plantItems.length === 0) {
                                    alert('Minimal satu jenis tanaman harus diisi!');
                                    isValid = false;
                                } else {
                                    plantItems.forEach((item, index) => {
                                        const requiredPlantFields = [
                                            `plants[${index}][jenis_tanaman]`,
                                            `plants[${index}][tipe]`,
                                            `plants[${index}][jumlah_pohon]`,
                                            `plants[${index}][tahun_tanam]`
                                        ];

                                        requiredPlantFields.forEach(fieldName => {
                                            const input = item.querySelector(`[name="${fieldName}"]`);
                                            if (!input.value.trim()) {
                                                input.classList.add('is-invalid');
                                                isValid = false;
                                                if (!firstError) firstError = input;
                                            } else {
                                                input.classList.remove('is-invalid');
                                            }
                                        });
                                    });
                                }

                                if (isValid) {
                                    this.submit();
                                } else if (firstError) {
                                    firstError.focus();
                                }
                            });
                            </script>
                            @endpush

                    <!-- Enhanced Plant Species Section -->
                    <div class="section-card">
                        <h5 class="section-title">
                            <i class="fas fa-seedling"></i>Data Tanaman
                        </h5>
                        <p class="text-muted mb-4">
                            <i class="fas fa-info-circle me-1"></i>
                            Tambahkan minimal satu jenis tanaman yang dikelola KTHR Anda untuk melengkapi profil.
                        </p>
                        
                        <div id="plantsContainer">
                            <div class="plant-item">
                                <div class="plant-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="plant-title mb-0">
                                            <i class="fas fa-leaf me-2"></i>
                                            Tanaman #1
                                        </h6>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                                <i class="fas fa-seedling me-1"></i>Aktif
                                            </span>
                                            <button type="button" class="btn btn-sm btn-remove-plant remove-plant"
                                                    style="display: none;" title="Hapus Tanaman">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="plant-content">
                                    <div class="row g-3">
                                        <!-- Informasi Dasar Tanaman -->
                                        <div class="col-12">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control" id="jenis_tanaman_0"
                                                            name="plants[0][jenis_tanaman]" placeholder="Masukkan jenis tanaman" required>
                                                        <label for="jenis_tanaman_0">
                                                            <i class="fas fa-tree me-1 text-success"></i>
                                                            Jenis Tanaman <span class="text-danger">*</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <select class="form-select @error('plants.0.tipe') is-invalid @enderror" 
                                                                id="tipe_0" name="plants[0][tipe]" required>
                                                            <option value="">Pilih Tipe Tanaman</option>
                                                            <option value="Kayu">🌳 Kayu</option>
                                                            <option value="Bukan Kayu">🌿 Bukan Kayu</option>
                                                        </select>
                                                        <label for="tipe_0">
                                                            <i class="fas fa-tags me-1 text-primary"></i>
                                                            Tipe <span class="text-danger">*</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Data Kuantitatif -->
                                        <div class="col-12">
                                            <div class="card border-0 bg-light">
                                                <div class="card-body p-3">
                                                    <h6 class="card-title text-muted mb-3">
                                                        <i class="fas fa-chart-bar me-2"></i>Data Kuantitatif
                                                    </h6>
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <div class="form-floating">
                                                                <input type="number" class="form-control" id="jumlah_pohon_0"
                                                                    name="plants[0][jumlah_pohon]" min="1" placeholder="0" required>
                                                                <label for="jumlah_pohon_0">
                                                                    <i class="fas fa-calculator me-1 text-info"></i>
                                                                    Jumlah Pohon <span class="text-danger">*</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-floating">
                                                                <input type="number" class="form-control" id="tahun_tanam_0"
                                                                    name="plants[0][tahun_tanam]" min="1900" max="{{ date('Y') }}"
                                                                    placeholder="{{ date('Y') }}" required>
                                                                <label for="tahun_tanam_0">
                                                                    <i class="fas fa-calendar me-1 text-warning"></i>
                                                                    Tahun Tanam <span class="text-danger">*</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Upload Gambar -->
                                        <div class="col-12">
                                            <div class="card border-0 bg-primary-subtle">
                                                <div class="card-body p-3">
                                                    <h6 class="card-title text-primary mb-3">
                                                        <i class="fas fa-camera me-2"></i>Dokumentasi Tegakan
                                                    </h6>
                                                    <div class="upload-area">
                                                        <label for="gambar_tegakan_0" class="form-label d-block">
                                                            <i class="fas fa-image me-1 text-primary"></i>
                                                            Gambar Tegakan <span class="text-danger">*</span>
                                                        </label>
                                                        <div class="position-relative">
                                                            <input type="file" class="form-control" id="gambar_tegakan_0"
                                                                name="plants[0][gambar_tegakan]" 
                                                                accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                                                            <div class="form-text mt-2">
                                                                <i class="fas fa-info-circle me-1"></i>
                                                                <strong>Format:</strong> JPG, JPEG, PNG, GIF, WEBP | 
                                                                <strong>Ukuran Maks:</strong> 5MB
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <div class="text-center mt-4">
                            <button type="button" class="btn btn-add-plant" id="addPlant">
                                <i class="fas fa-plus me-2"></i>Tambah Tanaman Baru
                            </button>
                        </div>
                    </div>

                    <div class="form-actions">
                        <div class="d-flex justify-content-between align-items-center">
                            <button type="button" class="btn btn-outline-secondary btn-lg" onclick="window.history.back()">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </button>
                            <button type="submit" class="btn btn-submit">
                                <i class="fas fa-save me-2"></i>Simpan Profil Lengkap
                            </button>
                        </div>
                        <div class="text-center mt-3">
                            <small class="text-muted">
                                <i class="fas fa-shield-alt me-1"></i>
                                Data Anda akan disimpan dengan aman dan digunakan untuk proses kemitraan
                            </small>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                let plantIndex = 1;
                const plantsContainer = document.getElementById('plantsContainer');
                const addPlantBtn = document.getElementById('addPlant');

                addPlantBtn.addEventListener('click', function () {
                    const plantItem = document.createElement('div');
                    plantItem.className = 'plant-item';
                    plantItem.innerHTML = `
                        <div class="plant-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="plant-title mb-0">
                                    <i class="fas fa-leaf me-2"></i>
                                    Tanaman #${plantIndex + 1}
                                </h6>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                                        <i class="fas fa-seedling me-1"></i>Aktif
                                    </span>
                                    <button type="button" class="btn btn-sm btn-remove-plant remove-plant" title="Hapus Tanaman">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="plant-content">
                            <div class="row g-3">
                                <!-- Informasi Dasar Tanaman -->
                                <div class="col-12">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="text" class="form-control @error('plants.${plantIndex}.jenis_tanaman') is-invalid @enderror" 
                                                    id="jenis_tanaman_${plantIndex}" name="plants[${plantIndex}][jenis_tanaman]" 
                                                    placeholder="Masukkan jenis tanaman" required>
                                                <label for="jenis_tanaman_${plantIndex}">
                                                    <i class="fas fa-tree me-1 text-success"></i>
                                                    Jenis Tanaman <span class="text-danger">*</span>
                                                </label>
                                                <div class="invalid-feedback">Jenis tanaman harus diisi</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <select class="form-select @error('plants.${plantIndex}.tipe') is-invalid @enderror" 
                                                        id="tipe_${plantIndex}" name="plants[${plantIndex}][tipe]" required>
                                                    <option value="">Pilih Tipe Tanaman</option>
                                                    <option value="Kayu">🌳 Kayu</option>
                                                    <option value="Bukan Kayu">🌿 Bukan Kayu</option>
                                                </select>
                                                <label for="tipe_${plantIndex}">
                                                    <i class="fas fa-tags me-1 text-primary"></i>
                                                    Tipe <span class="text-danger">*</span>
                                                </label>
                                                <div class="invalid-feedback">Tipe tanaman harus dipilih</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Data Kuantitatif -->
                                <div class="col-12">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body p-3">
                                            <h6 class="card-title text-muted mb-3">
                                                <i class="fas fa-chart-bar me-2"></i>Data Kuantitatif
                                            </h6>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <input type="number" class="form-control @error('plants.${plantIndex}.jumlah_pohon') is-invalid @enderror" 
                                                            id="jumlah_pohon_${plantIndex}" name="plants[${plantIndex}][jumlah_pohon]" 
                                                            min="1" placeholder="0" required>
                                                        <label for="jumlah_pohon_${plantIndex}">
                                                            <i class="fas fa-calculator me-1 text-info"></i>
                                                            Jumlah Pohon <span class="text-danger">*</span>
                                                        </label>
                                                        <div class="invalid-feedback">Jumlah pohon harus diisi</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <input type="number" class="form-control @error('plants.${plantIndex}.tahun_tanam') is-invalid @enderror" 
                                                            id="tahun_tanam_${plantIndex}" name="plants[${plantIndex}][tahun_tanam]" 
                                                            min="1900" max="${new Date().getFullYear()}" placeholder="${new Date().getFullYear()}" required>
                                                        <label for="tahun_tanam_${plantIndex}">
                                                            <i class="fas fa-calendar me-1 text-warning"></i>
                                                            Tahun Tanam <span class="text-danger">*</span>
                                                        </label>
                                                        <div class="invalid-feedback">Tahun tanam harus diisi</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Upload Gambar -->
                                <div class="col-12">
                                    <div class="card border-0 bg-primary-subtle">
                                        <div class="card-body p-3">
                                            <h6 class="card-title text-primary mb-3">
                                                <i class="fas fa-camera me-2"></i>Dokumentasi Tegakan
                                            </h6>
                                            <div class="upload-area">
                                                <label for="gambar_tegakan_${plantIndex}" class="form-label d-block">
                                                    <i class="fas fa-image me-1 text-primary"></i>
                                                    Gambar Tegakan <span class="text-danger">*</span>
                                                </label>
                                                <div class="position-relative">
                                                    <input type="file" class="form-control @error('plants.${plantIndex}.gambar_tegakan') is-invalid @enderror" 
                                                        id="gambar_tegakan_${plantIndex}" name="plants[${plantIndex}][gambar_tegakan]" 
                                                        accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                                                    <div class="form-text mt-2">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        <strong>Format:</strong> JPG, JPEG, PNG, GIF, WEBP | 
                                                        <strong>Ukuran Maks:</strong> 5MB
                                                    </div>
                                                    <div class="invalid-feedback">Format file tidak sesuai</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        `;

                    plantsContainer.appendChild(plantItem);
                    
                    // Add event listeners to new inputs for progress tracking
                    const newInputs = plantItem.querySelectorAll('input, select, textarea');
                    newInputs.forEach(input => {
                        input.addEventListener('input', updateProgress);
                        input.addEventListener('change', updateProgress);
                        
                        // Add file validation for image inputs
                        if (input.type === 'file' && input.accept.includes('image')) {
                            input.addEventListener('change', function() {
                                validateImageFile(this);
                            });
                        }
                    });
                    
                    plantIndex++;
                    updateRemoveButtons();
                    updateProgress();
                });

                // Handle remove plant
                plantsContainer.addEventListener('click', function (e) {
                    if (e.target.closest('.remove-plant')) {
                        e.target.closest('.plant-item').remove();
                        updateRemoveButtons();
                        updateProgress();
                    }
                });

                function updateRemoveButtons() {
                    const plantItems = plantsContainer.querySelectorAll('.plant-item');
                    plantItems.forEach((item, index) => {
                        const removeBtn = item.querySelector('.remove-plant');
                        if (plantItems.length > 1) {
                            removeBtn.style.display = 'block';
                        } else {
                            removeBtn.style.display = 'none';
                        }

                        // Update numbering
                        const title = item.querySelector('.plant-title');
                        title.innerHTML = `<i class="fas fa-leaf me-2"></i>Tanaman #${index + 1}`;
                    });
                }
            });
        </script>
    @endpush
@endsection