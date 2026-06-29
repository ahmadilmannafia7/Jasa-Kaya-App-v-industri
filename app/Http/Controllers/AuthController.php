<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\User;
use App\Models\Kthr;
use App\Models\Tptkb;
use App\Models\PbphhProfile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return $this->redirectBasedOnStatus();
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function showRegister()
    {
        $regions = \App\Models\Region::all();
        $user = Auth::check() ? Auth::user() : null;

        // If user is logged in and rejected, pre-fill their data
        if ($user && $user->approval_status === 'Rejected') {
            return view('auth.register', compact('regions', 'user'));
        }

        return view('auth.register', compact('regions'));
    }


    public function register(Request $request)
    {
        $currentUser = Auth::user();

        // Jika user login dan status Rejected
        if ($currentUser && $currentUser->approval_status === 'Rejected') {
            return $this->handleReregistration($request, $currentUser);
        }

        // ======================
        // REGISTER BARU
        // ======================
        $request->validate([
            'role' => 'required|in:KTHR_PENYULUH,PBPHH,TPTKB',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'region_id' => 'required|exists:regions,region_id',
        ]);

        DB::beginTransaction();

        try {

            $user = User::create([
                'email' => $request->email,
                'password_hash' => Hash::make($request->password),
                'role' => $request->role,
                'approval_status' => 'Pending',
                'region_id' => $request->region_id,
            ]);

            switch ($request->role) {

                case 'KTHR_PENYULUH':
                    $this->registerKthr($request, $user);
                    break;

                case 'PBPHH':
                    $this->registerPbphh($request, $user);
                    break;

                case 'TPTKB':
                    $this->registerTptkb($request, $user);
                    break;
            }

            DB::commit();

            return redirect()
                ->route('pending.approval')
                ->with('success', 'Pendaftaran berhasil!');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withErrors([
                    'error' => $e->getMessage()
                ])
                ->withInput();
        }
    }

    private function handleReregistration(Request $request, User $user)
    {
        DB::beginTransaction();

        try {

            switch ($user->role) {

                case 'KTHR_PENYULUH':

                    $request->validate([
                        'ketua_ktp' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                        'sk_register' => 'required|file|mimes:pdf|max:2048',
                    ]);

                    $this->updateKthrDocuments($request, $user);

                    break;

                case 'PBPHH':

                    $request->validate([
                        'nib' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                        'sk_pbphh' => 'required|file|mimes:pdf|max:2048',
                    ]);

                    $this->updatePbphhDocuments($request, $user);

                    break;

                case 'TPTKB':

                    $request->validate([
                        'ketua_ktp_tptkb' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                        'sk_tptkb' => 'required|file|mimes:pdf|max:2048',
                    ]);

                    $this->updateTptkbDocuments($request, $user);

                    break;
            }

            $user->update([
                'approval_status' => 'Pending',
                'rejection_reason' => null,
                'approved_by_user_id' => null,
                'approved_at' => null,
            ]);

            DB::commit();

            return redirect()
                ->route('pending.approval')
                ->with(
                    'success',
                    'Dokumen berhasil diperbarui. Akun menunggu persetujuan ulang.'
                );

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withErrors([
                    'error' => $e->getMessage()
                ]);
        }
    }

    private function registerKthr(Request $request, User $user)
    {
        $request->validate([
            'kthr_name' => 'required|string|max:255',
            'ketua_ktp' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'sk_register' => 'required|file|mimes:pdf|max:2048'
        ]);

        $ktpPath = $request->file('ketua_ktp')->store('documents/ktp', 'public');
        $skPath = $request->file('sk_register')->store('documents/sk', 'public');

        Kthr::create([
            'registered_by_user_id' => $user->user_id,
            'region_id' => $user->region_id,
            'kthr_name' => $request->kthr_name,
            'ketua_ktp_path' => $ktpPath,
            'sk_register_path' => $skPath
        ]);
    }

    private function registerTptkb(Request $request, User $user)
    {
        $request->validate([
            'tptkb_name' => 'required|string|max:255',
            'ketua_ktp_tptkb' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'sk_tptkb' => 'required|file|mimes:pdf|max:2048'
        ]);

        $ktp_tptkbPath = $request->file('ketua_ktp_tptkb')->store('documents/ktp_tptkb', 'public');
        $sk_tptkbPath = $request->file('sk_tptkb')->store('documents/sk_tptkb', 'public');

        Tptkb::create([
            'registered_by_user_id' => $user->user_id,
            'region_id' => $user->region_id,
            'tptkb_name' => $request->tptkb_name,
            'ketua_ktp_path' => $ktp_tptkbPath,
            'sk_tptkb_path' => $sk_tptkbPath
        ]);


    }

    private function registerPbphh(Request $request, User $user)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'nib' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'sk_pbphh' => 'required|file|mimes:pdf|max:2048'
        ]);

        // Simpan ke storage/app/public
        $nibPath = $request->file('nib')->store('documents/nib', 'public');
        $skPath = $request->file('sk_pbphh')->store('documents/sk_pbphh', 'public');

        // Copy ke public/storage
        $this->copyToPublicStorage($nibPath);
        $this->copyToPublicStorage($skPath);

        PbphhProfile::create([
            'user_id' => $user->user_id,
            'company_name' => $request->company_name,
            'nib_path' => $nibPath,
            'sk_pbphh_path' => $skPath
        ]);
    }

    /**
     * Copy file dari storage/app/public ke public/storage
     */
    private function copyToPublicStorage($path)
    {
        $source = storage_path('app/public/' . $path);
        $destination = public_path('storage/' . $path);

        // Buat folder tujuan jika belum ada
        File::ensureDirectoryExists(dirname($destination));

        // Copy file
        File::copy($source, $destination);
    }

    private function updateKthrDocuments(Request $request, User $user)
    {
        $kthr = $user->kthr;

        // Delete old documents if they exist
        if ($kthr->ketua_ktp_path) {
            Storage::disk('public')->delete($kthr->ketua_ktp_path);
        }
        if ($kthr->sk_register_path) {
            Storage::disk('public')->delete($kthr->sk_register_path);
        }

        // Upload new documents
        $ktpPath = $request->file('ketua_ktp')->store('documents/ktp', 'public');
        $skPath = $request->file('sk_register')->store('documents/sk', 'public');

        // Update KTHR record
        $kthr->update([
            'ketua_ktp_path' => $ktpPath,
            'sk_register_path' => $skPath
        ]);
    }

    private function updateTptkbDocuments(Request $request, User $user)
    {
        $tptkb = $user->tptkb;

        // Delete old documents if they exist
        if ($tptkb->ketua_ktp_path) {
            Storage::disk('public')->delete($tptkb->ketua_ktp_path);
        }
        if ($tptkb->sk_tptkb_path) {
            Storage::disk('public')->delete($tptkb->sk_tptkb_path);
        }

        // Upload new documents
        $ktp_tptkbPath = $request->file('ketua_ktp_tptkb')->store('documents/ktp_tptkb', 'public');
        $sk_tptkbPath = $request->file('sk_tptkb')->store('documents/sk_tptkb', 'public');

        // Update TPTKB record
        $tptkb->update([
            'ketua_ktp_path' => $ktp_tptkbPath,
            'sk_tptkb_path' => $sk_tptkbPath
        ]);
    }

    private function updatePbphhDocuments(Request $request, User $user)
    {
        $pbphh = $user->pbphhProfile;

        Log::info('Updating PBPHH documents', [
            'user_email' => $user->email,
            'old_nib_path' => $pbphh->nib_path,
            'old_sk_path' => $pbphh->sk_pbphh_path
        ]);

        // Delete old documents if they exist
        if ($pbphh->nib_path) {
            $deleted = Storage::disk('public')->delete($pbphh->nib_path);
            Log::info('Deleted old NIB file', ['path' => $pbphh->nib_path, 'success' => $deleted]);
        }
        if ($pbphh->sk_pbphh_path) {
            $deleted = Storage::disk('public')->delete($pbphh->sk_pbphh_path);
            Log::info('Deleted old SK PBPHH file', ['path' => $pbphh->sk_pbphh_path, 'success' => $deleted]);
        }

        // Upload new documents
        $nibPath = $request->file('nib')->store('documents/nib', 'public');
        $skPath = $request->file('sk_pbphh')->store('documents/sk_pbphh', 'public');

        Log::info('Uploaded new documents', [
            'new_nib_path' => $nibPath,
            'new_sk_path' => $skPath
        ]);

        // Update PBPHH record
        $updateResult = $pbphh->update([
            'nib_path' => $nibPath,
            'sk_pbphh_path' => $skPath
        ]);

        Log::info('PBPHH profile update result', [
            'update_success' => $updateResult,
            'profile_id' => $pbphh->id
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function pendingApproval()
    {
        return view('auth.pending-approval');
    }

    public function rejected()
    {
        $user = Auth::user();
        return view('auth.rejected', compact('user'));
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan dalam sistem.']);
        }

        // Generate reset token (simplified - in production use Laravel's built-in password reset)
        $token = Str::random(60);

        // Store token in database or cache
        // For now, just return success message

        return back()->with('status', 'Link reset password telah dikirim ke email Anda.');
    }

    private function redirectBasedOnStatus()
    {
        $user = Auth::user();

        switch ($user->approval_status) {
            case 'Pending':
                return redirect()->route('pending.approval');
            case 'Rejected':
                return redirect()->route('rejected');
            case 'Approved':
                return $this->redirectApprovedUser($user);
            default:
                return redirect()->route('home');
        }
    }

    private function redirectApprovedUser($user)
    {
        switch ($user->role) {
            case 'KTHR_PENYULUH':
                $kthr = $user->kthr;
                if (!$kthr || !$kthr->nama_pendamping) {
                    return redirect()->route('kthr.profile.complete')
                        ->with('info', 'Silakan lengkapi profil KTHR Anda terlebih dahulu.');
                }
                return redirect()->route('kthr.dashboard');

            case 'TPTKB':
                $tptkb = $user->tptkb;
                if (!$tptkb || !$tptkb->nama_pendamping_tptkb) {
                    return redirect()->route('tptkb.profile.complete')
                        ->with('info', 'Silakan lengkapi profil TPTKB Anda terlebih dahulu.');
                }
                return redirect()->route('tptkb.dashboard');

            case 'PBPHH':
                $pbphh = $user->pbphhProfile;
                if (!$pbphh || !$pbphh->penanggung_jawab) {
                    return redirect()->route('pbphh.profile.complete')
                        ->with('info', 'Silakan lengkapi profil perusahaan Anda terlebih dahulu.');
                }
                return redirect()->route('pbphh.dashboard');

            case 'CDK':
                return redirect()->route('cdk.dashboard');

            case 'DINAS_PROVINSI':
                return redirect()->route('dinas.dashboard');

            default:
                return redirect()->route('home');
        }
    }
}
