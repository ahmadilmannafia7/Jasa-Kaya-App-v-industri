@extends('layouts.cdk')

@section('title', 'Monitoring Wilayah - CDK Dashboard')

@section('dashboard-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2">
            <i class="fas fa-chart-line me-2"></i>Monitoring Wilayah
            <small class="text-muted">{{ $region->name }}</small>
        </h1>
        <small class="text-muted">Terakhir diperbarui: {{ now()->format('H:i:s') }}</small>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="refreshData()">
            <i class="fas fa-sync-alt me-1"></i>Refresh Data
        </button>
    </div>
</div>

<!-- Partnership Statistics -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="stats-card text-center">
            <div class="stats-icon">
                <i class="fas fa-handshake"></i>
            </div>
            <div class="stats-number">{{ $partnershipStats['total_partnerships'] ?? 0 }}</div>
            <div class="stats-label">Total Kemitraan</div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="stats-card text-center">
            <div class="stats-icon">
                <i class="fas fa-sync-alt"></i>
            </div>
            <div class="stats-number">{{ $partnershipStats['active_partnerships'] ?? 0 }}</div>
            <div class="stats-label">Kemitraan Aktif</div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="stats-card text-center">
            <div class="stats-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stats-number">{{ $partnershipStats['completed_partnerships'] ?? 0 }}</div>
            <div class="stats-label">Kemitraan Selesai</div>
        </div>
    </div>
</div>

<!-- KTHR Section -->
<div class="card mb-4">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-tree me-2"></i>KTHR di Wilayah
                <span class="badge bg-success ms-2">{{ $kthrs->total() }}</span>
            </h5>
            <form method="GET" action="{{ route('cdk.monitoring') }}" class="d-flex">
                <input type="hidden" name="pbphh_page" value="{{ request('pbphh_page', 1) }}">
                <input type="text" class="form-control form-control-sm me-2" name="kthr_search" 
                       value="{{ request('kthr_search') }}" placeholder="Cari KTHR...">
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>
    <div class="card-body">
        @if($kthrs->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nama KTHR</th>
                            <th>Email</th>
                            <th>Luas Areal</th>
                            <th>Jumlah Anggota</th>
                            <th>Status</th>
                            <th>Jenis Tanaman</th>
                            <th>Kemitraan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kthrs as $kthr)
                            <tr>
                                <td>
                                    <strong>{{ $kthr->kthr_name }}</strong><br>
                                    <small class="text-muted">{{ $kthr->nama_pendamping ?? 'Belum diisi' }}</small>
                                </td>
                                <td>{{ $kthr->user->email }}</td>
                                <td>
                                    @if($kthr->luas_areal_ha)
                                        {{ number_format($kthr->luas_areal_ha, 2) }} Ha
                                    @else
                                        <em class="text-muted">Belum diisi</em>
                                    @endif
                                </td>
                                <td>
                                    @if($kthr->jumlah_anggota)
                                        {{ number_format($kthr->jumlah_anggota) }} orang
                                    @else
                                        <em class="text-muted">Belum diisi</em>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        @if($kthr->is_siap_mitra)
                                            <span class="badge bg-success">Siap Mitra</span>
                                        @endif
                                        @if($kthr->is_siap_tebang)
                                            <span class="badge bg-info">Siap Tebang</span>
                                        @endif
                                        @if(!$kthr->is_siap_mitra && !$kthr->is_siap_tebang)
                                            <span class="badge bg-warning">Belum Siap</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($kthr->plantSpecies && $kthr->plantSpecies->count() > 0)
                                        @foreach($kthr->plantSpecies->take(2) as $plant)
                                            <small class="d-block">
                                                <span class="badge bg-light text-dark">{{ $plant->tipe }}</span>
                                                {{ $plant->jenis_tanaman }}
                                            </small>
                                        @endforeach
                                        @if($kthr->plantSpecies->count() > 2)
                                            <small class="text-muted">+{{ $kthr->plantSpecies->count() - 2 }} lainnya</small>
                                        @endif
                                    @else
                                        <em class="text-muted">Belum ada data</em>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $partnerships = $kthr->permintaanKerjasama ? $kthr->permintaanKerjasama->count() : 0;
                                        $active = $kthr->permintaanKerjasama ? $kthr->permintaanKerjasama
                                                    ->filter(function ($item) {
                                                        return in_array($item->status, ['Disetujui', 'Dijadwalkan', 'Menunggu Tanda Tangan']);
                                                    })->count() : 0;
                                    @endphp
                                    <small>
                                        Total: <strong>{{ $partnerships }}</strong><br>
                                        Aktif: <strong class="text-success">{{ $active }}</strong>
                                    </small>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {{ $kthrs->appends(request()->except('kthr_page'))->links('pagination::bootstrap-4', ['pageName' => 'kthr_page']) }}
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-tree fa-3x text-muted mb-3"></i>
                <h6 class="text-muted">Tidak ada KTHR ditemukan</h6>
            </div>
        @endif
    </div>
</div>

<!-- PBPHH Section -->
<div class="card mb-4">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-industry me-2"></i>PBPHH di Wilayah
                <span class="badge bg-info ms-2">{{ $pbphhs->total() }}</span>
            </h5>
            <form method="GET" action="{{ route('cdk.monitoring') }}" class="d-flex">
                <input type="hidden" name="kthr_page" value="{{ request('kthr_page', 1) }}">
                <input type="text" class="form-control form-control-sm me-2" name="pbphh_search" 
                       value="{{ request('pbphh_search') }}" placeholder="Cari PBPHH...">
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>
    <div class="card-body">
        @if($pbphhs->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nama Perusahaan</th>
                            <th>Email</th>
                            <th>Penanggung Jawab</th>
                            <th>Kapasitas Produksi</th>
                            <th>Produk Utama</th>
                            <th>Kebutuhan Bahan Baku</th>
                            <th>Kemitraan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pbphhs as $pbphh)
                            <tr>
                                <td>
                                    <strong>{{ $pbphh->company_name }}</strong><br>
                                    <small class="text-muted">
                                        @if($pbphh->tahun_berdiri)
                                            Berdiri {{ $pbphh->tahun_berdiri }} ({{ $pbphh->company_age }} tahun)
                                        @else
                                            Tahun berdiri belum diisi
                                        @endif
                                    </small>
                                </td>
                                <td>{{ $pbphh->user->email }}</td>
                                <td>
                                    @if($pbphh->penanggung_jawab)
                                        {{ $pbphh->penanggung_jawab }}<br>
                                        @if($pbphh->jumlah_karyawan)
                                            <small class="text-muted">{{ number_format($pbphh->jumlah_karyawan) }} karyawan</small>
                                        @endif
                                    @else
                                        <em class="text-muted">Belum diisi</em>
                                    @endif
                                </td>
                                <td>
                                    @if($pbphh->kapasitas_izin_produksi_m3)
                                        {{ number_format($pbphh->kapasitas_izin_produksi_m3, 2) }} m³
                                    @else
                                        <em class="text-muted">Belum diisi</em>
                                    @endif
                                </td>
                                <td>
                                    {{ $pbphh->jenis_produk_utama ?? 'Belum diisi' }}
                                </td>
                                <td>
                                    @if($pbphh->materialNeeds && $pbphh->materialNeeds->count() > 0)
                                        @foreach($pbphh->materialNeeds->take(2) as $need)
                                            <small class="d-block">
                                                <span class="badge bg-light text-dark">{{ $need->tipe }}</span>
                                                {{ $need->jenis_kayu }}: {{ $need->formatted_volume }}/bulan
                                            </small>
                                        @endforeach
                                        @if($pbphh->materialNeeds->count() > 2)
                                            <small class="text-muted">+{{ $pbphh->materialNeeds->count() - 2 }} lainnya</small>
                                        @endif
                                        <small class="text-info d-block mt-1">
                                            <strong>Total: {{ number_format($pbphh->total_monthly_need, 2) }} m³/bulan</strong>
                                        </small>
                                    @else
                                        <em class="text-muted">Belum ada data</em>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $partnerships = $pbphh->permintaanKerjasama ? $pbphh->permintaanKerjasama->count() : 0;
                                        $active = $pbphh->permintaanKerjasama ? $pbphh->permintaanKerjasama
                                                    ->filter(function ($item) {
                                                        return in_array($item->status, ['Disetujui', 'Dijadwalkan', 'Menunggu Tanda Tangan']);
                                                    })->count() : 0;
                                    @endphp
                                    <small>
                                        Total: <strong>{{ $partnerships }}</strong><br>
                                        Aktif: <strong class="text-success">{{ $active }}</strong>
                                    </small>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {{ $pbphhs->appends(request()->except('pbphh_page'))->links('pagination::bootstrap-4', ['pageName' => 'pbphh_page']) }}
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-industry fa-3x text-muted mb-3"></i>
                <h6 class="text-muted">Tidak ada PBPHH ditemukan</h6>
            </div>
        @endif
    </div>
</div>

<script>
function refreshData() {
    // Show loading notification
    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Memperbarui...';
    button.disabled = true;
    
    // Reload page after short delay
    setTimeout(() => {
        location.reload();
    }, 500);
}
</script>
@endsection
