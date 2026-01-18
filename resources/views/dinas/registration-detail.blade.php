@extends('layouts.dinas')

@section('title', 'Detail Pendaftaran - Dinas Kehutanan')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@push('styles')
<link href="{{ asset('css/dinas-dashboard.css') }}" rel="stylesheet">
@endpush

@section('dashboard-content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="fas fa-user-circle me-2"></i>Detail Pendaftaran
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
                        <i class="fas fa-info-circle me-2"></i>Informasi Pengguna
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Email:</strong>
                        </div>
                        <div class="col-sm-9">
                            {{ $user->email }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Role:</strong>
                        </div>
                        <div class="col-sm-9">
                            <span class="badge bg-{{ $user->role === 'KTHR_PENYULUH' ? 'success' : 'info' }}">
                                {{ $user->role === 'KTHR_PENYULUH' ? 'KTHR Penyuluh' : 'PBPHH' }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Status Approval:</strong>
                        </div>
                        <div class="col-sm-9">
                            <span class="badge bg-{{ $user->approval_status === 'Approved' ? 'success' : ($user->approval_status === 'Pending' ? 'warning' : 'danger') }}">
                                {{ $user->approval_status }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Wilayah:</strong>
                        </div>
                        <div class="col-sm-9">
                            {{ $user->region ? $user->region->name : 'Belum diisi' }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Tanggal Daftar:</strong>
                        </div>
                        <div class="col-sm-9">
                            {{ $user->created_at->format('d F Y, H:i') }}
                        </div>
                    </div>
                    @if($user->approved_at)
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Tanggal Approval:</strong>
                        </div>
                        <div class="col-sm-9">
                            {{ \Carbon\Carbon::parse($user->approved_at)->format('d F Y, H:i') }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Statistik
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <i class="fas {{ $user->role === 'KTHR_PENYULUH' ? 'fa-tree' : 'fa-industry' }} fa-3x text-{{ $user->role === 'KTHR_PENYULUH' ? 'success' : 'info' }}"></i>
                    </div>
                    <div class="text-center">
                        <h6>{{ $user->role === 'KTHR_PENYULUH' ? 'KTHR Penyuluh' : 'PBPHH' }}</h6>
                        <p class="text-muted">Terdaftar {{ $user->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($user->role === 'KTHR_PENYULUH' && $user->kthr)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-tree me-2"></i>Detail KTHR
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
                                    {{ $user->kthr->kthr_name ?? 'Belum diisi' }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Alamat Sekretariat:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $user->kthr->alamat_sekretariat ?? 'Belum diisi' }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Luas Areal:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $user->kthr->luas_areal_ha ? number_format($user->kthr->luas_areal_ha, 2) . ' Ha' : 'Belum diisi' }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Nama Pendamping:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $user->kthr->nama_pendamping ?? 'Belum diisi' }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Jumlah Anggota:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $user->kthr->jumlah_anggota ?? 'Belum diisi' }} orang
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Koordinat:</strong>
                                </div>
                                <div class="col-sm-8">
                                    @if($user->kthr->coordinate_lat && $user->kthr->coordinate_lng)
                                        {{ $user->kthr->coordinate_lat }}, {{ $user->kthr->coordinate_lng }}
                                    @else
                                        Belum diisi
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>No. Telepon:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $user->kthr->phone ?? 'Belum diisi' }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Wilayah:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $user->kthr->region ? $user->kthr->region->name : 'Belum diisi' }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Pertemuan Tahunan:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $user->kthr->jumlah_pertemuan_tahunan ?? 'Belum diisi' }} kali/tahun
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Status Kesiapan:</strong>
                                </div>
                                <div class="col-sm-8">
                                    <span class="badge bg-{{ $user->kthr->is_siap_mitra ? 'success' : 'warning' }} me-1">
                                        {{ $user->kthr->is_siap_mitra ? 'Siap Bermitra' : 'Belum Siap Bermitra' }}
                                    </span>
                                    <span class="badge bg-{{ $user->kthr->is_siap_tebang ? 'success' : 'warning' }}">
                                        {{ $user->kthr->is_siap_tebang ? 'Siap Tebang' : 'Belum Siap Tebang' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($user->kthr->ketua_ktp_path || $user->kthr->sk_register_path || $user->kthr->shp_file_path)
                    <hr>
                    <h6><i class="fas fa-file-alt me-2"></i>Dokumen</h6>
                    <div class="row">
                        @if($user->kthr->ketua_ktp_path)
                        <div class="col-md-4 mb-2">
                            <a href="{{ Storage::url($user->kthr->ketua_ktp_path) }}" target="_blank" class="btn btn-outline-primary">
                                <i class="fas fa-id-card me-1"></i>KTP Ketua
                            </a>
                        </div>
                        @endif
                        @if($user->kthr->sk_register_path)
                        <div class="col-md-4 mb-2">
                            <a href="{{ Storage::url($user->kthr->sk_register_path) }}" target="_blank" class="btn btn-outline-secondary">
                                <i class="fas fa-file-alt me-1"></i>SK Register
                            </a>
                        </div>
                        @endif
                        @if($user->kthr->shp_file_path)
                        <div class="col-md-4 mb-2">
                            <a href="{{ Storage::url($user->kthr->shp_file_path) }}" target="_blank" class="btn btn-outline-info">
                                <i class="fas fa-map me-1"></i>File SHP
                            </a>
                        </div>
                        @endif
                    </div>
                    @endif
                    
                    @if($user->kthr->plantSpecies && $user->kthr->plantSpecies->count() > 0)
                    <hr>
                    <h6><i class="fas fa-seedling me-2"></i>Jenis Tanaman</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Jenis Tanaman</th>
                                    <th>Tipe</th>
                                    <th>Jumlah Pohon</th>
                                    <th>Tahun Tanam</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->kthr->plantSpecies as $species)
                                <tr>
                                    <td>{{ $species->jenis_tanaman ?? 'Belum diisi' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $species->tipe === 'Kayu' ? 'success' : 'info' }}">
                                            {{ $species->tipe ?? 'Belum diisi' }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($species->jumlah_pohon ?? 0) }}</td>
                                    <td>{{ $species->tahun_tanam ?? 'Belum diisi' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <hr>
                    <h6><i class="fas fa-seedling me-2"></i>Jenis Tanaman</h6>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>Belum ada data jenis tanaman yang diinput.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($user->role === 'PBPHH' && $user->pbphhProfile)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-industry me-2"></i>Detail PBPHH
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
                                    {{ $user->pbphhProfile->company_name ?? 'Belum diisi' }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Alamat Perusahaan:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $user->pbphhProfile->alamat_perusahaan ?? 'Belum diisi' }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Penanggung Jawab:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $user->pbphhProfile->penanggung_jawab ?? 'Belum diisi' }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>No. Telepon:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $user->pbphhProfile->phone ?? 'Belum diisi' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Jenis Produk Utama:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $user->pbphhProfile->jenis_produk_utama ?? 'Belum diisi' }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Tahun Berdiri:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $user->pbphhProfile->tahun_berdiri ?? 'Belum diisi' }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Jumlah Karyawan:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $user->pbphhProfile->jumlah_karyawan ? number_format($user->pbphhProfile->jumlah_karyawan) . ' orang' : 'Belum diisi' }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Kapasitas Produksi:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $user->pbphhProfile->kapasitas_izin_produksi_m3 ? number_format($user->pbphhProfile->kapasitas_izin_produksi_m3, 2) . ' m³' : 'Belum diisi' }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Rencana Produksi Tahunan:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $user->pbphhProfile->rencana_produksi_tahunan_m3 ? number_format($user->pbphhProfile->rencana_produksi_tahunan_m3, 2) . ' m³/tahun' : 'Belum diisi' }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Website:</strong>
                                </div>
                                <div class="col-sm-8">
                                    @if($user->pbphhProfile->website)
                                        <a href="{{ $user->pbphhProfile->website }}" target="_blank" class="text-primary">
                                            {{ $user->pbphhProfile->website }}
                                        </a>
                                    @else
                                        Belum diisi
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Deskripsi Perusahaan:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $user->pbphhProfile->deskripsi_perusahaan ?? 'Belum diisi' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($user->pbphhProfile->materialNeeds && $user->pbphhProfile->materialNeeds->count() > 0)
                    <hr>
                    <h6><i class="fas fa-boxes me-2"></i>Kebutuhan Material</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Jenis Kayu</th>
                                    <th>Tipe</th>
                                    <th>Kebutuhan Bulanan</th>
                                    <th>Spesifikasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->pbphhProfile->materialNeeds as $need)
                                <tr>
                                    <td>{{ $need->jenis_kayu ?? 'Belum diisi' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $need->tipe === 'Kayu' ? 'success' : 'info' }}">
                                            {{ $need->tipe ?? 'Belum diisi' }}
                                        </span>
                                    </td>
                                    <td>{{ $need->kebutuhan_bulanan_m3 ? number_format($need->kebutuhan_bulanan_m3, 2) . ' m³/bulan' : 'Belum diisi' }}</td>
                                    <td>{{ $need->spesifikasi_tambahan ?? 'Tidak ada' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <hr>
                    <h6><i class="fas fa-boxes me-2"></i>Kebutuhan Material</h6>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>Belum ada data kebutuhan material yang diinput.
                    </div>
                    @endif
                    
                    @if($user->pbphhProfile->nib_path || $user->pbphhProfile->sk_pbphh_path)
                    <hr>
                    <h6><i class="fas fa-file-alt me-2"></i>Dokumen</h6>
                    <div class="row">
                        @if($user->pbphhProfile->nib_path)
                        <div class="col-md-6 mb-2">
                            <a href="{{ Storage::url($user->pbphhProfile->nib_path) }}" target="_blank" class="btn btn-outline-primary">
                                <i class="fas fa-certificate me-1"></i>Lihat NIB
                            </a>
                        </div>
                        @endif
                        @if($user->pbphhProfile->sk_pbphh_path)
                        <div class="col-md-6 mb-2">
                            <a href="{{ Storage::url($user->pbphhProfile->sk_pbphh_path) }}" target="_blank" class="btn btn-outline-secondary">
                                <i class="fas fa-file-alt me-1"></i>Lihat SK PBPHH
                            </a>
                        </div>
                        @endif
                    </div>
                    @endif
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
@endpush