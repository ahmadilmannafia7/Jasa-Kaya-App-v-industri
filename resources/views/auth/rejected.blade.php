@extends('layouts.app')

@section('title', 'Pendaftaran Ditolak - JASA KAYA')

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
    color: white;
    padding: 1.5rem;
    border-radius: 0.5rem 0.5rem 0 0;
}

.auth-icon {
    font-size: 3rem;
    color: white;
    margin-bottom: 1rem;
    animation: shake 0.5s ease-in-out infinite;
}

@keyframes shake {
    0%, 100% { transform: rotate(0deg); }
    25% { transform: rotate(-5deg); }
    75% { transform: rotate(5deg); }
}

.status-alert {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
    border: none;
    border-radius: 1rem;
}

.reason-box {
    background-color: rgba(220, 53, 69, 0.1);
    border-radius: 1rem;
    padding: 1.5rem;
    margin: 1rem 0;
    border-left: 4px solid #dc3545;
}

.action-list {
    background-color: rgba(255, 255, 255, 0.5);
    border-radius: 1rem;
    padding: 1.5rem;
    margin: 1rem 0;
}

.action-item {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
    padding: 0.5rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
}

.action-item:hover {
    background-color: rgba(255, 255, 255, 0.8);
}

.btn-register {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-register:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.btn-contact {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    color: #0d6efd;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-contact:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.btn-logout {
    color: #6c757d;
    transition: all 0.3s ease;
    text-decoration: none;
}

.btn-logout:hover {
    color: #dc3545;
}

.auth-footer {
    background-color: rgba(255, 255, 255, 0.1);
    border-top: 1px solid rgba(255, 255, 255, 0.2);
    padding: 1rem;
    color: #6c757d;
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
                    <i class="fas fa-times-circle auth-icon"></i>
                    <h4 class="fw-bold mb-0">Pendaftaran Ditolak</h4>
                </div>
                <div class="card-body p-4">
                    <div class="status-alert p-3">
                        <h5 class="alert-heading">
                            <i class="fas fa-exclamation-triangle me-2"></i>Status: Ditolak
                        </h5>
                        <p class="mb-0">
                            Maaf, pendaftaran Anda tidak dapat disetujui saat ini.
                        </p>
                    </div>
                    
                    @if($user->rejection_reason)
                    <div class="mb-4">
                        <h6 class="fw-bold">Alasan Penolakan:</h6>
                        <div class="reason-box">
                            <p class="mb-0">{{ $user->rejection_reason }}</p>
                        </div>
                    </div>
                    @endif
                    
                    <div class="action-list">
                        <h6 class="fw-bold mb-3">Apa yang dapat Anda lakukan?</h6>
                        <div class="action-items">
                            <div class="action-item">
                                <i class="fas fa-check text-success me-2"></i>
                                Perbaiki dokumen sesuai dengan alasan penolakan
                            </div>
                            <div class="action-item">
                                 <i class="fas fa-check text-success me-2"></i>
                                 Pastikan semua dokumen jelas dan dapat dibaca
                             </div>
                             <div class="action-item">
                                 <i class="fas fa-check text-success me-2"></i>
                                 Daftar ulang dengan dokumen yang telah diperbaiki
                             </div>
                             <div class="action-item">
                                 <i class="fas fa-check text-success me-2"></i>
                                 Hubungi kami jika memerlukan bantuan
                             </div>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <div class="d-flex justify-content-center gap-3">
                            <a href="{{ route('register') }}" class="btn btn-register">
                                <i class="fas fa-user-plus me-2"></i>Daftar Ulang
                            </a>
                            <a href="{{ route('contact') }}" class="btn btn-contact">
                                <i class="fas fa-phone me-2"></i>Hubungi Kami
                            </a>
                        </div>
                        
                        <div class="mt-3">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-logout">
                                    <i class="fas fa-sign-out-alt me-1"></i>Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center auth-footer">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Proses verifikasi dilakukan untuk memastikan kualitas dan keamanan platform
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
