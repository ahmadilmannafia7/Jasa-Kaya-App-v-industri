@extends('layouts.dinas')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dinas-dashboard.css') }}">
@endpush

@section('title', 'Manajemen Wilayah - Dinas Kehutanan Provinsi')

@section('dashboard-content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-3 mb-4 border-bottom">
        <div class="page-header">
            <h1 class="h2 mb-2">
                <div class="header-icon me-3">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                Manajemen Wilayah
            </h1>
            <p class="text-muted mb-0">
                <i class="fas fa-building me-2"></i>Dinas Kehutanan Provinsi
                <span class="mx-2">•</span>
                <i class="fas fa-calendar me-1"></i>{{ now()->format('d F Y') }}
            </p>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-secondary modern-btn" onclick="exportData()">
                    <i class="fas fa-download me-1"></i>Export Data
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary modern-btn" onclick="printReport()">
                    <i class="fas fa-print me-1"></i>Print
                </button>
            </div>
            <button type="button" class="btn btn-sm btn-primary modern-btn" data-bs-toggle="modal" data-bs-target="#addRegionModal">
                <i class="fas fa-plus me-1"></i>Tambah Wilayah
            </button>
            <button type="button" class="btn btn-sm btn-primary modern-btn ms-2" onclick="location.reload()">
                <i class="fas fa-sync-alt me-1"></i>Refresh
            </button>
        </div>
    </div>

<!-- Executive Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-3">
            <div class="stats-card text-center">
                <div class="stats-icon">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <div class="stats-number">
                    {{ number_format($regions->total()) }}
                </div>
                <div class="stats-label">Total Wilayah</div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-3">
            <div class="stats-card text-center">
                <div class="stats-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stats-number">{{ number_format($regions->sum('users_count')) }}</div>
                <div class="stats-label">Total Pengguna</div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-3">
            <div class="stats-card text-center">
                <div class="stats-icon">
                    <i class="fas fa-tree"></i>
                </div>
                <div class="stats-number">{{ number_format($regions->sum('kthrs_count')) }}</div>
                <div class="stats-label">Total KTHR</div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-3">
            <div class="stats-card text-center">
                <div class="stats-icon">
                    <i class="fas fa-industry"></i>
                </div>
                <div class="stats-number">{{ number_format($regions->sum('pbphhs_count')) }}</div>
                <div class="stats-label">Total PBPHH</div>
            </div>
        </div>
    </div>

<!-- Search and Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="executive-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-search me-2"></i>Filter & Pencarian
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('dinas.region-management') }}" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Cari Wilayah</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" name="search" class="form-control" placeholder="Nama wilayah..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tipe Wilayah</label>
                            <select name="region_type" class="form-select">
                                <option value="">Semua Tipe</option>
                                <option value="Provinsi" {{ request('region_type') == 'Provinsi' ? 'selected' : '' }}>Provinsi</option>
                                <option value="Kabupaten" {{ request('region_type') == 'Kabupaten' ? 'selected' : '' }}>Kabupaten</option>
                                <option value="Kecamatan" {{ request('region_type') == 'Kecamatan' ? 'selected' : '' }}>Kecamatan</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary modern-btn">
                                    <i class="fas fa-filter me-1"></i>Filter
                                </button>
                                <a href="{{ route('dinas.region-management') }}" class="btn btn-outline-secondary modern-btn">
                                    <i class="fas fa-times me-1"></i>Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<!-- Regions Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="executive-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-table me-2"></i>Data Wilayah
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-executive">
                            <thead>
                                <tr>
                                    <th>Wilayah</th>
                                    <th>Tipe</th>
                                    <th>Pengguna</th>
                                    <th>KTHR</th>
                                    <th>PBPHH</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                             <tbody>
                                 @forelse($regions as $region)
                                     <tr>
                                         <td>
                                             <div class="d-flex align-items-center">
                                                 <div class="me-3">
                                                     <i class="fas fa-map-marker-alt text-primary"></i>
                                                 </div>
                                                 <div>
                                                     <div class="fw-semibold">{{ $region->name }}</div>
                                                     <small class="text-muted">ID: {{ $region->region_id }}</small>
                                                 </div>
                                             </div>
                                         </td>
                                         <td>
                                             <span class="badge bg-primary">{{ $region->type }}</span>
                                         </td>
                                         <td class="text-center">
                                             <span class="fw-semibold">{{ number_format($region->users_count) }}</span>
                                         </td>
                                         <td class="text-center">
                                             <span class="fw-semibold">{{ number_format($region->kthrs_count) }}</span>
                                         </td>
                                         <td class="text-center">
                                             <span class="fw-semibold">{{ number_format($region->pbphhs_count) }}</span>
                                         </td>
                                         <td>
                                             @if($region->users_count > 0 || $region->kthrs_count > 0 || $region->pbphhs_count > 0)
                                                 <span class="badge bg-success">Aktif</span>
                                             @else
                                                 <span class="badge bg-secondary">Kosong</span>
                                             @endif
                                         </td>
                                         <td>
                                             <div class="btn-group" role="group">
                                                 <button type="button" class="btn btn-sm btn-outline-warning" onclick="editRegion({{ $region->region_id }}, {{ json_encode($region->name) }}, {{ json_encode($region->type) }})" title="Edit">
                                                     <i class="fas fa-edit"></i>
                                                 </button>
                                                 @if($region->users_count == 0 && $region->kthrs_count == 0 && $region->pbphhs_count == 0)
                                                     <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteRegion({{ $region->region_id }}, {{ json_encode($region->name) }})" title="Hapus">
                                                         <i class="fas fa-trash"></i>
                                                     </button>
                                                 @endif
                                             </div>
                                         </td>
                                     </tr>
                                 @empty
                                     <tr>
                                         <td colspan="7" class="text-center py-4">
                                             <div class="text-muted">
                                                 <i class="fas fa-inbox fa-3x mb-3"></i>
                                                 <p>Tidak ada data wilayah yang ditemukan.</p>
                                             </div>
                                         </td>
                                     </tr>
                                 @endforelse
                             </tbody>
                         </table>
                     </div>

                     <!-- Pagination -->
                     @if($regions->hasPages())
                         <div class="card-footer bg-light">
                             <div class="d-flex justify-content-between align-items-center">
                                 <div class="text-muted">
                                     Menampilkan {{ $regions->firstItem() }} - {{ $regions->lastItem() }} dari {{ $regions->total() }} wilayah
                                 </div>
                                 {{ $regions->appends(request()->query())->links() }}
                             </div>
                         </div>
                     @endif
                 </div>
             </div>
         </div>
     </div>

