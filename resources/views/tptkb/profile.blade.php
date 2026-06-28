@extends('layouts.tptkb')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tptkb-dashboard.css') }}">
@endpush

@section('title', 'Profil TPTKB - JASA KAYA')

@section('dashboard-content')
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-primary mb-1">Profil TPTKB</h2>
            <p class="text-muted mb-0">Informasi lengkap tentang {{ $tptkb->tptkb_name }}</p>
        </div>
        <div>
            <a href="{{ route('tptkb.profile.complete') }}" class="btn btn-primary">
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
                                    <div class="fw-bold">{{ $tptkb->nama_pendamping_tptkb }}</div>
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
                                    <div class="fw-bold">{{ $tptkb->alamat_tptkb }}</div>
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
                        <div class="fw-bold">{{ $tptkb->coordinate_lat }}, {{ $tptkb->coordinate_lng }}</div>
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
                    <i class="fas fa-seedling me-2"></i>Data Supply
                </h5>
                <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addSupplyModal">
                    <i class="fas fa-plus me-2"></i>Tambah Supply
                </button>
            </div>
        </div>
        <div class="card-body">
            @forelse($tptkb->materialSupplies as $supply)
                <div class="card mb-3 border-start border-success border-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                @if($supply->gambar_supply_path)
                                    @php
                                        // Gunakan Storage facade untuk memeriksa keberadaan file
                                        $imageExists = \Illuminate\Support\Facades\Storage::disk('public')->exists($supply->gambar_supply_path);
                                        // Log untuk debugging
                                        \Illuminate\Support\Facades\Log::info('Image check', [
                                            'path' => $supply->gambar_supply_path,
                                            'exists' => $imageExists
                                        ]);
                                    @endphp
                                    @if($imageExists)
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($supply->gambar_supply_path) }}"
                                            alt="Gambar Supply {{ $supply->supply_kayu }}" class="img-fluid rounded"
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
                                                <small class="text-muted d-block">{{ $supply->gambar_supply_path }}</small>
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
                                        <div class="fw-bold text-success">{{ $supply->supply_kayu }}</div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <small class="text-muted">Tipe</small>
                                        <div class="fw-bold">
                                            <span class="badge bg-info">{{ $supply->tipe }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <small class="text-muted">Jumlah Pohon</small>
                                        <div class="fw-bold">{{ number_format($supply->jumlah) }} Pohon</div>
                                    </div>
                                    
                                    <div class="col-12 mt-2">
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-primary btn-edit-supply"
                                                data-id="{{ $supply->supply_id }}" data-jenis="{{ $supply->supply_kayu }}"
                                                data-tipe="{{ $supply->tipe }}" data-jumlah="{{ $supply->jumlah }}"
                                                
                                                <i class="fas fa-edit me-1"></i>Edit
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete-supply"
                                                data-id="{{ $supply->supply_id }}" data-jenis="{{ $supply->supply_kayu }}">
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
                    <a href="{{ route('tptkb.profile.complete') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Tambah Data Supply
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Modal Tambah/Edit Supply -->
    <div class="modal fade" id="addSupplyModal" tabindex="-1" aria-labelledby="addSupplyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSupplyModalLabel">
                        <i class="fas fa-seedling me-2"></i>Tambah Data Supply
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="supplyForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="supplyId" name="supply_id">
                    <input type="hidden" id="formMethod" name="_method" value="POST">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="supply_kayu" class="form-label">Jenis Supply <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="supply_kayu" name="supply_kayu" required>
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
                                <label for="jumlah" class="form-label">Jumlah <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="jumlah" name="jumlah" min="1"
                                    required>
                                <div class="invalid-feedback"></div>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="gambar_supply" class="form-label">Gambar Tegakan</label>
                                <input type="file" class="form-control" id="gambar_supply" name="gambar_supply"
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
                var lat = "{{ $tptkb->coordinate_lat ?? 'null' }}";
                var lng = "{{ $tptkb->coordinate_lng ?? 'null' }}";

                // Convert string 'null' to JavaScript null
                if (lat === "null" || lng === "null") {
                    alert('Koordinat belum tersedia. Silakan lengkapi data koordinat terlebih dahulu.');
                    return;
                }

                const url = `https://www.google.com/maps?q=${lat},${lng}&z=15`;
                window.open(url, '_blank');
            }

            // Fungsi untuk menambah tanaman baru
            function addSupply() {
                // Reset form
                document.getElementById('supplyForm').reset();
                document.getElementById('supplyId').value = '';
                document.getElementById('formMethod').value = 'POST';
                document.getElementById('addSupplyModalLabel').innerHTML = '<i class="fas fa-seedling me-2"></i>Tambah Data Supply';
                document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save me-2"></i>Simpan';

                // Clear validation errors
                clearValidationErrors();

                // Show modal with explicit configuration
                const modal = new bootstrap.Modal(document.getElementById('addSupplyModal'), {
                    backdrop: false,
                    keyboard: true,
                    focus: true
                });
                modal.show();

                // Ensure modal stays visible
                setTimeout(() => {
                    const modalElement = document.getElementById('addSupplyModal');
                    modalElement.classList.add('show');
                    modalElement.style.display = 'block';
                    modalElement.setAttribute('aria-modal', 'true');
                    modalElement.removeAttribute('aria-hidden');
                }, 100);
            }

            // Fungsi untuk edit tanaman
            function editSupply(id, jenis, tipe, jumlah) {
                document.getElementById('supplyId').value = id;
                document.getElementById('formMethod').value = 'PUT';
                document.getElementById('supply_kayu').value = jenis;
                document.getElementById('tipe').value = tipe;
                document.getElementById('jumlah').value = jumlah;
                

                document.getElementById('addSupplyModalLabel').innerHTML = '<i class="fas fa-edit me-2"></i>Edit Data Supply';
                document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save me-2"></i>Perbarui';

                // Clear validation errors
                clearValidationErrors();

                // Show modal with explicit configuration
                const modal = new bootstrap.Modal(document.getElementById('addSupplyModal'), {
                    backdrop: false,
                    keyboard: true,
                    focus: true
                });
                modal.show();

                // Ensure modal stays visible
                setTimeout(() => {
                    const modalElement = document.getElementById('addSupplyModal');
                    modalElement.classList.add('show');
                    modalElement.style.display = 'block';
                    modalElement.setAttribute('aria-modal', 'true');
                    modalElement.removeAttribute('aria-hidden');
                }, 100);
            }

            // Fungsi untuk hapus tanaman
            function deleteSupply(id, jenis) {
                if (confirm(`Apakah Anda yakin ingin menghapus data supply "${jenis}"?`)) {
                    fetch(`/tptkb/supplies/${id}`, {
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
                                alert(data.message || 'Terjadi kesalahan saat menghapus data supply.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat menghapus data supply.');
                        });
                }
            }

            // Fungsi untuk clear validation errors
            function clearValidationErrors() {
                document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
            }

            // Handle form submission
            document.getElementById('supplyForm').addEventListener('submit', function (e) {
                e.preventDefault();

                const formData = new FormData(this);
                const supplyId = document.getElementById('supplyId').value;
                const method = document.getElementById('formMethod').value;

                let url = '/tptkb/supplies';
                if (method === 'PUT' && supplyId) {
                    url = `/tptkb/supplies/${supplyId}`;
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
                            bootstrap.Modal.getInstance(document.getElementById('addSupplyModal')).hide();
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
                            alert(data.message || 'Terjadi kesalahan saat menyimpan data supply.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menyimpan data supply.');
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
                    const modal = document.getElementById('addSupplyModal');
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
                                const modal = document.getElementById('addSupplyModal');
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

                // Edit supply buttons
                document.querySelectorAll('.btn-edit-supply').forEach(button => {
                    button.addEventListener('click', function () {
                        const id = this.dataset.id;
                        const jenis = this.dataset.jenis;
                        const tipe = this.dataset.tipe;
                        const jumlah = this.dataset.jumlah;

                        editSupply(id, jenis, tipe, jumlah);
                    });
                });

                // Delete supply buttons
                document.querySelectorAll('.btn-delete-supply').forEach(button => {
                    button.addEventListener('click', function () {
                        const id = this.dataset.id;
                        const jenis = this.dataset.jenis;

                        deleteSupply(id, jenis);
                    });
                });
            });

            // Reset form when modal is shown for adding new supply
            document.getElementById('addSupplyModal').addEventListener('show.bs.modal', function (e) {
                // Only reset if triggered by button click, not by programmatic show
                if (e.relatedTarget && !e.relatedTarget.classList.contains('btn-edit-supply')) {
                    // Reset form for add mode
                    document.getElementById('supplyForm').reset();
                    document.getElementById('supplyId').value = '';
                    document.getElementById('formMethod').value = 'POST';
                    document.getElementById('addSupplyModalLabel').innerHTML = '<i class="fas fa-seedling me-2"></i>Tambah Data Supply';
                    document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save me-2"></i>Simpan';
                    clearValidationErrors();
                }
            });

            // Debug function to check for blocking elements
            window.debugModalInteraction = function () {
                console.log('=== Modal Debug Info ===');
                const modal = document.getElementById('addSupplyModal');
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
            document.getElementById('addSupplyModal').addEventListener('click', function (e) {
                console.log('Modal clicked:', e.target);
                // Prevent modal from closing when clicking inside modal content
                if (e.target.closest('.modal-content')) {
                    e.stopPropagation();
                }
            });

            // Ensure all form elements are clickable
            document.querySelectorAll('#addSupplyModal input, #addSupplyModal select, #addSupplyModal button').forEach(element => {
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