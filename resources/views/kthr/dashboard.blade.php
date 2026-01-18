@extends('layouts.kthr')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/kthr-dashboard.css') }}">
@endpush

@section('title', 'Dashboard KTHR - JASA KAYA')

@section('dashboard-content')
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-primary mb-1">Dashboard KTHR</h2>
        <p class="text-muted mb-0">Selamat datang, {{ $kthr->kthr_name }}</p>
    </div>
    <div class="text-end">
        <small class="text-muted">Terakhir login: {{ Auth::user()->updated_at->format('d M Y, H:i') }}</small>
    </div>
</div>

<!-- Status Kesiapan -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-primary">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-check-circle me-2"></i>Status Kesiapan KTHR
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="status-card">
                            <div class="status-header">
                                <div class="status-icon">
                                    <i class="fas fa-handshake"></i>
                                </div>
                                <div class="status-info">
                                    <h6 class="status-title mb-1">Siap Bermitra</h6>
                                    <p class="status-desc mb-0">Menandakan KTHR siap menjalin kemitraan dengan industri</p>
                                </div>
                            </div>
                            <div class="status-toggle">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="siapMitra" 
                                           {{ $kthr->is_siap_mitra ? 'checked' : '' }}
                                           onchange="updateStatus('is_siap_mitra', this.checked)">
                                    <label class="form-check-label" for="siapMitra">
                                        <span class="switch-text">{{ $kthr->is_siap_mitra ? 'Aktif' : 'Nonaktif' }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="status-card">
                            <div class="status-header">
                                <div class="status-icon">
                                    <i class="fas fa-tree"></i>
                                </div>
                                <div class="status-info">
                                    <h6 class="status-title mb-1">Siap Tebang</h6>
                                    <p class="status-desc mb-0">Menandakan hasil hutan siap untuk dipanen</p>
                                </div>
                            </div>
                            <div class="status-toggle">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="siapTebang" 
                                           {{ $kthr->is_siap_tebang ? 'checked' : '' }}
                                           onchange="updateStatus('is_siap_tebang', this.checked)">
                                    <label class="form-check-label" for="siapTebang">
                                        <span class="switch-text">{{ $kthr->is_siap_tebang ? 'Aktif' : 'Nonaktif' }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-primary text-white h-100">
            <div class="card-body text-center">
                <i class="fas fa-inbox fa-3x mb-3"></i>
                <h3 class="fw-bold">{{ $stats['total_requests'] }}</h3>
                <p class="mb-0">Total Permintaan</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-warning text-white h-100">
            <div class="card-body text-center">
                <i class="fas fa-clock fa-3x mb-3"></i>
                <h3 class="fw-bold">{{ $stats['pending_requests'] }}</h3>
                <p class="mb-0">Menunggu Respon</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-info text-white h-100">
            <div class="card-body text-center">
                <i class="fas fa-handshake fa-3x mb-3"></i>
                <h3 class="fw-bold">{{ $stats['active_partnerships'] }}</h3>
                <p class="mb-0">Kemitraan Aktif</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-success text-white h-100">
            <div class="card-body text-center">
                <i class="fas fa-check-circle fa-3x mb-3"></i>
                <h3 class="fw-bold">{{ $stats['completed_partnerships'] }}</h3>
                <p class="mb-0">Selesai</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-inbox me-2"></i>Permintaan Terbaru
                </h5>
                <a href="{{ route('kthr.requests') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body">
                @if($recentRequests->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Perusahaan</th>
                                    <th>Jenis Kayu</th>
                                    <th>Volume (m³/bulan)</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentRequests as $request)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ optional($request->pbphhProfile)->company_name ?? '-' }}</div>
                                        <small class="text-muted">{{ optional(optional($request->pbphhProfile)->user)->email ?? '-' }}</small>
                                    </td>
                                    <td>{{ $request->wood_type }}</td>
                                    <td>{{ number_format($request->monthly_volume_m3, 2) }}</td>
                                    <td>
                                        <span class="badge {{ $request->status_badge }}">
                                            {{ $request->status }}
                                        </span>
                                    </td>
                                    <td>{{ $request->created_at->format('d M Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-inbox fa-4x"></i>
                        <h5>Belum Ada Permintaan</h5>
                        <p>Permintaan kerjasama dari industri akan muncul di sini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>Pertemuan Mendatang
                </h5>
            </div>
            <div class="card-body activity-feed">
                @if($upcomingMeetings->count() > 0)
                    @foreach($upcomingMeetings as $meeting)
                    <div class="border-start border-primary border-3 ps-3 mb-3">
                        <div class="fw-bold">{{ optional(optional($meeting->permintaanKerjasama)->pbphhProfile)->company_name ?? '-' }}</div>
                        <div class="text-muted small">
                            <i class="fas fa-calendar me-1"></i>
                            {{ $meeting->scheduled_time->format('d M Y, H:i') }}
                        </div>
                        <div class="text-muted small">
                            <i class="fas fa-{{ $meeting->method === 'online' ? 'video' : 'map-marker-alt' }} me-1"></i>
                            {{ $meeting->method === 'online' ? 'Online' : 'Lapangan' }}
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <i class="fas fa-calendar-times fa-3x"></i>
                        <h6>Tidak Ada Pertemuan</h6>
                        <p class="mb-0">Pertemuan terjadwal akan muncul di sini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Define data variables safely
const kthrData = {
    nama_pendamping: @json($kthr->nama_pendamping ?? ''),
    alamat_sekretariat: @json($kthr->alamat_sekretariat ?? ''),
    coordinate_lat: @json($kthr->coordinate_lat ?? null),
    coordinate_lng: @json($kthr->coordinate_lng ?? null),
    luas_areal_ha: @json($kthr->luas_areal_ha ?? null),
    jumlah_anggota: @json($kthr->jumlah_anggota ?? null),
    jumlah_pertemuan_tahunan: @json($kthr->jumlah_pertemuan_tahunan ?? null)
};

function updateStatus(field, value) {
    const switchElement = document.getElementById(field === 'is_siap_mitra' ? 'siapMitra' : 'siapTebang');
    const textElement = switchElement.nextElementSibling.querySelector('.switch-text');
    const statusCard = switchElement.closest('.status-card');
    
    // Add loading state
    statusCard.style.opacity = '0.7';
    statusCard.style.pointerEvents = 'none';
    
    const requestData = {
        [field]: value,
        ...kthrData
    };
    
    fetch('{{ route("kthr.profile") }}', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(requestData)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        // Update text dynamically
        textElement.textContent = value ? 'Aktif' : 'Nonaktif';
        
        // Remove loading state
        statusCard.style.opacity = '1';
        statusCard.style.pointerEvents = 'auto';
        
        // Show success notification
        showNotification('success', 'Status berhasil diperbarui!');
        
        // Add visual feedback
        statusCard.style.transform = 'scale(1.02)';
        setTimeout(() => {
            statusCard.style.transform = '';
        }, 200);
    })
    .catch(error => {
        console.error('Error:', error);
        
        // Revert checkbox state
        switchElement.checked = !value;
        
        // Remove loading state
        statusCard.style.opacity = '1';
        statusCard.style.pointerEvents = 'auto';
        
        // Show error notification
        showNotification('error', 'Gagal memperbarui status. Silakan coba lagi.');
    });
}

function showNotification(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle';
    
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        <i class="fas ${icon} me-2"></i>${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Auto remove after 4 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 4000);
}
</script>
@endpush
@endsection