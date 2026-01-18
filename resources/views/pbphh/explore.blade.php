@extends('layouts.pbphh')

@section('title', 'Eksplorasi KTHR - JASA KAYA')

@section('dashboard-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-primary mb-1">Eksplorasi KTHR</h2>
        <p class="text-muted mb-0">Temukan mitra KTHR yang sesuai dengan kebutuhan Anda</p>
    </div>
</div>

<!-- Filter Section -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-filter me-2"></i>Filter Pencarian
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('pbphh.explore') }}" id="filterForm">
            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="search" class="form-label">Nama KTHR</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Cari nama KTHR...">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status Kesiapan</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Semua Status</option>
                            <option value="siap_mitra" {{ request('status') === 'siap_mitra' ? 'selected' : '' }}>Siap Bermitra</option>
                            <option value="siap_tebang" {{ request('status') === 'siap_tebang' ? 'selected' : '' }}>Siap Tebang</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="jenis_tanaman" class="form-label">Jenis Tanaman</label>
                        <input type="text" class="form-control" id="jenis_tanaman" name="jenis_tanaman" 
                               value="{{ request('jenis_tanaman') }}" placeholder="Contoh: Jati, Mahoni">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="tipe_tanaman" class="form-label">Tipe Tanaman</label>
                        <select class="form-select" id="tipe_tanaman" name="tipe_tanaman">
                            <option value="">Semua Tipe</option>
                            <option value="Kayu" {{ request('tipe_tanaman') === 'Kayu' ? 'selected' : '' }}>Kayu</option>
                            <option value="Bukan Kayu" {{ request('tipe_tanaman') === 'Bukan Kayu' ? 'selected' : '' }}>Bukan Kayu</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Filter Berdasarkan Lokasi</label>
                        <div class="input-group">
                            <input type="hidden" id="lat" name="lat" value="{{ request('lat') }}">
                            <input type="hidden" id="lng" name="lng" value="{{ request('lng') }}">
                            <input type="number" class="form-control" id="radius" name="radius" 
                                   value="{{ request('radius', 50) }}" placeholder="Radius (km)" min="1" max="500">
                            <button type="button" class="btn btn-outline-secondary" id="getCurrentLocation">
                                <i class="fas fa-crosshairs"></i>
                            </button>
                        </div>
                        <small class="text-muted">Klik tombol untuk menggunakan lokasi Anda saat ini</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Cari
                            </button>
                            <a href="{{ route('pbphh.explore') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-undo me-2"></i>Reset
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Map and Results -->
<div class="row">
    <!-- Map -->
    <div class="col-lg-5 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-map me-2"></i>Peta Lokasi KTHR
                </h5>
            </div>
            <div class="card-body p-0">
                <div id="map" style="height: 500px; width: 100%;"></div>
            </div>
        </div>
    </div>

    <!-- Results -->
    <div class="col-lg-7 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Daftar KTHR ({{ $kthrs->total() }} hasil)
                </h5>
                <div class="btn-group btn-group-sm" role="group">
                    <input type="radio" class="btn-check" name="viewMode" id="listView" checked>
                    <label class="btn btn-outline-primary" for="listView">
                        <i class="fas fa-list"></i>
                    </label>
                    <input type="radio" class="btn-check" name="viewMode" id="gridView">
                    <label class="btn btn-outline-primary" for="gridView">
                        <i class="fas fa-th-large"></i>
                    </label>
                </div>
            </div>
            <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                @if($kthrs->count() > 0)
                    <div id="kthrList">
                        @foreach($kthrs as $kthr)
                        <div class="kthr-item border rounded p-3 mb-3" data-kthr-id="{{ $kthr->kthr_id }}">
                            <div class="row">
                                <div class="col-md-8">
                                    <h6 class="fw-bold text-success">{{ $kthr->kthr_name }}</h6>
                                    <div class="d-flex gap-2 mb-2">
                                        @if($kthr->is_siap_mitra)
                                            <span class="badge bg-success">Siap Mitra</span>
                                        @endif
                                        @if($kthr->is_siap_tebang)
                                            <span class="badge bg-warning">Siap Tebang</span>
                                        @endif
                                    </div>
                                    <div class="small text-muted mb-1">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        Luas: {{ number_format($kthr->luas_areal_ha, 2) }} Ha | 
                                        {{ $kthr->jumlah_anggota }} Anggota
                                    </div>
                                    <div class="small text-muted mb-1">
                                        <i class="fas fa-user me-1"></i>
                                        Pendamping: {{ $kthr->nama_pendamping }}
                                    </div>
                                    @if($kthr->plantSpecies->count() > 0)
                                        <div class="small mb-2">
                                            <strong>Jenis Tanaman:</strong>
                                            @foreach($kthr->plantSpecies->take(3) as $plant)
                                                <span class="badge bg-light text-dark me-1">{{ $plant->jenis_tanaman }}</span>
                                            @endforeach
                                            @if($kthr->plantSpecies->count() > 3)
                                                <span class="text-muted">+{{ $kthr->plantSpecies->count() - 3 }} lainnya</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-4 text-end">
                                    <button type="button" class="btn btn-sm btn-outline-info mb-2 w-100 kthr-detail-btn" 
                                            data-kthr-id="{{ $kthr->kthr_id }}">
                                        <i class="fas fa-eye me-1"></i>Detail
                                    </button>
                                    <button type="button" class="btn btn-sm btn-primary w-100 partnership-btn" 
                                            data-kthr-id="{{ $kthr->kthr_id }}" data-kthr-name="{{ $kthr->kthr_name }}">
                                        <i class="fas fa-handshake me-1"></i>Ajukan Kemitraan
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $kthrs->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-search fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak Ada KTHR Ditemukan</h5>
                        <p class="text-muted">Coba ubah filter pencarian Anda</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Partnership Request Modal -->
