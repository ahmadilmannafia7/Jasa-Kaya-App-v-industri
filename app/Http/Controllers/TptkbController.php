<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\KthrPlantSpecies;
use App\Models\PermintaanKerjasama;
use App\Models\Pertemuan;
use App\Models\KesepakatanKerjasama;
use App\Models\User;
use App\Models\PbphhProfile;
use App\Models\Tptkb;
use App\Models\TptkbMaterialSupply;



class TptkbController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $tptkb = $user->tptkb;

        \Illuminate\Support\Facades\Log::info('User and TPTKB data', [
            'user_id' => $user->id,
            'has_tptkb' => (bool) $tptkb,
            'tptkb_id' => $tptkb ? $tptkb->tptkb_id : null
        ]);

        $stats = [
            'total_requests' => PermintaanKerjasama::where('tptkb_id', $tptkb->tptkb_id)->count(),
            'pending_requests' => PermintaanKerjasama::where('tptkb_id', $tptkb->tptkb_id)
                ->where('status', 'Terkirim')->count(),
            'active_partnerships' => PermintaanKerjasama::where('tptkb_id', $tptkb->tptkb_id)
                ->whereIn('status', ['Disetujui', 'Dijadwalkan'])->count(),
            'completed_partnerships' => PermintaanKerjasama::where('tptkb_id', $tptkb->tptkb_id)
                ->where('status', 'Selesai')->count(),
        ];

        $recentRequests = PermintaanKerjasama::where('tptkb_id', $tptkb->tptkb_id)
            ->with(['pbphhProfile.user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $upcomingMeetings = Pertemuan::whereHas('permintaanKerjasama', function ($query) use ($tptkb) {
            $query->where('tptkb_id', $tptkb->tptkb_id);
        })
            ->where('status', 'Dijadwalkan')
            ->where('scheduled_time', '>', now())
            ->with(['permintaanKerjasama.pbphhProfile.user', 'scheduledBy'])
            ->orderBy('scheduled_time')
            ->limit(3)
            ->get();

        return view('tptkb.dashboard', compact('tptkb', 'stats', 'recentRequests', 'upcomingMeetings'));
    }

    public function completeProfile()
    {
        $user = Auth::user();
        $tptkb = $user->tptkb;

        return view('tptkb.complete-profile', compact('tptkb'));
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

            \Illuminate\Support\Facades\Log::info('Storing TPTKB profile - Start', [
                'request' => $request->all(),
                'files' => $request->allFiles()
            ]);
            \Illuminate\Support\Facades\Log::info('Current User', ['user_id' => Auth::id(), 'role' => Auth::user()->role]);

            \Illuminate\Support\Facades\Log::info('Validating supplies', [
                'has_supplies' => $request->has('supplies'),
                'supplies_count' => $request->has('supplies') ? count($request->supplies) : 0
            ]);

            if (!$request->has('supplies') || count($request->supplies) === 0) {
                \Illuminate\Support\Facades\Log::info('Supply validation failed, redirecting back');
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Minimal satu jenis bahan harus diisi.');
            }

            \Illuminate\Support\Facades\Log::info('Starting validation');
            $request->validate([
                'nama_pendamping_tptkb' => 'required|string|max:255',
                'phone' => 'nullable|string|max:20',
                'alamat_tptkb' => 'required|string',
                'coordinate_lat' => 'required|numeric|between:-90,90',
                'coordinate_lng' => 'required|numeric|between:-180,180',
                'supplies.*.supply_kayu' => 'required|string|max:100',
                'supplies.*.jumlah' => 'required|integer|min:1',
                'supplies.*.gambar_supply' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
            ]);

            $user = Auth::user();
            $tptkb = $user->tptkb;

            $updateData = $request->only([
                'nama_pendamping_tptkb',
                'phone',
                'alamat_tptkb',
                'coordinate_lat',
                'coordinate_lng',
            ]);



            if (!$tptkb) {
                $updateData['registered_by_user_id'] = $user->user_id;
                $updateData['region_id'] = $user->region_id;
                $updateData['tptkb_name'] = 'Default Name';
                $updateData['ketua_ktp_path'] = 'documents/ktp/default.pdf';
                $updateData['sk_tptkb_path'] = 'documents/sk/default.pdf';

                $tptkb = Tptkb::create($updateData);
            } else {
                $tptkb->update($updateData);
            }

            $tptkb->materialSupplies()->delete();

            if ($request->has('supplies')) {
                foreach ($request->supplies as $supplyData) {
                    $materialSupplies = new TptkbMaterialSupply([
                        'tptkb_id' => $tptkb->tptkb_id,
                        'supply_kayu' => $supplyData['supply_kayu'],
                        'tipe' => $supplyData['tipe'],
                        'jumlah' => $supplyData['jumlah'],
                        // 'spesifikasi_tambahan' => $supplyData['spesifikasi_tambahan'],
                    ]);

                    if (isset($supplyData['gambar_supply']) && $supplyData['gambar_supply'] instanceof \Illuminate\Http\UploadedFile) {
                        $materialSupplies->gambar_supply_path = $supplyData['gambar_supply']->store('supply_images', 'public');
                    }

                    $materialSupplies->save();
                }
            }

            $tptkb->update([
                'is_siap_mitra' => true,
            ]);

            \Illuminate\Support\Facades\Log::info('Tptkb profile stored successfully', [
                'tptkb_id' => $tptkb->tptkb_id,
                'material_supply_count' => $tptkb->materialSupplies()->count()
            ]);

            \Illuminate\Support\Facades\Log::info('Redirecting to dashboard');
            return redirect('/tptkb/dashboard')->with('success', 'Profil Tptkb berhasil disimpan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Illuminate\Support\Facades\Log::error('Validation error storing Tptkb profile', [
                'error' => $e->getMessage(),
                'errors' => $e->errors(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Terdapat kesalahan pada data yang dimasukkan. Silakan periksa kembali format file gambar.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error storing Tptkb profile', [
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
        $tptkb = $user->tptkb->load('materialSupplies');

        return view('tptkb.profile', compact('tptkb'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'nama_pendamping_tptkb' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'alamat_tptkb' => 'required|string',
            'coordinate_lat' => 'required|numeric|between:-90,90',
            'coordinate_lng' => 'required|numeric|between:-180,180',
            'is_siap_mitra' => 'boolean',
        ]);

        $user = Auth::user();
        $tptkb = $user->tptkb;

        $tptkb->update($request->all());

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function requests()
    {
        $user = Auth::user();
        $tptkb = $user->tptkb;

        $requests = PermintaanKerjasama::where('tptkb_id', $tptkb->tptkb_id)
            ->with(['pbphhProfile.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('tptkb.requests', compact('requests'));
    }

    public function respondToRequest(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'rejection_reason' => 'required_if:action,reject|string'
        ]);

        $permintaan = PermintaanKerjasama::where('request_id', $id)
            ->where('tptkb_id', Auth::user()->tptkb->tptkb_id)
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
            ->where('tptkb_id', Auth::user()->tptkb->tptkb_id)
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
            'rejection_reason' => 'Dibatalkan oleh TPTKB'
        ]);

        return redirect()->back()->with('success', 'Permintaan kerjasama berhasil dibatalkan!');
    }

    public function partnerships()
    {
        $user = Auth::user();
        $tptkb = $user->tptkb;

        $partnerships = PermintaanKerjasama::where('tptkb_id', $tptkb->tptkb_id)
            ->whereIn('status', ['Disetujui', 'Menunggu Jadwal', 'Dijadwalkan', 'Menunggu Tanda Tangan', 'Selesai'])
            ->with(['pbphhProfile.user', 'pertemuan.kesepakatan'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('tptkb.partnerships', compact('partnerships'));
    }

    public function signAgreement(Request $request, $id)
    {
        $partnership = PermintaanKerjasama::where('request_id', $id)
            ->where('tptkb_id', Auth::user()->tptkb->tptkb_id)
            ->with('pertemuan.kesepakatan')
            ->firstOrFail();

        if (!$partnership->pertemuan || !$partnership->pertemuan->kesepakatan) {
            return redirect()->back()->with('error', 'Kesepakatan belum tersedia!');
        }

        $kesepakatan = $partnership->pertemuan->kesepakatan;
        $kesepakatan->update(['signed_by_tptkb_at' => now()]);

        if ($kesepakatan->signed_by_tptkb_at && $kesepakatan->signed_by_pbphh_at) {
            $partnership->update(['status' => 'Selesai']);
        }

        return redirect()->back()->with('success', 'Kesepakatan berhasil ditandatangani!');
    }

    // Method untuk mengelola data tanaman
    public function storeSupply(Request $request)
    {
        try {
            $request->validate([
                'supply_kayu' => 'required|string|max:100',
                'tipe' => 'required|in:Kayu,Bukan Kayu',
                'jumlah' => 'required|integer|min:1',
                'gambar_supply' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
            ]);

            $user = Auth::user();
            $tptkb = $user->tptkb;

            if (!$tptkb) {
                return response()->json([
                    'success' => false,
                    'message' => 'TPTKB tidak ditemukan.'
                ], 404);
            }

            $supplyData = [
                'tptkb_id' => $tptkb->tptkb_id,
                'supply_kayu' => $request->supply_kayu,
                'tipe' => $request->tipe,
                'jumlah' => $request->jumlah,
            ];

            if ($request->hasFile('gambar_supply')) {
                $supplyData['gambar_supply_path'] = $request->file('gambar_supply')->store('supply_images', 'public');
            }

            $supply = TptkbMaterialSupply::create($supplyData);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data supply berhasil ditambahkan!',
                    'supply' => $supply
                ]);
            }

            return redirect()->back()->with('success', 'Data supply berhasil ditambahkan!');
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

    public function updateSupply(Request $request, $id)
    {
        try {
            $request->validate([
                'supply_kayu' => 'required|string|max:100',
                'tipe' => 'required|in:Kayu,Bukan Kayu',
                'jumlah_supply' => 'required|integer|min:1',
                'gambar_supply' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
            ]);

            $user = Auth::user();
            $tptkb = $user->tptkb;

            $supply = TptkbMaterialSupply::where('supply_id', $id)
                ->where('tptkb_id', $tptkb->tptkb_id)
                ->firstOrFail();

            $updateData = [
                'supply_kayu' => $request->supply_kayu,
                'tipe' => $request->tipe,
                'jumlah_supply' => $request->jumlah_supply,
            ];

            if ($request->hasFile('gambar_supply')) {
                // Hapus gambar lama jika ada
                if ($supply->gambar_supply_path) {
                    Storage::disk('public')->delete($supply->gambar_supply_path);
                }
                $updateData['gambar_supply_path'] = $request->file('gambar_supply')->store('supply_images', 'public');
            }

            $supply->update($updateData);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data supply berhasil diperbarui!',
                    'supply' => $supply
                ]);
            }

            return redirect()->back()->with('success', 'Data supply berhasil diperbarui!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error updating supply data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memperbarui data supply.'
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data supply.');
        }
    }

    public function deleteSupply($id)
    {
        try {
            $user = Auth::user();
            $tptkb = $user->tptkb;

            $supply = TptkbMaterialSupply::where('supply_id', $id)
                ->where('tptkb_id', $tptkb->tptkb_id)
                ->firstOrFail();

            // Hapus gambar jika ada
            if ($supply->gambar_supply_path) {
                Storage::disk('public')->delete($supply->gambar_supply_path);
            }

            $supply->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data supply berhasil dihapus!'
                ]);
            }

            return redirect()->back()->with('success', 'Data supply berhasil dihapus!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error deleting supply data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus data supply.'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data supply.');
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
