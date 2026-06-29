@extends('layouts.kthr')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/kthr-dashboard.css') }}">
@endpush

@section('title', 'Profil KTHR - JASA KAYA')

@section('dashboard-content')
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-primary mb-1">Profil KTHR</h2>
            <p class="text-muted mb-0">Informasi lengkap tentang {{ $kthr->kthr_name }}</p>
        </div>
        <div>
            <a href="{{ route('kthr.profile.complete') }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Edit Profil
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Informasi Dasar -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informasi Dasar
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper me-3">
                                    <i class="fas fa-user-tie text-primary"></i>
                                </div>
                                <div>
                                    <small class="text-muted">Nama Pendamping</small>
                                    <div class="fw-bold">{{ $kthr->nama_pendamping }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper me-3">
                                    <i class="fas fa-users text-info"></i>
                                </div>
                                <div>
                                    <small class="text-muted">Jumlah Anggota</small>
                                    <div class="fw-bold">{{ number_format($kthr->jumlah_anggota) }} Orang</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper me-3">
                                    <i class="fas fa-map-marker-alt text-danger"></i>
                                </div>
                                <div>
                                    <small class="text-muted">Alamat Sekretariat</small>
                                    <div class="fw-bold">{{ $kthr->alamat_sekretariat }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper me-3">
                                    <i class="fas fa-calendar-alt text-warning"></i>
                                </div>
                                <div>
                                    <small class="text-muted">Pertemuan KTH</small>
                                    <div class="fw-bold">{{ $kthr->jumlah_pertemuan_tahunan }} Kali/Tahun</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Lokasi -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-globe me-2"></i>Informasi Lokasi
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-crosshairs text-primary me-2"></i>
                            <small class="text-muted">Koordinat</small>
                        </div>
                        <div class="fw-bold">{{ $kthr->coordinate_lat }}, {{ $kthr->coordinate_lng }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-expand-arrows-alt text-success me-2"></i>
                            <small class="text-muted">Luas Areal</small>
                        </div>
                        <div class="fw-bold text-success">{{ number_format($kthr->luas_areal_ha, 2) }} Hektar</div>
                    </div>
                    <div class="text-center mt-3">
                        <button class="btn btn-outline-primary btn-sm" onclick="showMap()">
                            <i class="fas fa-map me-2"></i>Lihat di Peta
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Tanaman -->
    <div class="card">
        <div class="card-header bg-warning text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-seedling me-2"></i>Data Tanaman
                </h5>
                <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addPlantModal">
                    <i class="fas fa-plus me-2"></i>Tambah Tanaman
                </button>
            </div>
        </div>
        <div class="card-body">
            @forelse($kthr->plantSpecies as $plant)
                <div class="card mb-3 border-start border-success border-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                @if($plant->gambar_tegakan_path)
                                    @php
                                        // Gunakan Storage facade untuk memeriksa keberadaan file
                                        $imageExists = \Illuminate\Support\Facades\Storage::disk('public')->exists($plant->gambar_tegakan_path);
                                        // Log untuk debugging
                                        \Illuminate\Support\Facades\Log::info('Image check', [
                                            'path' => $plant->gambar_tegakan_path,
                                            'exists' => $imageExists
                                        ]);
                                    @endphp
                                    @if($imageExists)
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($plant->gambar_tegakan_path) }}"
                                            alt="Gambar Tegakan {{ $plant->jenis_tanaman }}" class="img-fluid rounded"
                                            style="height: 120px; object-fit: cover; width: 100%;"
                                            onerror="console.error('Error loading image:', this.src); this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="bg-light rounded align-items-center justify-content-center"
                                            style="height: 120px; display: none;">
                                            <div class="text-center">
                                                <i class="fas fa-exclamation-triangle fa-2x text-warning mb-2"></i>
                                                <small class="text-muted d-block">Gambar tidak dapat dimuat</small>
                                            </div>
                                        </div>
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                            style="height: 120px;">
                                            <div class="text-center">
                                                <i class="fas fa-exclamation-triangle fa-2x text-warning mb-2"></i>
                                                <small class="text-muted d-block">File gambar tidak ditemukan:</small>
                                                <small class="text-muted d-block">{{ $plant->gambar_tegakan_path }}</small>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                        style="height: 120px;">
                                        <div class="text-center">
                                            <i class="fas fa-tree fa-3x text-muted mb-2"></i>
                                            <small class="text-muted">Tidak ada gambar</small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <small class="text-muted">Jenis Tanaman</small>
                                        <div class="fw-bold text-success">{{ $plant->jenis_tanaman }}</div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <small class="text-muted">Tipe</small>
                                        <div class="fw-bold">
                                            <span class="badge bg-info">{{ $plant->tipe }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <small class="text-muted">Jumlah Pohon</small>
                                        <div class="fw-bold">{{ number_format($plant->jumlah_pohon) }} Pohon</div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <small class="text-muted">Tahun Tanam</small>
                                        <div class="fw-bold">{{ $plant->tahun_tanam }}</div>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-primary btn-edit-plant"
                                                data-id="{{ $plant->plant_id }}" data-jenis="{{ $plant->jenis_tanaman }}"
                                                data-tipe="{{ $plant->tipe }}" data-jumlah="{{ $plant->jumlah_pohon }}"
                                                data-tahun="{{ $plant->tahun_tanam }}">
                                                <i class="fas fa-edit me-1"></i>Edit
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete-plant"
                                                data-id="{{ $plant->plant_id }}" data-jenis="{{ $plant->jenis_tanaman }}">
                                                <i class="fas fa-trash me-1"></i>Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-seedling fa-4x"></i>
                    <h5>Belum Ada Data Tanaman</h5>
                    <p>Data tanaman akan muncul di sini setelah ditambahkan</p>
                    <a href="{{ route('kthr.profile.complete') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Tambah Data Tanaman
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Modal Tambah/Edit Tanaman -->
    <div class="modal fade" id="addPlantModal" tabindex="-1" aria-labelledby="addPlantModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPlantModalLabel">
                        <i class="fas fa-seedling me-2"></i>Tambah Data Tanaman
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="plantForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="plantId" name="plant_id">
                    <input type="hidden" id="formMethod" name="_method" value="POST">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="jenis_tanaman" class="form-label">Jenis Tanaman <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="jenis_tanaman" name="jenis_tanaman" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tipe" class="form-label">Tipe <span class="text-danger">*</span></label>
                                <select class="form-select" id="tipe" name="tipe" required>
                                    <option value="">Pilih Tipe</option>
                                    <option value="Kayu">Kayu</option>
                                    <option value="Bukan Kayu">Bukan Kayu</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="jumlah_pohon" class="form-label">Jumlah Pohon <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="jumlah_pohon" name="jumlah_pohon" min="1"
                                    required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tahun_tanam" class="form-label">Tahun Tanam <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="tahun_tanam" name="tahun_tanam" min="1900"
                                    max="{{ date('Y') }}" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="gambar_tegakan" class="form-label">Gambar Tegakan</label>
                                <input type="file" class="form-control" id="gambar_tegakan" name="gambar_tegakan"
                                    accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                                <div class="form-text">Format: JPG, JPEG, PNG, GIF, WEBP. Maksimal 5MB.</div>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save me-2"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function showMap() {
                var lat = "{{ $kthr->coordinate_lat ?? 'null' }}";
                var lng = "{{ $kthr->coordinate_lng ?? 'null' }}";

                // Convert string 'null' to JavaScript null
                if (lat === "null" || lng === "null") {
                    alert('Koordinat belum tersedia. Silakan lengkapi data koordinat terlebih dahulu.');
                    return;
                }

                const url = `https://www.google.com/maps?q=${lat},${lng}&z=15`;
                window.open(url, '_blank');
            }

            // Fungsi untuk menambah tanaman baru
            function addPlant() {
                // Reset form
                document.getElementById('plantForm').reset();
                document.getElementById('plantId').value = '';
                document.getElementById('formMethod').value = 'POST';
                document.getElementById('addPlantModalLabel').innerHTML = '<i class="fas fa-seedling me-2"></i>Tambah Data Tanaman';
                document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save me-2"></i>Simpan';

                // Clear validation errors
                clearValidationErrors();

                // Show modal with explicit configuration
                const modal = new bootstrap.Modal(document.getElementById('addPlantModal'), {
                    backdrop: false,
                    keyboard: true,
                    focus: true
                });
                modal.show();

                // Ensure modal stays visible
                setTimeout(() => {
                    const modalElement = document.getElementById('addPlantModal');
                    modalElement.classList.add('show');
                    modalElement.style.display = 'block';
                    modalElement.setAttribute('aria-modal', 'true');
                    modalElement.removeAttribute('aria-hidden');
                }, 100);
            }

            // Fungsi untuk edit tanaman
            function editPlant(id, jenis, tipe, jumlah, tahun) {
                document.getElementById('plantId').value = id;
                document.getElementById('formMethod').value = 'PUT';
                document.getElementById('jenis_tanaman').value = jenis;
                document.getElementById('tipe').value = tipe;
                document.getElementById('jumlah_pohon').value = jumlah;
                document.getElementById('tahun_tanam').value = tahun;

                document.getElementById('addPlantModalLabel').innerHTML = '<i class="fas fa-edit me-2"></i>Edit Data Tanaman';
                document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save me-2"></i>Perbarui';

                // Clear validation errors
                clearValidationErrors();

                // Show modal with explicit configuration
                const modal = new bootstrap.Modal(document.getElementById('addPlantModal'), {
                    backdrop: false,
                    keyboard: true,
                    focus: true
                });
                modal.show();

                // Ensure modal stays visible
                setTimeout(() => {
                    const modalElement = document.getElementById('addPlantModal');
                    modalElement.classList.add('show');
                    modalElement.style.display = 'block';
                    modalElement.setAttribute('aria-modal', 'true');
                    modalElement.removeAttribute('aria-hidden');
                }, 100);
            }

            // Fungsi untuk hapus tanaman
            function deletePlant(id, jenis) {
                if (confirm(`Apakah Anda yakin ingin menghapus data tanaman "${jenis}"?`)) {
                    fetch(`/kthr/plants/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            } else {
                                alert(data.message || 'Terjadi kesalahan saat menghapus data tanaman.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat menghapus data tanaman.');
                        });
                }
            }

            // Fungsi untuk clear validation errors
            function clearValidationErrors() {
                document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
            }

            // Handle form submission
            document.getElementById('plantForm').addEventListener('submit', function (e) {
                e.preventDefault();

                const formData = new FormData(this);
                const plantId = document.getElementById('plantId').value;
                const method = document.getElementById('formMethod').value;

                let url = '/kthr/plants';
                if (method === 'PUT' && plantId) {
                    url = `/kthr/plants/${plantId}`;
                }

                // Clear previous validation errors
                clearValidationErrors();

                // Disable submit button
                const submitBtn = document.getElementById('submitBtn');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Close modal and reload page
                            bootstrap.Modal.getInstance(document.getElementById('addPlantModal')).hide();
                            location.reload();
                        } else {
                            // Show validation errors
                            if (data.errors) {
                                Object.keys(data.errors).forEach(field => {
                                    const input = document.getElementById(field);
                                    if (input) {
                                        input.classList.add('is-invalid');
                                        const feedback = input.parentNode.querySelector('.invalid-feedback');
                                        if (feedback) {
                                            feedback.textContent = data.errors[field][0];
                                        }
                                    }
                                });
                            }
                            alert(data.message || 'Terjadi kesalahan saat menyimpan data tanaman.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menyimpan data tanaman.');
                    })
                    .finally(() => {
                        // Re-enable submit button
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    });
            });

            // Event listeners for edit and delete buttons
            document.addEventListener('DOMContentLoaded', function () {
                // Smart modal backdrop cleanup - only when modal is not active
                function destroyAllModalBackdrops() {
                    const modal = document.getElementById('addPlantModal');
                    const isModalActive = modal && (modal.classList.contains('show') || modal.style.display === 'block');

                    // Only cleanup if modal is not currently active
                    if (!isModalActive) {
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

                        // Ensure modal is properly hidden
                        if (modal) {
                            modal.classList.remove('show');
                            modal.style.display = 'none';
                            modal.setAttribute('aria-hidden', 'true');
                            modal.removeAttribute('aria-modal');
                        }
                    }
                }

                // Initial cleanup
                destroyAllModalBackdrops();

                // Set up MutationObserver to watch for modal backdrops (only remove when modal is not active)
                const observer = new MutationObserver(function (mutations) {
                    mutations.forEach(function (mutation) {
                        mutation.addedNodes.forEach(function (node) {
                            if (node.nodeType === 1 && node.classList && node.classList.contains('modal-backdrop')) {
                                const modal = document.getElementById('addPlantModal');
                                const isModalActive = modal && (modal.classList.contains('show') || modal.style.display === 'block');

                                if (!isModalActive) {
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

                // Periodic cleanup every 2 seconds (reduced frequency)
                setInterval(destroyAllModalBackdrops, 2000);

                // Override Bootstrap Modal to prevent backdrop creation
                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    const originalShow = bootstrap.Modal.prototype.show;
                    bootstrap.Modal.prototype.show = function () {
                        this._config.backdrop = false;
                        this._config.keyboard = true;
                        return originalShow.call(this);
                    };

                    const originalToggle = bootstrap.Modal.prototype.toggle;
                    bootstrap.Modal.prototype.toggle = function () {
                        this._config.backdrop = false;
                        this._config.keyboard = true;
                        return originalToggle.call(this);
                    };
                }

                // Edit plant buttons
                document.querySelectorAll('.btn-edit-plant').forEach(button => {
                    button.addEventListener('click', function () {
                        const id = this.dataset.id;
                        const jenis = this.dataset.jenis;
                        const tipe = this.dataset.tipe;
                        const jumlah = this.dataset.jumlah;
                        const tahun = this.dataset.tahun;

                        editPlant(id, jenis, tipe, jumlah, tahun);
                    });
                });

                // Delete plant buttons
                document.querySelectorAll('.btn-delete-plant').forEach(button => {
                    button.addEventListener('click', function () {
                        const id = this.dataset.id;
                        const jenis = this.dataset.jenis;

                        deletePlant(id, jenis);
                    });
                });
            });

            // Reset form when modal is shown for adding new plant
            document.getElementById('addPlantModal').addEventListener('show.bs.modal', function (e) {
                // Only reset if triggered by button click, not by programmatic show
                if (e.relatedTarget && !e.relatedTarget.classList.contains('btn-edit-plant')) {
                    // Reset form for add mode
                    document.getElementById('plantForm').reset();
                    document.getElementById('plantId').value = '';
                    document.getElementById('formMethod').value = 'POST';
                    document.getElementById('addPlantModalLabel').innerHTML = '<i class="fas fa-seedling me-2"></i>Tambah Data Tanaman';
                    document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save me-2"></i>Simpan';
                    clearValidationErrors();
                }
            });

            // Debug function to check for blocking elements
            window.debugModalInteraction = function () {
                console.log('=== Modal Debug Info ===');
                const modal = document.getElementById('addPlantModal');
                console.log('Modal element:', modal);
                console.log('Modal classes:', modal?.className);
                console.log('Modal style:', modal?.style.cssText);
                console.log('Modal z-index:', window.getComputedStyle(modal)?.zIndex);

                const backdrop = document.querySelector('.modal-backdrop');
                console.log('Backdrop element:', backdrop);
                console.log('Backdrop classes:', backdrop?.className);

                const body = document.body;
                console.log('Body classes:', body.className);
                console.log('Body overflow:', window.getComputedStyle(body).overflow);

                // Check for elements with high z-index
                const allElements = document.querySelectorAll('*');
                const highZIndexElements = [];
                allElements.forEach(el => {
                    const zIndex = window.getComputedStyle(el).zIndex;
                    if (zIndex !== 'auto' && parseInt(zIndex) > 1000) {
                        highZIndexElements.push({ element: el, zIndex: zIndex });
                    }
                });
                console.log('High z-index elements:', highZIndexElements);
            };

            // Add click event to modal to ensure it's interactive
            document.getElementById('addPlantModal').addEventListener('click', function (e) {
                console.log('Modal clicked:', e.target);
                // Prevent modal from closing when clicking inside modal content
                if (e.target.closest('.modal-content')) {
                    e.stopPropagation();
                }
            });

            // Ensure all form elements are clickable
            document.querySelectorAll('#addPlantModal input, #addPlantModal select, #addPlantModal button').forEach(element => {
                element.addEventListener('click', function (e) {
                    console.log('Form element clicked:', this.id || this.name || this.tagName);
                    e.stopPropagation();
                });
            });

            // File validation
            document.getElementById('gambar_tegakan').addEventListener('change', function () {
                const file = this.files[0];
                if (file) {
                    // Check file type
                    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                    if (!allowedTypes.includes(file.type)) {
                        this.classList.add('is-invalid');
                        this.parentNode.querySelector('.invalid-feedback').textContent = 'Format file tidak didukung. Gunakan JPG, JPEG, PNG, GIF, atau WEBP.';
                        this.value = '';
                        return;
                    }

                    // Check file size (5MB = 5 * 1024 * 1024 bytes)
                    if (file.size > 5 * 1024 * 1024) {
                        this.classList.add('is-invalid');
                        this.parentNode.querySelector('.invalid-feedback').textContent = 'Ukuran file terlalu besar. Maksimal 5MB.';
                        this.value = '';
                        return;
                    }

                    // Clear validation if file is valid
                    this.classList.remove('is-invalid');
                    this.parentNode.querySelector('.invalid-feedback').textContent = '';
                }
            });
        </script>
    @endpush
@endsection