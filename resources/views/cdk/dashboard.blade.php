@extends('layouts.cdk')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/cdk-dashboard.css') }}">
    <style>
         .stats-card:hover {
             transform: translateY(-2px);
             transition: transform 0.2s ease-in-out;
         }
         
         .card {
            border: none;
            border-radius: 10px;
        }
        
        .card-header {
            border-bottom: 1px solid #e9ecef;
            border-radius: 10px 10px 0 0 !important;
        }
        
        .btn {
            border-radius: 8px;
            transition: all 0.2s ease-in-out;
        }
        
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        @media (max-width: 768px) {
            .stats-card {
                margin-bottom: 1rem;
            }
            
            .card-body {
                padding: 1rem;
            }
        }
    </style>
@endpush

@section('title', 'Dashboard CDK - ' . $region->name)

@section('dashboard-content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="fas fa-tachometer-alt me-2"></i>Dashboard CDK
            <small class="text-muted">{{ $region->name }}</small>
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-download me-1"></i>Export
                </button>
            </div>
            <button type="button" class="btn btn-sm btn-primary">
                <i class="fas fa-sync-alt me-1"></i>Refresh
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stats-card text-center h-100 shadow-sm">
                <div class="stats-icon text-primary">
                    <i class="fas fa-user-clock"></i>
                </div>
                <div class="stats-number text-primary fw-bold">{{ $stats['pending_kthr_approvals'] }}</div>
                <div class="stats-label text-muted small">KTHR Approval</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stats-card text-center h-100 shadow-sm">
                <div class="stats-icon text-info">
                    <i class="fas fa-handshake"></i>
                </div>
                <div class="stats-number text-info fw-bold">{{ $stats['partnerships_need_facilitation'] }}</div>
                <div class="stats-label text-muted small">Perlu Fasilitasi</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stats-card text-center h-100 shadow-sm">
                <div class="stats-icon text-success">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stats-number text-success fw-bold">{{ $stats['scheduled_meetings'] }}</div>
                <div class="stats-label text-muted small">Pertemuan Terjadwal</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stats-card text-center h-100 shadow-sm">
                <div class="stats-icon text-warning">
                    <i class="fas fa-tree"></i>
                </div>
                <div class="stats-number text-warning fw-bold">{{ $stats['active_kthrs'] }}</div>
                <div class="stats-label text-muted small">KTHR Aktif</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stats-card text-center h-100 shadow-sm">
                <div class="stats-icon text-secondary">
                    <i class="fas fa-industry"></i>
                </div>
                <div class="stats-number text-secondary fw-bold">{{ $stats['active_pbphhs'] }}</div>
                <div class="stats-label text-muted small">PBPHH Aktif</div>
            </div>
        </div>
    </div>

    <!-- Urgent Tasks Section -->
    <div class="row g-3 mb-4">
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        Approval Mendesak
                    </h5>
                </div>
                <div class="card-body activity-feed">
                    @forelse($urgentTasks['pending_approvals'] as $user)
                        <div class="d-flex align-items-center mb-3 p-2 urgent-task rounded">
                            <div class="flex-shrink-0">
                                <i class="fas fa-tree fa-2x text-warning"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">
                                    {{ $user->kthr?->kthr_name ?? '-' }}
                                </h6>
                                <small class="text-muted">{{ $user->email }}</small><br>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $user->created_at->diffForHumans() }}
                                </small>
                            </div>
                            <div class="flex-shrink-0">
                                <a href="{{ route('cdk.approvals') }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-check-circle fa-3x mb-2"></i>
                            <p>Tidak ada approval mendesak</p>
                        </div>
                    @endforelse
                </div>
                @if($urgentTasks['pending_approvals']->count() > 0)
                    <div class="card-footer text-center">
                        <a href="{{ route('cdk.approvals') }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-list me-1"></i>Lihat Semua
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-handshake text-info me-2"></i>
                        Perlu Fasilitasi
                    </h5>
                </div>
                <div class="card-body activity-feed">
                    @forelse($urgentTasks['partnerships_to_facilitate'] as $partnership)
                        <div class="d-flex align-items-center mb-3 p-2 priority-medium rounded">
                            <div class="flex-shrink-0">
                                <i class="fas fa-handshake fa-2x text-info"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">{{ $partnership->kthr?->kthr_name ?? '-' }}</h6>
                                <small class="text-muted">{{ $partnership->pbphhProfile?->company_name ?? '-' }}</small>

                                <small class="text-muted">
                                    <i class="fas fa-cube me-1"></i>{{ $partnership->formatted_volume }}/bulan
                                </small>
                            </div>
                            <div class="flex-shrink-0">
                                <a href="{{ route('cdk.meetings') }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-calendar-plus"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-check-circle fa-3x mb-2"></i>
                            <p>Tidak ada yang perlu difasilitasi</p>
                        </div>
                    @endforelse
                </div>
                @if($urgentTasks['partnerships_to_facilitate']->count() > 0)
                    <div class="card-footer text-center">
                        <a href="{{ route('cdk.meetings') }}" class="btn btn-info btn-sm">
                            <i class="fas fa-calendar-alt me-1"></i>Jadwalkan Pertemuan
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-check text-success me-2"></i>
                        Pertemuan Mendatang
                    </h5>
                </div>
                <div class="card-body activity-feed">
                    @forelse($urgentTasks['upcoming_meetings'] as $meeting)
                        <div class="d-flex align-items-center mb-3 p-2 priority-low rounded">
                            <div class="flex-shrink-0">
                                <i class="fas fa-calendar-check fa-2x text-success"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">{{ $meeting->permintaanKerjasama->kthr?->kthr_name ?? '-' }}</h6>
                                <small
                                    class="text-muted">{{ $meeting->permintaanKerjasama->pbphhProfile?->company_name ?? '-' }}</small>
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $meeting->scheduled_time->format('d/m/Y H:i') }}
                                </small><br>
                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    {{ ucfirst($meeting->method) }}
                                </small>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="badge bg-success">{{ $meeting->scheduled_time->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-calendar fa-3x mb-2"></i>
                            <p>Tidak ada pertemuan mendatang</p>
                        </div>
                    @endforelse
                </div>
                @if($urgentTasks['upcoming_meetings']->count() > 0)
                    <div class="card-footer text-center">
                        <a href="{{ route('cdk.meetings') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-calendar-alt me-1"></i>Lihat Jadwal
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt text-warning me-2"></i>Aksi Cepat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('cdk.approvals') }}" class="btn btn-outline-primary w-100 py-2">
                                <i class="fas fa-user-check me-2"></i>Review Approval
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('cdk.meetings') }}" class="btn btn-outline-info w-100 py-2">
                                <i class="fas fa-calendar-plus me-2"></i>Jadwalkan Pertemuan
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('cdk.monitoring') }}" class="btn btn-outline-success w-100 py-2">
                                <i class="fas fa-chart-line me-2"></i>Monitoring Wilayah
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('cdk.reports') }}" class="btn btn-outline-secondary w-100 py-2">
                                <i class="fas fa-file-alt me-2"></i>Generate Laporan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Regional Performance Summary -->
    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar text-primary me-2"></i>Ringkasan Kinerja Wilayah
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <div class="border-end">
                                <h3 class="text-primary">{{ $stats['active_kthrs'] + $stats['active_pbphhs'] }}</h3>
                                <p class="text-muted mb-0">Total Entitas Aktif</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="border-end">
                                <h3 class="text-success">
                                    {{ $stats['partnerships_need_facilitation'] + $stats['scheduled_meetings'] }}
                                </h3>
                                <p class="text-muted mb-0">Kemitraan Dalam Proses</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h3 class="text-info">
                                {{ round((($stats['active_kthrs'] + $stats['active_pbphhs']) / max(1, $stats['pending_kthr_approvals'] + $stats['active_kthrs'] + $stats['active_pbphhs'])) * 100) }}%
                            </h3>
                            <p class="text-muted mb-0">Tingkat Approval</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-tasks text-success me-2"></i>Status Tugas
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Approval Pending</span>
                            <span
                                class="badge bg-warning">{{ $stats['pending_kthr_approvals'] }}</span>
                        </div>
                        <div class="progress mt-1" style="height: 6px;">
                            <div class="progress-bar bg-warning"
                                style="width: {{ min(100, $stats['pending_kthr_approvals'] * 10) }}%">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Perlu Fasilitasi</span>
                            <span class="badge bg-info">{{ $stats['partnerships_need_facilitation'] }}</span>
                        </div>
                        <div class="progress mt-1" style="height: 6px;">
                            <div class="progress-bar bg-info"
                                style="width: {{ min(100, $stats['partnerships_need_facilitation'] * 20) }}%"></div>
                        </div>
                    </div>
                    <div class="mb-0">
                        <div class="d-flex justify-content-between">
                            <span>Pertemuan Terjadwal</span>
                            <span class="badge bg-success">{{ $stats['scheduled_meetings'] }}</span>
                        </div>
                        <div class="progress mt-1" style="height: 6px;">
                            <div class="progress-bar bg-success"
                                style="width: {{ min(100, $stats['scheduled_meetings'] * 15) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Auto refresh every 5 minutes
        setTimeout(function () {
            location.reload();
        }, 300000);

        // Add click handlers for quick stats
        document.addEventListener('DOMContentLoaded', function () {
            // Add hover effects to stats cards
            const statsCards = document.querySelectorAll('.stats-card');
            statsCards.forEach(card => {
                card.addEventListener('mouseenter', function () {
                    this.style.transform = 'translateY(-2px)';
                });
                card.addEventListener('mouseleave', function () {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Add functionality to Export button
            const exportBtn = document.querySelector('.btn-outline-secondary');
            if (exportBtn) {
                exportBtn.addEventListener('click', function() {
                    // Get data from PHP variables
                    const regionName = {!! json_encode($region->name) !!};
                    const statsData = {
                        pending_approvals: {!! json_encode($stats['pending_kthr_approvals']) !!},
                        partnerships_facilitation: {!! json_encode($stats['partnerships_need_facilitation']) !!},
                        scheduled_meetings: {!! json_encode($stats['scheduled_meetings']) !!},
                        active_kthrs: {!! json_encode($stats['active_kthrs']) !!},
                        active_pbphhs: {!! json_encode($stats['active_pbphhs']) !!}
                    };
                    
                    // Convert to CSV format
                    const csvContent = "data:text/csv;charset=utf-8," 
                        + "Metric,Value\n"
                        + "Region," + regionName + "\n"
                        + "Pending KTHR Approvals," + statsData.pending_approvals + "\n"
                        + "Partnerships Need Facilitation," + statsData.partnerships_facilitation + "\n"
                        + "Scheduled Meetings," + statsData.scheduled_meetings + "\n"
                        + "Active KTHRs," + statsData.active_kthrs + "\n"
                        + "Active PBPHHs," + statsData.active_pbphhs + "\n"
                        + "Exported At," + new Date().toISOString();
                    
                    // Create download link
                    const encodedUri = encodeURI(csvContent);
                    const link = document.createElement("a");
                    link.setAttribute("href", encodedUri);
                    link.setAttribute("download", "dashboard_stats_" + new Date().toISOString().split('T')[0] + ".csv");
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    
                    // Show success message
                    alert('Data dashboard berhasil diekspor!');
                });
            }

            // Add functionality to Refresh button
            const refreshBtn = document.querySelector('.btn-primary');
            if (refreshBtn) {
                refreshBtn.addEventListener('click', function() {
                    // Show loading state
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Refreshing...';
                    this.disabled = true;
                    
                    // Reload the page after a short delay
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                });
            }
        });
    </script>
@endpush