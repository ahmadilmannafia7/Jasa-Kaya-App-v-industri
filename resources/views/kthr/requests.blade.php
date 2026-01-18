@extends('layouts.kthr')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/kthr-dashboard.css') }}">
@endpush

@section('title', 'Permintaan Masuk - JASA KAYA')

@section('dashboard-content')
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-primary mb-1">Permintaan Kerjasama</h2>
        <p class="text-muted mb-0">Kelola permintaan kerjasama dari industri PBPHH</p>
    </div>
    <div>
        <span class="badge bg-light text-dark">
            <i class="fas fa-inbox me-1"></i>
            {{ $requests->total() }} Total Permintaan
        </span>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($requests->count() > 0)
            <div class="row">
                @foreach($requests as $request)
                <div class="col-12 mb-4">
                    <div class="card border-start border-primary border-3">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="icon-wrapper me-3">
                                                    <i class="fas fa-building text-primary"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted">Perusahaan</small>
                                                    <div class="fw-bold">{{ $request->pbphhProfile->company_name }}</div>
                                                    <small class="text-muted">{{ $request->pbphhProfile->user->email }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="icon-wrapper me-3">
                                                    <i class="fas fa-tree text-success"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted">Kebutuhan</small>
                                                    <div class="fw-bold text-success">{{ $request->wood_type }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="icon-wrapper me-3">
                                                    <i class="fas fa-cube text-info"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted">Volume per Bulan</small>
                                                    <div class="fw-bold text-info">{{ number_format($request->monthly_volume_m3, 2) }} m³</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="icon-wrapper me-3">
                                                    <i class="fas fa-calendar text-warning"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted">Tanggal Permintaan</small>
                                                    <div class="fw-bold">{{ $request->created_at->format('d M Y, H:i') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-lg-end">
                                    <div class="mb-3">
                                        <span class="badge {{ $request->status_badge }} fs-6">
                                            {{ $request->status }}
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column gap-2">
                                        <!-- Tombol Lihat Profil Perusahaan -->
                                        <button type="button" class="btn btn-outline-primary" 
                                                onclick="showCompanyProfile('{{ $request->pbphh_id }}')">
                                            <i class="fas fa-building me-2"></i>Lihat Profil Perusahaan
                                        </button>
                                        
                                        @if($request->status === 'Terkirim')
                                            <button type="button" class="btn btn-success" 
                                                    onclick="respondToRequest('{{ $request->request_id }}', 'approve')">
                                                <i class="fas fa-check me-2"></i>Setujui Permintaan
                                            </button>
                                            <button type="button" class="btn btn-outline-danger" 
                                                    onclick="showRejectModal('{{ $request->request_id }}')">
                                                <i class="fas fa-times me-2"></i>Tolak Permintaan
                                            </button>
                                        @elseif($request->status === 'Ditolak' && $request->rejection_reason)
                                            <button type="button" class="btn btn-outline-info" 
                                                    onclick="showRejectionReason(@json($request->rejection_reason))">
                                                <i class="fas fa-info-circle me-2"></i>Lihat Alasan Penolakan
                                            </button>
                                        @elseif($request->status === 'Disetujui')
                                            <div class="alert alert-success mb-0">
                                                <i class="fas fa-check-circle me-2"></i>
                                                <strong>Permintaan Disetujui</strong>
                                            </div>
                                            <button type="button" class="btn btn-outline-danger mt-2" 
                                                    onclick="cancelRequest('{{ $request->request_id }}')">
                                                <i class="fas fa-times-circle me-2"></i>Batalkan Permintaan
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $requests->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-inbox fa-4x"></i>
                <h5>Belum Ada Permintaan</h5>
                <p>Permintaan kerjasama dari industri akan muncul di sini</p>
                <div class="mt-3">
                    <small class="text-muted">Pastikan status kesiapan KTHR sudah diaktifkan di dashboard</small>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tolak Permintaan Kerjasama</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <input type="hidden" name="action" value="reject">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" 
                                  rows="4" required placeholder="Jelaskan alasan penolakan permintaan ini..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-2"></i>Tolak Permintaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Rejection Reason Modal -->
<div class="modal fade" id="rejectionReasonModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Alasan Penolakan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="rejectionReasonText"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Company Profile Modal -->
<div class="modal fade" id="companyProfileModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-building me-2"></i>Profil Perusahaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="companyProfileContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Memuat profil perusahaan...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function respondToRequest(requestId, action) {
    if (action === 'approve') {
        if (confirm('Apakah Anda yakin ingin menyetujui permintaan ini?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/kthr/requests/' + requestId + '/respond';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'approve';
            
            form.appendChild(csrfToken);
            form.appendChild(actionInput);
            document.body.appendChild(form);
            form.submit();
        }
    }
}

function cancelRequest(requestId) {
    if (confirm('Apakah Anda yakin ingin membatalkan permintaan kerjasama ini? Tindakan ini tidak dapat dibatalkan.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/kthr/requests/' + requestId + '/cancel';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}

function showRejectModal(requestId) {
    const form = document.getElementById('rejectForm');
    form.action = '/kthr/requests/' + requestId + '/respond';
    
    const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    modal.show();
}

function showRejectionReason(reason) {
    document.getElementById('rejectionReasonText').textContent = reason;
    const modal = new bootstrap.Modal(document.getElementById('rejectionReasonModal'));
    modal.show();
}

function showCompanyProfile(pbphhId) {
    const modal = new bootstrap.Modal(document.getElementById('companyProfileModal'));
    const content = document.getElementById('companyProfileContent');
    
    // Show loading state
    content.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Memuat profil perusahaan...</p>
        </div>
    `;
    
    modal.show();
    
    // Fetch company profile data
    fetch(`/kthr/company-profile/${pbphhId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const company = data.data;
                content.innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary mb-3"><i class="fas fa-info-circle me-2"></i>Informasi Perusahaan</h6>
                            <table class="table table-sm">
                                <tr><td class="fw-semibold">Nama Perusahaan</td><td>${company.company_name}</td></tr>
                                <tr><td class="fw-semibold">Direktur</td><td>${company.director_name || '-'}</td></tr>
                                <tr><td class="fw-semibold">Email</td><td>${company.email || '-'}</td></tr>
                                <tr><td class="fw-semibold">Telepon</td><td>${company.phone || '-'}</td></tr>
                                <tr><td class="fw-semibold">Alamat</td><td>${company.address || '-'}</td></tr>
                                <tr><td class="fw-semibold">Wilayah</td><td>${company.region_name || '-'}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold text-success mb-3"><i class="fas fa-industry me-2"></i>Kebutuhan Bahan Baku</h6>
                            ${company.material_needs && company.material_needs.length > 0 ? 
                                `<div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Jenis</th>
                                                <th>Tipe</th>
                                                <th>Kebutuhan/Bulan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${company.material_needs.map(need => `
                                                <tr>
                                                    <td>${need.wood_type}</td>
                                                    <td><span class="badge ${need.material_type === 'Kayu' ? 'bg-success' : 'bg-info'} badge-sm">${need.material_type}</span></td>
                                                    <td>${parseFloat(need.monthly_volume_m3).toFixed(2)} m³</td>
                                                </tr>
                                            `).join('')}
                                        </tbody>
                                    </table>
                                </div>` : 
                                '<p class="text-muted">Belum ada data kebutuhan bahan baku</p>'
                            }
                            
                            <h6 class="fw-bold text-warning mb-2 mt-4"><i class="fas fa-flag me-2"></i>Status Verifikasi</h6>
                            <div class="mb-3">
                                <span class="badge ${company.verification_status === 'Approved' ? 'bg-success' : company.verification_status === 'Pending' ? 'bg-warning' : 'bg-danger'} fs-6">
                                    ${company.verification_status === 'Approved' ? 'Terverifikasi' : company.verification_status === 'Pending' ? 'Menunggu Verifikasi' : 'Ditolak'}
                                </span>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                content.innerHTML = '<div class="text-center py-4"><i class="fas fa-exclamation-triangle fa-2x text-warning"></i><br><p class="mt-2 text-muted">Gagal memuat profil perusahaan</p></div>';
            }
        })
        .catch(error => {
            console.error('Error fetching company profile:', error);
            content.innerHTML = '<div class="text-center py-4"><i class="fas fa-exclamation-triangle fa-2x text-danger"></i><br><p class="mt-2 text-muted">Terjadi kesalahan saat memuat data</p></div>';
        });
}
</script>
@endpush
@endsection
