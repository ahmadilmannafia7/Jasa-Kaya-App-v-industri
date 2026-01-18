@extends('layouts.dinas')

@section('title', 'Manajemen Pengguna - Dinas Kehutanan Provinsi')

@section('dashboard-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-users-cog me-2"></i>Manajemen Pengguna
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="fas fa-user-plus me-2"></i>Tambah Pengguna
            </a>
        </div>
        <div class="btn-group">
            <button type="button" class="btn btn-outline-secondary" onclick="location.reload()">
                <i class="fas fa-sync-alt me-2"></i>Refresh
            </button>
        </div>
    </div>
</div>

<!-- Tabel Pengguna -->
<div class="executive-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-executive">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Peran</th>
                        <th>Wilayah</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->email }}</td>
                            <td><span class="badge bg-info">{{ $user->role }}</span></td>
                            <td>{{ $user->region?->name ?? '-' }}</td>
                            <td>
                                @if($user->approval_status === 'Pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($user->approval_status === 'Approved')
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="#editUserModal{{ $user->user_id }}" class="btn btn-outline-primary" title="Edit Pengguna" data-bs-toggle="modal">
                                        <i class="fas fa-edit me-1"></i><span class="d-none d-md-inline">Edit</span>
                                    </a>
                                    <button type="button" class="btn btn-outline-info" title="Lihat Detail" onclick="viewUserDetail('{{ $user->user_id }}')">
                                        <i class="fas fa-eye me-1"></i><span class="d-none d-md-inline">Detail</span>
                                    </button>
                                    <form action="{{ route('dinas.user-management.delete', $user->user_id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete('{{ $user->email }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus Pengguna">
                                            <i class="fas fa-trash me-1"></i><span class="d-none d-md-inline">Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Tidak ada pengguna</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-3">
            {{ $users->links() }}
        </div>
    </div>
</div>

<!-- Modal Tambah Pengguna -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('dinas.user-management.create') }}">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserLabel">Tambah Pengguna Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required minlength="8">
                </div>
                <div class="mb-3">
                    <label class="form-label">Peran</label>
                    <select name="role" class="form-select" required>
                        <option value="CDK">CDK</option>
                        <option value="DINAS_PROVINSI">Dinas Provinsi</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Wilayah</label>
                    <select name="region_id" class="form-select" required>
                        @foreach ($regions as $region)
                            <option value="{{ $region->region_id }}">{{ $region->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-user-plus me-2"></i>Simpan Pengguna
                </button>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Batal
                </button>
            </div>
        </div>
    </form>
  </div>
</div>

<!-- Modal Edit Pengguna -->
@foreach ($users as $user)
<div class="modal fade" id="editUserModal{{ $user->user_id }}" tabindex="-1" aria-labelledby="editUserLabel{{ $user->user_id }}" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('dinas.user-management.update', $user->user_id) }}">
        @csrf
        @method('PUT')
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserLabel{{ $user->user_id }}">Edit Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password (opsional)</label>
                    <input type="password" name="password" class="form-control" placeholder="Biarkan kosong jika tidak diubah">
                </div>
                <div class="mb-3">
                    <label class="form-label">Peran</label>
                    <select name="role" class="form-select" required>
                        <option value="CDK" {{ $user->role === 'CDK' ? 'selected' : '' }}>CDK</option>
                        <option value="DINAS_PROVINSI" {{ $user->role === 'DINAS_PROVINSI' ? 'selected' : '' }}>Dinas Provinsi</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Wilayah</label>
                    <select name="region_id" class="form-select" required>
                        @foreach ($regions as $region)
                            <option value="{{ $region->region_id }}" {{ $region->region_id == $user->region_id ? 'selected' : '' }}>{{ $region->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status Persetujuan</label>
                    <select name="approval_status" class="form-select" required>
                        <option value="Pending" {{ $user->approval_status === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Approved" {{ $user->approval_status === 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Rejected" {{ $user->approval_status === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Perbarui
                </button>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Batal
                </button>
            </div>
        </div>
    </form>
  </div>
</div>
@endforeach

@endsection

@push('styles')
<style>
    /* Enhanced Button Styling */
    .btn-group .btn {
        border-radius: 0.375rem;
        margin-right: 3px;
        transition: all 0.2s ease-in-out;
        font-weight: 500;
        position: relative;
        overflow: hidden;
    }
    
    .btn-group .btn:last-child {
        margin-right: 0;
    }
    
    .btn-group .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 2;
    }
    
    .btn-group .btn:active {
        transform: translateY(0);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    /* Action Button Colors */
    .btn-outline-primary:hover {
        background-color: #198652;
        border-color: #198652;
        color: white;
    }
    
    .btn-outline-info:hover {
        background-color: #17a2b8;
        border-color: #17a2b8;
        color: white;
    }
    
    .btn-outline-danger:hover {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
    }
    
    /* Table and Layout */
    .table-executive .btn-group {
        white-space: nowrap;
        display: flex;
        gap: 2px;
    }
    
    .executive-card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid rgba(0,0,0,.125);
        border-radius: 0.5rem;
    }
    
    /* Header Button Toolbar */
    .btn-toolbar .btn-group {
        margin-bottom: 0.5rem;
    }
    
    .btn-toolbar .btn {
        font-weight: 500;
        padding: 0.5rem 1rem;
        transition: all 0.2s ease-in-out;
    }
    
    .btn-toolbar .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .btn-toolbar {
            flex-direction: column;
            gap: 0.5rem;
            align-items: stretch;
        }
        
        .btn-group {
            width: 100%;
            justify-content: center;
        }
        
        .btn-group .btn {
            flex: 1;
            margin-right: 2px;
        }
        
        .table-executive .btn-group {
            flex-direction: column;
            gap: 2px;
        }
        
        .table-executive .btn-group .btn {
            margin-right: 0;
            margin-bottom: 2px;
        }
        
        .table-executive .btn-group .btn:last-child {
            margin-bottom: 0;
        }
    }
    
    @media (max-width: 576px) {
        .btn-group .btn span {
            display: none !important;
        }
        
        .btn-group .btn {
            padding: 0.375rem 0.5rem;
        }
    }
    
    /* Modal Enhancements */
    .modal-header {
        background: linear-gradient(135deg, #198652 0%, #16a085 100%);
        color: white;
        border-bottom: none;
        border-radius: 0.5rem 0.5rem 0 0;
    }
    
    .modal-header .btn-close {
        filter: invert(1);
        opacity: 0.8;
    }
    
    .modal-header .btn-close:hover {
        opacity: 1;
    }
    
    .modal-footer {
        border-top: 1px solid #e9ecef;
        padding: 1rem 1.5rem;
        background-color: #f8f9fa;
        border-radius: 0 0 0.5rem 0.5rem;
    }
    
    .modal-footer .btn {
        min-width: 100px;
        font-weight: 500;
    }
    
    /* Loading States */
    .btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }
    
    .btn .fa-spinner {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Badge Enhancements */
    .badge {
        font-weight: 500;
        padding: 0.375rem 0.75rem;
    }
    
    /* Table Enhancements */
    .table-executive th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        color: #495057;
    }
    
    .table-executive tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>
@endpush

@push('scripts')
<script>
    // Enhanced user management functions
    function viewUserDetail(userId) {
        // Create a simple detail modal or redirect to detail page
        const user = @json($users->items());
        const userData = user.find(u => u.user_id == userId);
        
        if (userData) {
            const detailHtml = `
                <div class="modal fade" id="userDetailModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><i class="fas fa-user me-2"></i>Detail Pengguna</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-sm-4"><strong>Email:</strong></div>
                                    <div class="col-sm-8">${userData.email}</div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-4"><strong>Peran:</strong></div>
                                    <div class="col-sm-8"><span class="badge bg-info">${userData.role}</span></div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-4"><strong>Wilayah:</strong></div>
                                    <div class="col-sm-8">${userData.region && userData.region.name ? userData.region.name : '-'}</div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-4"><strong>Status:</strong></div>
                                    <div class="col-sm-8">
                                        <span class="badge bg-${userData.approval_status === 'Approved' ? 'success' : userData.approval_status === 'Pending' ? 'warning' : 'danger'}">
                                            ${userData.approval_status}
                                        </span>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-4"><strong>Dibuat:</strong></div>
                                    <div class="col-sm-8">${new Date(userData.created_at).toLocaleDateString('id-ID')}</div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-2"></i>Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Remove existing detail modal if any
            const existingModal = document.getElementById('userDetailModal');
            if (existingModal) {
                existingModal.remove();
            }
            
            // Add new modal to body
            document.body.insertAdjacentHTML('beforeend', detailHtml);
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('userDetailModal'));
            modal.show();
        }
    }
    
    function confirmDelete(userEmail) {
        return confirm(`Apakah Anda yakin ingin menghapus pengguna dengan email "${userEmail}"?\n\nTindakan ini tidak dapat dibatalkan.`);
    }

    // Smart Modal Backdrop Cleanup - Only cleanup when no modals are active
    function destroyAllModalBackdrops() {
        const activeModals = document.querySelectorAll('.modal.show');
        if (activeModals.length === 0) {
            const backdrops = document.querySelectorAll('.modal-backdrop, [class*="modal-backdrop"]');
            backdrops.forEach(backdrop => {
                backdrop.remove();
            });
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('padding-right');
            document.body.style.removeProperty('overflow');
        }
    }

    // MutationObserver to detect and remove backdrop elements in real-time
    const backdropObserver = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            mutation.addedNodes.forEach(function(node) {
                if (node.nodeType === 1) {
                    // Check if the added node is a backdrop
                    if (node.classList && (node.classList.contains('modal-backdrop') || 
                        node.className.includes('modal-backdrop'))) {
                        const activeModals = document.querySelectorAll('.modal.show');
                        if (activeModals.length === 0) {
                            node.remove();
                        }
                    }
                    // Check for backdrop in child nodes
                    const childBackdrops = node.querySelectorAll && node.querySelectorAll('.modal-backdrop, [class*="modal-backdrop"]');
                    if (childBackdrops) {
                        const activeModals = document.querySelectorAll('.modal.show');
                        if (activeModals.length === 0) {
                            childBackdrops.forEach(backdrop => backdrop.remove());
                        }
                    }
                }
            });
        });
    });

    // Start observing
    backdropObserver.observe(document.body, {
        childList: true,
        subtree: true
    });

    // Override Bootstrap Modal to prevent backdrop creation
    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        const originalShow = bootstrap.Modal.prototype.show;
        bootstrap.Modal.prototype.show = function() {
            this._config.backdrop = false;
            return originalShow.call(this);
        };
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Clean up any existing backdrops on page load
        destroyAllModalBackdrops();

        // Enhanced Add User Modal
        function showAddUserModal() {
            destroyAllModalBackdrops();
            const modal = new bootstrap.Modal(document.getElementById('addUserModal'), {
                backdrop: false,
                keyboard: true,
                focus: true
            });
            modal.show();
            
            // Ensure modal is visible and clickable
            setTimeout(function() {
                const modalElement = document.getElementById('addUserModal');
                modalElement.style.display = 'block';
                modalElement.classList.add('show');
                modalElement.setAttribute('aria-modal', 'true');
                modalElement.removeAttribute('aria-hidden');
                destroyAllModalBackdrops();
            }, 100);
        }

        // Enhanced Edit User Modal
        function showEditUserModal(modalId) {
            destroyAllModalBackdrops();
            const modal = new bootstrap.Modal(document.getElementById(modalId), {
                backdrop: false,
                keyboard: true,
                focus: true
            });
            modal.show();
            
            // Ensure modal is visible and clickable
            setTimeout(function() {
                const modalElement = document.getElementById(modalId);
                modalElement.style.display = 'block';
                modalElement.classList.add('show');
                modalElement.setAttribute('aria-modal', 'true');
                modalElement.removeAttribute('aria-hidden');
                destroyAllModalBackdrops();
            }, 100);
        }

        // Override click handlers for add user button
        const addUserBtn = document.querySelector('[data-bs-target="#addUserModal"]');
        if (addUserBtn) {
            addUserBtn.removeAttribute('data-bs-toggle');
            addUserBtn.removeAttribute('data-bs-target');
            addUserBtn.addEventListener('click', function(e) {
                e.preventDefault();
                showAddUserModal();
            });
        }

        // Override click handlers for edit user buttons
        const editUserBtns = document.querySelectorAll('[href^="#editUserModal"]');
        editUserBtns.forEach(function(btn) {
            btn.removeAttribute('data-bs-toggle');
            const modalId = btn.getAttribute('href').substring(1);
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                showEditUserModal(modalId);
            });
        });

        // Handle add user form submission
        const addUserForm = document.querySelector('#addUserModal form');
        if (addUserForm) {
            addUserForm.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
                submitBtn.disabled = true;
                
                // Re-enable button after 3 seconds if form doesn't submit
                setTimeout(function() {
                    if (submitBtn.disabled) {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }
                }, 3000);
            });
        }
        
        // Handle edit user forms submission
        const editUserForms = document.querySelectorAll('[id^="editUserModal"] form');
        editUserForms.forEach(function(form) {
            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memperbarui...';
                submitBtn.disabled = true;
                
                // Re-enable button after 3 seconds if form doesn't submit
                setTimeout(function() {
                    if (submitBtn.disabled) {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }
                }, 3000);
            });
        });
        
        // Handle delete forms with loading state
        const deleteForms = document.querySelectorAll('form[action*="delete"]');
        deleteForms.forEach(function(form) {
            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                
                // Add small delay to show loading state
                setTimeout(function() {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i><span class="d-none d-md-inline">Menghapus...</span>';
                    submitBtn.disabled = true;
                }, 100);
            });
        });
        
        // Add hover effects for action buttons
        const actionButtons = document.querySelectorAll('.btn-group .btn');
        actionButtons.forEach(function(btn) {
            btn.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-1px)';
                this.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
            });
            
            btn.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            });
        });
        
        // Add refresh button functionality with loading state
        const refreshBtn = document.querySelector('button[onclick="location.reload()"]');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memuat...';
                this.disabled = true;
                
                setTimeout(function() {
                    location.reload();
                }, 500);
            });
        }

        // Periodic cleanup every 500ms
        setInterval(destroyAllModalBackdrops, 500);

        // Cleanup on window events
        window.addEventListener('resize', destroyAllModalBackdrops);
        window.addEventListener('scroll', destroyAllModalBackdrops);
        document.addEventListener('click', function(e) {
            // If clicking outside modal, cleanup backdrops
            if (!e.target.closest('.modal-content') && !e.target.closest('[data-bs-toggle="modal"]')) {
                setTimeout(destroyAllModalBackdrops, 50);
            }
        });
    });
</script>
@endpush