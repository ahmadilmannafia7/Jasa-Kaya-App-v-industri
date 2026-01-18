<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AllowRegisterAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow access if user is not authenticated (guest)
        if (!Auth::check()) {
            return $next($request);
        }
        
        // Allow access if user is authenticated and has rejected status
        $user = Auth::user();
        if ($user && $user->approval_status === 'Rejected') {
            return $next($request);
        }
        
        // Redirect authenticated users with other statuses to their appropriate dashboard
        switch ($user->approval_status) {
            case 'Pending':
                return redirect()->route('pending.approval');
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
                return redirect()->route('kthr.dashboard');
            case 'PBPHH':
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