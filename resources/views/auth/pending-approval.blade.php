@extends('layouts.app')

@section('title', 'Menunggu Persetujuan - JASA KAYA')

@push('styles')
<style>
.auth-container {
    min-height: 100vh;
    background: #ffffff;
    position: relative;
    overflow: hidden;
}

.auth-card {
    backdrop-filter: blur(10px);
    background-color: rgba(255, 255, 255, 0.95);
    border: none;
    transition: transform 0.3s ease;
}

.auth-card:hover {
    transform: translateY(-5px);
}

.auth-header {
    background: linear-gradient(135deg, #198652 0%, #0d7142 100%);
    color: #ffffff;
    padding: 1.5rem;
    border-radius: 0.5rem 0.5rem 0 0;
}

.auth-icon {
    font-size: 3rem;
    color: #212529;
    margin-bottom: 1rem;
    animation: clockSpin 3s ease-in-out infinite;
}

@keyframes clockSpin {
    0% { transform: rotate(0deg); }
    25% { transform: rotate(10deg); }
    75% { transform: rotate(-10deg); }
    100% { transform: rotate(0deg); }
}

.status-badge {
    background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);
    color: #212529;
    padding: 0.5rem 1rem;
    border-radius: 2rem;
    font-weight: 600;
    display: inline-block;
    margin-bottom: 1rem;
}

.doc-list {
    background-color: rgba(255, 255, 255, 0.5);
    border-radius: 1rem;
    padding: 1.5rem;
    margin: 1rem 0;
}

.doc-item {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
    padding: 0.5rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
}

.doc-item:hover {
    background-color: rgba(255, 255, 255, 0.8);
}

.btn-refresh {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-refresh:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.btn-logout {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-logout:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.auth-footer {
    background-color: rgba(255, 255, 255, 0.1);
    border-top: 1px solid rgba(255, 255, 255, 0.2);
    padding: 1rem;
}

.auth-link {
    color: #198652;
    transition: all 0.3s ease;
}

.auth-link:hover {
    color: #198652;
    text-decoration: underline !important;
}
</style>
@endpush

@section('content')
<div class="auth-container py-5">
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card auth-card shadow">
                <div class="auth-header text-center">
                    <i class="fas fa-clock auth-icon"></i>
                    <h4 class="fw-bold mb-0">Akun Menunggu Persetujuan</h4>
                </div>
                <div class="card-body p-4 text-center">
                    <div class="status-badge">Status: Sedang Diproses</div>
                    <p class="mb-4">
                        Terima kasih telah mendaftar di JASA KAYA. Akun Anda sedang dalam proses verifikasi oleh 
                        @if(Auth::user()->role === 'KTHR_PENYULUH')
                            <strong>CDK (Cabang Dinas Kehutanan)</strong>.
                        @else
                            <strong>Dinas Kehutanan Provinsi</strong>.
                        @endif
                    </p>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Estimasi Waktu Verifikasi:</strong><br>
                        @if(Auth::user()->role === 'KTHR_PENYULUH')
                            1-3 hari kerja
                        @else
                            3-5 hari kerja
                        @endif
                    </div>
                    
                    <div class="doc-list">
                        <h6 class="fw-bold mb-3">Dokumen yang Diverifikasi:</h6>
                        <div class="doc-items">
                            @if(Auth::user()->role === 'KTHR_PENYULUH')
                                <div class="doc-item"><i class="fas fa-check text-success me-2"></i>KTP Ketua KTHR</div>
                                <div class="doc-item"><i class="fas fa-check text-success me-2"></i>SK Pendaftaran KTHR</div>
                            @else
                                <div class="doc-item"><i class="fas fa-check text-success me-2"></i>NIB (Nomor Induk Berusaha)</div>
                                <div class="doc-item"><i class="fas fa-check text-success me-2"></i>SK PBPHH</div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <small class="text-muted">
                            <i class="fas fa-envelope me-1"></i>
                            Kami akan mengirim notifikasi ke <strong>{{ Auth::user()->email }}</strong> 
                            setelah proses verifikasi selesai.
                        </small>
                    </div>
                    
                    <div class="d-flex justify-content-center gap-3">
                        <button type="button" class="btn btn-refresh" onclick="location.reload()">
                            <i class="fas fa-sync-alt me-2"></i>Refresh Status
                        </button>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-logout">
                                <i class="fas fa-sign-out-alt me-2"></i>Keluar
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-footer text-center auth-footer">
                    <small class="text-muted">
                        Butuh bantuan? <a href="{{ route('contact') }}" class="auth-link text-decoration-none">Hubungi kami</a>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