<div class="modal fade" id="partnershipModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajukan Kemitraan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="partnershipForm" method="POST" action="{{ route('pbphh.request.partnership') }}">
                @csrf
                <input type="hidden" name="kthr_id" id="modalKthrId">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Anda akan mengajukan kemitraan dengan <strong id="modalKthrName"></strong>
                    </div>
                    
                    <div class="mb-3">
                        <label for="wood_type" class="form-label">Jenis Kayu yang Dibutuhkan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="wood_type" name="wood_type" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="monthly_volume_m3" class="form-label">Volume Kebutuhan per Bulan (m³) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="monthly_volume_m3" name="monthly_volume_m3" 
                               step="0.01" min="0.01" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="additional_notes" class="form-label">Catatan Tambahan</label>
                        <textarea class="form-control" id="additional_notes" name="additional_notes" 
                                  rows="3" placeholder="Spesifikasi khusus, jadwal pengiriman, dll."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="submitPartnershipBtn">
                        <i class="fas fa-paper-plane me-2"></i>Kirim Permintaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- KTHR Detail Modal -->
<div class="modal fade" id="kthrDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail KTHR</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="kthrDetailContent">
                <!-- Content will be loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
.kthr-item:hover {
    background-color: #f8f9fa;
    cursor: pointer;
}
.kthr-item.selected {
    border-color: #0d6efd !important;
    background-color: #e7f3ff;
}

/* Modal fixes */
.modal {
    z-index: 1055 !important;
}
.modal-dialog {
    z-index: 1060 !important;
}
.modal-content {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.25) !important; /* Shadow untuk kontras tanpa backdrop */
    border: 1px solid rgba(0, 0, 0, 0.125) !important; /* Border untuk definisi modal */
}

/* KTHR Detail Modal Styles */
#kthrDetailModal .modal-dialog {
    max-width: 900px;
}

#kthrDetailContent .table td {
    padding: 0.5rem 0.75rem;
    border-top: 1px solid #dee2e6;
}

#kthrDetailContent .table td:first-child {
    width: 40%;
    font-weight: 500;
    color: #6c757d;
}

.badge-sm {
    font-size: 0.75em;
    padding: 0.25em 0.5em;
}

#kthrDetailContent .table-responsive {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
}

#kthrDetailContent .table thead th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    font-size: 0.875rem;
}

/* Plant Image Styles */
.plant-image {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border: 2px solid #e9ecef;
}

.plant-image:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    border-color: #007bff;
}

#plantImageModal .modal-body {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
}

#plantImageDisplay {
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    transition: transform 0.3s ease;
}

