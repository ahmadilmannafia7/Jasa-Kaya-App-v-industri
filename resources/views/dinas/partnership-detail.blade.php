@extends('layouts.dinas')

@section('title', 'Detail Kemitraan - Dinas Kehutanan')

@push('styles')
<link href="{{ asset('css/dinas-dashboard.css') }}" rel="stylesheet">
@endpush

@section('dashboard-content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="fas fa-handshake me-2"></i>Detail Kemitraan
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
                        <i class="fas fa-info-circle me-2"></i>Informasi Kemitraan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>ID Permintaan:</strong>
                        </div>
                        <div class="col-sm-9">
                            #{{ $partnership->permintaan_kerjasama_id }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Status:</strong>
                        </div>
                        <div class="col-sm-9">
                            <span class="badge bg-{{ $partnership->status === 'Selesai' ? 'success' : ($partnership->status === 'Pending' ? 'warning' : 'info') }}">
                                {{ $partnership->status }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Tanggal Permintaan:</strong>
                        </div>
                        <div class="col-sm-9">
                            {{ $partnership->created_at->format('d F Y, H:i') }}
                        </div>
                    </div>
                    @if($partnership->updated_at != $partnership->created_at)
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Terakhir Diupdate:</strong>
                        </div>
                        <div class="col-sm-9">
                            {{ $partnership->updated_at->format('d F Y, H:i') }}
                        </div>
                    </div>
                    @endif
                    @if($partnership->additional_notes)
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Catatan Tambahan:</strong>
                        </div>
                        <div class="col-sm-9">
                            {{ $partnership->additional_notes }}
                        </div>
                    </div>
                    @endif
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
                                    {{ $partnership->kthr->kthr_name }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Email:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $partnership->kthr->user->email }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Alamat Sekretariat:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $partnership->kthr->alamat_sekretariat ?? 'Tidak diisi' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Luas Areal:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ number_format($partnership->kthr->luas_areal_ha, 2) }} Ha
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Telepon:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $partnership->kthr->phone ?? 'Tidak diisi' }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Wilayah:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $partnership->kthr->user->region ? $partnership->kthr->user->region->name : 'Tidak diketahui' }}
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
                                    {{ $partnership->pbphhProfile->company_name }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Email:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $partnership->pbphhProfile->user->email }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Alamat:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $partnership->pbphhProfile->alamat_perusahaan ?? 'Tidak diisi' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Jenis Produk Utama:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $partnership->pbphhProfile->jenis_produk_utama ?? 'Tidak diisi' }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Penanggung Jawab:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $partnership->pbphhProfile->penanggung_jawab ?? 'Tidak diisi' }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Telepon:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $partnership->pbphhProfile->phone ?? 'Tidak diisi' }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Kapasitas Produksi:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $partnership->pbphhProfile->kapasitas_izin_produksi_m3 ? number_format($partnership->pbphhProfile->kapasitas_izin_produksi_m3, 2) . ' m³' : 'Tidak diisi' }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Tahun Berdiri:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $partnership->pbphhProfile->tahun_berdiri ?? 'Tidak diisi' }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Wilayah:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $partnership->pbphhProfile->user->region ? $partnership->pbphhProfile->user->region->name : 'Tidak diketahui' }}
                                </div>
                            </div>
                            @if($partnership->pbphhProfile->deskripsi_perusahaan)
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Deskripsi Perusahaan:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $partnership->pbphhProfile->deskripsi_perusahaan }}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- PBPHH Material Needs -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-boxes me-2"></i>Kebutuhan Material PBPHH
                    </h5>
                </div>
                <div class="card-body">
                    @if($partnership->pbphhProfile->materialNeeds && $partnership->pbphhProfile->materialNeeds->count() > 0)
                        @foreach($partnership->pbphhProfile->materialNeeds as $need)
                        <div class="border rounded p-3 mb-3">
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <strong>Jenis Kayu:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $need->jenis_kayu ?? 'Tidak diisi' }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <strong>Tipe:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $need->tipe ?? 'Tidak diisi' }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <strong>Kebutuhan Bulanan:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $need->kebutuhan_bulanan_m3 ? number_format($need->kebutuhan_bulanan_m3, 2) . ' m³/bulan' : 'Tidak diisi' }}
                                </div>
                            </div>
                            @if($need->spesifikasi_tambahan)
                            <div class="row">
                                <div class="col-sm-4">
                                    <strong>Spesifikasi:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $need->spesifikasi_tambahan }}
                                </div>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-boxes fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">Belum ada kebutuhan material yang diinput</h6>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Status Kemitraan
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-handshake fa-3x text-{{ $partnership->status === 'Selesai' ? 'success' : 'warning' }}"></i>
                    </div>
                    <h6>{{ $partnership->status }}</h6>
                    <p class="text-muted">Dibuat {{ $partnership->created_at->diffForHumans() }}</p>
                </div>
            </div>

            <!-- Timeline -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>Timeline
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6>Permintaan Dibuat</h6>
                                <small class="text-muted">{{ $partnership->created_at->format('d M Y, H:i') }}</small>
                            </div>
                        </div>
                        
                        @if($partnership->pertemuans && $partnership->pertemuans->count() > 0)
                        @foreach($partnership->pertemuans as $meeting)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6>Pertemuan {{ $meeting->status }}</h6>
                                <small class="text-muted">{{ $meeting->scheduled_time->format('d M Y, H:i') }}</small>
                            </div>
                        </div>
                        @endforeach
                        @endif
                        
                        @if($partnership->kesepakatanKerjasama)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6>Kesepakatan Dibuat</h6>
                                <small class="text-muted">{{ $partnership->kesepakatanKerjasama->created_at->format('d M Y, H:i') }}</small>
                                @if($partnership->kesepakatanKerjasama->final_document_path)
                                <div class="mt-2">
                                    <a href="{{ Storage::url($partnership->kesepakatanKerjasama->final_document_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-file-download me-1"></i>Unduh Dokumen
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($partnership->pertemuans && $partnership->pertemuans->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-check me-2"></i>Riwayat Pertemuan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal Jadwal</th>
                                    <th>Status</th>
                                    <th>Lokasi</th>
                                    <th>Ringkasan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($partnership->pertemuans as $meeting)
                                <tr>
                                    <td>{{ $meeting->scheduled_time->format('d M Y, H:i') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $meeting->status === 'Selesai' ? 'success' : ($meeting->status === 'Dijadwalkan' ? 'primary' : 'warning') }}">
                                            {{ $meeting->status }}
                                        </span>
                                    </td>
                                    <td>{{ $meeting->location ?? 'Tidak diisi' }}</td>
                                    <td>{{ Str::limit($meeting->meeting_summary ?? 'Belum ada ringkasan', 50) }}</td>
                                    <td>
                                        <a href="{{ route('dinas.meeting.detail', $meeting->meeting_id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
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