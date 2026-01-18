@extends('layouts.dinas')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dinas-dashboard.css') }}">
@endpush

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'Dashboard Eksekutif - Dinas Kehutanan Provinsi')

@section('dashboard-content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-3 mb-4 border-bottom">
        <div class="page-header">
            <h1 class="h2 mb-2">
                <div class="header-icon me-3">
                    <i class="fas fa-chart-line"></i>
                </div>
                Dashboard Eksekutif
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
            </div>
            <button type="button" class="btn btn-sm btn-primary modern-btn" onclick="location.reload()">
                <i class="fas fa-sync-alt me-1"></i>Refresh
            </button>
        </div>
    </div>

    <!-- Executive Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
            <div class="stats-card text-center">
                <div class="stats-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stats-number">
                    {{ number_format($stats['total_approved_kthrs'] + $stats['total_approved_pbphhs']) }}
                </div>
                <div class="stats-label">Total Entitas Aktif</div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
            <div class="stats-card text-center">
                <div class="stats-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <div class="stats-number">{{ number_format($stats['total_partnerships']) }}</div>
                <div class="stats-label">Total Kemitraan</div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
            <div class="stats-card text-center">
                <div class="stats-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stats-number">{{ number_format($stats['completed_partnerships']) }}</div>
                <div class="stats-label">Kemitraan Selesai</div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
            <div class="stats-card text-center">
                <div class="stats-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stats-number">{{ number_format($stats['completed_meetings']) }}</div>
                <div class="stats-label">Pertemuan Selesai</div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
            <div class="stats-card text-center">
                <div class="stats-icon">
                    <i class="fas fa-file-contract"></i>
                </div>
                <div class="stats-number">{{ number_format($stats['agreements_created']) }}</div>
                <div class="stats-label">Kerjasama</div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
            <div class="stats-card text-center">
                <div class="stats-icon">
                    <i class="fas fa-user-clock"></i>
                </div>
                <div class="stats-number">{{ number_format($stats['pending_pbphh_approvals']) }}</div>
                <div class="stats-label">PBPHH Pending</div>
            </div>
        </div>
    </div>





    <!-- Approval Mendesak -->
    @if(isset($pendingPbphhApprovals) && $pendingPbphhApprovals->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-warning border-0 shadow-sm">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-exclamation-triangle text-warning me-2 fs-4"></i>
                    <h5 class="mb-0 text-warning fw-bold">Approval Mendesak</h5>
                </div>
                
                @foreach($pendingPbphhApprovals->take(1) as $user)
                <div class="bg-white rounded-3 p-3 border-start border-warning border-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning text-white rounded-2 p-2 me-3">
                                <i class="fas fa-building fs-4"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 fw-bold">{{ $user->pbphhProfile->company_name ?? 'Belum diisi' }}</h6>
                                <p class="mb-1 text-muted">{{ $user->email }}</p>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>{{ $user->created_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('dinas.approvals') }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-eye me-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
                
                <div class="text-center mt-3">
                    <a href="{{ route('dinas.approvals') }}" class="btn btn-warning">
                        <i class="fas fa-list me-1"></i>Lihat Semua
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Recent Activities -->
    <div class="row mb-4">
        <div class="col-lg-4 mb-3">
            <div class="executive-card h-100">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-user-plus me-2"></i>Pendaftaran Terbaru
                    </h6>
                </div>
                <div class="card-body activity-feed">
                    @forelse($recentActivities['new_registrations'] as $user)
                        <div class="d-flex align-items-center mb-3 p-2 priority-low rounded">
                            <div class="flex-shrink-0">
                                <i
                                    class="fas {{ $user->role === 'KTHR_PENYULUH' ? 'fa-tree' : 'fa-industry' }} fa-2x text-success"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">
                                    @if($user->role === 'KTHR_PENYULUH' && $user->kthr)
                                        {{ $user->kthr->kthr_name }}
                                    @elseif($user->role === 'PBPHH' && $user->pbphhProfile)
                                        {{ $user->pbphhProfile->company_name }}
                                    @else
                                        {{ $user->email }}
                                    @endif
                                </h6>
                                <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-{{ $user->role === 'KTHR_PENYULUH' ? 'success' : 'info' }}">
                                        {{ $user->role === 'KTHR_PENYULUH' ? 'KTHR' : 'PBPHH' }}
                                    </span>
                                    <a href="{{ route('dinas.registration.detail', $user->user_id) }}" 
                                       class="btn btn-outline-primary btn-sm" 
                                       data-bs-toggle="tooltip" 
                                       title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-inbox fa-3x mb-2"></i>
                            <p>Tidak ada pendaftaran baru</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-3">
            <div class="executive-card h-100">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-handshake me-2"></i>Kemitraan Terbaru
                    </h6>
                </div>
                <div class="card-body activity-feed">
                    @forelse($recentActivities['recent_partnerships'] as $partnership)
                        <div class="d-flex align-items-center mb-3 p-2 priority-medium rounded">
                            <div class="flex-shrink-0">
                                <i class="fas fa-handshake fa-2x text-warning"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">{{ $partnership->kthr->kthr_name }}</h6>
                                <small class="text-muted">{{ $partnership->pbphhProfile->company_name }}</small><br>
                                <small class="text-muted">{{ $partnership->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-{{ $partnership->status === 'Selesai' ? 'success' : 'warning' }}">
                                        {{ $partnership->status }}
                                    </span>
                                    <a href="{{ route('dinas.partnership.detail', $partnership->request_id) }}" 
                                       class="btn btn-outline-primary btn-sm" 
                                       data-bs-toggle="tooltip" 
                                       title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-handshake fa-3x mb-2"></i>
                            <p>Tidak ada kemitraan baru</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-3">
            <div class="executive-card h-100">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-calendar-check me-2"></i>Pertemuan Terbaru
                    </h6>
                </div>
                <div class="card-body activity-feed">
                    @forelse($recentActivities['recent_meetings'] as $meeting)
                        <div class="d-flex align-items-center mb-3 p-2 priority-high rounded">
                            <div class="flex-shrink-0">
                                <i class="fas fa-calendar-check fa-2x text-danger"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">{{ $meeting->permintaanKerjasama->kthr->kthr_name }}</h6>
                                <small
                                    class="text-muted">{{ $meeting->permintaanKerjasama->pbphhProfile->company_name }}</small><br>
                                <small class="text-muted">{{ $meeting->scheduled_time->format('d/m/Y H:i') }}</small>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-{{ $meeting->status === 'Selesai' ? 'success' : 'primary' }}">
                                        {{ $meeting->status }}
                                    </span>
                                    <a href="{{ route('dinas.meeting.detail', $meeting->meeting_id) }}" 
                                       class="btn btn-outline-primary btn-sm" 
                                       data-bs-toggle="tooltip" 
                                       title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-calendar fa-3x mb-2"></i>
                            <p>Tidak ada pertemuan baru</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Pending PBPHH Approvals Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="executive-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-clock me-2 text-warning"></i>PBPHH Menunggu Approval
                        @if($stats['pending_pbphh_approvals'] > 0)
                            <span class="badge bg-warning ms-2 pulse-badge">{{ $stats['pending_pbphh_approvals'] }}</span>
                        @endif
                    </h5>
                    <a href="{{ route('dinas.approvals') }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-external-link-alt me-1"></i>Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    @if(isset($pendingPbphhApprovals) && $pendingPbphhApprovals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-executive">
                                <thead>
                                    <tr>
                                        <th>Perusahaan</th>
                                        <th>Email</th>
                                        <th>Wilayah</th>
                                        <th>Tanggal Daftar</th>
                                        <th>Dokumen</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingPbphhApprovals as $user)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-primary text-white me-2">
                                                        <i class="fas fa-building"></i>
                                                    </div>
                                                    <div>
                                                        <strong>{{ $user->pbphhProfile->company_name ?? 'Belum diisi' }}</strong>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @if($user->region)
                                                    {{ $user->region->name }}
                                                @else
                                                    <span class="text-muted">Belum diisi</span>
                                                @endif
                                            </td>
                                            <td>{{ $user->created_at->format('d M Y, H:i') }}</td>
                                            <td>
                                                @if($user->pbphhProfile && $user->pbphhProfile->nib_path && $user->pbphhProfile->sk_pbphh_path)
                                                    <div class="d-flex gap-1">
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
                                                @else
                                                    <div class="text-center">
                                                        <i class="fas fa-exclamation-triangle text-warning"></i>
                                                        <small class="text-muted d-block">Belum upload</small>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('dinas.approvals') }}" class="btn btn-success btn-sm">
                                                        <i class="fas fa-check"></i>
                                                    </a>
                                                    <a href="{{ route('dinas.approvals') }}" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-times"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                            <h5>Tidak ada PBPHH yang menunggu approval</h5>
                            <p class="text-muted">Semua permintaan approval telah ditangani</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="executive-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>Aksi Cepat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 col-sm-4 mb-2">
                            <a href="{{ route('dinas.approvals') }}" class="btn btn-outline-warning w-100">
                                <i class="fas fa-user-check me-2"></i>Review PBPHH
                                @if($stats['pending_pbphh_approvals'] > 0)
                                <span class="badge bg-warning ms-1">{{ $stats['pending_pbphh_approvals'] }}</span>
                                @endif
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-4 mb-2">
                            <a href="{{ route('dinas.user-management') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-users-cog me-2"></i>Kelola Pengguna
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-4 mb-2">
                            <a href="{{ route('dinas.region-management') }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-map-marked-alt me-2"></i>Kelola Wilayah
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-4 mb-2">
                            <a href="{{ route('dinas.monitoring') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-chart-line me-2"></i>Monitoring
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-4 mb-2">
                            <a href="{{ route('dinas.reports') }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-file-alt me-2"></i>Laporan
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-4 mb-2">
                            <button type="button" class="btn btn-outline-dark w-100" onclick="exportData()">
                                <i class="fas fa-download me-2"></i>Export Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Fungsi tambahan
        function viewRegionDetails(regionId) {
            console.log('Viewing details for region:', regionId);
        }

        function assignCDK(regionId) {
            console.log('Assigning CDK to region:', regionId);
        }

        // Dashboard data for export
        const dashboardData = {
            stats: {
                total_kthrs: {{ $stats['total_kthrs'] ?? 0 }},
                total_pbphhs: {{ $stats['total_pbphhs'] ?? 0 }},
                active_partnerships: {{ $stats['active_partnerships'] ?? 0 }},
                pending_pbphh_approvals: {{ $stats['pending_pbphh_approvals'] ?? 0 }},
                completed_meetings: {{ $stats['completed_meetings'] ?? 0 }},
                pending_meetings: {{ $stats['pending_meetings'] ?? 0 }}
            },
            recent_partnerships: @json($recentPartnerships ?? []),
            upcoming_meetings: @json($upcomingMeetings ?? []),
            pending_approvals: @json($pendingPbphhApprovals ?? [])
        };

        function exportData() {
            // Show loading state
            const exportBtn = document.querySelector('button[onclick="exportData()"]');
            const originalText = exportBtn.innerHTML;
            exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengekspor...';
            exportBtn.disabled = true;
            
            // Convert to CSV format
            let csvContent = "data:text/csv;charset=utf-8,";
            
            // Add header
            csvContent += "Dashboard Dinas Kehutanan - Export Data\n";
            csvContent += "Tanggal Export: " + new Date().toLocaleString('id-ID') + "\n\n";
            
            // Add statistics
            csvContent += "STATISTIK DASHBOARD\n";
            csvContent += "Total KTHR," + dashboardData.stats.total_kthrs + "\n";
            csvContent += "Total PBPHH," + dashboardData.stats.total_pbphhs + "\n";
            csvContent += "Kemitraan Aktif," + dashboardData.stats.active_partnerships + "\n";
            csvContent += "PBPHH Menunggu Approval," + dashboardData.stats.pending_pbphh_approvals + "\n";
            csvContent += "Pertemuan Selesai," + dashboardData.stats.completed_meetings + "\n";
            csvContent += "Pertemuan Mendatang," + dashboardData.stats.pending_meetings + "\n\n";
            
            // Add recent partnerships
            if (dashboardData.recent_partnerships && dashboardData.recent_partnerships.length > 0) {
                csvContent += "KEMITRAAN TERBARU\n";
                csvContent += "KTHR,PBPHH,Status,Tanggal\n";
                dashboardData.recent_partnerships.forEach(partnership => {
                    const kthrName = partnership.kthr ? partnership.kthr.kthr_name : 'N/A';
                    const pbphhName = partnership.pbphh_profile ? partnership.pbphh_profile.company_name : 'N/A';
                    csvContent += '"' + kthrName + '","' + pbphhName + '","' + partnership.status + '","' + partnership.created_at + '"\n';
                });
                csvContent += "\n";
            }
            
            // Add upcoming meetings
            if (dashboardData.upcoming_meetings && dashboardData.upcoming_meetings.length > 0) {
                csvContent += "PERTEMUAN MENDATANG\n";
                csvContent += "KTHR,PBPHH,Waktu,Status\n";
                dashboardData.upcoming_meetings.forEach(meeting => {
                    const kthrName = meeting.permintaan_kerjasama && meeting.permintaan_kerjasama.kthr ? meeting.permintaan_kerjasama.kthr.kthr_name : 'N/A';
                    const pbphhName = meeting.permintaan_kerjasama && meeting.permintaan_kerjasama.pbphh_profile ? meeting.permintaan_kerjasama.pbphh_profile.company_name : 'N/A';
                    csvContent += '"' + kthrName + '","' + pbphhName + '","' + meeting.scheduled_time + '","' + meeting.status + '"\n';
                });
                csvContent += "\n";
            }
            
            // Create and download file
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "dashboard-dinas-" + new Date().toISOString().split('T')[0] + ".csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            // Reset button state
            setTimeout(function() {
                exportBtn.innerHTML = originalText;
                exportBtn.disabled = false;
                
                // Show success message
                const toast = document.createElement('div');
                toast.className = 'toast-notification';
                toast.innerHTML = '<i class="fas fa-check-circle me-2"></i>Data berhasil diekspor!';
                toast.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #28a745; color: white; padding: 12px 20px; border-radius: 5px; z-index: 9999; box-shadow: 0 4px 6px rgba(0,0,0,0.1);';
                document.body.appendChild(toast);
                
                setTimeout(function() {
                    document.body.removeChild(toast);
                }, 3000);
            }, 1000);
        }

        // Auto refresh setiap 10 menit
        setTimeout(() => location.reload(), 600000);
    </script>
@endpush