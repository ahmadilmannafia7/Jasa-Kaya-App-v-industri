<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KthrController;
use App\Http\Controllers\PbphhController;
use App\Http\Controllers\CdkController;
use App\Http\Controllers\DinasController;

// Homepage & Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/process', [HomeController::class, 'process'])->name('process');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
});

// Registration routes - accessible by guests and rejected users
Route::middleware('allow.register')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes
Route::middleware(['web', 'auth', 'check.approval'])->group(function () {

    // KTHR/Penyuluh Dashboard
    Route::prefix('kthr')->name('kthr.')->group(function () {

        // Dashboard KTHR
        Route::get('/dashboard', [KthrController::class, 'dashboard'])->name('dashboard');

        // Lengkapi profil KTHR
        Route::get('/profile/complete', [KthrController::class, 'completeProfile'])->name('profile.complete');
        Route::post('/profile/complete', [KthrController::class, 'storeProfile'])->name('profile.complete.submit');

        // Lihat & update profil
        Route::get('/profile', [KthrController::class, 'profile'])->name('profile');
        Route::put('/profile', [KthrController::class, 'updateProfile'])->name('profile.update');
        
        // Manajemen Data Tanaman
        Route::post('/plants', [KthrController::class, 'storePlant'])->name('plants.store');
        Route::put('/plants/{id}', [KthrController::class, 'updatePlant'])->name('plants.update');
        Route::delete('/plants/{id}', [KthrController::class, 'deletePlant'])->name('plants.delete');

        // Permintaan kemitraan
        Route::get('/requests', [KthrController::class, 'requests'])->name('requests');
        Route::post('/requests/{id}/respond', [KthrController::class, 'respondToRequest'])->name('requests.respond');
        Route::post('/requests/{id}/cancel', [KthrController::class, 'cancelRequest'])->name('requests.cancel');
        Route::get('/company-profile/{pbphhId}', [KthrController::class, 'getCompanyProfile'])->name('company.profile');

        // Kemitraan aktif & riwayat
        Route::get('/partnerships', [KthrController::class, 'partnerships'])->name('partnerships');
        Route::post('/partnerships/{id}/sign', [KthrController::class, 'signAgreement'])->name('partnerships.sign');
    });

    // PBPHH/Industry Dashboard
    Route::prefix('pbphh')->name('pbphh.')->group(function () {
        Route::get('/dashboard', [PbphhController::class, 'dashboard'])->name('dashboard');

        // Lengkapi profil (GET & POST)
        Route::get('/profile/complete', [PbphhController::class, 'completeProfile'])->name('profile.complete');
        Route::post('/profile/complete', [PbphhController::class, 'storeProfile'])->name('profile.complete.submit');// <-- DITAMBAHKAN NAMA ROUTE

        // Profil
        Route::get('/profile', [PbphhController::class, 'profile'])->name('profile');
        Route::put('/profile', [PbphhController::class, 'updateProfile'])->name('profile');

        // Eksplorasi dan Kemitraan
        Route::get('/explore', [PbphhController::class, 'exploreKthr'])->name('explore');
        Route::get('/kthr/{id}/detail', [PbphhController::class, 'getKthrDetail'])->name('kthr.detail');
        Route::post('/request-partnership', [PbphhController::class, 'requestPartnership'])->name('request.partnership');
        Route::get('/partnerships', [PbphhController::class, 'partnerships'])->name('partnerships');
        Route::post('/partnerships/{id}/sign', [PbphhController::class, 'signAgreement'])->name('partnerships.sign');

        // Kebutuhan Material
        Route::get('/material-needs', [PbphhController::class, 'manageMaterialNeeds'])->name('material-needs');
        Route::post('/material-needs', [PbphhController::class, 'storeMaterialNeed'])->name('material-needs.store');
        Route::put('/material-needs/{id}', [PbphhController::class, 'updateMaterialNeed'])->name('material-needs.update');
        Route::delete('/material-needs/{id}', [PbphhController::class, 'deleteMaterialNeed'])->name('material-needs.delete');

        // Kemitraan PBPHH-to-PBPHH (Kolaborasi Industri)
        Route::get('/industry-partners', [PbphhController::class, 'exploreIndustryPartners'])->name('industry.partners');
        Route::get('/industry-partners/{id}/detail', [PbphhController::class, 'getIndustryPartnerDetail'])->name('industry.partner.detail');
        Route::post('/industry-partners/request', [PbphhController::class, 'requestIndustryPartnership'])->name('industry.request');
        Route::get('/industry-partnerships', [PbphhController::class, 'industryPartnerships'])->name('industry.partnerships');
        Route::get('/industry-partnerships/requests', [PbphhController::class, 'industryPartnershipRequests'])->name('industry.partnership.requests');
        Route::post('/industry-partnerships/{id}/respond', [PbphhController::class, 'respondToIndustryPartnership'])->name('industry.partnership.respond');
        Route::post('/industry-partnerships/{id}/cancel', [PbphhController::class, 'cancelIndustryPartnership'])->name('industry.partnership.cancel');
        Route::put('/industry-partnerships/{id}/negotiate', [PbphhController::class, 'negotiateIndustryPartnership'])->name('industry.partnership.negotiate');
        Route::post('/industry-partnerships/{id}/finalize', [PbphhController::class, 'finalizeIndustryPartnership'])->name('industry.partnership.finalize');
    });


    // CDK Dashboard
    Route::prefix('cdk')->name('cdk.')->group(function () {
        Route::get('/dashboard', [CdkController::class, 'dashboard'])->name('dashboard');
        Route::get('/approvals', [CdkController::class, 'approvals'])->name('approvals');
        Route::post('/approvals/{id}/approve', [CdkController::class, 'approve'])->name('approvals.approve');
        Route::post('/approvals/{id}/reject', [CdkController::class, 'reject'])->name('approvals.reject');
        Route::get('/meetings', [CdkController::class, 'meetings'])->name('meetings');
    Route::get('/meetings/{id}/details', [CdkController::class, 'getMeetingDetails'])->name('meetings.details');
    Route::post('/meetings/schedule', [CdkController::class, 'scheduleMeeting'])->name('meetings.schedule');
    Route::put('/meetings/{id}/update', [CdkController::class, 'updateMeeting'])->name('meetings.update');
    Route::post('/meetings/{id}/start', [CdkController::class, 'startMeeting'])->name('meetings.start');
    Route::post('/meetings/{id}/cancel', [CdkController::class, 'cancelMeeting'])->name('meetings.cancel');
    Route::post('/meetings/{id}/complete', [CdkController::class, 'completeMeeting'])->name('meetings.complete');
    Route::delete('/meetings/{id}', [CdkController::class, 'deleteMeeting'])->name('meetings.delete');
    Route::delete('/agreements/{id}', [CdkController::class, 'deleteAgreement'])->name('agreements.delete');
        Route::get('/monitoring', [CdkController::class, 'monitoring'])->name('monitoring');
        Route::get('/reports', [CdkController::class, 'reports'])->name('reports');
    });

    // Dinas Provinsi Dashboard
    Route::prefix('dinas')->name('dinas.')->group(function () {
        Route::get('/dashboard', [DinasController::class, 'dashboard'])->name('dashboard');
        Route::get('/approvals', [DinasController::class, 'approvals'])->name('approvals');
        Route::post('/approvals/{id}/approve', [DinasController::class, 'approve'])->name('approvals.approve');
        Route::post('/approvals/{id}/reject', [DinasController::class, 'reject'])->name('approvals.reject');

        // User Management (Superadmin)
        Route::get('/user-management', [DinasController::class, 'userManagement'])->name('user-management');
        Route::post('/user-management', [DinasController::class, 'createUser'])->name('user-management.create');
        Route::put('/user-management/{id}', [DinasController::class, 'updateUser'])->name('user-management.update');
        Route::delete('/user-management/{id}', [DinasController::class, 'deleteUser'])->name('user-management.delete');

        // Region Management
        Route::get('/region-management', [DinasController::class, 'regionManagement'])->name('region-management');
        Route::post('/region-management', [DinasController::class, 'createRegion'])->name('region-management.create');
        Route::put('/region-management/{id}', [DinasController::class, 'updateRegion'])->name('region-management.update');
        Route::delete('/region-management/{id}', [DinasController::class, 'deleteRegion'])->name('region-management.delete');

        // Detail views
        Route::get('/registration-detail/{id}', [DinasController::class, 'registrationDetail'])->name('registration.detail');
        Route::get('/partnership-detail/{id}', [DinasController::class, 'partnershipDetail'])->name('partnership.detail');
        Route::get('/meeting-detail/{id}', [DinasController::class, 'meetingDetail'])->name('meeting.detail');

        // Monitoring & Reports
        Route::get('/monitoring', [DinasController::class, 'monitoring'])->name('monitoring');
        Route::get('/reports', [DinasController::class, 'reports'])->name('reports');
    });
});

// Status Check Routes
Route::middleware('auth')->group(function () {
    Route::get('/pending-approval', [AuthController::class, 'pendingApproval'])->name('pending.approval');
    Route::get('/rejected', [AuthController::class, 'rejected'])->name('rejected');
});
