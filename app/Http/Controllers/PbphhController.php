<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\PbphhProfile;
use App\Models\PbphhMaterialNeed;
use App\Models\PbphhPartnership;
use App\Models\Kthr;
use App\Models\PermintaanKerjasama;
use App\Models\Pertemuan;

class PbphhController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $pbphh = $user->pbphhProfile->load('materialNeeds');

        // Statistik untuk dashboard
        $stats = [
            'total_requests' => PermintaanKerjasama::where('pbphh_id', $pbphh->pbphh_id)->count(),
            'pending_requests' => PermintaanKerjasama::where('pbphh_id', $pbphh->pbphh_id)
                ->where('status', 'Terkirim')->count(),
            'active_partnerships' => PermintaanKerjasama::where('pbphh_id', $pbphh->pbphh_id)
                ->whereIn('status', ['Disetujui', 'Dijadwalkan'])->count(),
            'completed_partnerships' => PermintaanKerjasama::where('pbphh_id', $pbphh->pbphh_id)
                ->where('status', 'Selesai')->count(),
            
            // Statistik kemitraan industri (PBPHH-to-PBPHH)
            'industry_partnerships_sent' => PbphhPartnership::sentBy($pbphh->pbphh_id)->count(),
            'industry_partnerships_received' => PbphhPartnership::receivedBy($pbphh->pbphh_id)->count(),
            'industry_partnerships_active' => PbphhPartnership::where(function ($q) use ($pbphh) {
                    $q->where('requester_pbphh_id', $pbphh->pbphh_id)
                      ->orWhere('partner_pbphh_id', $pbphh->pbphh_id);
                })
                ->active()
                ->count(),
            'industry_partnerships_pending' => PbphhPartnership::receivedBy($pbphh->pbphh_id)
                ->where('status', 'Terkirim')
                ->count()
        ];

        // KTHR yang tersedia untuk kemitraan
        $availableKthr = Kthr::whereHas('user', function ($query) {
            $query->where('approval_status', 'Approved');
        })
            ->where('is_siap_mitra', true)
            ->with(['plantSpecies', 'user'])
            ->limit(6)
            ->get();

        // Permintaan terbaru
        $recentRequests = PermintaanKerjasama::where('pbphh_id', $pbphh->pbphh_id)
            ->with(['kthr.user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Pertemuan mendatang
        $upcomingMeetings = Pertemuan::whereHas('permintaanKerjasama', function ($query) use ($pbphh) {
            $query->where('pbphh_id', $pbphh->pbphh_id);
        })
            ->where('status', 'Dijadwalkan')
            ->where('scheduled_time', '>', now())
            ->with(['permintaanKerjasama.kthr.user', 'scheduledBy'])
            ->orderBy('scheduled_time')
            ->limit(3)
            ->get();

        return view('pbphh.dashboard', compact('pbphh', 'stats', 'availableKthr', 'recentRequests', 'upcomingMeetings'));
    }

    public function completeProfile()
    {
        $user = Auth::user();
        $pbphh = $user->pbphhProfile;

        return view('pbphh.complete-profile', compact('pbphh'));
    }

    public function storeProfile(Request $request)
    {
        $request->validate([
            'penanggung_jawab' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'alamat_perusahaan' => 'required|string',
            'coordinate_lat' => 'required|numeric|between:-90,90',
            'coordinate_lng' => 'required|numeric|between:-180,180',
            'kapasitas_izin_produksi_m3' => 'required|numeric|min:0',
            'jenis_produk_utama' => 'required|string|max:255',
            'tahun_berdiri' => 'required|integer|min:1900|max:' . date('Y'),
            'jumlah_karyawan' => 'required|integer|min:1',
            'website' => 'nullable|url',
            'deskripsi_perusahaan' => 'nullable|string',

            // Material needs data
            'materials.*.jenis_kayu' => 'required|string|max:100',
            'materials.*.tipe' => 'required|in:Kayu,Bukan Kayu',
            'materials.*.kebutuhan_bulanan_m3' => 'required|numeric|min:0.01',
            'materials.*.spesifikasi_tambahan' => 'nullable|string'
        ]);

        $user = Auth::user();
        $pbphh = $user->pbphhProfile;

        // Update PBPHH profile
        $pbphh->update($request->only([
            'penanggung_jawab',
            'phone',
            'alamat_perusahaan',
            'coordinate_lat',
            'coordinate_lng',
            'kapasitas_izin_produksi_m3',
            'jenis_produk_utama',
            'tahun_berdiri',
            'jumlah_karyawan',
            'website',
            'deskripsi_perusahaan'
        ]));

        // Save material needs
        if ($request->has('materials')) {
            foreach ($request->materials as $materialData) {
                PbphhMaterialNeed::create([
                    'pbphh_id' => $pbphh->pbphh_id,
                    'jenis_kayu' => $materialData['jenis_kayu'],
                    'tipe' => $materialData['tipe'],
                    'kebutuhan_bulanan_m3' => $materialData['kebutuhan_bulanan_m3'],
                    'spesifikasi_tambahan' => $materialData['spesifikasi_tambahan'] ?? null
                ]);
            }
        }

        return redirect()->route('pbphh.dashboard')->with('success', 'Profil perusahaan berhasil dilengkapi!');
    }

    public function profile()
    {
        $user = Auth::user();
        $pbphh = $user->pbphhProfile->load('materialNeeds');

        return view('pbphh.profile', compact('pbphh'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'penanggung_jawab' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'alamat_perusahaan' => 'required|string',
            'coordinate_lat' => 'required|numeric|between:-90,90',
            'coordinate_lng' => 'required|numeric|between:-180,180',
            'kapasitas_izin_produksi_m3' => 'required|numeric|min:0',
            'rencana_produksi_tahunan_m3' => 'nullable|numeric|min:0',
            'jenis_produk_utama' => 'required|string|max:255',
            'tahun_berdiri' => 'required|integer|min:1900|max:' . date('Y'),
            'jumlah_karyawan' => 'required|integer|min:1',
            'website' => 'nullable|url',
            'deskripsi_perusahaan' => 'nullable|string'
        ]);

        $user = Auth::user();
        $pbphh = $user->pbphhProfile;

        $pbphh->update($request->all());

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function exploreKthr(Request $request)
    {
        $query = Kthr::whereHas('user', function ($q) {
            $q->where('approval_status', 'Approved');
        })
            ->with(['plantSpecies', 'user']);

        // Filter berdasarkan status kesiapan
        if ($request->filled('status')) {
            if ($request->status === 'siap_mitra') {
                $query->where('is_siap_mitra', true);
            } elseif ($request->status === 'siap_tebang') {
                $query->where('is_siap_tebang', true);
            }
        }

        // Filter berdasarkan jenis tanaman
        if ($request->filled('jenis_tanaman')) {
            $query->whereHas('plantSpecies', function ($q) use ($request) {
                $q->where('jenis_tanaman', 'like', '%' . $request->jenis_tanaman . '%');
            });
        }

        // Filter berdasarkan tipe tanaman
        if ($request->filled('tipe_tanaman')) {
            $query->whereHas('plantSpecies', function ($q) use ($request) {
                $q->where('tipe', $request->tipe_tanaman);
            });
        }

        // Filter berdasarkan lokasi (radius)
        if ($request->filled('lat') && $request->filled('lng') && $request->filled('radius')) {
            $lat = $request->lat;
            $lng = $request->lng;
            $radius = $request->radius; // dalam km

            $query->whereRaw("
                (6371 * acos(cos(radians(?)) * cos(radians(coordinate_lat)) * 
                cos(radians(coordinate_lng) - radians(?)) + sin(radians(?)) * 
                sin(radians(coordinate_lat)))) <= ?
            ", [$lat, $lng, $lat, $radius]);
        }

        // Search berdasarkan nama KTHR
        if ($request->filled('search')) {
            $query->where('kthr_name', 'like', '%' . $request->search . '%');
        }

        $kthrs = $query->paginate(12);

        // Data untuk map
        $mapData = Kthr::whereHas('user', function ($q) {
            $q->where('approval_status', 'Approved');
        })
            ->where('is_siap_mitra', true)
            ->select('kthr_id', 'kthr_name', 'coordinate_lat', 'coordinate_lng', 'luas_areal_ha')
            ->get();

        return view('pbphh.explore', compact('kthrs', 'mapData'));
    }

    public function requestPartnership(Request $request)
    {
        $request->validate([
            'kthr_id' => 'required|exists:kthrs,kthr_id',
            'wood_type' => 'required|string|max:100',
            'monthly_volume_m3' => 'required|numeric|min:0.01',
            'additional_notes' => 'nullable|string'
        ]);

        $user = Auth::user();
        $pbphh = $user->pbphhProfile;

        // Check if request already exists
        $existingRequest = PermintaanKerjasama::where('pbphh_id', $pbphh->pbphh_id)
            ->where('kthr_id', $request->kthr_id)
            ->whereIn('status', ['Terkirim', 'Disetujui', 'Dijadwalkan'])
            ->first();

        if ($existingRequest) {
            return redirect()->back()->with('error', 'Anda sudah memiliki permintaan aktif dengan KTHR ini.');
        }

        PermintaanKerjasama::create([
            'pbphh_id' => $pbphh->pbphh_id,
            'kthr_id' => $request->kthr_id,
            'wood_type' => $request->wood_type,
            'monthly_volume_m3' => $request->monthly_volume_m3,
            'additional_notes' => $request->additional_notes,
            'status' => 'Terkirim'
        ]);

        return redirect()->back()->with('success', 'Permintaan kemitraan berhasil dikirim!');
    }

    public function partnerships()
    {
        $user = Auth::user();
        $pbphh = $user->pbphhProfile;

        $partnerships = PermintaanKerjasama::where('pbphh_id', $pbphh->pbphh_id)
            ->whereIn('status', ['Disetujui', 'Menunggu Jadwal', 'Dijadwalkan', 'Menunggu Tanda Tangan', 'Selesai'])
            ->with(['kthr.user', 'pertemuan.kesepakatan'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pbphh.partnerships', compact('partnerships'));
    }

    public function manageMaterialNeeds()
    {
        $user = Auth::user();
        $pbphh = $user->pbphhProfile;
        $materialNeeds = $pbphh->materialNeeds;

        return view('pbphh.material-needs', compact('pbphh', 'materialNeeds'));
    }

    public function storeMaterialNeed(Request $request)
    {
        $request->validate([
            'jenis_kayu' => 'required|string|max:100',
            'tipe' => 'required|in:Kayu,Bukan Kayu',
            'kebutuhan_bulanan_m3' => 'required|numeric|min:0.01',
            'spesifikasi_tambahan' => 'nullable|string'
        ]);

        $user = Auth::user();
        $pbphh = $user->pbphhProfile;

        PbphhMaterialNeed::create([
            'pbphh_id' => $pbphh->pbphh_id,
            'jenis_kayu' => $request->jenis_kayu,
            'tipe' => $request->tipe,
            'kebutuhan_bulanan_m3' => $request->kebutuhan_bulanan_m3,
            'spesifikasi_tambahan' => $request->spesifikasi_tambahan
        ]);

        return redirect()->back()->with('success', 'Kebutuhan bahan baku berhasil ditambahkan!');
    }

    public function updateMaterialNeed(Request $request, $id)
    {
        $request->validate([
            'jenis_kayu' => 'required|string|max:100',
            'tipe' => 'required|in:Kayu,Bukan Kayu',
            'kebutuhan_bulanan_m3' => 'required|numeric|min:0.01',
            'spesifikasi_tambahan' => 'nullable|string'
        ]);

        $user = Auth::user();
        $pbphh = $user->pbphhProfile;

        $materialNeed = PbphhMaterialNeed::where('need_id', $id)
            ->where('pbphh_id', $pbphh->pbphh_id)
            ->firstOrFail();

        $materialNeed->update($request->all());

        return redirect()->back()->with('success', 'Kebutuhan bahan baku berhasil diperbarui!');
    }

    public function deleteMaterialNeed($id)
    {
        $user = Auth::user();
        $pbphh = $user->pbphhProfile;

        $materialNeed = PbphhMaterialNeed::where('need_id', $id)
            ->where('pbphh_id', $pbphh->pbphh_id)
            ->firstOrFail();

        $materialNeed->delete();

        return redirect()->back()->with('success', 'Kebutuhan bahan baku berhasil dihapus!');
    }

    public function signAgreement(Request $request, $id)
    {
        $partnership = PermintaanKerjasama::where('request_id', $id)
            ->where('pbphh_id', Auth::user()->pbphhProfile->pbphh_id)
            ->with('pertemuan.kesepakatan')
            ->firstOrFail();

        if (!$partnership->pertemuan || !$partnership->pertemuan->kesepakatan) {
            return redirect()->back()->with('error', 'Kesepakatan belum tersedia!');
        }

        $kesepakatan = $partnership->pertemuan->kesepakatan;
        $kesepakatan->update(['signed_by_pbphh_at' => now()]);

        // Check if both parties have signed
        if ($kesepakatan->signed_by_kthr_at && $kesepakatan->signed_by_pbphh_at) {
            $partnership->update(['status' => 'Selesai']);
        }

        return redirect()->back()->with('success', 'Kesepakatan berhasil ditandatangani!');
    }

    public function getKthrDetail($id)
    {
        $kthr = Kthr::with(['plantSpecies', 'user', 'region'])
            ->whereHas('user', function ($q) {
                $q->where('approval_status', 'Approved');
            })
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'kthr_id' => $kthr->kthr_id,
                'kthr_name' => $kthr->kthr_name,
                'nama_pendamping' => $kthr->nama_pendamping,
                'phone' => $kthr->phone,
                'alamat_sekretariat' => $kthr->alamat_sekretariat,
                'luas_areal_ha' => $kthr->luas_areal_ha,
                'jumlah_anggota' => $kthr->jumlah_anggota,
                'jumlah_pertemuan_tahunan' => $kthr->jumlah_pertemuan_tahunan,
                'is_siap_mitra' => $kthr->is_siap_mitra,
                'is_siap_tebang' => $kthr->is_siap_tebang,
                'coordinate_lat' => $kthr->coordinate_lat,
                'coordinate_lng' => $kthr->coordinate_lng,
                'region_name' => $kthr->region ? $kthr->region->name : null,
                'user_email' => $kthr->user ? $kthr->user->email : null,
                'plant_species' => $kthr->plantSpecies->map(function ($plant) {
                    return [
                        'jenis_tanaman' => $plant->jenis_tanaman,
                        'tipe' => $plant->tipe,
                        'jumlah_pohon' => $plant->jumlah_pohon,
                        'tahun_tanam' => $plant->tahun_tanam,
                        'gambar_tegakan_path' => $plant->gambar_tegakan_path
                    ];
                })
            ]
        ]);
    }

    // ========================================
    // PBPHH-to-PBPHH Partnership Methods
    // ========================================

    /**
     * Explore PBPHH lain untuk kolaborasi industri
     */
    public function exploreIndustryPartners(Request $request)
    {
        $user = Auth::user();
        $currentPbphh = $user->pbphhProfile;

        $query = PbphhProfile::with(['user.region', 'materialNeeds'])
            ->whereHas('user', function ($q) {
                $q->where('approval_status', 'Approved');
            })
            ->where('pbphh_id', '!=', $currentPbphh->pbphh_id); // Exclude current PBPHH

        // Filter berdasarkan region
        if ($request->filled('region_id')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('region_id', $request->region_id);
            });
        }

        // Filter berdasarkan jenis produk
        if ($request->filled('jenis_produk')) {
            $query->where('jenis_produk_utama', 'like', '%' . $request->jenis_produk . '%');
        }

        // Filter berdasarkan kapasitas produksi
        if ($request->filled('min_capacity')) {
            $query->where('kapasitas_izin_produksi_m3', '>=', $request->min_capacity);
        }

        // Search by company name
        if ($request->filled('search')) {
            $query->where('company_name', 'like', '%' . $request->search . '%');
        }

        $partners = $query->paginate(12);

        return view('pbphh.industry-partners', compact('partners', 'currentPbphh'));
    }

    /**
     * Get detail PBPHH partner
     */
    public function getIndustryPartnerDetail($id)
    {
        $user = Auth::user();
        $currentPbphh = $user->pbphhProfile;

        $partner = PbphhProfile::with(['user.region', 'materialNeeds'])
            ->whereHas('user', function ($q) {
                $q->where('approval_status', 'Approved');
            })
            ->where('pbphh_id', '!=', $currentPbphh->pbphh_id)
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'pbphh_id' => $partner->pbphh_id,
                'company_name' => $partner->company_name,
                'penanggung_jawab' => $partner->penanggung_jawab,
                'phone' => $partner->phone,
                'alamat_perusahaan' => $partner->alamat_perusahaan,
                'coordinate_lat' => $partner->coordinate_lat,
                'coordinate_lng' => $partner->coordinate_lng,
                'kapasitas_izin_produksi_m3' => $partner->kapasitas_izin_produksi_m3,
                'jenis_produk_utama' => $partner->jenis_produk_utama,
                'tahun_berdiri' => $partner->tahun_berdiri,
                'jumlah_karyawan' => $partner->jumlah_karyawan,
                'website' => $partner->website,
                'deskripsi_perusahaan' => $partner->deskripsi_perusahaan,
                'region_name' => $partner->user && $partner->user->region ? $partner->user->region->name : null,
                'material_needs' => $partner->materialNeeds->map(function ($need) {
                    return [
                        'jenis_kayu' => $need->jenis_kayu,
                        'tipe' => $need->tipe,
                        'kebutuhan_bulanan_m3' => $need->kebutuhan_bulanan_m3,
                        'spesifikasi_tambahan' => $need->spesifikasi_tambahan
                    ];
                })
            ]
        ]);
    }

    /**
     * Request partnership dengan PBPHH lain
     */
    public function requestIndustryPartnership(Request $request)
    {
        $request->validate([
            'partner_pbphh_id' => 'required|exists:pbphh_profiles,pbphh_id',
            'partnership_type' => 'required|in:Pasokan Material,Kapasitas Produksi,Joint Venture,Distribusi,Lainnya',
            'description' => 'required|string|max:1000',
            'material_type' => 'nullable|string|max:100',
            'volume_needed_m3' => 'nullable|numeric|min:0',
            'duration_months' => 'nullable|string|max:50'
        ]);

        $user = Auth::user();
        $pbphh = $user->pbphhProfile;

        // Check if partnership request already exists
        $existingPartnership = \App\Models\PbphhPartnership::where('requester_pbphh_id', $pbphh->pbphh_id)
            ->where('partner_pbphh_id', $request->partner_pbphh_id)
            ->whereIn('status', ['Terkirim', 'Disetujui', 'Dalam Negosiasi', 'Aktif'])
            ->first();

        if ($existingPartnership) {
            return redirect()->back()->with('error', 'Anda sudah memiliki permintaan kemitraan aktif dengan PBPHH ini.');
        }

        // Validate not requesting partnership with self
        if ($pbphh->pbphh_id == $request->partner_pbphh_id) {
            return redirect()->back()->with('error', 'Tidak dapat mengajukan kemitraan dengan perusahaan sendiri.');
        }

        \App\Models\PbphhPartnership::create([
            'requester_pbphh_id' => $pbphh->pbphh_id,
            'partner_pbphh_id' => $request->partner_pbphh_id,
            'partnership_type' => $request->partnership_type,
            'description' => $request->description,
            'material_type' => $request->material_type,
            'volume_needed_m3' => $request->volume_needed_m3,
            'duration_months' => $request->duration_months,
            'status' => 'Terkirim'
        ]);

        return redirect()->back()->with('success', 'Permintaan kemitraan industri berhasil dikirim!');
    }

    /**
     * Lihat daftar kemitraan industri (yang diajukan & diterima)
     */
    public function industryPartnerships()
    {
        $user = Auth::user();
        $pbphh = $user->pbphhProfile;

        // Partnerships yang diajukan oleh PBPHH ini
        $sentPartnerships = \App\Models\PbphhPartnership::sentBy($pbphh->pbphh_id)
            ->with(['partner.user.region'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Partnerships yang diterima oleh PBPHH ini
        $receivedPartnerships = \App\Models\PbphhPartnership::receivedBy($pbphh->pbphh_id)
            ->with(['requester.user.region'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pbphh.industry-partnerships', compact('sentPartnerships', 'receivedPartnerships'));
    }

    /**
     * Lihat permintaan kemitraan yang masuk (inbox)
     */
    public function industryPartnershipRequests()
    {
        $user = Auth::user();
        $pbphh = $user->pbphhProfile;

        $requests = \App\Models\PbphhPartnership::receivedBy($pbphh->pbphh_id)
            ->with(['requester.user.region', 'requester.materialNeeds'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pbphh.industry-partnership-requests', compact('requests'));
    }

    /**
     * Respond to industry partnership request (approve/reject)
     */
    public function respondToIndustryPartnership(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'rejection_reason' => 'required_if:action,reject|string|max:500'
        ]);

        $user = Auth::user();
        $pbphh = $user->pbphhProfile;

        $partnership = \App\Models\PbphhPartnership::where('partnership_id', $id)
            ->where('partner_pbphh_id', $pbphh->pbphh_id)
            ->where('status', 'Terkirim')
            ->firstOrFail();

        if ($request->action === 'approve') {
            $partnership->update([
                'status' => 'Disetujui',
                'approved_at' => now()
            ]);
            $message = 'Permintaan kemitraan industri berhasil disetujui!';
        } else {
            $partnership->update([
                'status' => 'Ditolak',
                'rejection_reason' => $request->rejection_reason
            ]);
            $message = 'Permintaan kemitraan industri berhasil ditolak!';
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Cancel partnership request
     */
    public function cancelIndustryPartnership($id)
    {
        $user = Auth::user();
        $pbphh = $user->pbphhProfile;

        $partnership = \App\Models\PbphhPartnership::where('partnership_id', $id)
            ->where('requester_pbphh_id', $pbphh->pbphh_id)
            ->firstOrFail();

        if (!$partnership->isCancellable()) {
            return redirect()->back()->with('error', 'Kemitraan dengan status "' . $partnership->status . '" tidak dapat dibatalkan.');
        }

        $partnership->update([
            'status' => 'Dibatalkan',
            'rejection_reason' => 'Dibatalkan oleh requester'
        ]);

        return redirect()->back()->with('success', 'Permintaan kemitraan berhasil dibatalkan!');
    }

    /**
     * Negotiate partnership (update negotiation notes)
     */
    public function negotiateIndustryPartnership(Request $request, $id)
    {
        $request->validate([
            'negotiation_notes' => 'required|string|max:2000',
            'volume_needed_m3' => 'nullable|numeric|min:0',
            'duration_months' => 'nullable|string|max:50'
        ]);

        $user = Auth::user();
        $pbphh = $user->pbphhProfile;

        $partnership = \App\Models\PbphhPartnership::where('partnership_id', $id)
            ->where(function ($q) use ($pbphh) {
                $q->where('requester_pbphh_id', $pbphh->pbphh_id)
                  ->orWhere('partner_pbphh_id', $pbphh->pbphh_id);
            })
            ->firstOrFail();

        if (!$partnership->isNegotiable()) {
            return redirect()->back()->with('error', 'Kemitraan ini tidak dapat dinegosiasikan lagi.');
        }

        $updateData = [
            'status' => 'Dalam Negosiasi',
            'negotiation_notes' => $request->negotiation_notes
        ];

        if ($request->filled('volume_needed_m3')) {
            $updateData['volume_needed_m3'] = $request->volume_needed_m3;
        }

        if ($request->filled('duration_months')) {
            $updateData['duration_months'] = $request->duration_months;
        }

        $partnership->update($updateData);

        return redirect()->back()->with('success', 'Catatan negosiasi berhasil diperbarui!');
    }

    /**
     * Finalize partnership (move to active)
     */
    public function finalizeIndustryPartnership($id)
    {
        $user = Auth::user();
        $pbphh = $user->pbphhProfile;

        $partnership = \App\Models\PbphhPartnership::where('partnership_id', $id)
            ->where(function ($q) use ($pbphh) {
                $q->where('requester_pbphh_id', $pbphh->pbphh_id)
                  ->orWhere('partner_pbphh_id', $pbphh->pbphh_id);
            })
            ->whereIn('status', ['Disetujui', 'Dalam Negosiasi'])
            ->firstOrFail();

        $partnership->update([
            'status' => 'Aktif',
            'started_at' => now()
        ]);

        return redirect()->back()->with('success', 'Kemitraan industri berhasil diaktifkan!');
    }
}
