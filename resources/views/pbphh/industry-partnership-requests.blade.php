@extends('layouts.pbphh')

@section('title', 'Permintaan Kemitraan Masuk - JASA KAYA')

@section('dashboard-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-primary mb-1">Permintaan Kemitraan Masuk</h2>
        <p class="text-muted mb-0">Kelola permintaan kemitraan dari partner industri</p>
    </div>
    <a href="{{ route('pbphh.industry.partnerships') }}" class="btn btn-outline-primary">
        <i class="fas fa-arrow-left me-2"></i>Kembali ke Kemitraan
    </a>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h3 class="fw-bold mb-0">{{ $requests->where('status', 'Terkirim')->count() }}</h3>
                <p class="mb-0">Menunggu Respon</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h3 class="fw-bold mb-0">{{ $requests->where('status', 'Disetujui')->count() }}</h3>
                <p class="mb-0">Disetujui</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <h3 class="fw-bold mb-0">{{ $requests->where('status', 'Ditolak')->count() }}</h3>
                <p class="mb-0">Ditolak</p>
            </div>
        </div>
    </div>
</div>

<!-- Requests List -->
@forelse($requests as $partnership)
<div class="card mb-3 {{ $partnership->status === 'Terkirim' ? 'border-warning' : '' }}">
    <div class="card-body">
        <div class="row">
            <div class="col-md-9">
                <div class="d-flex align-items-start mb-3">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <h5 class="mb-0">
                                <i class="fas fa-building me-2 text-primary"></i>
                                {{ $partnership->requester->company_name }}
                            </h5>
                            @if($partnership->status === 'Terkirim')
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-clock me-1"></i>Baru
                            </span>
                            @endif
                        </div>
                        <p class="text-muted small mb-0">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            {{ $partnership->requester->user->region->name ?? 'N/A' }}
                            <span class="mx-2">•</span>
                            <i class="fas fa-calendar me-1"></i>
                            {{ $partnership->created_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                    <span class="badge {{ $partnership->status_badge }} fs-6">
                        {{ $partnership->status }}
                    </span>
                </div>

                <!-- Partnership Details -->
                <div class="card bg-light mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <small class="text-muted d-block">Jenis Kemitraan</small>
                                <span class="badge bg-primary">{{ $partnership->partnership_type }}</span>
                            </div>
                            @if($partnership->material_type)
                            <div class="col-md-6 mb-2">
                                <small class="text-muted d-block">Material</small>
                                <span class="badge bg-secondary">{{ $partnership->material_type }}</span>
                            </div>
                            @endif
                            @if($partnership->volume_needed_m3)
                            <div class="col-md-6 mb-2">
                                <small class="text-muted d-block">Volume Dibutuhkan</small>
                                <strong>{{ $partnership->formatted_volume }}</strong>
                            </div>
                            @endif
                            @if($partnership->duration_months)
                            <div class="col-md-6 mb-2">
                                <small class="text-muted d-block">Durasi</small>
                                <strong>{{ $partnership->duration_months }}</strong>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <h6 class="text-muted mb-2">Deskripsi Kemitraan:</h6>
                    <p class="mb-0">{{ $partnership->description }}</p>
                </div>

                <!-- Company Info -->
                <div class="row g-3">
                    <div class="col-md-4">
                        <small class="text-muted d-block">Penanggung Jawab</small>
                        <strong>{{ $partnership->requester->penanggung_jawab }}</strong>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Kontak</small>
                        <strong>{{ $partnership->requester->phone ?? '-' }}</strong>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Produk Utama</small>
                        <strong>{{ $partnership->requester->jenis_produk_utama }}</strong>
                    </div>
                </div>

                <!-- Material Needs -->
                @if($partnership->requester->materialNeeds->count() > 0)
                <div class="mt-3">
                    <small class="text-muted d-block mb-2">Kebutuhan Material Partner:</small>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($partnership->requester->materialNeeds->take(5) as $need)
                        <span class="badge bg-info text-dark">
                            {{ $need->jenis_kayu }}: {{ $need->kebutuhan_bulanan_m3 }} m³/bulan
                        </span>
                        @endforeach
                        @if($partnership->requester->materialNeeds->count() > 5)
                        <span class="badge bg-light text-dark">
                            +{{ $partnership->requester->materialNeeds->count() - 5 }} lainnya
                        </span>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <div class="col-md-3">
                @if($partnership->status === 'Terkirim')
                <div class="d-flex flex-column gap-2">
                    <button class="btn btn-success btn-sm" 
                            onclick="respond({{ $partnership->partnership_id }}, 'approve', '{{ $partnership->requester->company_name }}')">
                        <i class="fas fa-check me-1"></i>Setujui
                    </button>
                    <button class="btn btn-outline-danger btn-sm" 
                            onclick="respond({{ $partnership->partnership_id }}, 'reject', '{{ $partnership->requester->company_name }}')">
                        <i class="fas fa-times me-1"></i>Tolak
                    </button>
                    <button class="btn btn-outline-primary btn-sm" 
                            onclick="viewCompanyDetail({{ $partnership->requester->pbphh_id }})">
                        <i class="fas fa-eye me-1"></i>Detail Perusahaan
                    </button>
                </div>
                @elseif($partnership->status === 'Disetujui')
                <div class="alert alert-success mb-0">
                    <i class="fas fa-check-circle me-2"></i>
                    Anda telah menyetujui permintaan ini
                </div>
                @elseif($partnership->status === 'Ditolak')
                <div class="alert alert-danger mb-0">
                    <i class="fas fa-times-circle me-2"></i>
                    Anda telah menolak permintaan ini
                    @if($partnership->rejection_reason)
                    <hr>
                    <small><strong>Alasan:</strong><br>{{ $partnership->rejection_reason }}</small>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@empty
<div class="card">
    <div class="card-body text-center py-5">
        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
        <h5 class="text-muted">Tidak ada permintaan kemitraan</h5>
        <p class="text-muted">Permintaan dari partner akan muncul di sini</p>
    </div>
</div>
@endforelse

<!-- Pagination -->
@if($requests->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $requests->links() }}
</div>
@endif

<!-- Modal Respond -->
<div class="modal fade" id="respondModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="respondForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="respondModalTitle">Respond Partnership</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" id="respond_action">
                    
                    <div id="approveConfirm" style="display: none;">
                        <div class="alert alert-success">
                            <i class="fas fa-info-circle me-2"></i>
                            Anda akan menyetujui permintaan dari: <strong id="companyName"></strong>
                        </div>
                        <p>Setelah disetujui, Anda dapat melakukan negosiasi lebih lanjut atau langsung mengaktifkan kemitraan.</p>
                    </div>
                    
                    <div id="rejectReasonField" style="display: none;">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Anda akan menolak permintaan dari: <strong id="companyNameReject"></strong>
                        </div>
                        <label for="rejection_reason" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="rejection_reason" id="rejection_reason" rows="4" 
                                  placeholder="Jelaskan alasan penolakan dengan jelas..."></textarea>
                        <small class="text-muted">Alasan ini akan dilihat oleh partner</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="respondSubmitBtn">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Company Detail -->
<div class="modal fade" id="companyDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Perusahaan Partner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="companyDetailContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function respond(partnershipId, action, companyName) {
    const modal = new bootstrap.Modal(document.getElementById('respondModal'));
    const form = document.getElementById('respondForm');
    const actionInput = document.getElementById('respond_action');
    const rejectField = document.getElementById('rejectReasonField');
    const approveConfirm = document.getElementById('approveConfirm');
    const modalTitle = document.getElementById('respondModalTitle');
    const submitBtn = document.getElementById('respondSubmitBtn');
    
    form.action = `/pbphh/industry-partnerships/${partnershipId}/respond`;
    actionInput.value = action;
    
    if (action === 'reject') {
        modalTitle.textContent = 'Tolak Permintaan Kemitraan';
        rejectField.style.display = 'block';
        approveConfirm.style.display = 'none';
        submitBtn.className = 'btn btn-danger';
        submitBtn.innerHTML = '<i class="fas fa-times me-2"></i>Tolak';
        document.getElementById('rejection_reason').setAttribute('required', 'required');
        document.getElementById('companyNameReject').textContent = companyName;
    } else {
        modalTitle.textContent = 'Setujui Permintaan Kemitraan';
        rejectField.style.display = 'none';
        approveConfirm.style.display = 'block';
        submitBtn.className = 'btn btn-success';
        submitBtn.innerHTML = '<i class="fas fa-check me-2"></i>Setujui';
        document.getElementById('rejection_reason').removeAttribute('required');
        document.getElementById('rejection_reason').value = ''; // Clear the value
        document.getElementById('companyName').textContent = companyName;
    }
    
    modal.show();
}

function viewCompanyDetail(pbphhId) {
    const modal = new bootstrap.Modal(document.getElementById('companyDetailModal'));
    modal.show();
    
    fetch(`/pbphh/industry-partners/${pbphhId}/detail`)
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
                            <h6 class="text-muted">Alamat</h6>
                            <p>${partner.alamat_perusahaan}</p>
                        </div>
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
                
                document.getElementById('companyDetailContent').innerHTML = content;
            }
        })
        .catch(error => {
            document.getElementById('companyDetailContent').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Gagal memuat data perusahaan
                </div>
            `;
        });
}
</script>
@endpush
