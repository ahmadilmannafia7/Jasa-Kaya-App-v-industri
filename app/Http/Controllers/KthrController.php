<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Kthr;
use App\Models\KthrPlantSpecies;
use App\Models\PermintaanKerjasama;
use App\Models\Pertemuan;
use App\Models\KesepakatanKerjasama;
use App\Models\User;
use App\Models\PbphhProfile;
use Illuminate\Support\Facades\File;


class KthrController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $kthr = $user->kthr;

        \Illuminate\Support\Facades\Log::info('User and KTHR data', [
            'user_id' => $user->id,
            'has_kthr' => (bool) $kthr,
            'kthr_id' => $kthr ? $kthr->kthr_id : null
        ]);

        $stats = [
            'total_requests' => PermintaanKerjasama::where('kthr_id', $kthr->kthr_id)->count(),
            'pending_requests' => PermintaanKerjasama::where('kthr_id', $kthr->kthr_id)
                ->where('status', 'Terkirim')->count(),
            'active_partnerships' => PermintaanKerjasama::where('kthr_id', $kthr->kthr_id)
                ->whereIn('status', ['Disetujui', 'Dijadwalkan'])->count(),
            'completed_partnerships' => PermintaanKerjasama::where('kthr_id', $kthr->kthr_id)
                ->where('status', 'Selesai')->count(),
        ];

        $recentRequests = PermintaanKerjasama::where('kthr_id', $kthr->kthr_id)
            ->with(['pbphhProfile.user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $upcomingMeetings = Pertemuan::whereHas('permintaanKerjasama', function ($query) use ($kthr) {
            $query->where('kthr_id', $kthr->kthr_id);
        })
            ->where('status', 'Dijadwalkan')
            ->where('scheduled_time', '>', now())
            ->with(['permintaanKerjasama.pbphhProfile.user', 'scheduledBy'])
            ->orderBy('scheduled_time')
            ->limit(3)
            ->get();

        return view('kthr.dashboard', compact('kthr', 'stats', 'recentRequests', 'upcomingMeetings'));
    }

    public function completeProfile()
    {
        $user = Auth::user();
        $kthr = $user->kthr;

        return view('kthr.complete-profile', compact('kthr'));
    }

    public function storeProfile(Request $request)
    {
        try {
            \Illuminate\Support\Facades\Log::info('Request Method and URL', [
                'method' => $request->method(),
                'url' => $request->url(),
                'is_ajax' => $request->ajax()
            ]);

            \Illuminate\Support\Facades\Log::info('Request Headers', [
                'headers' => $request->headers->all()
            ]);

            \Illuminate\Support\Facades\Log::info('Storing KTHR profile - Start', [
                'request' => $request->all(),
                'files' => $request->allFiles()
            ]);
            \Illuminate\Support\Facades\Log::info('Current User', ['user_id' => Auth::id(), 'role' => Auth::user()->role]);

            \Illuminate\Support\Facades\Log::info('Validating plants', [
                'has_plants' => $request->has('plants'),
                'plants_count' => $request->has('plants') ? count($request->plants) : 0
            ]);

            if (!$request->has('plants') || count($request->plants) === 0) {
                \Illuminate\Support\Facades\Log::info('Plant validation failed, redirecting back');
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Minimal satu jenis tanaman harus diisi.');
            }

            \Illuminate\Support\Facades\Log::info('Starting validation');
            $request->validate([
                'nama_pendamping' => 'required|string|max:255',
                'phone' => 'nullable|string|max:20',
                'alamat_sekretariat' => 'required|string',
                'coordinate_lat' => 'required|numeric|between:-90,90',
                'coordinate_lng' => 'required|numeric|between:-180,180',
                'luas_areal_ha' => 'required|numeric|min:0',
                'jumlah_anggota' => 'required|integer|min:1',
                'jumlah_pertemuan_tahunan' => 'required|integer|min:0',
                'shp_file' => 'nullable|file|mimes:zip,shp|max:10240',
                'plants.*.jenis_tanaman' => 'required|string|max:100',
                'plants.*.tipe' => 'required|in:Kayu,Bukan Kayu',
                'plants.*.jumlah_pohon' => 'required|integer|min:1',
                'plants.*.tahun_tanam' => 'required|integer|min:1900|max:' . date('Y'),
                'plants.*.gambar_tegakan' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
            ]);

            $user = Auth::user();
            $kthr = $user->kthr;

            $updateData = $request->only([
                'nama_pendamping',
                'phone',
                'alamat_sekretariat',
                'coordinate_lat',
                'coordinate_lng',
                'luas_areal_ha',
                'jumlah_anggota',
                'jumlah_pertemuan_tahunan'
            ]);

            if ($request->hasFile('shp_file')) {
                $updateData['shp_file_path'] = $request->file('shp_file')->store('shp_files', 'public');

                $source = storage_path('app/public/' . $updateData['shp_file_path']);
                $destination = public_path('storage/' . $updateData['shp_file_path']);

                if (!file_exists(dirname($destination))) {
                    mkdir(dirname($destination), 0755, true);
                }

                copy($source, $destination);
            }

            if (!$kthr) {
                $updateData['registered_by_user_id'] = $user->user_id;
                $updateData['region_id'] = $user->region_id;
                $updateData['kthr_name'] = 'Default Name';
                $updateData['ketua_ktp_path'] = 'documents/ktp/default.pdf';
                $updateData['sk_register_path'] = 'documents/sk/default.pdf';

                $kthr = Kthr::create($updateData);
            } else {
                $kthr->update($updateData);
            }

            $kthr->plantSpecies()->delete();

            if ($request->has('plants')) {
                foreach ($request->plants as $plantData) {
                    $plantSpecies = new KthrPlantSpecies([
                        'kthr_id' => $kthr->kthr_id,
                        'jenis_tanaman' => $plantData['jenis_tanaman'],
                        'tipe' => $plantData['tipe'],
                        'jumlah_pohon' => $plantData['jumlah_pohon'],
                        'tahun_tanam' => $plantData['tahun_tanam'],
                    ]);

                    if (isset($plantData['gambar_tegakan']) && $plantData['gambar_tegakan'] instanceof \Illuminate\Http\UploadedFile) {

                        $plantSpecies->gambar_tegakan_path = $plantData['gambar_tegakan']->store('plant_images', 'public');

                        $source = storage_path('app/public/' . $plantSpecies->gambar_tegakan_path);
                        $destination = public_path('storage/' . $plantSpecies->gambar_tegakan_path);

                        if (!file_exists(dirname($destination))) {
                            mkdir(dirname($destination), 0755, true);
                        }

                        copy($source, $destination);
                    }

                    $plantSpecies->save();
                }
            }

            $kthr->update([
                'is_siap_mitra' => true,
                'is_siap_tebang' => false
            ]);

            \Illuminate\Support\Facades\Log::info('KTHR profile stored successfully', [
                'kthr_id' => $kthr->kthr_id,
                'plant_species_count' => $kthr->plantSpecies()->count()
            ]);

            \Illuminate\Support\Facades\Log::info('Redirecting to dashboard');
            return redirect('/kthr/dashboard')->with('success', 'Profil KTHR berhasil disimpan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Illuminate\Support\Facades\Log::error('Validation error storing KTHR profile', [
                'error' => $e->getMessage(),
                'errors' => $e->errors(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Terdapat kesalahan pada data yang dimasukkan. Silakan periksa kembali format file gambar.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error storing KTHR profile', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan profil. Silakan coba lagi.');
        }
    }

    public function profile()
    {
        $user = Auth::user();
        $kthr = $user->kthr->load('plantSpecies');

        return view('kthr.profile', compact('kthr'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'nama_pendamping' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'alamat_sekretariat' => 'required|string',
            'coordinate_lat' => 'required|numeric|between:-90,90',
            'coordinate_lng' => 'required|numeric|between:-180,180',
            'luas_areal_ha' => 'required|numeric|min:0',
            'jumlah_anggota' => 'required|integer|min:1',
            'jumlah_pertemuan_tahunan' => 'required|integer|min:0',
            'is_siap_mitra' => 'boolean',
            'is_siap_tebang' => 'boolean'
        ]);

        $user = Auth::user();
        $kthr = $user->kthr;

        $kthr->update($request->all());

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function requests()
    {
        $user = Auth::user();
        $kthr = $user->kthr;

        $requests = PermintaanKerjasama::where('kthr_id', $kthr->kthr_id)
            ->with(['pbphhProfile.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('kthr.requests', compact('requests'));
    }

    public function respondToRequest(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'rejection_reason' => 'required_if:action,reject|string'
        ]);

        $permintaan = PermintaanKerjasama::where('request_id', $id)
            ->where('kthr_id', Auth::user()->kthr->kthr_id)
            ->where('status', 'Terkirim')
            ->firstOrFail();

        if ($request->action === 'approve') {
            $permintaan->update(['status' => 'Disetujui']);
            $message = 'Permintaan kerjasama berhasil disetujui!';
        } else {
            $permintaan->update([
                'status' => 'Ditolak',
                'rejection_reason' => $request->rejection_reason
            ]);
            $message = 'Permintaan kerjasama berhasil ditolak!';
        }

        return redirect()->back()->with('success', $message);
    }

    public function cancelRequest($id)
    {
        $permintaan = PermintaanKerjasama::where('request_id', $id)
            ->where('kthr_id', Auth::user()->kthr->kthr_id)
            ->whereIn('status', ['Disetujui', 'Menunggu Jadwal'])
            ->firstOrFail();

        // Periksa apakah permintaan sudah memiliki pertemuan yang dijadwalkan
        if ($permintaan->pertemuan && $permintaan->pertemuan->status !== 'Dibatalkan') {
            return redirect()->back()->with('error', 'Tidak dapat membatalkan permintaan yang sudah dijadwalkan pertemuan. Hubungi CDK untuk membatalkan pertemuan terlebih dahulu.');
        }

        // Periksa apakah permintaan sudah memiliki kesepakatan
        if ($permintaan->kesepakatanKerjasama) {
            return redirect()->back()->with('error', 'Tidak dapat membatalkan permintaan yang sudah memiliki kesepakatan kerjasama.');
        }

        $permintaan->update([
            'status' => 'Dibatalkan',
            'rejection_reason' => 'Dibatalkan oleh KTHR'
        ]);

        return redirect()->back()->with('success', 'Permintaan kerjasama berhasil dibatalkan!');
    }

    public function partnerships()
    {
        $user = Auth::user();
        $kthr = $user->kthr;

        $partnerships = PermintaanKerjasama::where('kthr_id', $kthr->kthr_id)
            ->whereIn('status', ['Disetujui', 'Menunggu Jadwal', 'Dijadwalkan', 'Menunggu Tanda Tangan', 'Selesai'])
            ->with(['pbphhProfile.user', 'pertemuan.kesepakatan'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('kthr.partnerships', compact('partnerships'));
    }

    public function signAgreement(Request $request, $id)
    {
        $partnership = PermintaanKerjasama::where('request_id', $id)
            ->where('kthr_id', Auth::user()->kthr->kthr_id)
            ->with('pertemuan.kesepakatan')
            ->firstOrFail();

        if (!$partnership->pertemuan || !$partnership->pertemuan->kesepakatan) {
            return redirect()->back()->with('error', 'Kesepakatan belum tersedia!');
        }

        $kesepakatan = $partnership->pertemuan->kesepakatan;
        $kesepakatan->update(['signed_by_kthr_at' => now()]);

        if ($kesepakatan->signed_by_kthr_at && $kesepakatan->signed_by_pbphh_at) {
            $partnership->update(['status' => 'Selesai']);
        }

        return redirect()->back()->with('success', 'Kesepakatan berhasil ditandatangani!');
    }

    // Method untuk mengelola data tanaman
    public function storePlant(Request $request)
    {
        try {
            $request->validate([
                'jenis_tanaman' => 'required|string|max:100',
                'tipe' => 'required|in:Kayu,Bukan Kayu',
                'jumlah_pohon' => 'required|integer|min:1',
                'tahun_tanam' => 'required|integer|min:1900|max:' . date('Y'),
                'gambar_tegakan' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
            ]);

            $user = Auth::user();
            $kthr = $user->kthr;

            if (!$kthr) {
                return response()->json([
                    'success' => false,
                    'message' => 'KTHR tidak ditemukan.'
                ], 404);
            }

            $plantData = [
                'kthr_id' => $kthr->kthr_id,
                'jenis_tanaman' => $request->jenis_tanaman,
                'tipe' => $request->tipe,
                'jumlah_pohon' => $request->jumlah_pohon,
                'tahun_tanam' => $request->tahun_tanam,
            ];

            if ($request->hasFile('gambar_tegakan')) {

                // Simpan ke storage/app/public/plant_images
                $path = $request->file('gambar_tegakan')->store('plant_images', 'public');

                // Simpan path ke database
                $plantData['gambar_tegakan_path'] = $path;

                // Pastikan folder public/storage/plant_images ada
                if (!File::exists(public_path('storage/plant_images'))) {
                    File::makeDirectory(public_path('storage/plant_images'), 0755, true);
                }

                // Copy file dari storage ke public/storage
                File::copy(
                    storage_path('app/public/' . $path),
                    public_path('storage/' . $path)
                );
            }

            $plant = KthrPlantSpecies::create($plantData);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data tanaman berhasil ditambahkan!',
                    'plant' => $plant
                ]);
            }

            return redirect()->back()->with('success', 'Data tanaman berhasil ditambahkan!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terdapat kesalahan pada data yang dimasukkan.',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Terdapat kesalahan pada data yang dimasukkan.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error storing plant data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan data tanaman.'
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data tanaman.');
        }
    }

    public function updatePlant(Request $request, $id)
    {
        try {
            $request->validate([
                'jenis_tanaman' => 'required|string|max:100',
                'tipe' => 'required|in:Kayu,Bukan Kayu',
                'jumlah_pohon' => 'required|integer|min:1',
                'tahun_tanam' => 'required|integer|min:1900|max:' . date('Y'),
                'gambar_tegakan' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
            ]);

            $user = Auth::user();
            $kthr = $user->kthr;

            $plant = KthrPlantSpecies::where('plant_id', $id)
                ->where('kthr_id', $kthr->kthr_id)
                ->firstOrFail();

            $updateData = [
                'jenis_tanaman' => $request->jenis_tanaman,
                'tipe' => $request->tipe,
                'jumlah_pohon' => $request->jumlah_pohon,
                'tahun_tanam' => $request->tahun_tanam,
            ];

            if ($request->hasFile('gambar_tegakan')) {
                // Hapus gambar lama jika ada
                if ($plant->gambar_tegakan_path) {
                    Storage::disk('public')->delete($plant->gambar_tegakan_path);
                }
                $updateData['gambar_tegakan_path'] = $request->file('gambar_tegakan')->store('plant_images', 'public');
            }

            $plant->update($updateData);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data tanaman berhasil diperbarui!',
                    'plant' => $plant
                ]);
            }

            return redirect()->back()->with('success', 'Data tanaman berhasil diperbarui!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error updating plant data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memperbarui data tanaman.'
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data tanaman.');
        }
    }

    public function deletePlant($id)
    {
        try {
            $user = Auth::user();
            $kthr = $user->kthr;

            $plant = KthrPlantSpecies::where('plant_id', $id)
                ->where('kthr_id', $kthr->kthr_id)
                ->firstOrFail();

            // Hapus gambar jika ada
            if ($plant->gambar_tegakan_path) {

                // Hapus dari storage/app/public
                Storage::disk('public')->delete($plant->gambar_tegakan_path);

                // Hapus dari public/storage
                $publicImage = public_path('storage/' . $plant->gambar_tegakan_path);

                if (file_exists($publicImage)) {
                    unlink($publicImage);
                }
            }

            $plant->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data tanaman berhasil dihapus!'
                ]);
            }

            return redirect()->back()->with('success', 'Data tanaman berhasil dihapus!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error deleting plant data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus data tanaman.'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data tanaman.');
        }
    }

    public function getCompanyProfile($pbphhId)
    {
        try {
            $pbphhProfile = PbphhProfile::with(['user.region', 'materialNeeds'])
                ->where('pbphh_id', $pbphhId)
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => [
                    'company_name' => $pbphhProfile->company_name,
                    'director_name' => $pbphhProfile->penanggung_jawab,
                    'email' => $pbphhProfile->user->email,
                    'phone' => $pbphhProfile->phone,
                    'address' => $pbphhProfile->alamat_perusahaan,
                    'region_name' => $pbphhProfile->user->region ? $pbphhProfile->user->region->name : null,
                    'verification_status' => $pbphhProfile->user->approval_status,
                    'material_needs' => $pbphhProfile->materialNeeds->map(function ($need) {
                        return [
                            'wood_type' => $need->jenis_kayu,
                            'material_type' => $need->tipe,
                            'monthly_volume_m3' => $need->kebutuhan_bulanan_m3,
                            'additional_specifications' => $need->spesifikasi_tambahan
                        ];
                    })
                ]
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error fetching company profile', [
                'pbphh_id' => $pbphhId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil profil perusahaan.'
            ], 500);
        }
    }
}
