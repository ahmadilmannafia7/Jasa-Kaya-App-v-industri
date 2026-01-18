@extends('layouts.dinas')

@section('title', 'Laporan Strategis - ' . ucfirst($reportType))

@section('dashboard-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-file-alt me-2"></i>Laporan Strategis
        <small class="text-muted">{{ ucfirst($reportType) }}</small>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-download me-1"></i>Export
            </button>
        </div>
        <button type="button" class="btn btn-sm btn-primary" onclick="location.reload()">
            <i class="fas fa-sync-alt me-1"></i>Refresh
        </button>
    </div>
</div>

<div class="alert alert-info mb-4">
    <i class="fas fa-calendar me-2"></i><strong>Periode:</strong> {{ $dateFrom }} sampai {{ $dateTo }}
</div>

    @if ($reportType == 'summary')
    <div class="row mb-4">
        <div class="col-lg-6 mb-3">
            <div class="executive-card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-plus me-2"></i>Registrasi Pengguna
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>KTHR:</span>
                        <span class="badge bg-success">{{ $reportData['registrations']['kthr'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>PBPHH:</span>
                        <span class="badge bg-primary">{{ $reportData['registrations']['pbphh'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-3">
            <div class="executive-card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-check-circle me-2"></i>Persetujuan Akun
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>KTHR Disetujui:</span>
                        <span class="badge bg-success">{{ $reportData['approvals']['kthr_approved'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>PBPHH Disetujui:</span>
                        <span class="badge bg-primary">{{ $reportData['approvals']['pbphh_approved'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-3">
            <div class="executive-card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-handshake me-2"></i>Permintaan Kerjasama
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Dibuat:</span>
                        <span class="badge bg-info">{{ $reportData['partnerships']['created'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Selesai:</span>
                        <span class="badge bg-success">{{ $reportData['partnerships']['completed'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-3">
            <div class="executive-card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-check me-2"></i>Pertemuan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Terjadwal:</span>
                        <span class="badge bg-warning">{{ $reportData['meetings']['scheduled'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Selesai:</span>
                        <span class="badge bg-success">{{ $reportData['meetings']['completed'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Informasi:</strong> Tampilan untuk tipe laporan <code>{{ $reportType }}</code> belum tersedia.
        </div>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-code me-2"></i>Data Raw
                </h5>
            </div>
            <div class="card-body">
                <pre class="bg-light p-3 rounded">{{ json_encode($reportData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        </div>
    @endif
@endsection
