@extends('layouts.dinas')

@section('title', 'Approval PBPHH - Dinas Kehutanan Provinsi')

@section('dashboard-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-user-check me-2"></i>Approval Akun PBPHH
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">

        <button type="button" class="btn btn-sm btn-primary" onclick="location.reload()">
            <i class="fas fa-sync-alt me-1"></i>Refresh
        </button>
    </div>
</div>

<!-- Statistics Summary -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="executive-card h-100">
            <div class="card-body text-center">
                <div class="d-flex align-items-center justify-content-center mb-2">
                    <div class="icon-circle bg-warning text-white me-3">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">{{ $users->where('approval_status', 'Pending')->count() }}</h3>
                        <small class="text-muted">Pending</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="executive-card h-100">
            <div class="card-body text-center">
                <div class="d-flex align-items-center justify-content-center mb-2">
                    <div class="icon-circle bg-success text-white me-3">
                        <i class="fas fa-check"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">{{ $users->where('approval_status', 'Approved')->count() }}</h3>
                        <small class="text-muted">Approved</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="executive-card h-100">
            <div class="card-body text-center">
                <div class="d-flex align-items-center justify-content-center mb-2">
                    <div class="icon-circle bg-danger text-white me-3">
                        <i class="fas fa-times"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">{{ $users->where('approval_status', 'Rejected')->count() }}</h3>
                        <small class="text-muted">Rejected</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="executive-card h-100">
            <div class="card-body text-center">
                <div class="d-flex align-items-center justify-content-center mb-2">
                    <div class="icon-circle bg-info text-white me-3">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">{{ $users->total() }}</h3>
                        <small class="text-muted">Total</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="executive-card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-filter me-2"></i>Filter & Pencarian
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('dinas.approvals') }}" class="row g-3">
            <div class="col-lg-2 col-md-4">
                <label for="status" class="form-label">
                    <i class="fas fa-flag me-1"></i>Status
                </label>
                <select class="form-select" id="status" name="status">
                    <option value="">Semua Status</option>
                    <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>
                        <i class="fas fa-clock"></i> Pending
                    </option>
                    <option value="Approved" {{ request('status') === 'Approved' ? 'selected' : '' }}>
                        <i class="fas fa-check"></i> Approved
                    </option>
                    <option value="Rejected" {{ request('status') === 'Rejected' ? 'selected' : '' }}>
                        <i class="fas fa-times"></i> Rejected
                    </option>
                </select>
            </div>
            <div class="col-lg-3 col-md-4">
                <label for="region_id" class="form-label">
                    <i class="fas fa-map-marker-alt me-1"></i>Wilayah
                </label>
                <select class="form-select" id="region_id" name="region_id">
                    <option value="">Semua Wilayah</option>
                    @foreach($regions as $region)
                        <option value="{{ $region->region_id }}" {{ request('region_id') == $region->region_id ? 'selected' : '' }}>
                            {{ $region->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-5 col-md-4">
                <label for="search" class="form-label">
                    <i class="fas fa-search me-1"></i>Pencarian
                </label>
                <div class="input-group">
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Email atau nama perusahaan...">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-lg-2 col-md-12">
                <label class="form-label">&nbsp;</label>
                <div class="d-grid gap-2">
                    <a href="{{ route('dinas.approvals') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-undo me-1"></i>Reset
                    </a>
                </div>
            </div>
        </form>
        
        @if(request()->hasAny(['status', 'region_id', 'search']))
            <div class="mt-3">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Menampilkan hasil filter:
                    @if(request('status'))
                        <span class="badge bg-secondary ms-1">Status: {{ request('status') }}</span>
                    @endif
                    @if(request('region_id'))
                        <span class="badge bg-secondary ms-1">Wilayah: {{ $regions->where('region_id', request('region_id'))->first()->name ?? 'Unknown' }}</span>
                    @endif
                    @if(request('search'))
                        <span class="badge bg-secondary ms-1">Pencarian: "{{ request('search') }}"</span>
                    @endif
                </small>
            </div>
        @endif
    </div>
</div>

<!-- Approval List -->
<div class="executive-card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-list me-2"></i>Daftar Akun PBPHH
            <span class="badge bg-warning ms-2">{{ $users->total() }}</span>
        </h5>
    </div>
    <div class="card-body">
        @if($users->count() > 0)
            <div class="table-responsive">
                <table class="table table-executive">
                    <thead>
                        <tr>
                            <th width="25%">
                                <i class="fas fa-building me-1"></i>Perusahaan & Email
                            </th>
                            <th width="12%">
                                <i class="fas fa-map-marker-alt me-1"></i>Wilayah
                            </th>
                            <th width="15%">
                                <i class="fas fa-calendar me-1"></i>Tanggal Daftar
                            </th>
                            <th width="10%">
                                <i class="fas fa-flag me-1"></i>Status
                            </th>
                            <th width="15%">
                                <i class="fas fa-file-alt me-1"></i>Dokumen
                            </th>
                            <th width="23%">
                                <i class="fas fa-cogs me-1"></i>Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-primary text-white me-3">
                                            <i class="fas fa-building"></i>
                                        </div>
                                        <div>
                                            <strong class="d-block">
                                                @if($user->pbphhProfile)
                                                    {{ $user->pbphhProfile->company_name }}
                                                @else
                                                    <em class="text-muted">Belum diisi</em>
                                                @endif
                                            </strong>
                                            <small class="text-muted">{{ $user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($user->region)
                                        <span class="badge bg-info">
                                            <i class="fas fa-map-marker-alt me-1"></i>{{ $user->region->name }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-question me-1"></i>Tidak ada
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-nowrap">
                                        <strong class="d-block">{{ $user->created_at->format('d/m/Y') }}</strong>
                                        <small class="text-muted">{{ $user->created_at->format('H:i') }} • {{ $user->created_at->diffForHumans() }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if($user->approval_status === 'Pending')
                                        <span class="badge bg-warning text-dark px-3 py-2">
                                            <i class="fas fa-clock me-1"></i>Pending
                                        </span>
                                    @elseif($user->approval_status === 'Approved')
                                        <span class="badge bg-success px-3 py-2">
                                            <i class="fas fa-check me-1"></i>Approved
                                        </span>
                                    @else
                                        <span class="badge bg-danger px-3 py-2">
                                            <i class="fas fa-times me-1"></i>Rejected
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->pbphhProfile)
                                        <div class="d-flex gap-1 flex-wrap">
                                            <a href="{{ Storage::url($user->pbphhProfile->nib_path) }}" 
                                               target="_blank" class="btn btn-outline-primary btn-sm" 
                                               data-bs-toggle="tooltip" title="Lihat NIB">
                                                <i class="fas fa-certificate"></i>
                                            </a>
                                            <a href="{{ Storage::url($user->pbphhProfile->sk_pbphh_path) }}" 
                                               target="_blank" class="btn btn-outline-secondary btn-sm"
                                               data-bs-toggle="tooltip" title="Lihat SK PBPHH">
                                                <i class="fas fa-file-alt"></i>
                                            </a>
                                        </div>
                                        <small class="text-muted d-block mt-1">NIB & SK PBPHH</small>
                                    @else
                                        <div class="text-center">
                                            <i class="fas fa-exclamation-triangle text-warning"></i>
                                            <small class="text-muted d-block">Belum upload</small>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($user->approval_status === 'Pending')
                                        <div class="d-flex gap-1 flex-wrap">
                                            <button type="button" class="btn btn-success btn-sm" 
                                                    onclick="approveUser({{ $user->user_id }}, '{{ str_replace(["'", '"', "\n", "\r"], ["\\'", '\\"', '\\n', '\\r'], $user->pbphhProfile->company_name ?? 'PBPHH') }}')"
                                                    data-bs-toggle="tooltip" title="Setujui akun">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" 
                                                    onclick="rejectUser({{ $user->user_id }}, '{{ str_replace(["'", '"', "\n", "\r"], ["\\'", '\\"', '\\n', '\\r'], $user->pbphhProfile->company_name ?? 'PBPHH') }}')"
                                                    data-bs-toggle="tooltip" title="Tolak akun">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        <small class="text-muted d-block mt-1">Menunggu review</small>
                                    @else
                                        <div class="approval-info">
                                            @if($user->approvedBy)
                                                <small class="text-muted d-block">
                                                    <i class="fas fa-user me-1"></i>{{ $user->approvedBy->email }}
                                                </small>
                                            @endif
                                            @if($user->approved_at)
                                                <small class="text-muted d-block">
                                                    <i class="fas fa-clock me-1"></i>{{ $user->approved_at->format('d/m/Y H:i') }}
                                                </small>
                                            @endif
                                            @if($user->approval_status === 'Rejected' && $user->rejection_reason)
                                                <small class="text-danger d-block mt-1">
                                                    <i class="fas fa-comment me-1"></i>{{ Str::limit($user->rejection_reason, 50) }}
                                                </small>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $users->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">Tidak ada akun PBPHH yang perlu di-approve</h5>
                <p class="text-muted">Semua akun sudah diproses atau tidak ada pendaftaran baru.</p>
            </div>
        @endif
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle text-success me-2"></i>Konfirmasi Approval
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menyetujui akun PBPHH <strong id="approveName"></strong>?</p>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Setelah disetujui, perusahaan akan dapat mengakses dashboard dan melengkapi profil mereka.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="approveForm" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-1"></i>Ya, Setujui
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-times-circle text-danger me-2"></i>Konfirmasi Penolakan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menolak akun PBPHH <strong id="rejectName"></strong>?</p>
                    
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" 
                                  rows="3" required placeholder="Masukkan alasan penolakan..."></textarea>
                        <div class="form-text">Alasan ini akan dikirimkan kepada perusahaan.</div>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Penolakan ini bersifat permanen. Perusahaan harus mendaftar ulang jika ingin menggunakan sistem.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-1"></i>Ya, Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Auto-refresh notification for pending approvals
        const currentStatus = '{{ request("status") ?? "" }}';
        if (currentStatus === 'Pending' || currentStatus === '') {
            let refreshTimer = 180; // 3 minutes
            let refreshInterval = setInterval(function() {
                refreshTimer--;
                if (refreshTimer <= 0) {
                    // Show notification before refresh
                    if (confirm('Halaman akan di-refresh untuk memperbarui data. Lanjutkan?')) {
                        location.reload();
                    } else {
                        clearInterval(refreshInterval);
                    }
                }
            }, 1000);
        }
    });
    
    function approveUser(userId, companyName) {
        document.getElementById('approveName').textContent = companyName;
        document.getElementById('approveForm').action = '{{ route("dinas.approvals.approve", ":id") }}'.replace(':id', userId);
        new bootstrap.Modal(document.getElementById('approveModal')).show();
    }
    
    function rejectUser(userId, companyName) {
        document.getElementById('rejectName').textContent = companyName;
        document.getElementById('rejectForm').action = '{{ route("dinas.approvals.reject", ":id") }}'.replace(':id', userId);
        document.getElementById('rejection_reason').value = '';
        new bootstrap.Modal(document.getElementById('rejectModal')).show();
    }
    
    // Enhanced form submission with loading states
    document.getElementById('approveForm').addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Memproses...';
        submitBtn.disabled = true;
    });
    
    document.getElementById('rejectForm').addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Memproses...';
        submitBtn.disabled = true;
    });
</script>
@endpush
