@extends('layouts.pbphh')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pbphh-dashboard.css') }}">
@endpush

@section('title', 'Dashboard PBPHH - JASA KAYA')

@section('dashboard-content')
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-primary mb-1">Dashboard PBPHH</h2>
            <p class="text-muted mb-0">Selamat datang, {{ $pbphh->company_name }}</p>
        </div>
        <div class="text-end">
            <small class="text-muted">Terakhir login: {{ Auth::user()->updated_at->format('d M Y, H:i') }}</small>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-paper-plane fa-3x mb-3"></i>
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

    <!-- Industry Partnership Statistics -->
    @if(isset($stats['industry_partnerships_active']))
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-info">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-industry me-2"></i>Kemitraan Industri (PBPHH-to-PBPHH)
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3 col-6 mb-3">
                                <h4 class="fw-bold text-primary">{{ $stats['industry_partnerships_sent'] }}</h4>
                                <p class="text-muted mb-0 small">Terkirim</p>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <h4 class="fw-bold text-warning">{{ $stats['industry_partnerships_pending'] }}</h4>
                                <p class="text-muted mb-0 small">Pending</p>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <h4 class="fw-bold text-success">{{ $stats['industry_partnerships_active'] }}</h4>
                                <p class="text-muted mb-0 small">Aktif</p>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <h4 class="fw-bold text-info">{{ $stats['industry_partnerships_received'] }}</h4>
                                <p class="text-muted mb-0 small">Diterima</p>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('pbphh.industry.partners') }}" class="btn btn-sm btn-outline-info me-2">
                                <i class="fas fa-search me-1"></i>Cari Partner
                            </a>
                            <a href="{{ route('pbphh.industry.partnerships') }}" class="btn btn-sm btn-info">
                                <i class="fas fa-list me-1"></i>Lihat Kemitraan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-rocket me-2"></i>Aksi Cepat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="{{ route('pbphh.explore') }}"
                                class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                                <i class="fas fa-search fa-2x mb-2"></i>
                                <span>Cari KTHR</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="{{ route('pbphh.partnerships') }}"
                                class="btn btn-outline-success w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                                <i class="fas fa-handshake fa-2x mb-2"></i>
                                <span>Kelola Kemitraan</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="{{ route('pbphh.material-needs') }}"
                                class="btn btn-outline-info w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                                <i class="fas fa-boxes fa-2x mb-2"></i>
                                <span>Kebutuhan Bahan Baku</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="{{ route('pbphh.profile') }}"
                                class="btn btn-outline-secondary w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                                <i class="fas fa-building fa-2x mb-2"></i>
                                <span>Edit Profil</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Available KTHR -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <span class="badge bg-success">
                            <i class="fas fa-tree me-1"></i>KTHR
                        </span>
                        <i></i>KTHR Siap Bermitra
                    </h5>
                    <a href="{{ route('pbphh.explore') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    @if($availableKthr->count() > 0)
                        <div class="row">
                            @foreach($availableKthr as $kthr)
                                <div class="col-md-6 mb-3">
                                    <div class="card border-success h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="fw-bold ">{{ $kthr->kthr_name }}</h6>
                                                <div class="d-flex gap-1">
                                                    @if($kthr->is_siap_mitra)
                                                        <span class="badge bg-success">Siap Mitra</span>
                                                    @endif
                                                    @if($kthr->is_siap_tebang)
                                                        <span class="badge bg-warning">Siap Tebang</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="small text-muted mb-2">
                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                Luas: {{ number_format($kthr->luas_areal_ha, 2) }} Ha
                                            </div>
                                            <div class="small text-muted mb-2">
                                                <i class="fas fa-users me-1"></i>
                                                {{ $kthr->jumlah_anggota }} Anggota
                                            </div>
                                            @if($kthr->plantSpecies->count() > 0)
                                                <div class="small mb-2">
                                                    <strong>Jenis Tanaman:</strong>
                                                    @foreach($kthr->plantSpecies->take(2) as $plant)
                                                        <span class="badge bg-light text-dark">{{ $plant->jenis_tanaman }}</span>
                                                    @endforeach
                                                    @if($kthr->plantSpecies->count() > 2)
                                                        <span class="text-muted">+{{ $kthr->plantSpecies->count() - 2 }} lainnya</span>
                                                    @endif
                                                </div>
                                            @endif
                                            <div class="text-end">
                                                <a href="{{ route('pbphh.explore') }}?kthr={{ $kthr->kthr_id }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye me-1"></i>Lihat Detail
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada KTHR yang siap bermitra</p>
                            <a href="{{ route('pbphh.explore') }}" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Eksplorasi KTHR
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-clock me-2"></i>Aktivitas Terbaru
                    </h5>
                </div>
                <div class="card-body activity-feed">
                    @if($recentRequests->count() > 0)
                        @foreach($recentRequests as $request)
                            <div class="border-start border-primary border-3 ps-3 mb-3">

                                <div class="fw-bold">
                                    @if($request->kthr)
                                        <span class="badge bg-success">
                                            <i class="fas fa-tree me-1"></i>KTHR
                                        </span>
                                        {{ $request->kthr->kthr_name }}
                                    @elseif($request->tptkb)
                                        <span class="badge bg-warning">
                                            <i class="fas fa-warehouse me-1"></i>TPTKB
                                        </span>
                                        {{ $request->tptkb->tptkb_name }}
                                    @endif
                                </div>



                                <div class="small text-muted">
                                    <span class="">
                                        status:
                                    </span>
                                    <span class="badge {{ $request->status_badge }}">
                                        {{ $request->status }}
                                    </span>
                                </div>

                                <div class="small text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ $request->created_at->format('d M Y') }}
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-history fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">Belum ada aktivitas</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Upcoming Meetings -->
            @if($upcomingMeetings->count() > 0)
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-alt me-2"></i>Pertemuan Mendatang
                        </h5>
                    </div>

                    <div class="card-body activity-feed">
                        @foreach($upcomingMeetings as $meeting)
                            <div class="border-start border-success border-3 ps-3 mb-3">

                                <div class="fw-bold">
                                    @if($meeting->permintaanKerjasama->kthr)
                                        {{ $meeting->permintaanKerjasama->kthr->kthr_name }}
                                        <span class="badge bg-success ms-1">KTHR</span>
                                    @elseif($meeting->permintaanKerjasama->tptkb)
                                        {{ $meeting->permintaanKerjasama->tptkb->tptkb_name }}
                                        <span class="badge bg-info ms-1">TPTKB</span>
                                    @endif
                                </div>

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
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <!-- Available TPTKB -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <span class="badge bg-warning">
                            <i class="fas fa-warehouse me-1"></i>TPTKB
                        </span>
                        <i></i>TPTKB Siap Bermitra
                    </h5>
                    <a href="{{ route('pbphh.explore') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    @if($availableTptkb->count() > 0)
                        <div class="row">
                            @foreach($availableTptkb as $tptkb)
                                <div class="col-md-6 mb-3">
                                    <div class="card border-warning h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="fw-bold">{{ $tptkb->tptkb_name }}</h6>
                                                <div class="d-flex gap-1">
                                                    @if($tptkb->is_siap_mitra)
                                                        <span class="badge bg-success">Siap Mitra</span>
                                                    @endif
                                                </div>
                                            </div>

                                            @if($tptkb->materialSupplies->count() > 0)
                                                <div class="small mb-2">
                                                    <strong>Jenis Supply:</strong>
                                                    @foreach($tptkb->materialSupplies->take(2) as $supply)
                                                        <span class="badge bg-light text-dark">{{ $supply->supply_kayu }}</span>
                                                    @endforeach
                                                    @if($tptkb->materialSupplies->count() > 2)
                                                        <span class="text-muted">+{{ $tptkb->materialSupplies->count() - 2 }} lainnya</span>
                                                    @endif
                                                </div>
                                            @endif
                                            <div class="text-end">
                                                <a href="{{ route('pbphh.explore') }}?tptkb={{ $tptkb->tptkb_id }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye me-1"></i>Lihat Detail
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada TPTKB yang siap bermitra</p>
                            <a href="{{ route('pbphh.explore') }}" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Eksplorasi TPTKB
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>


    </div>

    <!-- Material Needs Summary -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-boxes me-2"></i>Ringkasan Kebutuhan Bahan Baku
                    </h5>
                    <a href="{{ route('pbphh.material-needs') }}" class="btn btn-sm btn-outline-primary">
                        Kelola
                    </a>
                </div>
                <div class="card-body">
                    @if($pbphh->materialNeeds->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Jenis Kayu</th>
                                        <th>Tipe</th>
                                        <th>Kebutuhan Bulanan</th>
                                        <th>Spesifikasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pbphh->materialNeeds as $need)
                                        <tr>
                                            <td>{{ $need->jenis_kayu }}</td>
                                            <td>
                                                <span class="badge bg-{{ $need->tipe === 'Kayu' ? 'success' : 'info' }}">
                                                    {{ $need->tipe }}
                                                </span>
                                            </td>
                                            <td>{{ number_format($need->kebutuhan_bulanan_m3, 2) }} m³</td>
                                            <td>{{ $need->spesifikasi_tambahan ?: '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-boxes fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada kebutuhan bahan baku yang didefinisikan</p>
                            <a href="{{ route('pbphh.material-needs') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Tambah Kebutuhan
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection