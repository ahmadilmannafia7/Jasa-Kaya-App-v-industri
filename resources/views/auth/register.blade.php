@extends('layouts.app')

@section('title', 'Daftar - JASA KAYA')

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

        .form-control,
        .form-select {
            border: 2px solid #e9ecef;
            padding: 0.75rem;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #198652;
            box-shadow: 0 0 0 0.15rem rgba(25, 134, 82, 0.15);
        }

        .role-card {
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid #e9ecef;
            background-color: white;
        }

        .role-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .role-card.selected-kthr {
            border-color: #198652;
            background-color: rgba(25, 134, 82, 0.08);
        }

        .role-card.selected-tptkb {
            border-color: #198652;
            background-color: rgba(25, 134, 82, 0.08);
        }

        .role-card.selected-pbphh {
            border-color: #198652;
            background-color: rgba(25, 134, 82, 0.08);
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
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .auth-link {
            color: var(--primary-color);
            transition: all 0.3s ease;
        }

        .auth-link:hover {
            color: var(--secondary-color);
            text-decoration: underline !important;
        }

        .file-upload {
            position: relative;
            overflow: hidden;
        }

        .file-upload input[type=file] {
            font-size: 100px;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            cursor: pointer;
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
                            <h4 class="fw-bold mb-0">
                                @if(isset($user) && $user->approval_status === 'Rejected')
                                    <i class="fas fa-sync-alt me-2"></i>Perbarui Dokumen
                                @else
                                    <i class="fas fa-user-plus me-2"></i>Daftar Akun Baru
                                @endif
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

                            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data"
                                id="registerForm">
                                @csrf

                                <!-- Role Selection -->
                                @if(isset($user) && $user->approval_status === 'Rejected')
                                    <!-- Show current role for rejected users -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Tipe Akun Anda</label>
                                        <div class="alert alert-info">
                                            @if($user->role === 'KTHR_PENYULUH')
                                                <i class="fas fa-users me-2"></i><strong>KTHR / Penyuluh</strong> - Kelompok Tani
                                                Hutan Rakyat
                                            @elseif($user->role === 'TPTKB')
                                                <i class="fas fa-wood me-2"></i><strong>TPTKB</strong> - Tempat Penampungan
                                                Terdaftar Kayu Bulat
                                            @else
                                                <i class="fas fa-industry me-2"></i><strong>PBPHH / Industri</strong> - Pengolahan
                                                Bahan Baku Hasil Hutan
                                            @endif
                                        </div>
                                        <input type="hidden" name="role" value="{{ $user->role }}">
                                    </div>
                                @else
                                    <!-- Normal role selection for new users -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Daftar Sebagai</label>
                                        <div class="row">

                                            <!-- KTHR -->
                                            <div class="col-md-4">
                                                <div class="card role-card" id="kthrCard">
                                                    <div class="card-body text-center">
                                                        <input type="radio" class="form-check-input" name="role"
                                                            value="KTHR_PENYULUH" id="kthr" required>

                                                        <label for="kthr" class="form-check-label d-block">
                                                            <i class="fas fa-tree fa-2x text-success mb-2"></i>
                                                            <h6>KTHR / Penyuluh</h6>
                                                            <small class="text-muted">
                                                                Kelompok Tani Hutan Rakyat
                                                            </small>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- TPTKB -->
                                            <div class="col-md-4">
                                                <div class="card role-card" id="tptkbCard">
                                                    <div class="card-body text-center">
                                                        <input type="radio" class="form-check-input" name="role" value="TPTKB"
                                                            id="tptkb" required>

                                                        <label for="tptkb" class="form-check-label d-block">
                                                            <i class="fas fa-warehouse fa-2x text-warning mb-2"></i>
                                                            <h6>TPTKB</h6>
                                                            <small class="text-muted">
                                                                Tempat Penampungan Terdaftar Kayu Bulat
                                                            </small>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- PBPHH -->
                                            <div class="col-md-4">
                                                <div class="card role-card" id="pbphhCard">
                                                    <div class="card-body text-center">
                                                        <input type="radio" class="form-check-input" name="role" value="PBPHH"
                                                            id="pbphh" required>

                                                        <label for="pbphh" class="form-check-label d-block">
                                                            <i class="fas fa-industry fa-2x text-primary mb-2"></i>
                                                            <h6>PBPHH / Industri</h6>
                                                            <small class="text-muted">
                                                                Pengolahan Bahan Baku Hasil Hutan
                                                            </small>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                @endif

                                <!-- Common Fields -->
                                @if(isset($user) && $user->approval_status === 'Rejected')
                                    <!-- Show existing data for rejected users -->
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" value="{{ $user->email }}" readonly>
                                        <small class="text-muted">Email tidak dapat diubah</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="region_id" class="form-label">Wilayah (CDK / Provinsi)</label>
                                        <input type="text" class="form-control" value="{{ $user->region->name }}" readonly>
                                        <small class="text-muted">Wilayah tidak dapat diubah</small>
                                    </div>

                                    @if($user->role === 'PBPHH')
                                        <div class="mb-3">
                                            <label for="company_name" class="form-label">Nama Perusahaan</label>
                                            <input type="text" class="form-control" value="{{ $user->pbphhProfile->company_name }}"
                                                readonly>
                                            <small class="text-muted">Nama perusahaan tidak dapat diubah</small>
                                        </div>
                                    @elseif($user->role === 'KTHR_PENYULUH')
                                        <div class="mb-3">
                                            <label for="kthr_name" class="form-label">Nama KTHR</label>
                                            <input type="text" class="form-control" value="{{ $user->kthr->kthr_name }}" readonly>
                                            <small class="text-muted">Nama KTHR tidak dapat diubah</small>
                                        </div>
                                    @elseif($user->role === 'TPTKB')
                                        <div class="mb-3">
                                            <label for="tptkb_name" class="form-label">Nama TPTKB</label>
                                            <input type="text" class="form-control" value="{{ $user->tptkb->tptkb_name }}" readonly>
                                            <small class="text-muted">Nama TPTKB tidak dapat diubah</small>
                                        </div>
                                    @endif

                                    <div class="alert alert-warning">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Informasi:</strong> Anda hanya perlu mengupload ulang dokumen yang diperlukan.
                                        Data lainnya tidak dapat diubah.
                                    </div>
                                @else
                                    <!-- Normal fields for new users -->
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ old('email') }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="region_id" class="form-label">Wilayah (CDK / Provinsi)</label>
                                        <select name="region_id" id="region_id" class="form-control" required>
                                            <option value="">-- Pilih Wilayah --</option>
                                            @foreach ($regions as $region)
                                                <option value="{{ $region->region_id }}" {{ old('region_id') == $region->region_id ? 'selected' : '' }}>
                                                    {{ $region->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="password" class="form-label">Kata Sandi</label>
                                                <input type="password" class="form-control" id="password" name="password"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="password_confirmation" class="form-label">Konfirmasi Kata
                                                    Sandi</label>
                                                <input type="password" class="form-control" id="password_confirmation"
                                                    name="password_confirmation" required>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if(isset($user) && $user->approval_status === 'Rejected')
                                    <!-- Document upload for rejected users -->
                                    @if($user->role === 'KTHR_PENYULUH')
                                        <div class="mb-4">
                                            <h6 class="fw-bold text-primary">Upload Ulang Dokumen KTHR</h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="ketua_ktp" class="form-label">KTP Ketua KTHR <span
                                                                class="text-danger">*</span></label>
                                                        <input type="file" class="form-control" id="ketua_ktp" name="ketua_ktp"
                                                            accept=".pdf,.jpg,.jpeg,.png" required>
                                                        <small class="text-muted">Format: PDF, JPG, PNG (Max: 2MB)</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="sk_register" class="form-label">SK Pendaftaran KTHR <span
                                                                class="text-danger">*</span></label>
                                                        <input type="file" class="form-control" id="sk_register" name="sk_register"
                                                            accept=".pdf" required>
                                                        <small class="text-muted">Format: PDF (Max: 2MB)</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($user->role === 'TPTKB')
                                        <div class="mb-4">
                                            <h6 class="fw-bold text-primary">Upload Ulang Dokumen TPTKB</h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="ketua_ktp_tptkb" class="form-label">KTP Ketua TPTKB <span
                                                                class="text-danger">*</span></label>
                                                        <input type="file" class="form-control" id="ketua_ktp_tptkb" name="ketua_ktp_tptkb"
                                                            accept=".pdf,.jpg,.jpeg,.png" required>
                                                        <small class="text-muted">Format: PDF, JPG, PNG (Max: 2MB)</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="sk_tptkb" class="form-label">SK Pendaftaran TPTKB <span
                                                                class="text-danger">*</span></label>
                                                        <input type="file" class="form-control" id="sk_tptkb" name="sk_tptkb"
                                                            accept=".pdf" required>
                                                        <small class="text-muted">Format: PDF (Max: 2MB)</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($user->role === 'PBPHH')
                                        <div class="mb-4">
                                            <h6 class="fw-bold text-primary">Upload Ulang Dokumen PBPHH</h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="nib" class="form-label">NIB (Nomor Induk Berusaha) <span
                                                                class="text-danger">*</span></label>
                                                        <input type="file" class="form-control" id="nib" name="nib"
                                                            accept=".pdf,.jpg,.jpeg,.png" required>
                                                        <small class="text-muted">Format: PDF, JPG, PNG (Max: 2MB)</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="sk_pbphh" class="form-label">SK PBPHH <span
                                                                class="text-danger">*</span></label>
                                                        <input type="file" class="form-control" id="sk_pbphh" name="sk_pbphh"
                                                            accept=".pdf" required>
                                                        <small class="text-muted">Format: PDF (Max: 2MB)</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <!-- Normal fields for new users -->
                                    <!-- KTHR Specific Fields -->
                                    <div id="kthrFields" style="display: none;">
                                        <div class="mb-3">
                                            <label for="kthr_name" class="form-label">Nama KTHR</label>
                                            <input type="text" class="form-control" id="kthr_name" name="kthr_name">
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="ketua_ktp" class="form-label">KTP Ketua KTHR</label>
                                                    <input type="file" class="form-control" id="ketua_ktp" name="ketua_ktp"
                                                        accept=".pdf,.jpg,.jpeg,.png">
                                                    <small class="text-muted">Format: PDF, JPG, PNG (Max: 2MB)</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="sk_register" class="form-label">SK Pendaftaran KTHR</label>
                                                    <input type="file" class="form-control" id="sk_register" name="sk_register"
                                                        accept=".pdf">
                                                    <small class="text-muted">Format: PDF (Max: 2MB)</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- TPTKB Specific Fields -->
                                    <div id="tptkbFields" style="display: none;">
                                        <div class="mb-3">
                                            <label for="tptkb_name" class="form-label">Nama TPTKB</label>
                                            <input type="text" class="form-control" id="tptkb_name" name="tptkb_name">
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="ketua_ktp_tptkb" class="form-label">KTP Ketua TPTKB</label>
                                                    <input type="file" class="form-control" id="ketua_ktp_tptkb" name="ketua_ktp_tptkb"
                                                        accept=".pdf,.jpg,.jpeg,.png">
                                                    <small class="text-muted">Format: PDF, JPG, PNG (Max: 2MB)</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="sk_tptkb" class="form-label">SK Pendaftaran TPTKB</label>
                                                    <input type="file" class="form-control" id="sk_tptkb" name="sk_tptkb"
                                                        accept=".pdf">
                                                    <small class="text-muted">Format: PDF (Max: 2MB)</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- PBPHH Specific Fields -->
                                    <div id="pbphhFields" style="display: none;">
                                        <div class="mb-3">
                                            <label for="company_name" class="form-label">Nama Perusahaan</label>
                                            <input type="text" class="form-control" id="company_name" name="company_name">
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="nib" class="form-label">NIB (Nomor Induk Berusaha)</label>
                                                    <input type="file" class="form-control" id="nib" name="nib"
                                                        accept=".pdf,.jpg,.jpeg,.png">
                                                    <small class="text-muted">Format: PDF, JPG, PNG (Max: 2MB)</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="sk_pbphh" class="form-label">SK PBPHH</label>
                                                    <input type="file" class="form-control" id="sk_pbphh" name="sk_pbphh"
                                                        accept=".pdf">
                                                    <small class="text-muted">Format: PDF (Max: 2MB)</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-auth btn-primary">
                                        @if(isset($user) && $user->approval_status === 'Rejected')
                                            <i class="fas fa-sync-alt me-2"></i>Perbarui Dokumen
                                        @else
                                            <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                                        @endif
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer text-center">
                            <p class="mb-0">Sudah punya akun?
                                <a href="{{ route('login') }}" class="auth-link text-decoration-none fw-bold">
                                    Masuk di sini
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    // Check if this is a rejected user re-registration
                    @if(isset($user) && $user->approval_status === 'Rejected')
                        // For rejected users, add form validation and submission logging
                        const form = document.getElementById('registerForm');
                        if (form) {
                            form.addEventListener('submit', function (e) {
                                console.log('Form submission started for rejected user re-registration');

                                // Validate files
                                const nibFile = document.getElementById('nib');
                                const skFile = document.getElementById('sk_pbphh');


                                if (!nibFile.files.length) {
                                    alert('Silakan pilih file NIB');
                                    e.preventDefault();
                                    return false;
                                }

                                if (!skFile.files.length) {
                                    alert('Silakan pilih file SK PBPHH');
                                    e.preventDefault();
                                    return false;
                                }

                                // Check file sizes (2MB = 2097152 bytes)
                                if (nibFile.files[0].size > 2097152) {
                                    alert('File NIB terlalu besar. Maksimal 2MB.');
                                    e.preventDefault();
                                    return false;
                                }

                                if (skFile.files[0].size > 2097152) {
                                    alert('File SK PBPHH terlalu besar. Maksimal 2MB.');
                                    e.preventDefault();
                                    return false;
                                }

                                // Check file types
                                const nibType = nibFile.files[0].type;
                                const skType = skFile.files[0].type;

                                const allowedNibTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
                                const allowedSkTypes = ['application/pdf'];


                                if (!allowedNibTypes.includes(nibType)) {
                                    alert('Format file NIB tidak valid. Gunakan PDF, JPG, JPEG, atau PNG.');
                                    e.preventDefault();
                                    return false;
                                }

                                if (!allowedSkTypes.includes(skType)) {
                                    alert('Format file SK PBPHH tidak valid. Gunakan PDF.');
                                    e.preventDefault();
                                    return false;
                                }

                                console.log('Form validation passed, submitting...');
                                console.log('NIB file:', nibFile.files[0].name, nibFile.files[0].type, nibFile.files[0].size);
                                console.log('SK file:', skFile.files[0].name, skFile.files[0].type, skFile.files[0].size);
                            });
                        }
                        return;
                    @endif

                                    // Normal registration logic for new users
                                    const urlParams = new URLSearchParams(window.location.search);
                    const roleParam = urlParams.get('role');

                    if (roleParam) {
                        if (roleParam === 'KTHR_PENYULUH') {
                            document.getElementById('kthr').checked = true;
                        } else if (roleParam === 'PBPHH') {
                            document.getElementById('pbphh').checked = true;
                        } else if (roleParam === 'TPTKB') {
                            document.getElementById('tptkb').checked = true;
                        }
                        toggleFields();
                    }

                    const kthrRadio = document.getElementById('kthr');
                    const pbphhRadio = document.getElementById('pbphh');
                    const tptkbRadio = document.getElementById('tptkb');
                    const kthrFields = document.getElementById('kthrFields');
                    const pbphhFields = document.getElementById('pbphhFields');
                    const tptkbFields = document.getElementById('tptkbFields');
                    const kthrCard = document.getElementById('kthrCard');
                    const pbphhCard = document.getElementById('pbphhCard');
                    const tptkbCard = document.getElementById('tptkbCard');

                    function toggleFields() {
                        if (kthrRadio && kthrRadio.checked) {
                            kthrFields.style.display = 'block';
                            pbphhFields.style.display = 'none';
                            tptkbFields.style.display = 'none';
                            kthrCard.classList.add('selected-kthr');
                            pbphhCard.classList.remove('selected-pbphh');
                            tptkbCard.classList.remove('selected-tptkb');
                            document.getElementById('kthr_name').required = true;
                            document.getElementById('ketua_ktp').required = true;
                            document.getElementById('sk_register').required = true;
                            document.getElementById('company_name').required = false;
                            document.getElementById('nib').required = false;
                            document.getElementById('sk_pbphh').required = false;
                            document.getElementById('tptkb_name').required = false;
                            document.getElementById('ketua_ktp_tptkb').required = false;
                            document.getElementById('sk_tptkb').required = false;
                        } else if (pbphhRadio && pbphhRadio.checked) {
                            kthrFields.style.display = 'none';
                            pbphhFields.style.display = 'block';
                            tptkbFields.style.display = 'none';
                            pbphhCard.classList.add('selected-pbphh');
                            kthrCard.classList.remove('selected-kthr');
                            tptkbCard.classList.remove('selected-tptkb');
                            document.getElementById('company_name').required = true;
                            document.getElementById('nib').required = true;
                            document.getElementById('sk_pbphh').required = true;
                            document.getElementById('kthr_name').required = false;
                            document.getElementById('ketua_ktp').required = false;
                            document.getElementById('sk_register').required = false;
                            document.getElementById('tptkb_name').required = false;
                            document.getElementById('ketua_ktp_tptkb').required = false;
                            document.getElementById('sk_tptkb').required = false;
                        } else if (tptkbRadio && tptkbRadio.checked) {
                            kthrFields.style.display = 'none';
                            pbphhFields.style.display = 'none';
                            tptkbFields.style.display = 'block';
                            tptkbCard.classList.add('selected-tptkb');
                            kthrCard.classList.remove('selected-kthr');
                            pbphhCard.classList.remove('selected-pbphh');
                            document.getElementById('tptkb_name').required = true;
                            document.getElementById('ketua_ktp_tptkb').required = true;
                            document.getElementById('sk_tptkb').required = true;
                            document.getElementById('company_name').required = false;
                            document.getElementById('nib').required = false;
                            document.getElementById('sk_pbphh').required = false;
                            document.getElementById('kthr_name').required = false;
                            document.getElementById('ketua_ktp').required = false;
                            document.getElementById('sk_register').required = false;
                        } else {
                            kthrFields.style.display = 'none';
                            pbphhFields.style.display = 'none';
                            tptkbFields.style.display = 'none';
                            kthrCard.classList.remove('selected-kthr');
                            pbphhCard.classList.remove('selected-pbphh');
                            tptkbCard.classList.remove('selected-tptkb');
                        }
                    }

                    if (kthrRadio) {
                        kthrRadio.addEventListener('change', toggleFields);
                    }

                    if (pbphhRadio) {
                        pbphhRadio.addEventListener('change', toggleFields);
                    }

                    if (tptkbRadio) {
                        tptkbRadio.addEventListener('change', toggleFields);
                    }
                });
            </script>
        @endpush
@endsection