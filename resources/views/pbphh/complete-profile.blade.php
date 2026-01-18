@extends('layouts.app')

@section('title', 'Lengkapi Profil Perusahaan - JASA KAYA')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-building me-2"></i>Lengkapi Profil Perusahaan
                        </h4>
                        <p class="mb-0 mt-2">Silakan lengkapi data profil perusahaan Anda untuk dapat mengakses semua fitur
                            sistem.</p>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('pbphh.profile.complete') }}" id="profileForm">
                            @csrf

                            <!-- Company Information -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-primary border-bottom pb-2">
                                        <i class="fas fa-info-circle me-2"></i>Informasi Perusahaan
                                    </h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="penanggung_jawab" class="form-label">Penanggung Jawab <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="penanggung_jawab"
                                            name="penanggung_jawab" value="{{ old('penanggung_jawab') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Nomor Telepon <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="phone" name="phone"
                                            value="{{ old('phone') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="jenis_produk_utama" class="form-label">Jenis Produk Utama <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="jenis_produk_utama"
                                            name="jenis_produk_utama" value="{{ old('jenis_produk_utama') }}" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="alamat_perusahaan" class="form-label">Alamat Perusahaan <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control" id="alamat_perusahaan" name="alamat_perusahaan"
                                            rows="3" required>{{ old('alamat_perusahaan') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Location Information -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-primary border-bottom pb-2">
                                        <i class="fas fa-map-marker-alt me-2"></i>Informasi Lokasi
                                    </h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="coordinate_lat" class="form-label">Latitude <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="coordinate_lat" name="coordinate_lat"
                                            value="{{ old('coordinate_lat') }}" step="0.00000001" min="-90" max="90"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="coordinate_lng" class="form-label">Longitude <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="coordinate_lng" name="coordinate_lng"
                                            value="{{ old('coordinate_lng') }}" step="0.00000001" min="-180" max="180"
                                            required>
                                    </div>
                                </div>
                            </div>

                            <!-- Production Information -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-primary border-bottom pb-2">
                                        <i class="fas fa-industry me-2"></i>Informasi Produksi
                                    </h5>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="kapasitas_izin_produksi_m3" class="form-label">Kapasitas Izin Produksi
                                            (m³/tahun) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="kapasitas_izin_produksi_m3"
                                            name="kapasitas_izin_produksi_m3"
                                            value="{{ old('kapasitas_izin_produksi_m3') }}" step="0.01" min="0" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="tahun_berdiri" class="form-label">Tahun Berdiri <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="tahun_berdiri" name="tahun_berdiri"
                                            value="{{ old('tahun_berdiri') }}" min="1900" max="{{ date('Y') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="jumlah_karyawan" class="form-label">Jumlah Karyawan <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="jumlah_karyawan"
                                            name="jumlah_karyawan" value="{{ old('jumlah_karyawan') }}" min="1" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="website" class="form-label">Website Perusahaan</label>
                                        <input type="url" class="form-control" id="website" name="website"
                                            value="{{ old('website') }}" placeholder="https://www.example.com">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="deskripsi_perusahaan" class="form-label">Deskripsi Perusahaan</label>
                                        <textarea class="form-control" id="deskripsi_perusahaan" name="deskripsi_perusahaan"
                                            rows="3">{{ old('deskripsi_perusahaan') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Material Needs Section -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-primary border-bottom pb-2">
                                        <i class="fas fa-boxes me-2"></i>Kebutuhan Bahan Baku
                                    </h5>
                                    <p class="text-muted">Tambahkan minimal satu jenis bahan baku yang dibutuhkan perusahaan
                                        Anda.</p>
                                </div>
                                <div class="col-12">
                                    <div id="materialsContainer">
                                        <div class="material-item border rounded p-3 mb-3">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="mb-0">Bahan Baku #1</h6>
                                                <button type="button" class="btn btn-sm btn-outline-danger remove-material"
                                                    style="display: none;">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Jenis Kebutuhan <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control"
                                                            name="materials[0][jenis_kayu]" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Tipe <span
                                                                class="text-danger">*</span></label>
                                                        <select class="form-select" name="materials[0][tipe]" required>
                                                            <option value="">Pilih Tipe</option>
                                                            <option value="Kayu">Kayu</option>
                                                            <option value="Bukan Kayu">Bukan Kayu</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Kebutuhan Bulanan (m³) <span
                                                                class="text-danger">*</span></label>
                                                        <input type="number" class="form-control"
                                                            name="materials[0][kebutuhan_bulanan_m3]" step="0.01" min="0.01"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Spesifikasi Tambahan</label>
                                                        <input type="text" class="form-control"
                                                            name="materials[0][spesifikasi_tambahan]"
                                                            placeholder="Diameter, panjang, kualitas, dll.">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="button" class="btn btn-outline-primary" id="addMaterial">
                                        <i class="fas fa-plus me-2"></i>Tambah Bahan Baku
                                    </button>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save me-2"></i>Simpan Profil
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                let materialIndex = 1;
                const materialsContainer = document.getElementById('materialsContainer');
                const addMaterialBtn = document.getElementById('addMaterial');

                addMaterialBtn.addEventListener('click', function () {
                    const materialItem = document.createElement('div');
                    materialItem.className = 'material-item border rounded p-3 mb-3';
                    materialItem.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Bahan Baku #${materialIndex + 1}</h6>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-material">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Jenis Kebutuhan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="materials[${materialIndex}][jenis_kayu]" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tipe <span class="text-danger">*</span></label>
                                <select class="form-select" name="materials[${materialIndex}][tipe]" required>
                                    <option value="">Pilih Tipe</option>
                                    <option value="Kayu">Kayu</option>
                                    <option value="Bukan Kayu">Bukan Kayu</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kebutuhan Bulanan (m³) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="materials[${materialIndex}][kebutuhan_bulanan_m3]" 
                                       step="0.01" min="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Spesifikasi Tambahan</label>
                                <input type="text" class="form-control" name="materials[${materialIndex}][spesifikasi_tambahan]" 
                                       placeholder="Diameter, panjang, kualitas, dll.">
                            </div>
                        </div>
                    </div>
                `;

                    materialsContainer.appendChild(materialItem);
                    materialIndex++;
                    updateRemoveButtons();
                });

                // Handle remove material
                materialsContainer.addEventListener('click', function (e) {
                    if (e.target.closest('.remove-material')) {
                        e.target.closest('.material-item').remove();
                        updateRemoveButtons();
                    }
                });

                function updateRemoveButtons() {
                    const materialItems = materialsContainer.querySelectorAll('.material-item');
                    materialItems.forEach((item, index) => {
                        const removeBtn = item.querySelector('.remove-material');
                        if (materialItems.length > 1) {
                            removeBtn.style.display = 'block';
                        } else {
                            removeBtn.style.display = 'none';
                        }

                        // Update numbering
                        const title = item.querySelector('h6');
                        title.textContent = `Bahan Baku #${index + 1}`;
                    });
                }
            });
        </script>
    @endpush
@endsection