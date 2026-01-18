@extends('layouts.pbphh')

@section('title', 'Profil Perusahaan - JASA KAYA')

@section('dashboard-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-primary mb-1">Profil Perusahaan</h2>
        <p class="text-muted mb-0">Kelola informasi profil perusahaan Anda</p>
    </div>
</div>

<div class="row">
    <!-- Company Information -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-building me-2"></i>Informasi Perusahaan
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('pbphh.profile') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="company_name" class="form-label">Nama Perusahaan</label>
                                <input type="text" class="form-control" id="company_name" 
                                       value="{{ $pbphh->company_name }}" readonly>
                                <small class="text-muted">Nama perusahaan tidak dapat diubah</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="penanggung_jawab" class="form-label">Penanggung Jawab <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="penanggung_jawab" name="penanggung_jawab" 
                                       value="{{ $pbphh->penanggung_jawab }}" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="{{ $pbphh->phone }}" placeholder="+62 xxx xxxx xxxx">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Placeholder for future field -->
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="alamat_perusahaan" class="form-label">Alamat Perusahaan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="alamat_perusahaan" name="alamat_perusahaan" 
                                  rows="3" required>{{ $pbphh->alamat_perusahaan }}</textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="coordinate_lat" class="form-label">Latitude <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="coordinate_lat" name="coordinate_lat" 
                                       value="{{ $pbphh->coordinate_lat }}" step="0.00000001" min="-90" max="90" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="coordinate_lng" class="form-label">Longitude <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="coordinate_lng" name="coordinate_lng" 
                                       value="{{ $pbphh->coordinate_lng }}" step="0.00000001" min="-180" max="180" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jenis_produk_utama" class="form-label">Jenis Produk Utama <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="jenis_produk_utama" name="jenis_produk_utama" 
                                       value="{{ $pbphh->jenis_produk_utama }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kapasitas_izin_produksi_m3" class="form-label">Kapasitas Izin Produksi (m³/tahun) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="kapasitas_izin_produksi_m3" name="kapasitas_izin_produksi_m3" 
                                       value="{{ $pbphh->kapasitas_izin_produksi_m3 }}" step="0.01" min="0" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="rencana_produksi_tahunan_m3" class="form-label">Rencana Produksi Tahunan (m³/tahun)</label>
                                <input type="number" class="form-control" id="rencana_produksi_tahunan_m3" name="rencana_produksi_tahunan_m3" 
                                       value="{{ $pbphh->rencana_produksi_tahunan_m3 }}" step="0.01" min="0" placeholder="Target produksi per tahun">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Placeholder for future field -->
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="tahun_berdiri" class="form-label">Tahun Berdiri <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="tahun_berdiri" name="tahun_berdiri" 
                                       value="{{ $pbphh->tahun_berdiri }}" min="1900" max="{{ date('Y') }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="jumlah_karyawan" class="form-label">Jumlah Karyawan <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="jumlah_karyawan" name="jumlah_karyawan" 
                                       value="{{ $pbphh->jumlah_karyawan }}" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="website" class="form-label">Website Perusahaan</label>
                                <input type="url" class="form-control" id="website" name="website" 
                                       value="{{ $pbphh->website }}" placeholder="https://www.example.com">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="deskripsi_perusahaan" class="form-label">Deskripsi Perusahaan</label>
                        <textarea class="form-control" id="deskripsi_perusahaan" name="deskripsi_perusahaan" 
                                  rows="4">{{ $pbphh->deskripsi_perusahaan }}</textarea>
                    </div>
                    
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Company Summary -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Ringkasan Perusahaan
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <i class="fas fa-building fa-3x text-primary mb-2"></i>
                    <h5 class="fw-bold">{{ $pbphh->company_name }}</h5>
                    <p class="text-muted">{{ $pbphh->jenis_produk_utama }}</p>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Tahun Berdiri:</span>
                        <strong>{{ $pbphh->tahun_berdiri }}</strong>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Jumlah Karyawan:</span>
                        <strong>{{ number_format($pbphh->jumlah_karyawan) }} orang</strong>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Kapasitas Produksi:</span>
                        <strong>{{ number_format($pbphh->kapasitas_izin_produksi_m3, 2) }} m³/tahun</strong>
                    </div>
                </div>
                
                @if($pbphh->website)
                <div class="mb-3">
                    <a href="{{ $pbphh->website }}" target="_blank" class="btn btn-outline-primary btn-sm w-100">
                        <i class="fas fa-external-link-alt me-2"></i>Kunjungi Website
                    </a>
                </div>
                @endif
                
                <div class="mb-3">
                    <small class="text-muted">Penanggung Jawab:</small>
                    <div class="fw-bold">{{ $pbphh->penanggung_jawab }}</div>
                </div>
            </div>
        </div>
        
        <!-- Documents -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-file-alt me-2"></i>Dokumen Perusahaan
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>NIB</span>
                        @if($pbphh->nib_url)
                            <a href="{{ $pbphh->nib_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>SK PBPHH</span>
                        @if($pbphh->sk_pbphh_url)
                            <a href="{{ $pbphh->sk_pbphh_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                </div>
                
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Dokumen tidak dapat diubah setelah verifikasi
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Material Needs Section -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-boxes me-2"></i>Kebutuhan Bahan Baku
                </h5>
                <a href="{{ route('pbphh.material-needs') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-edit me-1"></i>Kelola
                </a>
            </div>
            <div class="card-body">
                @if($pbphh->materialNeeds->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Jenis Kayu</th>
                                    <th>Tipe</th>
                                    <th>Kebutuhan Bulanan</th>
                                    <th>Kebutuhan Tahunan</th>
                                    <th>Spesifikasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pbphh->materialNeeds as $need)
                                <tr>
                                    <td>{{ $need->jenis_kayu }}</td>
                                    <td>
                                        <span class="badge bg-{{ $need->tipe === 'Kayu' ? 'success' : 'info' }}">
                                            {{ $need->tipe }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($need->kebutuhan_bulanan_m3, 2) }} m³</td>
                                    <td>{{ number_format($need->kebutuhan_bulanan_m3 * 12, 2) }} m³</td>
                                    <td>{{ $need->spesifikasi_tambahan ?: '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-light">
                                    <th colspan="2">Total</th>
                                    <th>{{ number_format($pbphh->materialNeeds->sum('kebutuhan_bulanan_m3'), 2) }} m³</th>
                                    <th>{{ number_format($pbphh->materialNeeds->sum('kebutuhan_bulanan_m3') * 12, 2) }} m³</th>
                                    <th>-</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-boxes fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada kebutuhan bahan baku yang didefinisikan</p>
                        <a href="{{ route('pbphh.material-needs') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Tambah Kebutuhan
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
