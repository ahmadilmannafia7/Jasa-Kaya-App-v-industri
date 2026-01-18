@extends('layouts.app')

@section('title', 'Masuk - JASA KAYA')

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
                        <i class="fas fa-sign-in-alt me-2"></i>Masuk ke Akun Anda
                    </h4>
                </div>
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ old('email') }}" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Kata Sandi</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Ingat saya
                            </label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-auth btn-primary">
                                <i class="fas fa-sign-in-alt me-2"></i>Masuk
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        <a href="{{ route('password.request') }}" class="auth-link text-decoration-none">
                            Lupa kata sandi?
                        </a>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <p class="mb-0">Belum punya akun? 
                        <a href="{{ route('register') }}" class="auth-link text-decoration-none fw-bold">
                            Daftar sekarang
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
