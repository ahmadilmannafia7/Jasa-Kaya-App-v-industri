@extends('layouts.cdk')

@section('title', 'Fasilitasi Pertemuan - CDK Dashboard')

@section('dashboard-content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="fas fa-calendar-alt me-2"></i>Fasilitasi Pertemuan
            <small class="text-muted">{{ $region->name }}</small>
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#scheduleModal">
                <i class="fas fa-calendar-plus me-1"></i>Jadwalkan Pertemuan
            </button>
        </div>
    </div>

    <!-- Partnerships Need Scheduling -->
    @if($needScheduling->count() > 0)
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="card-title mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>Perlu Dijadwalkan
                    <span class="badge bg-dark ms-2">{{ $needScheduling->count() }}</span>
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($needScheduling as $partnership)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card border-warning">
                                <div class="card-body">
                                    <h6 class="card-title">{{ $partnership->kthr->kthr_name }}</h6>
                                    <p class="card-text">
                                        <small class="text-muted">dengan</small><br>
                                        <strong>{{ $partnership->pbphhProfile->company_name }}</strong>
                                    </p>
                                    <div class="mb-2">
                                        <small class="text-muted">
                                            <i class="fas fa-cube me-1"></i>{{ $partnership->formatted_volume }}/bulan<br>
                                            <i class="fas fa-tree me-1"></i>{{ $partnership->wood_type }}<br>
                                            <i class="fas fa-clock me-1"></i>Disetujui
                                            {{ $partnership->updated_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    <button type="button" class="btn btn-warning btn-sm w-100"
                                        data-request-id="{{ $partnership->request_id }}"
                                        data-kthr-name="{{ $partnership->kthr->kthr_name }}"
                                        data-pbphh-name="{{ $partnership->pbphhProfile->company_name }}"
                                        onclick="schedulePartnership(this.dataset.requestId, this.dataset.kthrName, this.dataset.pbphhName)">
                                        <i class="fas fa-calendar-plus me-1"></i>Jadwalkan
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Enhanced Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('cdk.meetings') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="searchQuery" class="form-label">Pencarian</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="searchQuery" name="search" 
                            placeholder="Cari KTHR, PBPHH, atau lokasi..." value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Semua Status</option>
                        <option value="Dijadwalkan" {{ request('status') === 'Dijadwalkan' ? 'selected' : '' }}>Dijadwalkan</option>
                        <option value="Berlangsung" {{ request('status') === 'Berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                        <option value="Selesai" {{ request('status') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="Dibatalkan" {{ request('status') === 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="typeFilter" class="form-label">Jenis</label>
                    <select class="form-select" id="typeFilter" name="type">
                        <option value="">Semua Jenis</option>
                        <option value="online" {{ request('type') == 'online' ? 'selected' : '' }}>Online</option>
                        <option value="lapangan" {{ request('type') == 'lapangan' ? 'selected' : '' }}>Lapangan</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="date_from" class="form-label">Dari Tanggal</label>
                    <input type="date" class="form-control" id="date_from" name="date_from"
                        value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label for="date_to" class="form-label">Sampai Tanggal</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <div class="btn-group" role="group">
                            <button type="submit" class="btn btn-primary" title="Terapkan Filter">
                                <i class="fas fa-filter"></i>
                            </button>
                            <a href="{{ route('cdk.meetings') }}" class="btn btn-outline-secondary" title="Reset Filter">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Meetings List -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list me-2 text-primary"></i>Daftar Pertemuan
                </h5>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-success btn-sm" onclick="exportMeetings()">
                        <i class="fas fa-download me-1"></i>Export Excel
                    </button>
                    <button type="button" class="btn btn-outline-info btn-sm" onclick="printMeetings()">
                        <i class="fas fa-print me-1"></i>Print
                    </button>
                    <span class="badge bg-info fs-6">{{ $meetings->total() }} Total</span>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($meetings->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>KTHR</th>
                                <th>PBPHH</th>
                                <th>Jadwal</th>
                                <th>Tipe/Lokasi</th>
                                <th>Status</th>
                                <th>Kesepakatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($meetings as $meeting)
                                <tr>
                                    <td>
                                        <strong>{{ $meeting->permintaanKerjasama->kthr->kthr_name }}</strong><br>
                                        <small class="text-muted">{{ $meeting->permintaanKerjasama->kthr->user->email }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $meeting->permintaanKerjasama->pbphhProfile->company_name }}</strong><br>
                                        <small
                                            class="text-muted">{{ $meeting->permintaanKerjasama->pbphhProfile->user->email }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $meeting->scheduled_time->format('d/m/Y') }}</strong><br>
                                        <small class="text-muted">{{ $meeting->scheduled_time->format('H:i') }}</small><br>
                                        <em class="text-muted">{{ $meeting->scheduled_time->diffForHumans() }}</em>
                                    </td>
                                    <td>
                                        <span class="badge {{ $meeting->method === 'online' ? 'bg-info' : 'bg-success' }}">
                                            <i
                                                class="fas {{ $meeting->method === 'online' ? 'fa-video' : 'fa-map-marker-alt' }} me-1"></i>
                                            {{ ucfirst($meeting->method) }}
                                        </span><br>
                                        <small class="text-muted">{{ $meeting->location }}</small>
                                    </td>
                                    <td>
                                        <span class="badge {{ $meeting->status_badge }}">
                                            {{ $meeting->status }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($meeting->kesepakatan)
                                            <div class="text-success">
                                                <i class="fas fa-check-circle me-1"></i>
                                                <small>Rp
                                                    {{ number_format($meeting->kesepakatan->agreed_price_per_m3, 0, ',', '.') }}/m³</small><br>
                                                <small>{{ $meeting->kesepakatan->durasi_kontrak_bulan ? $meeting->kesepakatan->durasi_kontrak_bulan . ' bulan' : 'Tidak ditentukan' }}</small>
                                            </div>
                                        @else
                                            <span class="text-muted">
                                                <i class="fas fa-minus me-1"></i>Belum ada
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group-vertical btn-group-sm">
                                            @if($meeting->status === 'Dijadwalkan')
                                                <button type="button" class="btn btn-info btn-sm"
                                                    data-meeting-id="{{ $meeting->meeting_id }}" onclick="startMeeting(this.dataset.meetingId)">
                                                    <i class="fas fa-play me-1"></i>Mulai
                                                </button>
                                                <button type="button" class="btn btn-outline-warning btn-sm"
                                                    data-meeting-id="{{ $meeting->meeting_id }}" onclick="editMeeting(this.dataset.meetingId)">
                                                    <i class="fas fa-edit me-1"></i>Edit
                                                </button>
                                            @elseif($meeting->status === 'Berlangsung' && !$meeting->kesepakatan)
                                                <button type="button" class="btn btn-success btn-sm"
                                                    data-meeting-id="{{ $meeting->meeting_id }}" onclick="completeMeeting(this.dataset.meetingId)">
                                                    <i class="fas fa-check me-1"></i>Selesai
                                                </button>
                                            @elseif($meeting->status === 'Berlangsung' && $meeting->kesepakatan)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle me-1"></i>Selesai
                                                </span>
                                            @endif
                                            <button type="button" class="btn btn-outline-secondary btn-sm"
                                                data-meeting-id="{{ $meeting->meeting_id }}" onclick="viewMeetingDetails(this.dataset.meetingId)">
                                                <i class="fas fa-eye me-1"></i>Detail
                                            </button>
                                            @if($meeting->kesepakatan)
                                                <button type="button" class="btn btn-outline-danger btn-sm"
                                                    data-agreement-id="{{ $meeting->kesepakatan->agreement_id }}" onclick="deleteAgreement(this.dataset.agreementId)">
                                                    <i class="fas fa-trash me-1"></i>Hapus Kerjasama
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-outline-danger btn-sm"
                                                    data-meeting-id="{{ $meeting->meeting_id }}" onclick="deleteMeeting(this.dataset.meetingId)">
                                                    <i class="fas fa-trash me-1"></i>Hapus Pertemuan
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $meetings->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-calendar fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Tidak ada pertemuan</h5>
                    <p class="text-muted">Belum ada pertemuan yang dijadwalkan atau sesuai filter.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Schedule Meeting Modal -->
    <div class="modal fade" id="scheduleModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-calendar-plus me-2"></i>Jadwalkan Pertemuan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="scheduleForm" method="POST" action="{{ route('cdk.meetings.schedule') }}" novalidate>
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="schedule_request_id" name="request_id">

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong id="schedule_partnership_info">Pilih kemitraan dari daftar di atas</strong>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="scheduled_time" class="form-label">Tanggal & Waktu <span
                                            class="text-danger">*</span></label>
                                    <input type="datetime-local" class="form-control" id="scheduled_time"
                                        name="scheduled_time" required min="{{ now()->format('Y-m-d\TH:i') }}">
                                    <div class="invalid-feedback">
                                        Tanggal dan waktu pertemuan harus diisi dan tidak boleh di masa lalu.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="meeting_type" class="form-label">Tipe Pertemuan <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="meeting_type" name="meeting_type" required>
                                        <option value="">Pilih Tipe</option>
                                        <option value="online">Online (Video Call)</option>
                                        <option value="lapangan">Lapangan (Tatap Muka)</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Tipe pertemuan harus dipilih.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">Lokasi/Link <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="location" name="location" required
                                placeholder="Masukkan alamat atau link meeting..." minlength="5">
                            <div class="form-text">Untuk online: masukkan link Zoom/Meet. Untuk lapangan: masukkan alamat
                                lengkap.</div>
                            <div class="invalid-feedback">
                                Lokasi/Link pertemuan harus diisi minimal 5 karakter.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="meeting_notes" class="form-label">Catatan Pertemuan</label>
                            <textarea class="form-control" id="meeting_notes" name="meeting_notes" rows="3"
                                placeholder="Catatan khusus untuk pertemuan (opsional)..." maxlength="500"></textarea>
                            <div class="form-text">Maksimal 500 karakter</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="scheduleSubmitBtn">
                            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                            <i class="fas fa-calendar-plus me-1"></i>Jadwalkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Complete Meeting Modal -->
    <div class="modal fade" id="completeMeetingModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-handshake me-2"></i>Selesaikan Pertemuan & Buat Kesepakatan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="completeMeetingForm" method="POST" enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="modal-body">
                        <!-- Meeting Info Alert -->
                        <div class="alert alert-info border-0 shadow-sm mb-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle me-3 fs-5"></i>
                                <div>
                                    <h6 class="mb-1 fw-bold">Informasi Pertemuan</h6>
                                    <p class="mb-0" id="completeMeetingInfo">Pilih pertemuan dari daftar untuk melengkapi kesepakatan</p>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Steps -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="step-item active">
                                        <div class="step-circle bg-success text-white">
                                            <i class="fas fa-calendar-check"></i>
                                        </div>
                                        <small class="step-label">Pertemuan Selesai</small>
                                    </div>
                                    <div class="step-line bg-success"></div>
                                    <div class="step-item active">
                                        <div class="step-circle bg-success text-white">
                                            <i class="fas fa-handshake"></i>
                                        </div>
                                        <small class="step-label">Buat Kesepakatan</small>
                                    </div>
                                    <div class="step-line bg-secondary"></div>
                                    <div class="step-item">
                                        <div class="step-circle bg-secondary text-white">
                                            <i class="fas fa-signature"></i>
                                        </div>
                                        <small class="step-label">Menunggu TTD</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Sections -->
                        <div class="row">
                            <!-- Left Column: Financial Terms -->
                            <div class="col-lg-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-light border-0">
                                        <h6 class="mb-0 fw-bold text-primary">
                                            <i class="fas fa-money-bill-wave me-2"></i>Ketentuan Finansial
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="harga_per_m3" class="form-label">Harga per m³ (Rp) <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control" id="harga_per_m3" name="harga_per_m3" required
                                                    min="1000" step="0.01" placeholder="0">
                                            </div>
                                            <div class="invalid-feedback">
                                                Harga per m³ harus diisi dan minimal Rp 1.000.
                                            </div>
                                            <div class="form-text">
                                                <i class="fas fa-info-circle me-1"></i>Harga yang disepakati per meter kubik
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="durasi_kontrak_bulan" class="form-label">Durasi Kontrak (Bulan) <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="durasi_kontrak_bulan"
                                                    name="durasi_kontrak_bulan" required min="1" max="120" placeholder="12">
                                                <span class="input-group-text">Bulan</span>
                                            </div>
                                            <div class="invalid-feedback">
                                                Durasi kontrak harus antara 1-120 bulan.
                                            </div>
                                            <div class="form-text">
                                                <i class="fas fa-calendar-alt me-1"></i>Maksimal 10 tahun (120 bulan)
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="mekanisme_pembayaran" class="form-label">Mekanisme Pembayaran <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select mb-2" id="payment_template" onchange="fillPaymentTemplate()">
                                                <option value="">Pilih Template Pembayaran</option>
                                                <option value="dp_30">DP 30% + Pelunasan 70%</option>
                                                <option value="dp_50">DP 50% + Pelunasan 50%</option>
                                                <option value="cash">Pembayaran Tunai</option>
                                                <option value="installment">Pembayaran Cicilan</option>
                                                <option value="custom">Custom</option>
                                            </select>
                                            <textarea class="form-control" id="mekanisme_pembayaran"
                                                name="mekanisme_pembayaran" required minlength="10" rows="3"
                                                placeholder="Contoh: Transfer 30% DP, 70% setelah pengiriman"></textarea>
                                            <div class="invalid-feedback">
                                                Mekanisme pembayaran harus diisi minimal 10 karakter.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column: Delivery & Quality -->
                            <div class="col-lg-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-light border-0">
                                        <h6 class="mb-0 fw-bold text-info">
                                            <i class="fas fa-truck me-2"></i>Pengiriman & Kualitas
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="jadwal_pengiriman" class="form-label">Jadwal Pengiriman <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select mb-2" id="delivery_template" onchange="fillDeliveryTemplate()">
                                                <option value="">Pilih Template Pengiriman</option>
                                                <option value="monthly">Bulanan (Tanggal 15)</option>
                                                <option value="weekly">Mingguan</option>
                                                <option value="on_demand">Sesuai Permintaan</option>
                                                <option value="custom">Custom</option>
                                            </select>
                                            <textarea class="form-control" id="jadwal_pengiriman" name="jadwal_pengiriman"
                                                required minlength="10" rows="3" placeholder="Contoh: Setiap tanggal 15 setiap bulan"></textarea>
                                            <div class="invalid-feedback">
                                                Jadwal pengiriman harus diisi minimal 10 karakter.
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="kualitas_spesifikasi" class="form-label">Kualitas & Spesifikasi <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select mb-2" id="quality_template" onchange="fillQualityTemplate()">
                                                <option value="">Pilih Template Kualitas</option>
                                                <option value="grade_a">Grade A - Kualitas Premium</option>
                                                <option value="grade_b">Grade B - Kualitas Standar</option>
                                                <option value="grade_c">Grade C - Kualitas Ekonomis</option>
                                                <option value="custom">Custom</option>
                                            </select>
                                            <textarea class="form-control" id="kualitas_spesifikasi" name="kualitas_spesifikasi" rows="4"
                                                required minlength="20" placeholder="Deskripsikan standar kualitas dan spesifikasi kayu..."></textarea>
                                            <div class="invalid-feedback">
                                                Kualitas & spesifikasi harus diisi minimal 20 karakter.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Terms Section -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light border-0">
                                        <h6 class="mb-0 fw-bold text-warning">
                                            <i class="fas fa-file-contract me-2"></i>Syarat & Ketentuan Tambahan
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="syarat_tambahan" class="form-label">Syarat Tambahan (Opsional)</label>
                                            <textarea class="form-control" id="syarat_tambahan" name="syarat_tambahan" rows="3"
                                                placeholder="Syarat tambahan yang disepakati (opsional)..." maxlength="1000"></textarea>
                                            <div class="form-text">
                                                <i class="fas fa-info-circle me-1"></i>Maksimal 1000 karakter
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Meeting Summary Section -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light border-0">
                                        <h6 class="mb-0 fw-bold text-success">
                                            <i class="fas fa-clipboard-list me-2"></i>Ringkasan Pertemuan
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="meeting_summary" class="form-label">Ringkasan Hasil Pertemuan <span
                                                    class="text-danger">*</span></label>
                                            <textarea class="form-control" id="meeting_summary" name="meeting_summary" rows="5" required
                                                minlength="50" placeholder="Ringkasan hasil pertemuan dan kesepakatan yang dicapai..."></textarea>
                                            <div class="invalid-feedback">
                                                Ringkasan pertemuan harus diisi minimal 50 karakter.
                                            </div>
                                            <div class="form-text">
                                                <i class="fas fa-lightbulb me-1"></i>Jelaskan poin-poin penting yang dibahas dan kesepakatan yang dicapai
                                            </div>
                                        </div>
                                        
                                        <!-- Dokumen Kerjasama Upload -->
                                        <div class="mb-3">
                                            <label for="final_document" class="form-label">Dokumen Kerjasama <span class="text-danger">*</span></label>
                                            <input type="file" class="form-control" id="final_document" name="final_document" accept=".pdf,.doc,.docx" required>
                                            <div class="invalid-feedback">
                                                Dokumen kerjasama wajib diunggah.
                                            </div>
                                            <div class="form-text">
                                                <i class="fas fa-info-circle me-1"></i>Unggah dokumen kerjasama dalam format PDF, DOC, atau DOCX (Maks. 5MB)
                                            </div>
                                        </div>

                                        <!-- Agreement Preview -->
                                        <div class="alert alert-light border mt-3">
                                            <h6 class="fw-bold mb-2">
                                                <i class="fas fa-eye me-2"></i>Preview Kesepakatan
                                            </h6>
                                            <div id="agreementPreview" class="small text-muted">
                                                Isi form di atas untuk melihat preview kesepakatan
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <div class="d-flex justify-content-between w-100">
                            <div>
                                <button type="button" class="btn btn-outline-secondary" onclick="resetCompleteMeetingForm()">
                                    <i class="fas fa-undo me-1"></i>Reset Form
                                </button>
                                <button type="button" class="btn btn-outline-info" onclick="previewAgreement()">
                                    <i class="fas fa-eye me-1"></i>Preview
                                </button>
                            </div>
                            <div>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-1"></i>Batal
                                </button>
                                <button type="submit" class="btn btn-success" id="completeMeetingSubmitBtn">
                                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                    <i class="fas fa-handshake me-1"></i>Selesaikan & Buat Kesepakatan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
/* Step Progress Styling */
.step-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
}

.step-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    margin-bottom: 8px;
    transition: all 0.3s ease;
}

.step-line {
    height: 4px;
    flex: 1;
    margin: 0 10px;
    margin-top: -25px;
    border-radius: 2px;
}

.step-label {
    font-weight: 500;
    text-align: center;
    color: #6c757d;
}

.step-item.active .step-label {
    color: #198754;
    font-weight: 600;
}

/* Card Enhancements */
.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

/* Form Enhancements */
.form-control:focus, .form-select:focus {
    border-color: #198754;
    box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
}

/* Preview Box */
#agreementPreview {
    max-height: 200px;
    overflow-y: auto;
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #dee2e6;
}

/* Loading Animation */
.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .step-circle {
        width: 40px;
        height: 40px;
        font-size: 14px;
    }
    
    .step-label {
        font-size: 12px;
    }
    
    .modal-xl {
        max-width: 95%;
    }
}
</style>
@endpush

@push('scripts')
    <script>
        // Smart modal backdrop cleanup - only when modal is not active
        function destroyAllModalBackdrops() {
            const scheduleModal = document.getElementById('scheduleModal');
            const completeMeetingModal = document.getElementById('completeMeetingModal');
            const isScheduleModalActive = scheduleModal && (scheduleModal.classList.contains('show') || scheduleModal.style.display === 'block');
            const isCompleteMeetingModalActive = completeMeetingModal && (completeMeetingModal.classList.contains('show') || completeMeetingModal.style.display === 'block');
            
            // Only cleanup if no modal is currently active
            if (!isScheduleModalActive && !isCompleteMeetingModalActive) {
                // Remove any modal backdrops
                document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
                    backdrop.remove();
                });
                
                // Ensure body doesn't have modal-open class
                document.body.classList.remove('modal-open');
                
                // Reset body styles
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
                document.body.style.marginRight = '';
                
                // Ensure modals are properly hidden
                [scheduleModal, completeMeetingModal].forEach(modal => {
                    if (modal) {
                        modal.classList.remove('show');
                        modal.style.display = 'none';
                        modal.setAttribute('aria-hidden', 'true');
                        modal.removeAttribute('aria-modal');
                    }
                });
            }
        }
        
        // Initialize cleanup system
        document.addEventListener('DOMContentLoaded', function() {
            // Initial cleanup
            destroyAllModalBackdrops();
            
            // Set up MutationObserver to watch for modal backdrops
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    mutation.addedNodes.forEach(function(node) {
                        if (node.nodeType === 1 && node.classList && node.classList.contains('modal-backdrop')) {
                            const scheduleModal = document.getElementById('scheduleModal');
                            const completeMeetingModal = document.getElementById('completeMeetingModal');
                            const isAnyModalActive = (scheduleModal && (scheduleModal.classList.contains('show') || scheduleModal.style.display === 'block')) ||
                                                   (completeMeetingModal && (completeMeetingModal.classList.contains('show') || completeMeetingModal.style.display === 'block'));
                            
                            if (!isAnyModalActive) {
                                console.log('Modal backdrop detected and removed:', node);
                                node.remove();
                            }
                        }
                    });
                });
            });
            
            // Start observing
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
            
            // Periodic cleanup every 2 seconds
            setInterval(destroyAllModalBackdrops, 2000);
            
            // Override Bootstrap Modal to prevent backdrop creation
            const originalShow = bootstrap.Modal.prototype.show;
            const originalToggle = bootstrap.Modal.prototype.toggle;
            
            bootstrap.Modal.prototype.show = function() {
                this._config.backdrop = false;
                this._config.keyboard = true;
                return originalShow.call(this);
            };
            
            bootstrap.Modal.prototype.toggle = function() {
                this._config.backdrop = false;
                this._config.keyboard = true;
                return originalToggle.call(this);
            };
        });
        
        function schedulePartnership(requestId, kthrName, pbphhName) {
            document.getElementById('schedule_request_id').value = requestId;
            document.getElementById('schedule_partnership_info').innerHTML =
                `<strong>${kthrName}</strong> dengan <strong>${pbphhName}</strong>`;
            
            // Reset form validation
            const form = document.getElementById('scheduleForm');
            form.classList.remove('was-validated');
            
            // Clear previous values
            form.reset();
            document.getElementById('schedule_request_id').value = requestId;
            
            // Show modal with explicit configuration
            const modal = new bootstrap.Modal(document.getElementById('scheduleModal'), {
                backdrop: false,
                keyboard: true,
                focus: true
            });
            modal.show();
            
            // Ensure modal stays visible
            setTimeout(() => {
                const modalElement = document.getElementById('scheduleModal');
                modalElement.classList.add('show');
                modalElement.style.display = 'block';
                modalElement.setAttribute('aria-modal', 'true');
                modalElement.removeAttribute('aria-hidden');
            }, 100);
        }

        function startMeeting(meetingId) {
            if (confirm('Apakah Anda yakin ingin memulai pertemuan ini?')) {
                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("cdk.meetings.start", ":id") }}'.replace(':id', meetingId);

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                document.body.appendChild(form);
                form.submit();
            }
        }

        function editMeeting(meetingId) {
            // Find meeting data from the table
            const meetingRow = document.querySelector(`[data-meeting-id="${meetingId}"]`).closest('tr');
            const cells = meetingRow.querySelectorAll('td');
            
            // Extract current meeting data
            const currentDateTime = cells[3].textContent.trim();
            const currentLocation = cells[4].textContent.trim();
            
            // Open schedule modal with current data
            document.getElementById('schedule_request_id').value = meetingId;
            document.getElementById('schedule_partnership_info').innerHTML = 
                `<strong>Edit Pertemuan ID: ${meetingId}</strong><br>Mengubah jadwal pertemuan yang sudah ada`;
            
            // Try to parse and set current datetime (if format matches)
            try {
                const dateMatch = currentDateTime.match(/(\d{2})\/(\d{2})\/(\d{4}) (\d{2}):(\d{2})/);
                if (dateMatch) {
                    const [, day, month, year, hour, minute] = dateMatch;
                    const isoDateTime = `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}T${hour}:${minute}`;
                    document.getElementById('scheduled_time').value = isoDateTime;
                }
            } catch (e) {
                console.log('Could not parse datetime:', currentDateTime);
            }
            
            // Set current location
            document.getElementById('location').value = currentLocation;
            
            // Change form action to update instead of create
            const form = document.getElementById('scheduleForm');
            form.action = '{{ route("cdk.meetings.update", ":id") }}'.replace(':id', meetingId);
            
            // Add method spoofing for PUT request
            let methodInput = form.querySelector('input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                form.appendChild(methodInput);
            }
            methodInput.value = 'PUT';
            
            // Change submit button text
            const submitBtn = document.getElementById('scheduleSubmitBtn');
            submitBtn.innerHTML = '<i class="fas fa-save me-1"></i>Update Pertemuan';
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('scheduleModal'));
            modal.show();
        }

        function completeMeeting(meetingId) {
            const formAction = '{{ route("cdk.meetings.complete", ":id") }}'.replace(':id', meetingId);
            document.getElementById('completeMeetingForm').action = formAction;
            console.log('Setting form action to:', formAction);
            
            // Reset form validation
            const form = document.getElementById('completeMeetingForm');
            form.classList.remove('was-validated');
            
            // Clear previous values
            form.reset();
            
            // Update meeting info
            document.getElementById('completeMeetingInfo').innerHTML = 
                `Menyelesaikan pertemuan ID: <strong>${meetingId}</strong> dan membuat kesepakatan kerjasama`;
            
            // Show modal with explicit configuration
            const modal = new bootstrap.Modal(document.getElementById('completeMeetingModal'), {
                backdrop: false,
                keyboard: true,
                focus: true
            });
            modal.show();
            
            // Ensure modal stays visible
            setTimeout(() => {
                const modalElement = document.getElementById('completeMeetingModal');
                modalElement.classList.add('show');
                modalElement.style.display = 'block';
                modalElement.setAttribute('aria-modal', 'true');
                modalElement.removeAttribute('aria-hidden');
                
                // Initialize form listeners
                initializeCompleteMeetingForm();
            }, 100);
        }

        // Template Functions
        function fillPaymentTemplate() {
            const template = document.getElementById('payment_template').value;
            const textarea = document.getElementById('mekanisme_pembayaran');
            
            const templates = {
                'dp_30': 'Pembayaran dilakukan dengan sistem:\n- Down Payment (DP) 30% dari total nilai kontrak\n- Pelunasan 70% setelah barang diterima dan diperiksa\n- Transfer melalui rekening perusahaan',
                'dp_50': 'Pembayaran dilakukan dengan sistem:\n- Down Payment (DP) 50% dari total nilai kontrak\n- Pelunasan 50% setelah barang diterima dan diperiksa\n- Transfer melalui rekening perusahaan',
                'cash': 'Pembayaran dilakukan secara tunai:\n- Pembayaran 100% saat pengiriman barang\n- Dapat melalui transfer bank atau tunai\n- Bukti pembayaran wajib disimpan',
                'installment': 'Pembayaran dilakukan secara cicilan:\n- Cicilan bulanan selama masa kontrak\n- Pembayaran setiap tanggal yang disepakati\n- Transfer melalui rekening perusahaan'
            };
            
            if (templates[template]) {
                textarea.value = templates[template];
                textarea.classList.add('is-valid');
                updateAgreementPreview();
            }
        }

        function fillDeliveryTemplate() {
            const template = document.getElementById('delivery_template').value;
            const textarea = document.getElementById('jadwal_pengiriman');
            
            const templates = {
                'monthly': 'Pengiriman dilakukan setiap bulan:\n- Tanggal 15 setiap bulan\n- Volume sesuai kebutuhan bulanan\n- Koordinasi H-3 sebelum pengiriman',
                'weekly': 'Pengiriman dilakukan setiap minggu:\n- Setiap hari Jumat\n- Volume disesuaikan dengan kapasitas\n- Konfirmasi H-1 sebelum pengiriman',
                'on_demand': 'Pengiriman sesuai permintaan:\n- Berdasarkan order dari pembeli\n- Lead time minimal 7 hari kerja\n- Konfirmasi ketersediaan stok terlebih dahulu'
            };
            
            if (templates[template]) {
                textarea.value = templates[template];
                textarea.classList.add('is-valid');
                updateAgreementPreview();
            }
        }

        function fillQualityTemplate() {
            const template = document.getElementById('quality_template').value;
            const textarea = document.getElementById('kualitas_spesifikasi');
            
            const templates = {
                'grade_a': 'Kualitas Grade A - Premium:\n- Kayu berkualitas tinggi tanpa cacat\n- Kelembaban maksimal 15%\n- Diameter minimal 20cm\n- Panjang sesuai permintaan\n- Bebas dari hama dan penyakit',
                'grade_b': 'Kualitas Grade B - Standar:\n- Kayu berkualitas baik dengan cacat minimal\n- Kelembaban maksimal 18%\n- Diameter minimal 15cm\n- Toleransi cacat kecil maksimal 5%\n- Bebas dari hama dan penyakit',
                'grade_c': 'Kualitas Grade C - Ekonomis:\n- Kayu berkualitas cukup untuk kebutuhan umum\n- Kelembaban maksimal 20%\n- Diameter minimal 12cm\n- Toleransi cacat sedang maksimal 10%\n- Bebas dari hama dan penyakit'
            };
            
            if (templates[template]) {
                textarea.value = templates[template];
                textarea.classList.add('is-valid');
                updateAgreementPreview();
            }
        }

        // Form Management Functions
        function resetCompleteMeetingForm() {
            if (confirm('Apakah Anda yakin ingin mereset semua data form?')) {
                const form = document.getElementById('completeMeetingForm');
                form.reset();
                form.classList.remove('was-validated');
                
                // Reset template selects
                document.getElementById('payment_template').value = '';
                document.getElementById('delivery_template').value = '';
                document.getElementById('quality_template').value = '';
                
                // Clear validation classes
                form.querySelectorAll('.form-control, .form-select').forEach(input => {
                    input.classList.remove('is-valid', 'is-invalid');
                });
                
                // Clear preview
                document.getElementById('agreementPreview').innerHTML = 'Isi form di atas untuk melihat preview kesepakatan';
                
                showNotification('Form berhasil direset', 'info');
            }
        }

        function previewAgreement() {
            updateAgreementPreview();
            
            // Scroll to preview
            document.getElementById('agreementPreview').scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }

        function updateAgreementPreview() {
            const harga = document.getElementById('harga_per_m3').value;
            const durasi = document.getElementById('durasi_kontrak_bulan').value;
            const pembayaran = document.getElementById('mekanisme_pembayaran').value;
            const pengiriman = document.getElementById('jadwal_pengiriman').value;
            const kualitas = document.getElementById('kualitas_spesifikasi').value;
            const syarat = document.getElementById('syarat_tambahan').value;
            const ringkasan = document.getElementById('meeting_summary').value;
            
            let preview = '<div class="row">';
            
            if (harga) {
                preview += `<div class="col-md-6 mb-2"><strong>Harga per m³:</strong><br>Rp ${parseInt(harga).toLocaleString('id-ID')}</div>`;
            }
            
            if (durasi) {
                preview += `<div class="col-md-6 mb-2"><strong>Durasi Kontrak:</strong><br>${durasi} bulan</div>`;
            }
            
            if (pembayaran) {
                preview += `<div class="col-12 mb-2"><strong>Mekanisme Pembayaran:</strong><br><pre class="small mb-0">${pembayaran}</pre></div>`;
            }
            
            if (pengiriman) {
                preview += `<div class="col-12 mb-2"><strong>Jadwal Pengiriman:</strong><br><pre class="small mb-0">${pengiriman}</pre></div>`;
            }
            
            if (kualitas) {
                preview += `<div class="col-12 mb-2"><strong>Kualitas & Spesifikasi:</strong><br><pre class="small mb-0">${kualitas}</pre></div>`;
            }
            
            if (syarat) {
                preview += `<div class="col-12 mb-2"><strong>Syarat Tambahan:</strong><br><pre class="small mb-0">${syarat}</pre></div>`;
            }
            
            if (ringkasan) {
                preview += `<div class="col-12 mb-2"><strong>Ringkasan Pertemuan:</strong><br><pre class="small mb-0">${ringkasan}</pre></div>`;
            }
            
            preview += '</div>';
            
            if (!harga && !durasi && !pembayaran && !pengiriman && !kualitas && !ringkasan) {
                preview = 'Isi form di atas untuk melihat preview kesepakatan';
            }
            
            document.getElementById('agreementPreview').innerHTML = preview;
        }

        function initializeCompleteMeetingForm() {
            console.log('Initializing complete meeting form');
            
            // Add real-time preview updates
            const formInputs = ['harga_per_m3', 'durasi_kontrak_bulan', 'mekanisme_pembayaran', 
                              'jadwal_pengiriman', 'kualitas_spesifikasi', 'syarat_tambahan', 'meeting_summary'];
            
            formInputs.forEach(inputId => {
                const input = document.getElementById(inputId);
                if (input) {
                    console.log(`Adding event listeners for ${inputId}`);
                    input.addEventListener('input', function() {
                        validateField(this);
                        updateAgreementPreview();
                    });
                    input.addEventListener('blur', function() {
                        validateField(this);
                        updateAgreementPreview();
                    });
                } else {
                    console.log(`Element ${inputId} not found`);
                }
            });
            
            // Add event listener for file input
                const fileInput = document.getElementById('final_document');
                if (fileInput) {
                    console.log('Adding event listener for final_document');
                    fileInput.addEventListener('change', function() {
                        console.log('File input changed');
                        if (this.files.length > 0) {
                            console.log('File selected:', this.files[0].name);
                            // Remove any previous custom validity message
                            this.setCustomValidity('');
                        } else {
                            console.log('No file selected');
                            // Set custom validity message for empty file input
                            this.setCustomValidity('Dokumen kerjasama wajib diunggah');
                        }
                        validateField(this);
                    });
                    
                    // Trigger validation on page load to handle pre-filled forms
                    setTimeout(() => validateField(fileInput), 500);
                } else {
                    console.log('File input element not found');
                }
            
            // Add event listener for modal close to clear file input
            const modal = document.getElementById('completeMeetingModal');
            if (modal) {
                modal.addEventListener('hidden.bs.modal', function() {
                    if (fileInput) {
                        fileInput.value = '';
                        console.log('File input cleared on modal close');
                    }
                    // Reset form validation state
                    if (form) {
                        form.classList.remove('was-validated');
                        const invalidInputs = form.querySelectorAll('.is-invalid');
                        invalidInputs.forEach(input => {
                            input.classList.remove('is-invalid');
                        });
                    }
                });
            }
            
            // Add form submission validation
            const form = document.getElementById('completeMeetingForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault(); // Prevent default form submission
                    console.log('Form submit event triggered');
                    
                    if (!validateCompleteMeetingForm()) {
                        showNotification('Mohon lengkapi semua field yang wajib diisi', 'error');
                        console.log('Form validation failed');
                        return false;
                    }
                    
                    console.log('Form validation passed');
                    
                    // Show loading state when validation passes
                    const submitBtn = document.getElementById('completeMeetingSubmitBtn');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Memproses...';
                    }
                    
                    // Manual form submission
                    console.log('Submitting form manually');
                    
                    // Create FormData object
                    const formData = new FormData(form);
                    
                    // Log form data for debugging
                    for (let pair of formData.entries()) {
                        console.log(pair[0] + ': ' + (pair[1] instanceof File ? pair[1].name : pair[1]));
                    }
                    
                    // Get CSRF token
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    console.log('CSRF Token:', csrfToken);
                    
                    // Add CSRF token to form data if not already present
                    if (!formData.has('_token')) {
                        formData.append('_token', csrfToken);
                    }
                    
                    console.log('Form action URL:', form.action);
                    
                    // Submit form using fetch API
                    fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        credentials: 'same-origin'
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        console.log('Response headers:', [...response.headers.entries()]);
                        
                        // Check if response is a redirect
                        if (response.redirected) {
                            console.log('Redirected to:', response.url);
                            window.location.href = response.url;
                            return null;
                        }
                        
                        // Check content type
                        const contentType = response.headers.get('content-type');
                        console.log('Content-Type:', contentType);
                        
                        return response.text().then(text => {
                            return { text, status: response.status, contentType };
                        });
                    })
                    .then(data => {
                        if (!data) return; // Handle redirect case
                        
                        console.log('Response data:', data.text.substring(0, 500) + '...');
                        
                        // If response is successful but not JSON
                        if (data.status >= 200 && data.status < 300 && !data.contentType?.includes('application/json')) {
                            console.log('Successful non-JSON response, redirecting to meetings page');
                            window.location.href = '{{ route("cdk.meetings") }}';
                            return;
                        }
                        
                        try {
                            // Try to parse as JSON
                            const jsonData = JSON.parse(data.text);
                            console.log('Parsed JSON data:', jsonData);
                            
                            if (jsonData.success) {
                                showNotification('Pertemuan selesai dan kesepakatan berhasil dibuat', 'success');
                                window.location.href = '{{ route("cdk.meetings") }}';
                            } else {
                                showNotification(jsonData.message || 'Terjadi kesalahan', 'error');
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = '<i class="fas fa-handshake me-1"></i>Selesaikan & Buat Kesepakatan';
                            }
                        } catch (e) {
                            console.error('Error parsing JSON:', e);
                            
                            // If not JSON, it's probably a redirect or HTML
                            if (data.text.includes('Pertemuan selesai dan kesepakatan berhasil dibuat')) {
                                showNotification('Pertemuan selesai dan kesepakatan berhasil dibuat', 'success');
                                window.location.href = '{{ route("cdk.meetings") }}';
                            } else {
                                // If there's an error message in the HTML
                                const parser = new DOMParser();
                                const htmlDoc = parser.parseFromString(data.text, 'text/html');
                                const errorMsg = htmlDoc.querySelector('.alert-danger')?.textContent || 'Terjadi kesalahan';
                                console.log('Error message from HTML:', errorMsg);
                                showNotification(errorMsg, 'error');
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = '<i class="fas fa-handshake me-1"></i>Selesaikan & Buat Kesepakatan';
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Terjadi kesalahan saat mengirim data', 'error');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-handshake me-1"></i>Selesaikan & Buat Kesepakatan';
                    });
                });
            }
        }
        
        function validateField(field) {
            const value = field.value.trim();
            const fieldId = field.id;
            
            // Remove previous validation classes
            field.classList.remove('is-valid', 'is-invalid');
            
            // Validation rules
            let isValid = true;
            let errorMessage = '';
            
            switch(fieldId) {
                case 'harga_per_m3':
                    if (!value || isNaN(value) || parseFloat(value) < 1000) {
                        isValid = false;
                        errorMessage = 'Harga minimal Rp 1.000';
                    }
                    break;
                    
                case 'durasi_kontrak_bulan':
                    if (!value || isNaN(value) || parseInt(value) < 1 || parseInt(value) > 120) {
                        isValid = false;
                        errorMessage = 'Durasi kontrak 1-120 bulan';
                    }
                    break;
                    
                case 'mekanisme_pembayaran':
                case 'jadwal_pengiriman':
                case 'kualitas_spesifikasi':
                    if (!value || value.length < 10) {
                        isValid = false;
                        errorMessage = 'Minimal 10 karakter';
                    }
                    break;
                    
                case 'meeting_summary':
                    if (!value || value.length < 20) {
                        isValid = false;
                        errorMessage = 'Minimal 20 karakter';
                    } else if (value.length > 2000) {
                        isValid = false;
                        errorMessage = 'Maksimal 2000 karakter';
                    }
                    break;
                    
                case 'final_document':
                    console.log('Validating final_document field');
                    console.log('Files:', field.files.length > 0 ? field.files[0].name : 'No file selected');
                    
                    if (field.files.length === 0) {
                        isValid = false;
                        errorMessage = 'Dokumen kerjasama wajib diunggah';
                        console.log('Error: No file uploaded');
                    } else {
                        const file = field.files[0];
                        const maxSize = 5 * 1024 * 1024; // 5MB
                        const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                        const fileExt = file.name.split('.').pop().toLowerCase();
                        const allowedExts = ['pdf', 'doc', 'docx'];
                        
                        console.log('File type:', file.type);
                        console.log('File extension:', fileExt);
                        console.log('File size:', file.size);
                        
                        // Check file type by both MIME type and extension
                        const validType = allowedTypes.includes(file.type) || allowedExts.includes(fileExt);
                        
                        if (!validType) {
                            isValid = false;
                            errorMessage = 'Format file harus PDF, DOC, atau DOCX';
                            console.log('Error: Invalid file type - MIME:', file.type, 'Extension:', fileExt);
                        } else if (file.size > maxSize) {
                            isValid = false;
                            errorMessage = 'Ukuran file maksimal 5MB';
                            console.log('Error: File too large -', Math.round(file.size / 1024 / 1024 * 100) / 100, 'MB');
                        } else {
                            console.log('File validation passed');
                            // Force valid state for file input
                            field.setCustomValidity('');
                        }
                    }
                    break;
            }
            
            // Apply validation classes
            if (isValid) {
                field.classList.add('is-valid');
            } else {
                field.classList.add('is-invalid');
            }
            
            // Show/hide error message
            const feedback = field.parentNode.querySelector('.invalid-feedback');
            if (feedback) {
                feedback.textContent = errorMessage;
            }
            
            return isValid;
        }
        
        function validateCompleteMeetingForm() {
            const requiredFields = ['harga_per_m3', 'durasi_kontrak_bulan', 'mekanisme_pembayaran', 
                                  'jadwal_pengiriman', 'kualitas_spesifikasi', 'meeting_summary', 'final_document'];
            
            let allValid = true;
            let firstInvalidField = null;
            
            console.log('Validating form fields...');
            
            requiredFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    const isFieldValid = validateField(field);
                    console.log(`Field ${fieldId} validation: ${isFieldValid ? 'valid' : 'invalid'}`);
                    
                    if (!isFieldValid) {
                        allValid = false;
                        if (!firstInvalidField) {
                            firstInvalidField = field;
                        }
                    }
                } else {
                    console.log(`Field ${fieldId} not found in the form`);
                    allValid = false;
                }
            });
            
            // Special check for file input
            const fileInput = document.getElementById('final_document');
            if (fileInput && fileInput.files.length === 0) {
                console.log('File input is empty, marking as invalid');
                fileInput.classList.add('is-invalid');
                const feedback = fileInput.parentNode.querySelector('.invalid-feedback');
                if (feedback) {
                    feedback.textContent = 'Dokumen kerjasama wajib diunggah';
                }
                allValid = false;
                if (!firstInvalidField) {
                    firstInvalidField = fileInput;
                }
            }
            
            // Scroll to first invalid field if validation fails
            if (firstInvalidField) {
                console.log('Scrolling to first invalid field:', firstInvalidField.id);
                firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstInvalidField.focus();
            }
            
            console.log(`Overall form validation: ${allValid ? 'valid' : 'invalid'}`);
            return allValid;
        }

        function viewMeetingDetails(meetingId) {
            // Fetch meeting details via AJAX
            fetch(`/cdk/meetings/${meetingId}/details`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMeetingDetailsModal(data.meeting);
                } else {
                    alert('Gagal memuat detail pertemuan: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memuat detail pertemuan');
            });
        }

        function showMeetingDetailsModal(meeting) {
            const modalHtml = `
                <div class="modal fade" id="meetingDetailsModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-info-circle me-2"></i>Detail Pertemuan
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6><i class="fas fa-tree me-2"></i>KTHR</h6>
                                        <p><strong>${meeting.kthr_name}</strong><br>
                                        <small class="text-muted">${meeting.kthr_email}</small></p>
                                        
                                        <h6><i class="fas fa-industry me-2"></i>PBPHH</h6>
                                        <p><strong>${meeting.pbphh_name}</strong><br>
                                        <small class="text-muted">${meeting.pbphh_email}</small></p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6><i class="fas fa-calendar me-2"></i>Jadwal</h6>
                                        <p>${meeting.scheduled_time}<br>
                                        <small class="text-muted">${meeting.scheduled_time_human}</small></p>
                                        
                                        <h6><i class="fas fa-map-marker-alt me-2"></i>Lokasi/Metode</h6>
                                        <p><span class="badge ${meeting.method === 'online' ? 'bg-info' : 'bg-success'}">
                                            <i class="fas ${meeting.method === 'online' ? 'fa-video' : 'fa-map-marker-alt'} me-1"></i>
                                            ${meeting.method === 'online' ? 'Online' : 'Lapangan'}
                                        </span><br>
                                        ${meeting.location}</p>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-12">
                                        <h6><i class="fas fa-info me-2"></i>Status</h6>
                                        <p><span class="badge ${meeting.status_badge}">${meeting.status}</span></p>
                                        
                                        ${meeting.meeting_notes ? `
                                            <h6><i class="fas fa-sticky-note me-2"></i>Catatan Pertemuan</h6>
                                            <p>${meeting.meeting_notes}</p>
                                        ` : ''}
                                        
                                        ${meeting.meeting_summary ? `
                                            <h6><i class="fas fa-file-alt me-2"></i>Ringkasan Pertemuan</h6>
                                            <p>${meeting.meeting_summary}</p>
                                        ` : ''}
                                        
                                        ${meeting.kesepakatan ? `
                                            <h6><i class="fas fa-handshake me-2"></i>Kesepakatan</h6>
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <strong>Harga:</strong> Rp ${meeting.kesepakatan.agreed_price_per_m3}/m³<br>
                                                            <strong>Durasi:</strong> ${meeting.kesepakatan.durasi_kontrak_bulan} bulan
                                                            ${meeting.kesepakatan.final_document_path ? `<br><a href="/storage/${meeting.kesepakatan.final_document_path}" target="_blank" class="btn btn-sm btn-outline-primary mt-2"><i class="fas fa-file-download me-1"></i>Unduh Dokumen</a>` : ''}
                                                        </div>
                                                        <div class="col-md-6">
                                                            <strong>Pembayaran:</strong> ${meeting.kesepakatan.payment_mechanism}<br>
                                                            <strong>Pengiriman:</strong> ${meeting.kesepakatan.delivery_schedule}
                                                        </div>
                                                    </div>
                                                    ${meeting.kesepakatan.other_notes ? `
                                                        <hr>
                                                        <strong>Catatan Tambahan:</strong><br>
                                                        ${meeting.kesepakatan.other_notes}
                                                    ` : ''}
                                                </div>
                                            </div>
                                        ` : ''}
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                ${meeting.status === 'Dijadwalkan' ? `
                                    <button type="button" class="btn btn-info" onclick="startMeeting(${meeting.meeting_id})">
                                        <i class="fas fa-play me-1"></i>Mulai Pertemuan
                                    </button>
                                ` : ''}
                                ${meeting.status === 'Berlangsung' && !meeting.kesepakatan ? `
                                    <button type="button" class="btn btn-success" onclick="completeMeeting(${meeting.meeting_id})">
                                        <i class="fas fa-check me-1"></i>Selesaikan Pertemuan
                                    </button>
                                ` : ''}
                                ${meeting.kesepakatan ? `
                                    <button type="button" class="btn btn-outline-danger" onclick="deleteAgreement(${meeting.kesepakatan.agreement_id})">
                                        <i class="fas fa-trash me-1"></i>Hapus Kerjasama
                                    </button>
                                ` : `
                                    <button type="button" class="btn btn-outline-danger" onclick="deleteMeeting(${meeting.meeting_id})">
                                        <i class="fas fa-trash me-1"></i>Hapus Pertemuan
                                    </button>
                                `}
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Remove existing modal if any
            const existingModal = document.getElementById('meetingDetailsModal');
            if (existingModal) {
                existingModal.remove();
            }
            
            // Add modal to body
            document.body.insertAdjacentHTML('beforeend', modalHtml);
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('meetingDetailsModal'), {
                backdrop: true,
                keyboard: true
            });
            modal.show();
            
            // Clean up modal when hidden
            document.getElementById('meetingDetailsModal').addEventListener('hidden.bs.modal', function() {
                this.remove();
            });
        }

        // Form validation and submission handling
        document.getElementById('scheduleForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (this.checkValidity()) {
                const submitBtn = document.getElementById('scheduleSubmitBtn');
                const spinner = submitBtn.querySelector('.spinner-border');
                const icon = submitBtn.querySelector('.fas');
                const isUpdate = this.querySelector('input[name="_method"]')?.value === 'PUT';
                
                // Show loading state
                submitBtn.disabled = true;
                if (spinner) {
                    spinner.classList.remove('d-none');
                }
                if (icon) {
                    icon.classList.add('d-none');
                }
                
                if (isUpdate) {
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Mengupdate...';
                } else {
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Menjadwalkan...';
                }
                
                // Submit form
                this.submit();
            } else {
                this.classList.add('was-validated');
            }
        });
        
        // Reset form when modal is closed
        document.getElementById('scheduleModal').addEventListener('hidden.bs.modal', function() {
            const form = document.getElementById('scheduleForm');
            const submitBtn = document.getElementById('scheduleSubmitBtn');
            
            // Reset form to create mode
            form.action = '{{ route("cdk.meetings.schedule") }}';
            form.reset();
            form.classList.remove('was-validated');
            
            // Remove method spoofing if exists
            const methodInput = form.querySelector('input[name="_method"]');
            if (methodInput) {
                methodInput.remove();
            }
            
            // Reset submit button
            submitBtn.innerHTML = '<i class="fas fa-calendar-plus me-1"></i>Jadwalkan';
            submitBtn.disabled = false;
            
            // Reset info text
            document.getElementById('schedule_partnership_info').innerHTML = 'Pilih kemitraan dari daftar di atas';
            
            // Clear validation classes
            form.querySelectorAll('.form-control, .form-select').forEach(input => {
                input.classList.remove('is-valid', 'is-invalid');
            });
        });
        
        // Form submission handled by initializeCompleteMeetingForm() function above
        // Removed duplicate event listener to prevent conflicts
        
        // Real-time validation feedback
        document.querySelectorAll('input[required], select[required], textarea[required]').forEach(field => {
            field.addEventListener('blur', function() {
                if (this.checkValidity()) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                } else {
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
                }
            });
            
            field.addEventListener('input', function() {
                if (this.classList.contains('is-invalid') && this.checkValidity()) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                }
            });
        });
        
        // Quick filter functionality
        document.querySelectorAll('input[name="quick_filter"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const form = document.querySelector('form[method="GET"]');
                const url = new URL(form.action);
                
                // Clear existing parameters
                url.search = '';
                
                switch(this.value) {
                    case 'today':
                        url.searchParams.set('date_from', new Date().toISOString().split('T')[0]);
                        url.searchParams.set('date_to', new Date().toISOString().split('T')[0]);
                        break;
                    case 'upcoming':
                        url.searchParams.set('status', 'Dijadwalkan');
                        url.searchParams.set('date_from', new Date().toISOString().split('T')[0]);
                        break;
                    case 'active':
                        url.searchParams.set('status', 'Berlangsung');
                        break;
                }
                
                window.location.href = url.toString();
            });
        });
        
        // Clear search functionality
        document.getElementById('clearSearch').addEventListener('click', function() {
            document.getElementById('searchQuery').value = '';
            document.querySelector('form[method="GET"]').submit();
        });
        
        // Real-time search with debounce
        let searchTimeout;
        document.getElementById('searchQuery').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length >= 3 || this.value.length === 0) {
                    document.querySelector('form[method="GET"]').submit();
                }
            }, 500);
        });
        
        // Enhanced table interactions
        document.querySelectorAll('.table tbody tr').forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#f8f9fa';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
            });
        });
        
        // Export meetings to Excel
         function exportMeetings() {
             const params = new URLSearchParams(window.location.search);
             params.set('export', 'excel');
             window.location.href = '{{ route("cdk.meetings") }}?' + params.toString();
         }
         
         // Print meetings
         function printMeetings() {
             const printContent = document.querySelector('.table-responsive').innerHTML;
             const printWindow = window.open('', '_blank');
             printWindow.document.write(`
                 <html>
                     <head>
                         <title>Daftar Pertemuan - {{ date('d/m/Y') }}</title>
                         <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
                         <style>
                             @media print {
                                 .btn, .pagination { display: none !important; }
                                 body { font-size: 12px; }
                                 .table { font-size: 11px; }
                             }
                         </style>
                     </head>
                     <body>
                         <div class="container-fluid">
                             <h3 class="text-center mb-4">Daftar Pertemuan Fasilitasi</h3>
                             <p class="text-center">Dicetak pada: {{ date('d/m/Y H:i') }}</p>
                             ${printContent}
                         </div>
                     </body>
                 </html>
             `);
             printWindow.document.close();
             printWindow.print();
         }
         
         // Real-time notifications
         function showNotification(message, type = 'info') {
             const notification = document.createElement('div');
             notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
             notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
             notification.innerHTML = `
                 ${message}
                 <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
             `;
             document.body.appendChild(notification);
             
             setTimeout(() => {
                 if (notification.parentNode) {
                     notification.remove();
                 }
             }, 5000);
         }
         
         // Check for new meetings or updates
         function checkForUpdates() {
             fetch('{{ route("cdk.meetings") }}?ajax=1&last_check=' + Date.now())
                 .then(response => response.json())
                 .then(data => {
                     if (data.hasUpdates) {
                         showNotification(
                             '<i class="fas fa-bell me-2"></i>Ada perubahan pada daftar pertemuan. <a href="#" onclick="location.reload()" class="alert-link">Refresh halaman</a>',
                             'warning'
                         );
                     }
                 })
                 .catch(error => console.log('Update check failed:', error));
         }
         
         // Keyboard shortcuts
         document.addEventListener('keydown', function(e) {
             if (e.ctrlKey || e.metaKey) {
                 switch(e.key) {
                     case 'f':
                         e.preventDefault();
                         document.getElementById('searchQuery').focus();
                         break;
                     case 'r':
                         e.preventDefault();
                         location.reload();
                         break;
                     case 'p':
                         e.preventDefault();
                         printMeetings();
                         break;
                 }
             }
         });
         
         // Auto-refresh for active meetings
         setInterval(checkForUpdates, 60000); // Check every minute
         
         setTimeout(function () {
             if (window.location.pathname.includes('/meetings')) {
                 location.reload();
             }
         }, 300000); // Full refresh every 5 minutes
         
         // Fungsi untuk menghapus kesepakatan kerjasama
         function deleteAgreement(agreementId) {
             if (confirm('Apakah Anda yakin ingin menghapus kesepakatan kerjasama ini? Tindakan ini tidak dapat dibatalkan.')) {
                 // Buat form untuk mengirim request DELETE
                 const form = document.createElement('form');
                 form.method = 'POST';
                 form.action = '{{ route("cdk.agreements.delete", ":id") }}'.replace(':id', agreementId);
                 
                 // Tambahkan CSRF token
                 const csrfToken = document.createElement('input');
                 csrfToken.type = 'hidden';
                 csrfToken.name = '_token';
                 csrfToken.value = '{{ csrf_token() }}';
                 form.appendChild(csrfToken);
                 
                 // Tambahkan method spoofing untuk DELETE
                 const methodInput = document.createElement('input');
                 methodInput.type = 'hidden';
                 methodInput.name = '_method';
                 methodInput.value = 'DELETE';
                 form.appendChild(methodInput);
                 
                 // Tambahkan form ke body dan submit
                 document.body.appendChild(form);
                 form.submit();
             }
         }
         
         // Fungsi untuk menghapus pertemuan
         function deleteMeeting(meetingId) {
             if (confirm('Apakah Anda yakin ingin menghapus pertemuan ini? Tindakan ini tidak dapat dibatalkan.')) {
                 // Buat form untuk mengirim request DELETE
                 const form = document.createElement('form');
                 form.method = 'POST';
                 form.action = '{{ route("cdk.meetings.delete", ":id") }}'.replace(':id', meetingId);
                 
                 // Tambahkan CSRF token
                 const csrfToken = document.createElement('input');
                 csrfToken.type = 'hidden';
                 csrfToken.name = '_token';
                 csrfToken.value = '{{ csrf_token() }}';
                 form.appendChild(csrfToken);
                 
                 // Tambahkan method spoofing untuk DELETE
                 const methodInput = document.createElement('input');
                 methodInput.type = 'hidden';
                 methodInput.name = '_method';
                 methodInput.value = 'DELETE';
                 form.appendChild(methodInput);
                 
                 // Tambahkan form ke body dan submit
                 document.body.appendChild(form);
                 form.submit();
             }
         }
     </script>
@endpush