<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share pending approvals count with dinas layout
        View::composer('layouts.dinas', function ($view) {
            if (Auth::check() && Auth::user()->role === 'DINAS_PROVINSI') {
                $pendingApprovals = User::where('role', 'PBPHH')
                    ->where('approval_status', 'Pending')
                    ->count();
                
                $view->with('pendingApprovals', $pendingApprovals);
            }
        });
    }
}
