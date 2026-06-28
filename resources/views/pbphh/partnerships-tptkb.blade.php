@extends('layouts.pbphh')

@section('title', 'Kemitraan - JASA KAYA')

@section('dashboard-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-primary mb-1">Manajemen Kemitraan</h2>
        <p class="text-muted mb-0">Kelola semua permintaan dan kemitraan Anda</p>
    </div>
    <a href="{{ route('pbphh.explore') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Ajukan Kemitraan Baru
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($partnerships->count() > 0)
            @foreach($partnerships as $partnership)
            <div class="card mb-3 border-start border-primary border-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="fw-bold text-primary">{{ $partnership->tptkb->tptkb_name }}</h5>
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <small class="text-muted">Jenis Kayu:</small>
                                    <div class="fw-bold">{{ $partnership->wood_type }}</div>
                                </div>
                                <div class="col-sm-6">
                                    <small class="text-muted">Volume per Bulan:</small>
                                    <div class="fw-bold">{{ number_format($partnership->monthly_volume_m3, 2) }} m³</div>
                                </div>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted">Email TPTKB:</small>
                                <div>{{ $partnership->tptkb->user->email }}</div>
                            </div>
                            @if($partnership->additional_notes)
                            <div class="mb-2">
                                <small class="text-muted">Catatan:</small>
                                <div>{{ $partnership->additional_notes }}</div>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-4 text-md-end">
                            <span class="badge {{ $partnership->status_badge }} fs-6 mb-2">
                                {{ $partnership->status }}
                            </span>
                            <div class="text-muted small">
                                Diajukan: {{ $partnership->created_at ? $partnership->created_at->format('d M Y') : '-' }}
                            </div>
                            @if($partnership->status === 'Ditolak' && $partnership->rejection_reason)
                            <button type="button" class="btn btn-sm btn-outline-info mt-2" 
                                    onclick="showRejectionReason('{{ addslashes($partnership->rejection_reason) }}')">
                                <i class="fas fa-info-circle me-1"></i>Lihat Alasan
                            </button>
                            @endif
                        </div>
                    </div>

                    @if($partnership->pertemuan)
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-success">
                                <i class="fas fa-calendar-alt me-2"></i>Informasi Pertemuan
                            </h6>
                            <div class="mb-1">
                                <small class="text-muted">Jadwal:</small>
                                <div>{{ $partnership->pertemuan->scheduled_time ? $partnership->pertemuan->scheduled_time->format('d M Y, H:i') : '-' }}</div>
                            </div>
                            <div class="mb-1">
                                <small class="text-muted">Metode:</small>
                                <div>
                                    <i class="fas fa-{{ $partnership->pertemuan->method === 'online' ? 'video' : 'map-marker-alt' }} me-1"></i>
                                    {{ $partnership->pertemuan->method === 'online' ? 'Online' : 'Lapangan' }}
                                </div>
                            </div>
                            <div class="mb-1">
                                <small class="text-muted">Lokasi:</small>
                                <div>{{ $partnership->pertemuan->location }}</div>
                            </div>
                            @if($partnership->pertemuan->notes)
                            <div class="mb-1">
                                <small class="text-muted">Catatan:</small>
                                <div>{{ $partnership->pertemuan->notes }}</div>
                            </div>
                            @endif
                        </div>

                        @if($partnership->pertemuan->kesepakatan)
                        <div class="col-md-6">
                            <h6 class="text-info">
                                <i class="fas fa-handshake me-2"></i>Detail Kesepakatan
                            </h6>
                            <div class="mb-1">
                                <small class="text-muted">Harga per m³:</small>
                                <div class="fw-bold text-success">Rp {{ number_format($partnership->pertemuan->kesepakatan->agreed_price_per_m3, 0, ',', '.') }}</div>
                            </div>
                            <div class="mb-1">
                                <small class="text-muted">Mekanisme Pembayaran:</small>
                                <div>{{ $partnership->pertemuan->kesepakatan->payment_mechanism }}</div>
                            </div>
                            <div class="mb-1">
                                <small class="text-muted">Jadwal Pengiriman:</small>
                                <div>{{ $partnership->pertemuan->kesepakatan->delivery_schedule }}</div>
                            </div>
                            @if($partnership->pertemuan->kesepakatan->other_notes)
                            <div class="mb-1">
                                <small class="text-muted">Catatan Lain:</small>
                                <div>{{ $partnership->pertemuan->kesepakatan->other_notes }}</div>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>

                    @if($partnership->pertemuan->kesepakatan)
                    <hr>
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <div class="me-4">
                                    <i class="fas fa-{{ $partnership->pertemuan->kesepakatan->signed_by_pbphh_at ? 'check-circle text-success' : 'clock text-warning' }} fa-2x"></i>
                                    <div class="small text-center mt-1">
                                        {{ $partnership->pertemuan->kesepakatan->signed_by_pbphh_at ? 'Ditandatangani' : 'Menunggu' }}
                                        <br><strong>PBPHH</strong>
                                    </div>
                                </div>
                                <div class="me-4">
                                    <i class="fas fa-{{ $partnership->pertemuan->kesepakatan->signed_by_tptkb_at ? 'check-circle text-success' : 'clock text-warning' }} fa-2x"></i>
                                    <div class="small text-center mt-1">
                                        {{ $partnership->pertemuan->kesepakatan->signed_by_tptkb_at ? 'Ditandatangani' : 'Menunggu' }}
                                        <br><strong>TPTKB</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            @if(!$partnership->pertemuan->kesepakatan->signed_by_pbphh_at)
                                <button type="button" class="btn btn-success" 
                                        onclick="signAgreement('{{ addslashes($partnership->request_id) }}')">
                                    <i class="fas fa-signature me-2"></i>Tanda Tangan Digital
                                </button>
                            @elseif($partnership->pertemuan->kesepakatan->signed_by_pbphh_at && $partnership->pertemuan->kesepakatan->signed_by_tptkb_at)
                                <div class="alert alert-success mb-0">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <strong>Kesepakatan Lengkap!</strong><br>
                                    <small>Ditandatangani pada {{ ($partnership->pertemuan->kesepakatan->signed_by_pbphh_at && is_object($partnership->pertemuan->kesepakatan->signed_by_pbphh_at)) ? $partnership->pertemuan->kesepakatan->signed_by_pbphh_at->format('d M Y, H:i') : '-' }}</small>
                                </div>
                                @if($partnership->pertemuan->kesepakatan->final_document_path)
                                    <a href="{{ Storage::url($partnership->pertemuan->kesepakatan->final_document_path) }}" target="_blank" class="btn btn-outline-primary btn-sm mt-2">
                                        <i class="fas fa-download me-1"></i>Unduh Dokumen Kerjasama
                                    </a>
                                @else
                                    <button type="button" class="btn btn-outline-secondary btn-sm mt-2" disabled>
                                        <i class="fas fa-download me-1"></i>Dokumen Tidak Tersedia
                                    </button>
                                @endif
                            @else
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-clock me-2"></i>
                                    <strong>Menunggu Tanda Tangan TPTKB</strong><br>
                                    <small>Anda telah menandatangani pada {{ ($partnership->pertemuan->kesepakatan->signed_by_pbphh_at && is_object($partnership->pertemuan->kesepakatan->signed_by_pbphh_at)) ? $partnership->pertemuan->kesepakatan->signed_by_pbphh_at->format('d M Y, H:i') : '-' }}</small>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif
                    @endif
                </div>
            </div>
            @endforeach
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $partnerships->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-handshake fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">Belum Ada Kemitraan</h5>
                <p class="text-muted">Mulai dengan mengajukan kemitraan kepada TPTKB yang sesuai</p>
                <a href="{{ route('pbphh.explore') }}" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i>Cari TPTKB
                </a>
            </div>
        @endif
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

@push('scripts')
<script>
function signAgreement(requestId) {
    if (confirm('Apakah Anda yakin ingin menandatangani kesepakatan ini? Tindakan ini tidak dapat dibatalkan.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/pbphh/partnerships/${requestId}/sign`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}

function showRejectionReason(reason) {
    document.getElementById('rejectionReasonText').textContent = reason;
    const modal = new bootstrap.Modal(document.getElementById('rejectionReasonModal'));
    modal.show();
}
</script>
@endpush
@endsection