<!-- Add Region Modal -->
<div class="modal fade" id="addRegionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Tambah Wilayah Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('dinas.region-management.create') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Wilayah</label>
                        <input type="text" name="region_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipe Wilayah</label>
                        <select name="region_type" class="form-select" required>
                            <option value="">Pilih Tipe</option>
                            <option value="Provinsi">Provinsi</option>
                            <option value="Kabupaten">Kabupaten</option>
                            <option value="Kecamatan">Kecamatan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kode Wilayah</label>
                        <input type="text" name="region_code" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Region Modal -->
<div class="modal fade" id="editRegionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Wilayah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editRegionForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Wilayah</label>
                        <input type="text" name="region_name" id="edit_region_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipe Wilayah</label>
                        <select name="region_type" id="edit_region_type" class="form-select" required>
                            <option value="Provinsi">Provinsi</option>
                            <option value="Kabupaten">Kabupaten</option>
                            <option value="Kecamatan">Kecamatan</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Edit region function
function editRegion(id, name, type) {
    document.getElementById('edit_region_name').value = name;
    document.getElementById('edit_region_type').value = type;
    document.getElementById('editRegionForm').action = `/dinas/region-management/${id}`;
    
    new bootstrap.Modal(document.getElementById('editRegionModal')).show();
}

// Delete region function
function deleteRegion(id, name) {
    if (confirm(`Apakah Anda yakin ingin menghapus wilayah "${name}"?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/dinas/region-management/${id}`;
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}



// Export data function
function exportData() {
    const csvContent = generateCSVContent();
    
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', 'data_wilayah_' + new Date().toISOString().split('T')[0] + '.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function generateCSVContent() {
    const headers = ['Nama Wilayah', 'Tipe', 'Jumlah Pengguna', 'Jumlah KTHR', 'Jumlah PBPHH'];
    let csvContent = headers.join(',') + '\n';
    
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        if (cells.length > 1) {
            const rowData = [
                cells[0].textContent.trim().split('\n')[0],
                cells[1].textContent.trim(),
                cells[2].textContent.trim(),
                cells[3].textContent.trim(),
                cells[4].textContent.trim()
            ];
            csvContent += rowData.join(',') + '\n';
        }
    });
    
    return csvContent;
}

// Print report function
function printReport() {
    window.print();
}

// Auto refresh every 5 minutes
setTimeout(function() {
    location.reload();
}, 300000);
</script>
@endpush
