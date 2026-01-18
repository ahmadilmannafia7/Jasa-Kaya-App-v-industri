@extends('layouts.pbphh')

@section('title', 'Eksplorasi Partner Industri - JASA KAYA')

@section('dashboard-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-primary mb-1">Eksplorasi Partner Industri</h2>
        <p class="text-muted mb-0">Temukan partner PBPHH untuk kolaborasi bisnis</p>
    </div>
    <a href="{{ route('pbphh.industry.partnerships') }}" class="btn btn-outline-primary">
        <i class="fas fa-list me-2"></i>Kemitraan Saya
    </a>
</div>

<!-- Filter Section -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-filter me-2"></i>Filter Pencarian
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('pbphh.industry.partners') }}" id="filterForm">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="search" class="form-label">Nama Perusahaan</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Cari nama perusahaan...">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="jenis_produk" class="form-label">Jenis Produk</label>
                        <input type="text" class="form-control" id="jenis_produk" name="jenis_produk" 
                               value="{{ request('jenis_produk') }}" placeholder="Contoh: Furniture, Plywood">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="min_capacity" class="form-label">Kapasitas Min (m³)</label>
                        <input type="number" class="form-control" id="min_capacity" name="min_capacity" 
                               value="{{ request('min_capacity') }}" placeholder="Kapasitas produksi minimal">
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i>Cari
                </button>
                <a href="{{ route('pbphh.industry.partners') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-redo me-2"></i>Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Partners Grid -->