#plantImageDisplay:hover {
    transform: scale(1.02);
}
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// Make functions globally accessible
window.showPartnershipModal = function(kthrId, kthrName) {
    try {
        console.log('showPartnershipModal called with:', kthrId, kthrName);
        
        const modalKthrId = document.getElementById('modalKthrId');
        const modalKthrName = document.getElementById('modalKthrName');
        const partnershipModal = document.getElementById('partnershipModal');
        
        if (!modalKthrId || !modalKthrName || !partnershipModal) {
            console.error('Modal elements not found');
            return;
        }
        
        modalKthrId.value = kthrId;
        modalKthrName.textContent = kthrName;
        
        // Check if bootstrap is available
        if (typeof bootstrap === 'undefined') {
            console.error('Bootstrap is not loaded');
            return;
        }
        
        const modal = new bootstrap.Modal(partnershipModal, {
            backdrop: false,
            keyboard: true
        });
        modal.show();
    } catch (error) {
        console.error('Error in showPartnershipModal:', error);
        alert('Terjadi kesalahan saat membuka modal. Silakan refresh halaman.');
    }
};

// Function to show plant image in modal
window.showPlantImage = function(imagePath, plantName) {
    // Create image modal if it doesn't exist
    let imageModal = document.getElementById('plantImageModal');
    if (!imageModal) {
        const modalHtml = `
            <div class="modal fade" id="plantImageModal" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Gambar Tegakan - <span id="plantImageTitle"></span></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img id="plantImageDisplay" src="" alt="" class="img-fluid" style="max-height: 500px; border-radius: 8px;">
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        imageModal = document.getElementById('plantImageModal');
    }
    
    // Set image and title
    document.getElementById('plantImageTitle').textContent = plantName;
    document.getElementById('plantImageDisplay').src = imagePath;
    document.getElementById('plantImageDisplay').alt = `Gambar tegakan ${plantName}`;
    
    // Show modal
    const modal = new bootstrap.Modal(imageModal);
    modal.show();
};

// Add function to properly close modal
window.closePartnershipModal = function() {
    console.log('Closing partnership modal');
    
    const modalElement = document.getElementById('partnershipModal');
    if (modalElement) {
        const modal = bootstrap.Modal.getInstance(modalElement);
        if (modal) {
            modal.hide();
        }
        
        // Force cleanup
        setTimeout(function() {
            modalElement.classList.remove('show');
            modalElement.style.display = 'none';
            modalElement.setAttribute('aria-hidden', 'true');
            modalElement.removeAttribute('aria-modal');
            
            // Remove backdrop
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
            
            // Reset body
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        }, 100);
    }
};

window.showKthrDetail = function(kthrId) {
    try {
        console.log('showKthrDetail called with:', kthrId);
        
        const kthrDetailModal = document.getElementById('kthrDetailModal');
        const content = document.getElementById('kthrDetailContent');
        
        if (!kthrDetailModal || !content) {
            console.error('Detail modal elements not found');
            return;
        }
        
        // Check if bootstrap is available
        if (typeof bootstrap === 'undefined') {
            console.error('Bootstrap is not loaded');
            return;
        }
        
        const modal = new bootstrap.Modal(kthrDetailModal, {
            backdrop: false,
            keyboard: true
        });
        content.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i><br><p class="mt-2 text-muted">Memuat data KTHR...</p></div>';
        modal.show();
        
        // Fetch actual data from API
        fetch(`/pbphh/kthr/${kthrId}/detail`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const kthr = data.data;
                
                // Build plant species badges
                let plantSpeciesHtml = '';
                if (kthr.plant_species && kthr.plant_species.length > 0) {
                    kthr.plant_species.forEach(plant => {
                        const badgeClass = plant.tipe === 'Kayu' ? 'bg-success' : 'bg-info';
                        plantSpeciesHtml += `<span class="badge ${badgeClass} me-1 mb-1">${plant.jenis_tanaman}</span>`;
                    });
                } else {
                    plantSpeciesHtml = '<span class="text-muted">Belum ada data tanaman</span>';
                }
                
                // Build status badges
                let statusHtml = '';
                if (kthr.is_siap_mitra) {
                    statusHtml += '<span class="badge bg-success me-1">Siap Mitra</span>';
                }
                if (kthr.is_siap_tebang) {
                    statusHtml += '<span class="badge bg-warning me-1">Siap Tebang</span>';
                }
                if (!kthr.is_siap_mitra && !kthr.is_siap_tebang) {
                    statusHtml = '<span class="badge bg-secondary">Belum Siap</span>';
                }
                
                // Build plant details table
                 let plantDetailsHtml = '';
                 if (kthr.plant_species && kthr.plant_species.length > 0) {
                     plantDetailsHtml = '<div class="table-responsive"><table class="table table-sm"><thead><tr><th>Jenis</th><th>Tipe</th><th>Jumlah</th><th>Tahun Tanam</th><th>Gambar</th></tr></thead><tbody>';
                     kthr.plant_species.forEach(plant => {
                         const imageHtml = plant.gambar_tegakan_path ? 
                             `<img src="/storage/${plant.gambar_tegakan_path}" alt="${plant.jenis_tanaman}" class="plant-image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; cursor: pointer;" onclick="showPlantImage('/storage/${plant.gambar_tegakan_path}', '${plant.jenis_tanaman}')" title="Klik untuk memperbesar">` : 
                             '<span class="text-muted small">Tidak ada gambar</span>';
                         plantDetailsHtml += `<tr><td>${plant.jenis_tanaman}</td><td><span class="badge ${plant.tipe === 'Kayu' ? 'bg-success' : 'bg-info'} badge-sm">${plant.tipe}</span></td><td>${plant.jumlah_pohon || '-'} pohon</td><td>${plant.tahun_tanam || '-'}</td><td>${imageHtml}</td></tr>`;
                     });
                     plantDetailsHtml += '</tbody></table></div>';
                 } else {
                     plantDetailsHtml = '<p class="text-muted">Belum ada data detail tanaman</p>';
                 }
                
                content.innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary mb-3"><i class="fas fa-info-circle me-2"></i>Informasi Umum</h6>
                            <table class="table table-sm">
                                <tr><td class="fw-semibold">Nama KTHR</td><td>${kthr.kthr_name}</td></tr>
                                <tr><td class="fw-semibold">Pendamping</td><td>${kthr.nama_pendamping || '-'}</td></tr>
                                <tr><td class="fw-semibold">Jumlah Anggota</td><td>${kthr.jumlah_anggota || '-'} orang</td></tr>
                                <tr><td class="fw-semibold">Luas Areal</td><td>${kthr.luas_areal_ha ? parseFloat(kthr.luas_areal_ha).toFixed(2) : '-'} Ha</td></tr>
                                <tr><td class="fw-semibold">Pertemuan/Tahun</td><td>${kthr.jumlah_pertemuan_tahunan || '-'} kali</td></tr>
                                <tr><td class="fw-semibold">Wilayah</td><td>${kthr.region_name || '-'}</td></tr>
                                <tr><td class="fw-semibold">Kontak</td><td>${kthr.phone || '-'}</td></tr>
                            </table>
                            
                            <h6 class="fw-bold text-primary mb-2 mt-4"><i class="fas fa-map-marker-alt me-2"></i>Lokasi</h6>
                            <p class="small text-muted mb-1">${kthr.alamat_sekretariat || 'Alamat tidak tersedia'}</p>
                            ${kthr.coordinate_lat && kthr.coordinate_lng ? 
                                `<p class="small text-muted">Koordinat: ${parseFloat(kthr.coordinate_lat).toFixed(6)}, ${parseFloat(kthr.coordinate_lng).toFixed(6)}</p>` : 
                                '<p class="small text-muted">Koordinat tidak tersedia</p>'
                            }
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold text-success mb-3"><i class="fas fa-seedling me-2"></i>Jenis Tanaman</h6>
                            <div class="mb-3">${plantSpeciesHtml}</div>
                            
                            <h6 class="fw-bold text-primary mb-2"><i class="fas fa-list me-2"></i>Detail Tanaman</h6>
                            ${plantDetailsHtml}
                            
                            <h6 class="fw-bold text-warning mb-2 mt-4"><i class="fas fa-flag me-2"></i>Status Kesiapan</h6>
                            <div class="mb-3">${statusHtml}</div>
                            
                            ${kthr.user_email ? `<h6 class="fw-bold text-info mb-2"><i class="fas fa-envelope me-2"></i>Kontak</h6><p class="small text-muted">${kthr.user_email}</p>` : ''}
                        </div>
                    </div>
                `;
            } else {
                content.innerHTML = '<div class="text-center py-4"><i class="fas fa-exclamation-triangle fa-2x text-warning"></i><br><p class="mt-2 text-muted">Gagal memuat data KTHR</p></div>';
            }
        })
        .catch(error => {
            console.error('Error fetching KTHR detail:', error);
            content.innerHTML = '<div class="text-center py-4"><i class="fas fa-exclamation-triangle fa-2x text-danger"></i><br><p class="mt-2 text-muted">Terjadi kesalahan saat memuat data</p></div>';
        });
    } catch (error) {
        console.error('Error in showKthrDetail:', error);
        alert('Terjadi kesalahan saat membuka detail. Silakan refresh halaman.');
    }
};

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded');
    
    // Force close any stuck modals on page load
    const stuckModal = document.getElementById('partnershipModal');
    if (stuckModal) {
        stuckModal.classList.remove('show');
        stuckModal.style.display = 'none';
        stuckModal.setAttribute('aria-hidden', 'true');
        stuckModal.removeAttribute('aria-modal');
        
        // Remove any existing backdrop
        const existingBackdrop = document.querySelector('.modal-backdrop');
        if (existingBackdrop) {
            existingBackdrop.remove();
        }
        
        // Reset body classes
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    }
    
    // Debug: Check if buttons exist
    const partnershipBtns = document.querySelectorAll('.partnership-btn');
    const detailBtns = document.querySelectorAll('.kthr-detail-btn');
    console.log('Found partnership buttons:', partnershipBtns.length);
    console.log('Found detail buttons:', detailBtns.length);
    
    // Test button click detection
    partnershipBtns.forEach(function(btn, index) {
        console.log('Partnership button', index, ':', btn.getAttribute('data-kthr-id'), btn.getAttribute('data-kthr-name'));
    });
    
    // Check if required elements exist
    const mapElement = document.getElementById('map');
    if (!mapElement) {
        console.error('Map element not found');
        return;
    }
    
    // Initialize map
    try {
        const map = L.map('map').setView([-2.5489, 118.0149], 5);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Add KTHR markers
        var mapDataJson = '{!! addslashes(json_encode($mapData ?? [])) !!}';
        var mapData = JSON.parse(mapDataJson);
        const markers = [];
        
        mapData.forEach(function(kthr) {
            if (kthr.coordinate_lat && kthr.coordinate_lng) {
                const marker = L.marker([kthr.coordinate_lat, kthr.coordinate_lng])
                    .addTo(map)
                    .bindPopup('<div class="text-center"><strong>' + kthr.kthr_name + '</strong><br><small>Luas: ' + kthr.luas_areal_ha + ' Ha</small><br><button class="btn btn-sm btn-primary mt-2" onclick="showKthrDetail(' + kthr.kthr_id + ')">Lihat Detail</button></div>');
                
                markers.push({
                    marker: marker,
                    kthr_id: kthr.kthr_id
                });
            }
        });

        // Highlight marker when KTHR item is hovered
        document.querySelectorAll('.kthr-item').forEach(function(item) {
            item.addEventListener('mouseenter', function() {
                const kthrId = parseInt(this.dataset.kthrId);
                const markerData = markers.find(function(m) { return m.kthr_id === kthrId; });
                if (markerData) {
                    markerData.marker.openPopup();
                }
            });
            
            item.addEventListener('click', function() {
                document.querySelectorAll('.kthr-item').forEach(function(i) { i.classList.remove('selected'); });
                this.classList.add('selected');
                
                const kthrId = parseInt(this.dataset.kthrId);
                const markerData = markers.find(function(m) { return m.kthr_id === kthrId; });
                if (markerData) {
                    map.setView(markerData.marker.getLatLng(), 12);
                    markerData.marker.openPopup();
                }
            });
        });

        // Get current location
        const getCurrentLocationBtn = document.getElementById('getCurrentLocation');
        if (getCurrentLocationBtn) {
            getCurrentLocationBtn.addEventListener('click', function() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        const latInput = document.getElementById('lat');
                        const lngInput = document.getElementById('lng');
                        if (latInput) latInput.value = position.coords.latitude;
                        if (lngInput) lngInput.value = position.coords.longitude;
                        
                        L.marker([position.coords.latitude, position.coords.longitude])
                            .addTo(map)
                            .bindPopup('Lokasi Anda')
                            .openPopup();
                        
                        map.setView([position.coords.latitude, position.coords.longitude], 10);
                    }, function(error) {
                        console.error('Geolocation error:', error);
                        alert('Tidak dapat mengakses lokasi Anda.');
                    });
                } else {
                    alert('Geolocation tidak didukung oleh browser ini.');
                }
            });
        }
    } catch (error) {
        console.error('Error initializing map:', error);
    }
    
    // Add form submission handler
    const partnershipForm = document.getElementById('partnershipForm');
    if (partnershipForm) {
        partnershipForm.addEventListener('submit', function(e) {
            console.log('Form submission attempted');
            
            const woodType = document.getElementById('wood_type');
            const monthlyVolume = document.getElementById('monthly_volume_m3');
            
            if (!woodType.value.trim()) {
                e.preventDefault();
                alert('Jenis Kayu yang Dibutuhkan harus diisi');
                woodType.focus();
                return false;
            }
            
            if (!monthlyVolume.value || monthlyVolume.value <= 0) {
                e.preventDefault();
                alert('Volume Kebutuhan per Bulan harus diisi dengan nilai yang valid');
                monthlyVolume.focus();
                return false;
            }
            
            console.log('Form validation passed, submitting...');
        });
    }
    
    // Add event delegation for KTHR buttons
    document.addEventListener('click', function(e) {
        console.log('Click detected on:', e.target);
        console.log('Target classes:', e.target.className);
        
        // Handle partnership button clicks
        if (e.target.closest('.partnership-btn')) {
            console.log('Partnership button clicked!');
            const btn = e.target.closest('.partnership-btn');
            const kthrId = btn.getAttribute('data-kthr-id');
            const kthrName = btn.getAttribute('data-kthr-name');
            console.log('Calling showPartnershipModal with:', kthrId, kthrName);
            showPartnershipModal(kthrId, kthrName);
            return;
        }
        
        // Handle detail button clicks
        if (e.target.closest('.kthr-detail-btn')) {
            console.log('Detail button clicked!');
            const btn = e.target.closest('.kthr-detail-btn');
            const kthrId = btn.getAttribute('data-kthr-id');
            console.log('Calling showKthrDetail with:', kthrId);
            showKthrDetail(kthrId);
            return;
        }
        
        // Handle form submit buttons
        if (e.target.matches('button[type="submit"]') && e.target.closest('#partnershipForm')) {
            console.log('Submit button clicked');
            e.stopPropagation();
        }
        
        if (e.target.matches('button[data-bs-dismiss="modal"]')) {
              console.log('Modal close button clicked');
              closePartnershipModal();
          }
     });
    
    // Add keydown event listener for ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const openModal = document.querySelector('.modal.show');
            if (openModal) {
                console.log('ESC key pressed, closing modal');
                closePartnershipModal();
            }
        }
    });
    
    // Specific handler for submit button to prevent backdrop interference
    const submitBtn = document.getElementById('submitPartnershipBtn');
    if (submitBtn) {
        submitBtn.addEventListener('click', function(e) {
            console.log('Submit button direct click');
            e.stopPropagation();
            
            // Validate form before submission
            const form = document.getElementById('partnershipForm');
            if (form && form.checkValidity()) {
                console.log('Form is valid, submitting...');
                form.submit();
            } else {
                console.log('Form validation failed');
                e.preventDefault();
            }
        });
    }
    
    // Debug modal events
    const partnershipModal = document.getElementById('partnershipModal');
    if (partnershipModal) {
        partnershipModal.addEventListener('shown.bs.modal', function() {
            console.log('Partnership modal shown');
        });
        
        partnershipModal.addEventListener('hidden.bs.modal', function() {
            console.log('Partnership modal hidden');
        });
    }
});
</script>
@endpush
@endsection
