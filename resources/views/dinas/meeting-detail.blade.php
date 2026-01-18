@extends('layouts.dinas')

@section('title', 'Detail Pertemuan - Dinas Kehutanan')

@push('styles')
<link href="{{ asset('css/dinas-dashboard.css') }}" rel="stylesheet">
@endpush

@section('dashboard-content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="fas fa-calendar-check me-2"></i>Detail Pertemuan
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('dinas.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informasi Pertemuan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>ID Pertemuan:</strong>
                        </div>
                        <div class="col-sm-9">
                            #{{ $meeting->pertemuan_id }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Status:</strong>
                        </div>
                        <div class="col-sm-9">
                            <span class="badge bg-{{ $meeting->status === 'Selesai' ? 'success' : ($meeting->status === 'Dijadwalkan' ? 'primary' : 'warning') }}">
                                {{ $meeting->status }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Tanggal & Waktu:</strong>
                        </div>
                        <div class="col-sm-9">
                            {{ $meeting->scheduled_time->format('d F Y, H:i') }} WIB
                        </div>
                    </div>
                    @if($meeting->actual_start_time)
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Waktu Mulai Aktual:</strong>
                        </div>
                        <div class="col-sm-9">
                            {{ $meeting->actual_start_time->format('d F Y, H:i') }} WIB
                        </div>
                    </div>
                    @endif
                    @if($meeting->actual_end_time)
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Waktu Selesai Aktual:</strong>
                        </div>
                        <div class="col-sm-9">
                            {{ $meeting->actual_end_time->format('d F Y, H:i') }} WIB
                        </div>
                    </div>
                    @endif
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Lokasi:</strong>
                        </div>
                        <div class="col-sm-9">
                            {{ $meeting->location ?? 'Belum ditentukan' }}
                        </div>
                    </div>
                    @if($meeting->meeting_summary)
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Ringkasan Pertemuan:</strong>
                        </div>
                        <div class="col-sm-9">
                            <div class="border rounded p-3 bg-light">
                                {{ $meeting->meeting_summary }}
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Dibuat:</strong>
                        </div>
                        <div class="col-sm-9">
                            {{ $meeting->created_at->format('d F Y, H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Partnership Information -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-handshake me-2"></i>Informasi Kemitraan Terkait
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>ID Kemitraan:</strong>
                        </div>
                        <div class="col-sm-9">
                            <a href="{{ route('dinas.partnership.detail', $meeting->permintaanKerjasama->request_id) }}" class="text-decoration-none">
                                #{{ $meeting->permintaanKerjasama->permintaan_kerjasama_id }}
                                <i class="fas fa-external-link-alt ms-1"></i>
                            </a>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Status Kemitraan:</strong>
                        </div>
                        <div class="col-sm-9">
                            <span class="badge bg-{{ $meeting->permintaanKerjasama->status === 'Selesai' ? 'success' : 'warning' }}">
                                {{ $meeting->permintaanKerjasama->status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KTHR Information -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-tree me-2"></i>Informasi KTHR
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Nama KTHR:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $meeting->permintaanKerjasama->kthr->kthr_name }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Email:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $meeting->permintaanKerjasama->kthr->user->email }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Kontak:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $meeting->permintaanKerjasama->kthr->nama_pendamping ?? 'Tidak diisi' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>No. Telepon:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $meeting->permintaanKerjasama->kthr->phone ?? 'Tidak diisi' }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Luas Areal:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ number_format($meeting->permintaanKerjasama->kthr->luas_areal_ha, 2) }} Ha
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Wilayah:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $meeting->permintaanKerjasama->kthr->user->region ? $meeting->permintaanKerjasama->kthr->user->region->name : 'Tidak diketahui' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PBPHH Information -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-industry me-2"></i>Informasi PBPHH
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Nama Perusahaan:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $meeting->permintaanKerjasama->pbphhProfile->company_name }}
                                    @if(isset($meeting->kesepakatan) && $meeting->kesepakatan->final_document_path)
                                        <br>
                                        <a href="{{ Storage::url($meeting->kesepakatan->final_document_path) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                                            <i class="fas fa-file-download me-1"></i>Unduh Dokumen Kerjasama
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Email:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $meeting->permintaanKerjasama->pbphhProfile->user->email }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Kontak Person:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $meeting->permintaanKerjasama->pbphhProfile->penanggung_jawab ?? 'Tidak diisi' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>No. Telepon:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $meeting->permintaanKerjasama->pbphhProfile->phone ?? 'Tidak diisi' }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Jenis Usaha:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $meeting->permintaanKerjasama->pbphhProfile->jenis_produk_utama ?? 'Tidak diisi' }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Wilayah:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $meeting->permintaanKerjasama->pbphhProfile->user->region ? $meeting->permintaanKerjasama->pbphhProfile->user->region->name : 'Tidak diketahui' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Status Pertemuan
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-calendar-check fa-3x text-{{ $meeting->status === 'Selesai' ? 'success' : ($meeting->status === 'Dijadwalkan' ? 'primary' : 'warning') }}"></i>
                    </div>
                    <h6>{{ $meeting->status }}</h6>
                    <p class="text-muted">Dijadwalkan {{ $meeting->scheduled_time->diffForHumans() }}</p>
                    @if($meeting->status === 'Selesai' && $meeting->actual_start_time && $meeting->actual_end_time)
                    <hr>
                    <small class="text-muted">
                        Durasi: {{ $meeting->actual_start_time->diffInMinutes($meeting->actual_end_time) }} menit
                    </small>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>Aksi Cepat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('dinas.partnership.detail', $meeting->permintaanKerjasama->request_id) }}" class="btn btn-outline-primary">
                            <i class="fas fa-handshake me-1"></i>Lihat Kemitraan
                        </a>
                        <a href="{{ route('dinas.registration.detail', $meeting->permintaanKerjasama->kthr->user->user_id) }}" class="btn btn-outline-success">
                            <i class="fas fa-tree me-1"></i>Detail KTHR
                        </a>
                        <a href="{{ route('dinas.registration.detail', $meeting->permintaanKerjasama->pbphhProfile->user->user_id) }}" class="btn btn-outline-info">
                            <i class="fas fa-industry me-1"></i>Detail PBPHH
                        </a>
                    </div>
                </div>
            </div>

            <!-- Meeting Timeline -->
            @if($meeting->status === 'Selesai')
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>Timeline Pertemuan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6>Pertemuan Dijadwalkan</h6>
                                <small class="text-muted">{{ $meeting->created_at->format('d M Y, H:i') }}</small>
                            </div>
                        </div>
                        
                        @if($meeting->actual_start_time)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6>Pertemuan Dimulai</h6>
                                <small class="text-muted">{{ $meeting->actual_start_time->format('d M Y, H:i') }}</small>
                            </div>
                        </div>
                        @endif
                        
                        @if($meeting->actual_end_time)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6>Pertemuan Selesai</h6>
                                <small class="text-muted">{{ $meeting->actual_end_time->format('d M Y, H:i') }}</small>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-content h6 {
    margin-bottom: 5px;
    font-size: 14px;
}
</style>
@endpush