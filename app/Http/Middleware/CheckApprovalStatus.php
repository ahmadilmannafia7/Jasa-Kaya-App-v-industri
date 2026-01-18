<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckApprovalStatus
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Check approval status
        switch ($user->approval_status) {
            case 'Pending':
                if (!$request->routeIs('pending.approval', 'logout')) {
                    return redirect()->route('pending.approval');
                }
                break;
                
            case 'Rejected':
                if (!$request->routeIs('rejected', 'logout', 'register')) {
                    return redirect()->route('rejected');
                }
                break;
                
            case 'Approved':
                // Check if profile is complete
                if ($this->needsProfileCompletion($user) && !$this->isProfileCompletionRoute($request)) {
                    return $this->redirectToProfileCompletion($user);
                }
                break;
        }

        $response = $next($request);
        
        \Illuminate\Support\Facades\Log::info('CheckApprovalStatus Middleware - After Processing', [
            'response_type' => get_class($response),
            'status_code' => method_exists($response, 'getStatusCode') ? $response->getStatusCode() : 'N/A',
            'target_url' => method_exists($response, 'getTargetUrl') ? $response->getTargetUrl() : 'N/A'
        ]);
        
        return $response;
    }

    private function needsProfileCompletion($user)
    {
        \Illuminate\Support\Facades\Log::info('Checking profile completion', ['user_id' => $user->id]);
        switch ($user->role) {
            case 'KTHR_PENYULUH':
                $kthr = $user->kthr;
                $needs_completion = !$kthr || 
                       !$kthr->nama_pendamping || 
                       !$kthr->phone || 
                       !$kthr->alamat_sekretariat || 
                       !$kthr->coordinate_lat || 
                       !$kthr->coordinate_lng || 
                       !$kthr->luas_areal_ha || 
                       !$kthr->jumlah_anggota || 
                       !$kthr->jumlah_pertemuan_tahunan || 
                       !$kthr->plantSpecies()->exists();
                \Illuminate\Support\Facades\Log::info('KTHR profile completion status', [
                    'needs_completion' => $needs_completion,
                    'has_kthr' => (bool)$kthr,
                    'has_plant_species' => $kthr ? $kthr->plantSpecies()->exists() : false
                ]);
                return $needs_completion;
                
            case 'PBPHH':
                $pbphh = $user->pbphhProfile;
                return !$pbphh || !$pbphh->penanggung_jawab;
                
            default:
                return false;
        }
    }

    private function isProfileCompletionRoute($request)
    {
        $isProfileCompletion = $request->routeIs('*.profile.complete', '*.profile.complete.submit', 'logout');
        \Illuminate\Support\Facades\Log::info('Checking profile completion route', [
            'current_route' => $request->route()->getName(),
            'is_profile_completion' => $isProfileCompletion
        ]);
        return $isProfileCompletion;
    }

    private function redirectToProfileCompletion($user)
    {
        switch ($user->role) {
            case 'KTHR_PENYULUH':
                return redirect()->route('kthr.profile.complete');
            case 'PBPHH':
                return redirect()->route('pbphh.profile.complete');
        }
    }
}