<div class="row">
    @forelse($partners as $partner)
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-100 shadow-sm hover-shadow">
            <div class="card-body">
                <div class="d-flex align-items-start mb-3">
                    <div class="flex-grow-1">
                        <h5 class="card-title fw-bold mb-1">{{ $partner->company_name }}</h5>
                        <p class="text-muted small mb-0">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            {{ $partner->user->region->name ?? 'N/A' }}
                        </p>
                    </div>
                    <span class="badge bg-success">Verified</span>
                </div>

                <div class="mb-3">
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="border rounded p-2 text-center">
                                <small class="text-muted d-block">Kapasitas</small>
                                <strong>{{ number_format($partner->kapasitas_izin_produksi_m3, 0) }} m³</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2 text-center">
                                <small class="text-muted d-block">Karyawan</small>
                                <strong>{{ number_format($partner->jumlah_karyawan, 0) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <p class="text-muted small mb-1"><strong>Produk Utama:</strong></p>
                    <span class="badge bg-primary">{{ $partner->jenis_produk_utama }}</span>
                </div>

                @if($partner->materialNeeds->count() > 0)
                <div class="mb-3">
                    <p class="text-muted small mb-1"><strong>Kebutuhan Material:</strong></p>
                    <div class="d-flex flex-wrap gap-1">
                        @foreach($partner->materialNeeds->take(3) as $need)
                        <span class="badge bg-secondary small">{{ $need->jenis_kayu }}</span>
                        @endforeach
                        @if($partner->materialNeeds->count() > 3)
                        <span class="badge bg-light text-dark small">+{{ $partner->materialNeeds->count() - 3 }} lainnya</span>
                        @endif
                    </div>
                </div>
                @endif

                @if($partner->deskripsi_perusahaan)
                <p class="text-muted small mb-3">
                    {{ Str::limit($partner->deskripsi_perusahaan, 100) }}
                </p>
                @endif

                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-primary flex-grow-1" 
                            onclick="showPartnerDetail({{ $partner->pbphh_id }})">
                        <i class="fas fa-eye me-1"></i>Detail
                    </button>
                    <button type="button" class="btn btn-sm btn-primary flex-grow-1" 
                            onclick="requestPartnership({{ $partner->pbphh_id }}, '{{ $partner->company_name }}')">
                        <i class="fas fa-handshake me-1"></i>Ajukan
                    </button>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Tidak ada partner ditemukan</h5>
                <p class="text-muted">Coba ubah filter pencarian Anda</p>
            </div>
        </div>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($partners->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $partners->links() }}
</div>
@endif

<!-- Modal Detail Partner -->
<div class="modal fade" id="partnerDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Partner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="partnerDetailContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Request Partnership -->
<div class="modal fade" id="requestPartnershipModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="partnershipRequestForm" method="POST" action="{{ route('pbphh.industry.request') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Ajukan Kemitraan Industri</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="partner_pbphh_id" id="partner_pbphh_id">
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Anda akan mengajukan kemitraan dengan: <strong id="partnerName"></strong>
                    </div>

                    <div class="mb-3">
                        <label for="partnership_type" class="form-label">Jenis Kemitraan <span class="text-danger">*</span></label>
                        <select class="form-select" id="partnership_type" name="partnership_type" required>
                            <option value="">Pilih Jenis Kemitraan</option>
                            <option value="Pasokan Material">Pasokan Material</option>
                            <option value="Kapasitas Produksi">Kapasitas Produksi</option>
                            <option value="Joint Venture">Joint Venture</option>
                            <option value="Distribusi">Distribusi</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi Kemitraan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="description" name="description" rows="4" 
                                  placeholder="Jelaskan detail kemitraan yang Anda inginkan..." required></textarea>
                        <small class="text-muted">Maksimal 1000 karakter</small>
                    </div>

                    <div class="row" id="materialFields" style="display: none;">
                        <div class="col-md-6 mb-3">
                            <label for="material_type" class="form-label">Jenis Material</label>
                            <input type="text" class="form-control" id="material_type" name="material_type" 
                                   placeholder="Contoh: Kayu Jati">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="volume_needed_m3" class="form-label">Volume Dibutuhkan (m³)</label>
                            <input type="number" class="form-control" id="volume_needed_m3" name="volume_needed_m3" 
                                   step="0.01" min="0" placeholder="0.00">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="duration_months" class="form-label">Durasi Kemitraan</label>
                        <input type="text" class="form-control" id="duration_months" name="duration_months" 
                               placeholder="Contoh: 12 bulan, 2 tahun">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i>Kirim Permintaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function showPartnerDetail(partnerId) {
    const modal = new bootstrap.Modal(document.getElementById('partnerDetailModal'));
    modal.show();
    
    fetch(`/pbphh/industry-partners/${partnerId}/detail`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const partner = data.data;
                let materialNeedsHtml = '';
                
                if (partner.material_needs && partner.material_needs.length > 0) {
                    materialNeedsHtml = '<ul class="list-group">';
                    partner.material_needs.forEach(need => {
                        materialNeedsHtml += `
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>${need.jenis_kayu} (${need.tipe})</span>
                                <span class="badge bg-primary">${need.kebutuhan_bulanan_m3} m³/bulan</span>
                            </li>
                        `;
                    });
                    materialNeedsHtml += '</ul>';
                } else {
                    materialNeedsHtml = '<p class="text-muted">Tidak ada data kebutuhan material</p>';
                }
                
                const content = `
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Nama Perusahaan</h6>
                            <p class="fw-bold">${partner.company_name}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Penanggung Jawab</h6>
                            <p>${partner.penanggung_jawab}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Kontak</h6>
                            <p>${partner.phone || '-'}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Region</h6>
                            <p>${partner.region_name || 'N/A'}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Kapasitas Produksi</h6>
                            <p class="fw-bold">${partner.kapasitas_izin_produksi_m3.toLocaleString()} m³</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Produk Utama</h6>
                            <p>${partner.jenis_produk_utama}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Tahun Berdiri</h6>
                            <p>${partner.tahun_berdiri}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Jumlah Karyawan</h6>
                            <p>${partner.jumlah_karyawan} orang</p>
                        </div>
                        ${partner.website ? `
                        <div class="col-md-12 mb-3">
                            <h6 class="text-muted">Website</h6>
                            <p><a href="${partner.website}" target="_blank">${partner.website}</a></p>
                        </div>
                        ` : ''}
                        <div class="col-md-12 mb-3">
                            <h6 class="text-muted">Deskripsi</h6>
                            <p>${partner.deskripsi_perusahaan || '-'}</p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <h6 class="text-muted">Kebutuhan Material</h6>
                            ${materialNeedsHtml}
                        </div>
                    </div>
                `;
                
                document.getElementById('partnerDetailContent').innerHTML = content;
            }
        })
        .catch(error => {
            document.getElementById('partnerDetailContent').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Gagal memuat data partner
                </div>
            `;
        });
}

function requestPartnership(partnerId, partnerName) {
    document.getElementById('partner_pbphh_id').value = partnerId;
    document.getElementById('partnerName').textContent = partnerName;
    
    const modal = new bootstrap.Modal(document.getElementById('requestPartnershipModal'));
    modal.show();
}

// Show/hide material fields based on partnership type
document.getElementById('partnership_type')?.addEventListener('change', function() {
    const materialFields = document.getElementById('materialFields');
    if (this.value === 'Pasokan Material') {
        materialFields.style.display = 'block';
    } else {
        materialFields.style.display = 'none';
    }
});
</script>
@endpush

@push('styles')
<style>
.hover-shadow {
    transition: all 0.3s ease;
}
.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
</style>
@endpush
