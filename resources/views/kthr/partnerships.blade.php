@extends('layouts.kthr')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/kthr-dashboard.css') }}">
@endpush

@section('title', 'Kemitraan - JASA KAYA')

@section('dashboard-content')
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-primary mb-1">Manajemen Kemitraan</h2>
        <p class="text-muted mb-0">Kelola kemitraan yang telah disetujui dan kesepakatan</p>
    </div>
    <div>
        <span class="badge bg-light text-dark">
            <i class="fas fa-handshake me-1"></i>
            {{ $partnerships->total() }} Total Kemitraan
        </span>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($partnerships->count() > 0)
            <div class="row">
                @foreach($partnerships as $partnership)
                    <div class="col-lg-6 mb-4">
                        <div class="card partnership-card mb-4 shadow-sm">
                            <div class="card-body p-4">
                                <div class="partnership-status">
                                    @if($partnership->status == 'active')
                                        <span class="badge bg-light text-dark border"><i class="fas fa-check-circle me-1"></i>Aktif</span>
                                    @elseif($partnership->status == 'completed')
                                        <span class="badge bg-light text-dark border"><i class="fas fa-flag-checkered me-1"></i>Selesai</span>
                                    @else
                                        <span class="badge bg-light text-dark border"><i class="fas fa-clock me-1"></i>{{ ucfirst($partnership->status) }}</span>
                                    @endif
                                </div>
                                
                                <!-- Company Header -->
                                <div class="d-flex align-items-center mb-4">
                                    <div class="company-avatar me-3">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <i class="fas fa-building"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1 fw-bold text-dark">{{ $partnership->pbphhProfile->company_name }}</h5>
                                        <p class="text-muted mb-0"><i class="fas fa-envelope me-1"></i>{{ $partnership->pbphhProfile->user->email }}</p>
                                    </div>
                                </div>
                                
                                <!-- Basic Info -->
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <div class="info-card p-3 bg-light rounded">
                                            <div class="d-flex align-items-center">
                                                <div class="icon-wrapper bg-light text-dark rounded-circle me-3 border" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-tree"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block mb-1">Jenis Kayu</small>
                                                    <span class="fw-bold text-dark">{{ $partnership->wood_type }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-card p-3 bg-light rounded">
                                            <div class="d-flex align-items-center">
                                                <div class="icon-wrapper bg-info text-white rounded-circle me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-cube"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block mb-1">Volume Bulanan</small>
                                                    <span class="fw-bold text-dark">{{ number_format($partnership->monthly_volume_m3, 2) }} m³</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @if($partnership->pertemuan)
                                <!-- Meeting Information -->
                                <div class="meeting-info mb-4">
                                    <div class="section-header mb-3">
                                        <h6 class="fw-bold mb-0 text-dark">
                                            <i class="fas fa-calendar-check me-2"></i>Informasi Pertemuan
                                        </h6>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="meeting-detail-card p-3 border rounded text-center">
                                                <div class="icon-wrapper bg-light text-dark rounded-circle mx-auto mb-2 border" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-calendar"></i>
                                </div>
                                                <small class="text-muted d-block mb-1">Tanggal</small>
                                                <span class="fw-bold text-dark">{{ ($partnership->pertemuan->scheduled_time && is_object($partnership->pertemuan->scheduled_time)) ? $partnership->pertemuan->scheduled_time->format('d M Y') : '-' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="meeting-detail-card p-3 border rounded text-center">
                                                <div class="icon-wrapper bg-light text-dark rounded-circle mx-auto mb-2 border" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-clock"></i>
                                </div>
                                                <small class="text-muted d-block mb-1">Waktu</small>
                                                <span class="fw-bold text-dark">{{ ($partnership->pertemuan->scheduled_time && is_object($partnership->pertemuan->scheduled_time)) ? $partnership->pertemuan->scheduled_time->format('H:i') : '-' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="meeting-detail-card p-3 border rounded text-center">
                                                @if($partnership->pertemuan->method === 'online')
                                                    <div class="icon-wrapper bg-light text-dark rounded-circle mx-auto mb-2 border" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-video"></i>
                                    </div>
                                                    <small class="text-muted d-block mb-1">Online</small>
                                                @else
                                                    <div class="icon-wrapper bg-light text-dark rounded-circle mx-auto mb-2 border" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fas fa-map-marker-alt"></i>
                                                    </div>
                                                    <small class="text-muted d-block mb-1">Lapangan</small>
                                                @endif
                                                <span class="fw-bold text-dark">{{ $partnership->pertemuan->location }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @if($partnership->pertemuan->kesepakatan)
                                <!-- Agreement Details -->
                                <div class="agreement-details mb-4">
                                    <div class="section-header mb-3">
                                        <h6 class="fw-bold mb-0 text-dark">
                                            <i class="fas fa-handshake me-2"></i>Detail Kesepakatan
                                        </h6>
                                    </div>
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <div class="agreement-card p-3 border rounded bg-light">
                                                <div class="d-flex align-items-center">
                                                    <div class="icon-wrapper bg-light text-dark rounded-circle me-3 border" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fas fa-money-bill-wave"></i>
                                                    </div>
                                                    <div>
                                                        <small class="text-muted d-block mb-1">Harga Disepakati</small>
                                                        <span class="fw-bold text-dark fs-6">Rp {{ number_format($partnership->pertemuan->kesepakatan->agreed_price_per_m3, 0, ',', '.') }}/m³</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="agreement-card p-3 border rounded bg-light">
                                                <div class="d-flex align-items-center">
                                                    <div class="icon-wrapper bg-light text-dark rounded-circle me-3 border" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-credit-card"></i>
                                    </div>
                                                    <div>
                                                        <small class="text-muted d-block mb-1">Pembayaran</small>
                                                        <span class="fw-bold text-dark">{{ $partnership->pertemuan->kesepakatan->payment_mechanism }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <div class="agreement-card p-3 border rounded bg-light">
                                                <div class="d-flex align-items-center">
                                                    <div class="icon-wrapper bg-light text-dark rounded-circle me-3 border" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-truck"></i>
                                    </div>
                                                    <div>
                                                        <small class="text-muted d-block mb-1">Jadwal Pengiriman</small>
                                                        <span class="fw-bold text-dark">{{ $partnership->pertemuan->kesepakatan->delivery_schedule }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if($partnership->pertemuan->kesepakatan->other_notes)
                                        <div class="alert alert-info mb-0">
                                            <div class="d-flex align-items-start">
                                                <i class="fas fa-sticky-note me-2 mt-1"></i>
                                                <div>
                                                    <small class="fw-bold d-block mb-1">Catatan Tambahan:</small>
                                                    <span class="small">{{ $partnership->pertemuan->kesepakatan->other_notes }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if($partnership->pertemuan->kesepakatan->final_document_path)
                                        <div class="mt-3">
                                            <a href="{{ Storage::url($partnership->pertemuan->kesepakatan->final_document_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-file-download me-2"></i>Unduh Dokumen Kerjasama
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Signature Status -->
                                <div class="signature-section mt-4 pt-4 border-top">
                                    <div class="section-header mb-3">
                                        <h6 class="fw-bold mb-0 text-dark">
                                            <i class="fas fa-signature me-2"></i>Status Penandatanganan
                                        </h6>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="signature-card p-3 border rounded {{ $partnership->pertemuan->kesepakatan->signed_by_kthr_at ? 'border-light bg-light' : 'border-light bg-light' }}">
                                                <div class="d-flex align-items-center">
                                                    @if($partnership->pertemuan->kesepakatan->signed_by_kthr_at)
                                                        <div class="icon-wrapper bg-light text-dark rounded-circle me-3 border" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                                            <i class="fas fa-check-circle"></i>
                                                        </div>
                                                        <div>
                                                            <small class="text-muted d-block mb-1">KTHR</small>
                                                            <span class="fw-bold text-dark">Ditandatangani</span>
                                                        </div>
                                                    @else
                                                        <div class="icon-wrapper bg-light text-dark rounded-circle me-3 border" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                                        <div>
                                                            <small class="text-muted d-block mb-1">KTHR</small>
                                                            <span class="fw-bold text-muted">Menunggu</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="signature-card p-3 border rounded {{ $partnership->pertemuan->kesepakatan->signed_by_pbphh_at ? 'border-light bg-light' : 'border-light bg-light' }}">
                                                <div class="d-flex align-items-center">
                                                    @if($partnership->pertemuan->kesepakatan->signed_by_pbphh_at)
                                                        <div class="icon-wrapper bg-light text-dark rounded-circle me-3 border" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                                            <i class="fas fa-check-circle"></i>
                                                        </div>
                                                        <div>
                                                            <small class="text-muted d-block mb-1">PBPHH</small>
                                                            <span class="fw-bold text-dark">Ditandatangani</span>
                                                        </div>
                                                    @else
                                                        <div class="icon-wrapper bg-light text-dark rounded-circle me-3 border" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                                        <div>
                                                            <small class="text-muted d-block mb-1">PBPHH</small>
                                                            <span class="fw-bold text-muted">Menunggu</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Action Buttons and Status -->
                                    <div class="mt-4">
                                        @if(!$partnership->pertemuan->kesepakatan->signed_by_kthr_at)
                                            <div class="text-center">
                                                <button class="btn btn-primary btn-lg px-4 py-2 shadow-sm" onclick="signAgreement('{{ $partnership->request_id }}')">
                                                    <i class="fas fa-signature me-2"></i>Tanda Tangan Digital
                                                </button>
                                                <p class="text-muted mt-2 mb-0 small">Klik untuk menandatangani kesepakatan ini</p>
                                            </div>
                                        @elseif($partnership->pertemuan->kesepakatan->signed_by_kthr_at && $partnership->pertemuan->kesepakatan->signed_by_pbphh_at)
                                            <div class="alert alert-light border shadow-sm mb-0">
                                                <div class="d-flex align-items-center">
                                                    <div class="icon-wrapper bg-light text-dark rounded-circle me-3 border" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fas fa-check-circle"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="alert-heading mb-1 fw-bold">Kesepakatan Lengkap!</h6>
                                                        <small class="mb-0">Ditandatangani pada {{ ($partnership->pertemuan->kesepakatan->signed_by_kthr_at && is_object($partnership->pertemuan->kesepakatan->signed_by_kthr_at)) ? $partnership->pertemuan->kesepakatan->signed_by_kthr_at->format('d M Y, H:i') : '-' }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-light border shadow-sm mb-0">
                                                <div class="d-flex align-items-center">
                                                    <div class="icon-wrapper bg-light text-dark rounded-circle me-3 border" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fas fa-clock"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="alert-heading mb-1 fw-bold">Menunggu Tanda Tangan PBPHH</h6>
                                                        <small class="mb-0">Anda telah menandatangani pada {{ ($partnership->pertemuan->kesepakatan->signed_by_kthr_at && is_object($partnership->pertemuan->kesepakatan->signed_by_kthr_at)) ? $partnership->pertemuan->kesepakatan->signed_by_kthr_at->format('d M Y, H:i') : '-' }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $partnerships->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-handshake fa-4x"></i>
                <h5>Belum Ada Kemitraan</h5>
                <p>Kemitraan yang telah disetujui akan muncul di sini</p>
                <div class="mt-3">
                    <a href="{{ route('kthr.requests') }}" class="btn btn-primary">
                        <i class="fas fa-inbox me-2"></i>Lihat Permintaan Masuk
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
.partnership-card {
    transition: all 0.3s ease;
    border: none !important;
}

.partnership-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

.partnership-status {
    position: absolute;
    top: 15px;
    right: 15px;
    z-index: 10;
}

.company-avatar {
    position: relative;
}

.info-card, .agreement-card, .meeting-detail-card, .signature-card {
    transition: all 0.2s ease;
    border: 1px solid #e9ecef !important;
}

.info-card:hover, .agreement-card:hover, .meeting-detail-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.icon-wrapper {
    flex-shrink: 0;
}

.section-header {
    border-bottom: 2px solid #f8f9fa;
    padding-bottom: 8px;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #6c757d;
}

.empty-state i {
    color: #dee2e6;
    margin-bottom: 20px;
}

.alert {
    border-radius: 10px;
}

.btn {
    border-radius: 8px;
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

@media (max-width: 768px) {
    .partnership-card {
        margin-bottom: 20px;
    }
    
    .meeting-detail-card, .signature-card {
        margin-bottom: 15px;
    }
    
    .icon-wrapper {
        width: 30px !important;
        height: 30px !important;
        font-size: 12px;
    }
}
</style>
@endpush

@push('scripts')
<script>
function signAgreement(requestId) {
    if (confirm('Apakah Anda yakin ingin menandatangani kesepakatan ini? Tindakan ini tidak dapat dibatalkan.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/kthr/partnerships/' + requestId + '/sign';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
@endsection
