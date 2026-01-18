@extends('layouts.app')

@section('title', 'Lupa Kata Sandi - JASA KAYA')

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

.form-control {
    border: 2px solid #e9ecef;
    padding: 0.75rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #198652;
    box-shadow: 0 0 0 0.15rem rgba(25, 134, 82, 0.15);
}

.btn-auth {
    padding: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    background: linear-gradient(135deg, #198652 0%, #0d7142 100%);
    border: none;
    transition: all 0.3s ease;
}

.btn-auth:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.auth-link {
    color: #198652;
    transition: all 0.3s ease;
}

.auth-link:hover {
    color: #198652;
    text-decoration: underline !important;
}

.auth-icon {
    font-size: 3rem;
    color: #198652;
    margin-bottom: 1rem;
    animation: iconFloat 3s ease-in-out infinite;
}

@keyframes iconFloat {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}
</style>
@endpush

@section('content')
<div class="auth-container py-5">
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card auth-card shadow">
                <div class="auth-header text-center">
                    <h4 class="fw-bold mb-0">
                        <i class="fas fa-key me-2"></i>Lupa Kata Sandi
                    </h4>
                </div>
                <div class="card-body p-4">
                    @if (session('status'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <div><i class="fas fa-exclamation-circle me-2"></i>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <div class="text-center mb-4">
                        <i class="fas fa-envelope auth-icon"></i>
                        <p class="text-muted">
                            Masukkan alamat email Anda dan kami akan mengirimkan link untuk reset kata sandi.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ old('email') }}" required autofocus>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-auth btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Kirim Link Reset
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <p class="mb-0">Ingat kata sandi Anda? 
                        <a href="{{ route('login') }}" class="auth-link text-decoration-none fw-bold">
                            Masuk di sini
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
