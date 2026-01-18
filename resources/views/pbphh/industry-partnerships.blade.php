@extends('layouts.pbphh')

@section('title', 'Kemitraan Industri - JASA KAYA')

@section('dashboard-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-primary mb-1">Kemitraan Industri</h2>
        <p class="text-muted mb-0">Kelola kemitraan dengan sesama PBPHH</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('pbphh.industry.partnership.requests') }}" class="btn btn-outline-primary">
            <i class="fas fa-inbox me-2"></i>Permintaan Masuk
            @if($receivedPartnerships->where('status', 'Terkirim')->count() > 0)
            <span class="badge bg-danger">{{ $receivedPartnerships->where('status', 'Terkirim')->count() }}</span>
            @endif
        </a>
        <a href="{{ route('pbphh.industry.partners') }}" class="btn btn-primary">
            <i class="fas fa-search me-2"></i>Cari Partner
        </a>
    </div>
</div>

<!-- Tabs -->
<ul class="nav nav-tabs mb-4" id="partnershipTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="sent-tab" data-bs-toggle="tab" data-bs-target="#sent" type="button">
            <i class="fas fa-paper-plane me-2"></i>Permintaan Terkirim ({{ $sentPartnerships->count() }})
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="received-tab" data-bs-toggle="tab" data-bs-target="#received" type="button">
            <i class="fas fa-inbox me-2"></i>Permintaan Diterima ({{ $receivedPartnerships->count() }})
        </button>
    </li>
</ul>

<!-- Tab Content -->
<div class="tab-content" id="partnershipTabsContent">
    <!-- Sent Partnerships -->
    <div class="tab-pane fade show active" id="sent" role="tabpanel">
        @forelse($sentPartnerships as $partnership)
        <div class="card mb-3">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-start mb-2">
                            <div class="flex-grow-1">
                                <h5 class="mb-1">
                                    <i class="fas fa-building me-2 text-primary"></i>
                                    {{ $partnership->partner->company_name }}
                                </h5>
                                <p class="text-muted small mb-2">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    {{ $partnership->partner->user->region->name ?? 'N/A' }}
                                </p>
                            </div>
                            <span class="badge {{ $partnership->status_badge }}">
                                {{ $partnership->status }}
                            </span>
                        </div>

                        <div class="mb-2">
                            <span class="badge bg-primary me-2">{{ $partnership->partnership_type }}</span>
                            @if($partnership->material_type)
                            <span class="badge bg-secondary">{{ $partnership->material_type }}</span>
                            @endif
                            @if($partnership->volume_needed_m3)
                            <span class="badge bg-info text-dark">{{ $partnership->formatted_volume }}</span>
                            @endif
                        </div>

                        <p class="mb-2">{{ Str::limit($partnership->description, 150) }}</p>

                        @if($partnership->duration_months)
                        <p class="text-muted small mb-0">
                            <i class="fas fa-calendar me-1"></i>Durasi: {{ $partnership->duration_months }}
                        </p>
                        @endif

                        @if($partnership->negotiation_notes)
                        <div class="alert alert-info small mt-2 mb-0">
                            <strong>Catatan Negosiasi:</strong> {{ $partnership->negotiation_notes }}
                        </div>
                        @endif

                        @if($partnership->rejection_reason)
                        <div class="alert alert-danger small mt-2 mb-0">
                            <strong>Alasan:</strong> {{ $partnership->rejection_reason }}
                        </div>
                        @endif
                    </div>

                    <div class="col-md-4 text-end">
                        <p class="text-muted small mb-2">
                            Dikirim: {{ $partnership->created_at->format('d M Y') }}
                        </p>

                        @if($partnership->isCancellable())
                        <div class="d-flex flex-column gap-2">
                            @if($partnership->isNegotiable())
                            <button class="btn btn-sm btn-outline-primary" 
                                    onclick="negotiate({{ $partnership->partnership_id }})">
                                <i class="fas fa-comments me-1"></i>Negosiasi
                            </button>
                            @endif
                            
                            @if(in_array($partnership->status, ['Disetujui', 'Dalam Negosiasi']))
                            <button class="btn btn-sm btn-success" 
                                    onclick="finalize({{ $partnership->partnership_id }})">
                                <i class="fas fa-check me-1"></i>Aktifkan
                            </button>
                            @endif

                            <button class="btn btn-sm btn-outline-danger" 
                                    onclick="cancelPartnership({{ $partnership->partnership_id }})">
                                <i class="fas fa-times me-1"></i>Batalkan
                            </button>
                        </div>
                        @elseif($partnership->status === 'Aktif')
                        <span class="badge bg-success">
                            <i class="fas fa-check-circle me-1"></i>Kemitraan Aktif
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-paper-plane fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada permintaan terkirim</h5>
                <p class="text-muted">Mulai cari partner dan ajukan kemitraan</p>
                <a href="{{ route('pbphh.industry.partners') }}" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i>Cari Partner
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Received Partnerships -->
    <div class="tab-pane fade" id="received" role="tabpanel">
        @forelse($receivedPartnerships as $partnership)
        <div class="card mb-3">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-start mb-2">
                            <div class="flex-grow-1">
                                <h5 class="mb-1">
                                    <i class="fas fa-building me-2 text-primary"></i>
                                    {{ $partnership->requester->company_name }}
                                </h5>
                                <p class="text-muted small mb-2">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    {{ $partnership->requester->user->region->name ?? 'N/A' }}
                                </p>
                            </div>
                            <span class="badge {{ $partnership->status_badge }}">
                                {{ $partnership->status }}
                            </span>
                        </div>

                        <div class="mb-2">
                            <span class="badge bg-primary me-2">{{ $partnership->partnership_type }}</span>
                            @if($partnership->material_type)
                            <span class="badge bg-secondary">{{ $partnership->material_type }}</span>
                            @endif
                            @if($partnership->volume_needed_m3)
                            <span class="badge bg-info text-dark">{{ $partnership->formatted_volume }}</span>
                            @endif
                        </div>

                        <p class="mb-2">{{ Str::limit($partnership->description, 150) }}</p>

                        @if($partnership->duration_months)
                        <p class="text-muted small mb-0">
                            <i class="fas fa-calendar me-1"></i>Durasi: {{ $partnership->duration_months }}
                        </p>
                        @endif

                        @if($partnership->negotiation_notes)
                        <div class="alert alert-info small mt-2 mb-0">
                            <strong>Catatan Negosiasi:</strong> {{ $partnership->negotiation_notes }}
                        </div>
                        @endif

                        @if($partnership->rejection_reason && $partnership->status === 'Ditolak')
                        <div class="alert alert-danger small mt-2 mb-0">
                            <strong>Alasan Penolakan:</strong> {{ $partnership->rejection_reason }}
                        </div>
                        @endif
                    </div>

                    <div class="col-md-4 text-end">
                        <p class="text-muted small mb-2">
                            Diterima: {{ $partnership->created_at->format('d M Y') }}
                        </p>

                        @if($partnership->status === 'Terkirim')
                        <div class="d-flex flex-column gap-2">
                            <button class="btn btn-sm btn-success" 
                                    onclick="respond({{ $partnership->partnership_id }}, 'approve')">
                                <i class="fas fa-check me-1"></i>Setujui
                            </button>
                            <button class="btn btn-sm btn-outline-danger" 
                                    onclick="respond({{ $partnership->partnership_id }}, 'reject')">
                                <i class="fas fa-times me-1"></i>Tolak
                            </button>
                        </div>
                        @elseif($partnership->isNegotiable())
                        <div class="d-flex flex-column gap-2">
                            <button class="btn btn-sm btn-outline-primary" 
                                    onclick="negotiate({{ $partnership->partnership_id }})">
                                <i class="fas fa-comments me-1"></i>Negosiasi
                            </button>
                            
                            @if(in_array($partnership->status, ['Disetujui', 'Dalam Negosiasi']))
                            <button class="btn btn-sm btn-success" 
                                    onclick="finalize({{ $partnership->partnership_id }})">
                                <i class="fas fa-check me-1"></i>Aktifkan
                            </button>
                            @endif
                        </div>
                        @elseif($partnership->status === 'Aktif')
                        <span class="badge bg-success">
                            <i class="fas fa-check-circle me-1"></i>Kemitraan Aktif
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada permintaan masuk</h5>
                <p class="text-muted">Permintaan kemitraan dari partner akan muncul di sini</p>
            </div>
        </div>
        @endforelse
    </div>
