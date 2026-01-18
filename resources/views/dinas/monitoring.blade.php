@extends('layouts.dinas')

@section('title', 'Monitoring - Dinas Kehutanan')

@push('styles')
<link href="{{ asset('css/dinas-dashboard.css') }}" rel="stylesheet">
@endpush

@section('dashboard-content')
    <!-- Header Section -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2 mb-1">
                    <i class="fas fa-chart-line header-icon"></i>Monitoring Provinsi
                </h1>
                <p class="text-muted mb-0">Dashboard komprehensif untuk monitoring kemitraan kehutanan</p>
            </div>
            <div class="btn-toolbar">
                <div class="btn-group me-2">
                    <button type="button" class="btn btn-outline-secondary modern-btn" onclick="exportData()">
                        <i class="fas fa-download me-1"></i>Export Data
                    </button>
                    <button type="button" class="btn btn-outline-secondary modern-btn" onclick="printReport()">
                        <i class="fas fa-print me-1"></i>Cetak Laporan
                    </button>
                </div>
                <button type="button" class="btn btn-primary modern-btn" onclick="location.reload()">
                    <i class="fas fa-sync-alt me-1"></i>Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Executive Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-3">
            <div class="stats-card text-center">
                <div class="stats-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stats-number">
                    {{ number_format($overallStats['total_entities']) }}
                </div>
                <div class="stats-label">Total Entitas Terdaftar</div>
                <small class="text-success"><i class="fas fa-arrow-up"></i> KTHR & PBPHH Aktif</small>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-3">
            <div class="stats-card text-center">
                <div class="stats-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <div class="stats-number">{{ number_format($overallStats['total_partnerships']) }}</div>
                <div class="stats-label">Total Kemitraan</div>
                <small class="text-info"><i class="fas fa-chart-line"></i> Semua Status</small>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-3">
            <div class="stats-card text-center">
                <div class="stats-icon">
                    <i class="fas fa-percentage"></i>
                </div>
                <div class="stats-number">{{ $overallStats['success_rate'] }}%</div>
                <div class="stats-label">Tingkat Keberhasilan</div>
                <small class="text-{{ $overallStats['success_rate'] >= 70 ? 'success' : 'warning' }}">
                    <i class="fas fa-{{ $overallStats['success_rate'] >= 70 ? 'check' : 'exclamation-triangle' }}"></i> 
                    {{ $overallStats['success_rate'] >= 70 ? 'Target Tercapai' : 'Perlu Peningkatan' }}
                </small>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-3">
            <div class="stats-card text-center">
                <div class="stats-icon">
                    <i class="fas fa-tree"></i>
                </div>
                <div class="stats-number">{{ number_format($overallStats['total_wood_potential'], 0, ',', '.') }}</div>
                <div class="stats-label">Potensi Kayu (Ha)</div>
                <small class="text-success"><i class="fas fa-leaf"></i> Luas Total Areal</small>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="executive-card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Tren Permintaan 12 Bulan Terakhir
                    </h5>
                </div>
                <div class="card-body">
                    @if (!empty($monthlyTrends) && $monthlyTrends->count() > 0)
                        <div class="chart-container">
                            <canvas id="monthlyChart"></canvas>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Data trend belum tersedia</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="executive-card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-pie-chart me-2"></i>Status Kemitraan
                    </h5>
                </div>
                <div class="card-body">
                    @if (!empty($partnershipStatus))
                        <div class="chart-container">
                            <canvas id="statusChart"></canvas>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-pie-chart fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Data status belum tersedia</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>


@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data for JavaScript
    window.monitoringData = {
        monthlyTrends: {!! json_encode($monthlyTrends ?? []) !!},
        partnershipStatus: {!! json_encode($partnershipStatus ?? []) !!}
    };
    
    // Chart initialization
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Trends Chart
    if (window.monitoringData.monthlyTrends && window.monitoringData.monthlyTrends.length > 0) {
        const monthlyCtx = document.getElementById('monthlyChart');
        if (monthlyCtx) {
            const ctx = monthlyCtx.getContext('2d');
            
            // Extract data
            const chartLabels = window.monitoringData.monthlyTrends.map(item => item.month);
            const totalData = window.monitoringData.monthlyTrends.map(item => item.total);
            const completedData = window.monitoringData.monthlyTrends.map(item => item.completed);
            
            // Format labels to be more readable
            const formattedLabels = chartLabels.map(label => {
                const parts = label.split('-');
                if (parts.length === 2) {
                    const year = parts[0];
                    const month = parts[1];
                    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
                    return monthNames[parseInt(month) - 1] + ' ' + year;
                }
                return label;
            });
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: formattedLabels,
                    datasets: [
                        {
                            label: 'Total Permintaan',
                            data: totalData,
                            backgroundColor: 'rgba(54, 162, 235, 0.1)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 6
                        },
                        {
                            label: 'Kemitraan Selesai',
                            data: completedData,
                            backgroundColor: 'rgba(40, 167, 69, 0.1)',
                            borderColor: 'rgba(40, 167, 69, 1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: 'rgba(40, 167, 69, 1)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.1)'
                            },
                            ticks: {
                                stepSize: 1
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            titleColor: 'white',
                            bodyColor: 'white',
                            borderColor: 'rgba(255,255,255,0.1)',
                            borderWidth: 1,
                            cornerRadius: 8,
                            displayColors: true
                        }
                    }
                }
            });
        }
    }

    // Partnership Status Pie Chart
    if (window.monitoringData.partnershipStatus && Object.keys(window.monitoringData.partnershipStatus).length > 0) {
        const statusCtx = document.getElementById('statusChart');
        if (statusCtx) {
            const ctx = statusCtx.getContext('2d');
            
            const statusData = window.monitoringData.partnershipStatus;
            const statusLabels = Object.keys(statusData);
            const statusValues = Object.values(statusData);
            
            // Color mapping for different statuses
            const statusColors = {
                'Pending': '#ffc107',
                'Diproses': '#17a2b8',
                'Disetujui': '#28a745',
                'Ditolak': '#dc3545',
                'Selesai': '#20c997'
            };
            
            const backgroundColors = statusLabels.map(status => statusColors[status] || '#6c757d');
            
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: statusLabels.map(status => status.charAt(0).toUpperCase() + status.slice(1)),
                    datasets: [{
                        data: statusValues,
                        backgroundColor: backgroundColors,
                        borderWidth: 3,
                        borderColor: '#fff',
                        hoverBorderWidth: 4,
                        hoverBorderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '60%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 15,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            titleColor: 'white',
                            bodyColor: 'white',
                            borderColor: 'rgba(255,255,255,0.1)',
                            borderWidth: 1,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((context.parsed / total) * 100).toFixed(1);
                                    return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
        }
    }
});



// Export data function
function exportData() {
    const csvContent = generateCSVContent();
    
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', 'monitoring_provinsi_' + new Date().toISOString().split('T')[0] + '.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function generateCSVContent() {
    const headers = ['Status', 'Jumlah'];
    let csvContent = headers.join(',') + '\n';
    
    if (window.monitoringData.partnershipStatus && Object.keys(window.monitoringData.partnershipStatus).length > 0) {
        Object.entries(window.monitoringData.partnershipStatus).forEach(([status, count]) => {
            const row = [
                '"' + status + '"',
                count
            ];
            csvContent += row.join(',') + '\n';
        });
    }
    
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