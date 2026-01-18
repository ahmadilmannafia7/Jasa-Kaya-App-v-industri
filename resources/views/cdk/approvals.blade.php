@extends('layouts.cdk')

@section('title', 'Approval Akun - CDK Dashboard')

@section('dashboard-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-user-check me-2"></i>Approval Akun
        <small class="text-muted">{{ $region->name }}</small>
    </h1>
</div>

<!-- Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('cdk.approvals') }}" class="row g-3">
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Semua Status</option>
                    <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Approved" {{ request('status') === 'Approved' ? 'selected' : '' }}>Approved</option>
                    <option value="Rejected" {{ request('status') === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <!-- CDK hanya dapat meng-approve KTHR di wilayahnya -->
            <div class="col-md-3">
                <label class="form-label">Tipe Akun</label>
                <div class="form-control-plaintext">
                    <span class="badge bg-success">
                        <i class="fas fa-tree me-1"></i>KTHR/Penyuluh
                    </span>
                </div>
            </div>
            <div class="col-md-4">
                <label for="search" class="form-label">Cari Email</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="{{ request('search') }}" placeholder="Masukkan email...">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>Filter
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Approval List -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-list me-2"></i>Daftar Akun Menunggu Approval
            <span class="badge bg-warning ms-2">{{ $users->total() }}</span>
        </h5>
    </div>
    <div class="card-body">
        @if($users->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tipe</th>
                            <th>Nama/Perusahaan</th>
                            <th>Email</th>
                            <th>Tanggal Daftar</th>
                            <th>Status</th>
                            <th>Dokumen</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>
                                    <span class="badge bg-success">
                                        <i class="fas fa-tree me-1"></i>KTHR
                                    </span>
                                </td>
                                <td>
                                    <strong>
                                        @if($user->kthr)
                                            {{ $user->kthr->kthr_name }}
                                        @else
                                            <em class="text-muted">Belum diisi</em>
                                        @endif
                                    </strong>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <small class="text-muted">
                                        {{ $user->created_at->format('d/m/Y H:i') }}
                                        <br>
                                        <em>{{ $user->created_at->diffForHumans() }}</em>
                                    </small>
                                </td>
                                <td>
                                    @if($user->approval_status === 'Pending')
                                        <span class="badge bg-warning">
                                            <i class="fas fa-clock me-1"></i>Pending
                                        </span>
                                    @elseif($user->approval_status === 'Approved')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>Approved
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times me-1"></i>Rejected
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->kthr && $user->kthr->ketua_ktp_path && $user->kthr->sk_register_path)
                                        <div class="btn-group-vertical btn-group-sm">
                                            <a href="{{ Storage::url($user->kthr->ketua_ktp_path) }}" 
                                               target="_blank" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-id-card me-1"></i>KTP
                                            </a>
                                            <a href="{{ Storage::url($user->kthr->sk_register_path) }}" 
                                               target="_blank" class="btn btn-outline-secondary btn-sm">
                                                <i class="fas fa-file-alt me-1"></i>SK
                                            </a>
                                        </div>
                                    @else
                                        <em class="text-muted">Dokumen belum lengkap</em>
                                    @endif
                                </td>
                                <td>
                                    @if($user->approval_status === 'Pending')
                                        <div class="btn-group-vertical btn-group-sm">
                                            <button type="button" class="btn btn-success btn-sm" 
                                                    onclick="approveUser({{ $user->user_id }}, {{ json_encode($user->kthr->kthr_name ?? 'KTHR') }})">
                                                <i class="fas fa-check me-1"></i>Approve
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" 
                                                    onclick="rejectUser({{ $user->user_id }}, {{ json_encode($user->kthr->kthr_name ?? 'KTHR') }})">
                                                <i class="fas fa-times me-1"></i>Reject
                                            </button>
                                        </div>
                                    @else
                                        <small class="text-muted">
                                            @if($user->approvedBy)
                                                Oleh: {{ $user->approvedBy->email }}<br>
                                            @endif
                                            {{ $user->approved_at ? $user->approved_at->format('d/m/Y H:i') : '' }}
                                            @if($user->approval_status === 'Rejected' && $user->rejection_reason)
                                                <br><em>{{ $user->rejection_reason }}</em>
                                            @endif
                                        </small>
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
                <h5 class="text-muted">Tidak ada akun yang perlu di-approve</h5>
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
                <p>Apakah Anda yakin ingin menyetujui akun <strong id="approveName"></strong>?</p>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Setelah disetujui, pengguna akan dapat mengakses dashboard dan melengkapi profil mereka.
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
                    <p>Apakah Anda yakin ingin menolak akun <strong id="rejectName"></strong>?</p>
                    
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" 
                                  rows="3" required placeholder="Masukkan alasan penolakan..."></textarea>
                        <div class="form-text">Alasan ini akan dikirimkan kepada pengguna.</div>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Penolakan ini bersifat permanen. Pengguna harus mendaftar ulang jika ingin menggunakan sistem.
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
    function approveUser(userId, userName) {
        document.getElementById('approveName').textContent = userName;
        document.getElementById('approveForm').action = '{{ route("cdk.approvals.approve", ":id") }}'.replace(':id', userId);
        new bootstrap.Modal(document.getElementById('approveModal')).show();
    }
    
    function rejectUser(userId, userName) {
        document.getElementById('rejectName').textContent = userName;
        document.getElementById('rejectForm').action = '{{ route("cdk.approvals.reject", ":id") }}'.replace(':id', userId);
        document.getElementById('rejection_reason').value = '';
        new bootstrap.Modal(document.getElementById('rejectModal')).show();
    }
    
    // Auto-refresh every 2 minutes for pending approvals
    @if(request('status') === 'Pending' || !request('status'))
        setTimeout(function() {
            location.reload();
        }, 120000);
    @endif
</script>
@endpush