</div>

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
                    <div id="rejectReasonField" style="display: none;">
                        <label for="rejection_reason" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="rejection_reason" id="rejection_reason" rows="3" 
                                  placeholder="Jelaskan alasan penolakan..."></textarea>
                    </div>
                    <div id="approveConfirm" style="display: none;">
                        <p>Apakah Anda yakin ingin menyetujui permintaan kemitraan ini?</p>
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

<!-- Modal Negotiate -->
<div class="modal fade" id="negotiateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="negotiateForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Negosiasi Kemitraan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="negotiation_notes" class="form-label">Catatan Negosiasi <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="negotiation_notes" id="negotiation_notes" rows="4" 
                                  placeholder="Masukkan catatan negosiasi..." required></textarea>
                        <small class="text-muted">Maksimal 2000 karakter</small>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="neg_volume_needed_m3" class="form-label">Volume (m³)</label>
                            <input type="number" class="form-control" name="volume_needed_m3" 
                                   id="neg_volume_needed_m3" step="0.01" placeholder="0.00">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="neg_duration_months" class="form-label">Durasi</label>
                            <input type="text" class="form-control" name="duration_months" 
                                   id="neg_duration_months" placeholder="Contoh: 12 bulan">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Simpan Negosiasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function respond(partnershipId, action) {
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
        modalTitle.textContent = 'Tolak Permintaan';
        rejectField.style.display = 'block';
        approveConfirm.style.display = 'none';
        submitBtn.className = 'btn btn-danger';
        submitBtn.textContent = 'Tolak';
        document.getElementById('rejection_reason').setAttribute('required', 'required');
    } else {
        modalTitle.textContent = 'Setujui Permintaan';
        rejectField.style.display = 'none';
        approveConfirm.style.display = 'block';
        submitBtn.className = 'btn btn-success';
        submitBtn.textContent = 'Setujui';
        document.getElementById('rejection_reason').removeAttribute('required');
        document.getElementById('rejection_reason').value = ''; // Clear the value
    }
    
    modal.show();
}

function negotiate(partnershipId) {
    const modal = new bootstrap.Modal(document.getElementById('negotiateModal'));
    const form = document.getElementById('negotiateForm');
    form.action = `/pbphh/industry-partnerships/${partnershipId}/negotiate`;
    modal.show();
}

function finalize(partnershipId) {
    if (confirm('Apakah Anda yakin ingin mengaktifkan kemitraan ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/pbphh/industry-partnerships/${partnershipId}/finalize`;
        
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);
        
        document.body.appendChild(form);
        form.submit();
    }
}

function cancelPartnership(partnershipId) {
    if (confirm('Apakah Anda yakin ingin membatalkan permintaan ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/pbphh/industry-partnerships/${partnershipId}/cancel`;
        
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
