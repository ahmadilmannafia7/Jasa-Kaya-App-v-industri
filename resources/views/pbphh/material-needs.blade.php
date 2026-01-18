@extends('layouts.pbphh')

@section('title', 'Kebutuhan Bahan Baku - JASA KAYA')

@section('dashboard-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-primary mb-1">Kebutuhan Bahan Baku</h2>
        <p class="text-muted mb-0">Kelola daftar kebutuhan bahan baku perusahaan Anda</p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMaterialModal">
        <i class="fas fa-plus me-2"></i>Tambah Kebutuhan
    </button>
</div>

<div class="card">
    <div class="card-body">
        @if($materialNeeds->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Jenis Kayu</th>
                            <th>Tipe</th>
                            <th>Kebutuhan Bulanan</th>
                            <th>Spesifikasi Tambahan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($materialNeeds as $need)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $need->jenis_kayu }}</div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $need->tipe === 'Kayu' ? 'success' : 'info' }}">
                                    {{ $need->tipe }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-bold">{{ number_format($need->kebutuhan_bulanan_m3, 2) }} m³</div>
                                <small class="text-muted">{{ number_format($need->kebutuhan_bulanan_m3 * 12, 2) }} m³/tahun</small>
                            </td>
                            <td>{{ $need->spesifikasi_tambahan ?: '-' }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                            onclick="editMaterial('{{ $need->need_id }}', '{{ $need->jenis_kayu }}', '{{ $need->tipe }}', '{{ $need->kebutuhan_bulanan_m3 }}', '{{ $need->spesifikasi_tambahan }}')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteMaterial('{{ $need->need_id }}', '{{ $need->jenis_kayu }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Summary -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h5 class="fw-bold text-primary">Total Kebutuhan Bulanan</h5>
                            <h3 class="text-success">{{ number_format($materialNeeds->sum('kebutuhan_bulanan_m3'), 2) }} m³</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h5 class="fw-bold text-primary">Total Kebutuhan Tahunan</h5>
                            <h3 class="text-info">{{ number_format($materialNeeds->sum('kebutuhan_bulanan_m3') * 12, 2) }} m³</h3>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-boxes fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">Belum Ada Kebutuhan Bahan Baku</h5>
                <p class="text-muted">Tambahkan kebutuhan bahan baku untuk memulai pencarian mitra KTHR</p>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMaterialModal">
                    <i class="fas fa-plus me-2"></i>Tambah Kebutuhan Pertama
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Add Material Modal -->
<div class="modal fade" id="addMaterialModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Kebutuhan Bahan Baku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('pbphh.material-needs.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="jenis_kayu" class="form-label">Jenis Kayu <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="jenis_kayu" name="jenis_kayu" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="tipe" class="form-label">Tipe <span class="text-danger">*</span></label>
                        <select class="form-select" id="tipe" name="tipe" required>
                            <option value="">Pilih Tipe</option>
                            <option value="Kayu">Kayu</option>
                            <option value="Bukan Kayu">Bukan Kayu</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="kebutuhan_bulanan_m3" class="form-label">Kebutuhan Bulanan (m³) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="kebutuhan_bulanan_m3" name="kebutuhan_bulanan_m3" 
                               step="0.01" min="0.01" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="spesifikasi_tambahan" class="form-label">Spesifikasi Tambahan</label>
                        <textarea class="form-control" id="spesifikasi_tambahan" name="spesifikasi_tambahan" 
                                  rows="3" placeholder="Diameter, panjang, kualitas, dll."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Material Modal -->
<div class="modal fade" id="editMaterialModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Kebutuhan Bahan Baku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editMaterialForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_jenis_kayu" class="form-label">Jenis Kayu <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_jenis_kayu" name="jenis_kayu" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_tipe" class="form-label">Tipe <span class="text-danger">*</span></label>
                        <select class="form-select" id="edit_tipe" name="tipe" required>
                            <option value="">Pilih Tipe</option>
                            <option value="Kayu">Kayu</option>
                            <option value="Bukan Kayu">Bukan Kayu</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_kebutuhan_bulanan_m3" class="form-label">Kebutuhan Bulanan (m³) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="edit_kebutuhan_bulanan_m3" name="kebutuhan_bulanan_m3" 
                               step="0.01" min="0.01" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_spesifikasi_tambahan" class="form-label">Spesifikasi Tambahan</label>
                        <textarea class="form-control" id="edit_spesifikasi_tambahan" name="spesifikasi_tambahan" 
                                  rows="3" placeholder="Diameter, panjang, kualitas, dll."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
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

        // Enhanced Add Material Modal
        function showAddMaterialModal() {
            destroyAllModalBackdrops();
            const modal = new bootstrap.Modal(document.getElementById('addMaterialModal'), {
                backdrop: false,
                keyboard: true,
                focus: true
            });
            modal.show();
            
            // Ensure modal is visible and clickable
            setTimeout(function() {
                const modalElement = document.getElementById('addMaterialModal');
                modalElement.style.display = 'block';
                modalElement.classList.add('show');
                modalElement.setAttribute('aria-modal', 'true');
                modalElement.removeAttribute('aria-hidden');
                destroyAllModalBackdrops();
            }, 100);
        }

        // Enhanced Edit Material Modal
        function showEditMaterialModal() {
            destroyAllModalBackdrops();
            const modal = new bootstrap.Modal(document.getElementById('editMaterialModal'), {
                backdrop: false,
                keyboard: true,
                focus: true
            });
            modal.show();
            
            // Ensure modal is visible and clickable
            setTimeout(function() {
                const modalElement = document.getElementById('editMaterialModal');
                modalElement.style.display = 'block';
                modalElement.classList.add('show');
                modalElement.setAttribute('aria-modal', 'true');
                modalElement.removeAttribute('aria-hidden');
                destroyAllModalBackdrops();
            }, 100);
        }

        // Override click handlers for add material button
        const addMaterialBtn = document.querySelector('[data-bs-target="#addMaterialModal"]');
        if (addMaterialBtn) {
            addMaterialBtn.removeAttribute('data-bs-toggle');
            addMaterialBtn.removeAttribute('data-bs-target');
            addMaterialBtn.addEventListener('click', function(e) {
                e.preventDefault();
                showAddMaterialModal();
            });
        }

        // Handle add material form submission
        const addMaterialForm = document.querySelector('#addMaterialModal form');
        if (addMaterialForm) {
            addMaterialForm.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
                submitBtn.disabled = true;
                
                // Hide modal and cleanup
                setTimeout(function() {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addMaterialModal'));
                    if (modal) {
                        modal.hide();
                    }
                    destroyAllModalBackdrops();
                }, 100);
            });
        }

        // Handle edit material form submission
        const editMaterialForm = document.querySelector('#editMaterialModal form');
        if (editMaterialForm) {
            editMaterialForm.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memperbarui...';
                submitBtn.disabled = true;
                
                // Hide modal and cleanup
                setTimeout(function() {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editMaterialModal'));
                    if (modal) {
                        modal.hide();
                    }
                    destroyAllModalBackdrops();
                }, 100);
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

    function editMaterial(id, jenisKayu, tipe, kebutuhan, spesifikasi) {
        document.getElementById('editMaterialForm').action = `/pbphh/material-needs/${id}`;
        document.getElementById('edit_jenis_kayu').value = jenisKayu;
        document.getElementById('edit_tipe').value = tipe;
        document.getElementById('edit_kebutuhan_bulanan_m3').value = kebutuhan;
        document.getElementById('edit_spesifikasi_tambahan').value = spesifikasi || '';
        
        destroyAllModalBackdrops();
        const modal = new bootstrap.Modal(document.getElementById('editMaterialModal'), {
            backdrop: false,
            keyboard: true,
            focus: true
        });
        modal.show();
        
        // Ensure modal is visible and clickable
        setTimeout(function() {
            const modalElement = document.getElementById('editMaterialModal');
            modalElement.style.display = 'block';
            modalElement.classList.add('show');
            modalElement.setAttribute('aria-modal', 'true');
            modalElement.removeAttribute('aria-hidden');
            destroyAllModalBackdrops();
        }, 100);
    }

    function deleteMaterial(id, jenisKayu) {
        if (confirm(`Apakah Anda yakin ingin menghapus kebutuhan "${jenisKayu}"?`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/pbphh/material-needs/${id}`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            
            form.appendChild(csrfToken);
            form.appendChild(methodInput);
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endpush
@endsection
