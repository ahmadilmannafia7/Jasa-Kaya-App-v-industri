<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Region;
use App\Models\Kthr;
use App\Models\PbphhProfile;
use App\Models\PermintaanKerjasama;
use App\Models\Pertemuan;
use App\Models\KesepakatanKerjasama;
use Carbon\Carbon;

class DinasController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // Executive Statistics
        $stats = [
            // Approval Statistics
            'pending_pbphh_approvals' => User::where('role', 'PBPHH')
                ->where('approval_status', 'Pending')
                ->count(),
            'total_approved_kthrs' => User::where('role', 'KTHR_PENYULUH')
                ->where('approval_status', 'Approved')
                ->count(),
            'total_approved_pbphhs' => User::where('role', 'PBPHH')
                ->where('approval_status', 'Approved')
                ->count(),

            // Partnership Statistics
            'total_partnerships' => PermintaanKerjasama::count(),
            'active_partnerships' => PermintaanKerjasama::whereIn(
                'status',
                ['Disetujui', 'Dijadwalkan', 'Menunggu Tanda Tangan']
            )->count(),
            'completed_partnerships' => PermintaanKerjasama::where('status', 'Selesai')->count(),

            // Meeting Statistics
            'total_meetings' => Pertemuan::count(),
            'completed_meetings' => Pertemuan::where('status', 'Selesai')->count(),
            'agreements_created' => KesepakatanKerjasama::count(),

            // Regional Statistics
            'total_regions' => Region::count(),
            'active_cdks' => User::where('role', 'CDK')->where('approval_status', 'Approved')->count()
        ];



        // Regional Performance
        $regionalPerformance = Region::withCount([
            'kthrs as total_kthrs',
            'pbphhs as total_pbphhs'
        ])
            ->with(['users' => function ($query) {
                $query->where('role', 'CDK')->where('approval_status', 'Approved');
            }])
            ->get()
            ->map(function ($region) {
                $partnerships = PermintaanKerjasama::whereHas('kthr.user', function ($q) use ($region) {
                    $q->where('region_id', $region->region_id);
                })->count();

                $region->total_partnerships = $partnerships;
                $region->has_cdk = $region->users->count() > 0;
                return $region;
            });



        // Recent Activities
        $recentActivities = [
            'new_registrations' => User::whereIn('role', ['KTHR_PENYULUH', 'PBPHH'])
                ->where('created_at', '>=', now()->subDays(7))
                ->with(['kthr', 'pbphhProfile'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
            'recent_partnerships' => PermintaanKerjasama::with(['kthr.user', 'pbphhProfile.user'])
                ->where('created_at', '>=', now()->subDays(7))
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
            'recent_meetings' => Pertemuan::with(['permintaanKerjasama.kthr.user', 'permintaanKerjasama.pbphhProfile.user'])
                ->where('created_at', '>=', now()->subDays(7))
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
        ];

        // Pending PBPHH Approvals for dashboard
        $pendingPbphhApprovals = User::where('role', 'PBPHH')
            ->where('approval_status', 'Pending')
            ->with(['pbphhProfile', 'region'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dinas.dashboard', compact(
            'stats',
            'regionalPerformance',
            'recentActivities',
            'pendingPbphhApprovals'
        ));
    }

    public function approvals(Request $request)
    {
        $query = User::where('role', 'PBPHH')
            ->with(['pbphhProfile', 'region']);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('approval_status', $request->status);
        } else {
            $query->where('approval_status', 'Pending');
        }

        // Filter berdasarkan region
        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }

        // Search berdasarkan email atau nama perusahaan
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('email', 'like', '%' . $request->search . '%')
                    ->orWhereHas('pbphhProfile', function ($subQ) use ($request) {
                        $subQ->where('company_name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        $users = $query->orderBy('created_at')->paginate(15);
        $regions = Region::orderBy('name')->get();

        return view('dinas.approvals', compact('users', 'regions'));
    }

    public function approve(Request $request, $id)
    {
        $user = Auth::user();

        $targetUser = User::where('user_id', $id)
            ->where('role', 'PBPHH')
            ->where('approval_status', 'Pending')
            ->firstOrFail();

        $targetUser->update([
            'approval_status' => 'Approved',
            'approved_by_user_id' => $user->user_id,
            'approved_at' => now()
        ]);

        return redirect()->back()->with('success', 'Akun PBPHH berhasil disetujui!');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $user = Auth::user();

        $targetUser = User::where('user_id', $id)
            ->where('role', 'PBPHH')
            ->where('approval_status', 'Pending')
            ->firstOrFail();

        $targetUser->update([
            'approval_status' => 'Rejected',
            'approved_by_user_id' => $user->user_id,
            'approved_at' => now(),
            'rejection_reason' => $request->rejection_reason
        ]);

        return redirect()->back()->with('success', 'Akun PBPHH berhasil ditolak!');
    }

    public function userManagement(Request $request)
    {
        $query = User::whereIn('role', ['CDK', 'DINAS_PROVINSI'])
            ->with(['region']);

        // Filter berdasarkan role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('approval_status', $request->status);
        }

        // Filter berdasarkan region
        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }

        // Search
        if ($request->filled('search')) {
            $query->where('email', 'like', '%' . $request->search . '%');
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        $regions = Region::orderBy('name')->get();

        return view('dinas.user-management', compact('users', 'regions'));
    }


    public function createUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role' => 'required|in:CDK,DINAS_PROVINSI',
            'region_id' => 'required_if:role,CDK|nullable|exists:regions,region_id'
        ]);

        User::create([
            'email' => $request->email,
            'password_hash' => Hash::make($request->password),
            'role' => $request->role,
            'region_id' => $request->region_id,
            'approval_status' => 'Approved',
            'approved_by_user_id' => Auth::id(),
            'approved_at' => now()
        ]);

        return redirect()->back()->with('success', 'Akun pengguna berhasil dibuat!');
    }


    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,' . $id . ',user_id',
            'role' => 'required|in:CDK,DINAS_PROVINSI',
            'region_id' => 'required|exists:regions,region_id',
            'approval_status' => 'required|in:Pending,Approved,Rejected'
        ]);

        $user = User::findOrFail($id);

        $updateData = [
            'email' => $request->email,
            'role' => $request->role,
            'region_id' => $request->region_id,
            'approval_status' => $request->approval_status
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8']);
            $updateData['password_hash'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->back()->with('success', 'Akun pengguna berhasil diperbarui!');
    }

    public function deleteUser($id)
    {
        $user = User::whereIn('role', ['CDK', 'DINAS_PROVINSI'])
            ->where('user_id', '!=', Auth::id())
            ->findOrFail($id);

        $user->delete();

        return redirect()->back()->with('success', 'Akun pengguna berhasil dihapus!');
    }

    public function regionManagement(Request $request)
    {
        $query = Region::withCount(['users', 'kthrs', 'pbphhs']);

        if ($request->filled('search')) {
            $query->where('region_name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('region_type')) {
            $query->where('region_type', $request->region_type);
        }

        $regions = $query->orderBy('name')->paginate(15);

        return view('dinas.region-management', compact('regions'));
    }

    public function createRegion(Request $request)
    {
        $request->validate([
            'region_name' => 'required|string|max:255',
            'region_type' => 'required|in:Provinsi,Kabupaten,Kecamatan',
            'region_code' => 'required|string|max:10|unique:regions',
            'parent_region_id' => 'nullable|exists:regions,region_id'
        ]);

        Region::create([
            'region_code' => $request->region_code,
            'name' => $request->region_name,
            'type' => $request->region_type,
            'parent_id' => $request->parent_region_id
        ]);

        return redirect()->back()->with('success', 'Wilayah berhasil ditambahkan!');
    }

    public function updateRegion(Request $request, $id)
    {
        $request->validate([
            'region_name' => 'required|string|max:255',
            'region_type' => 'required|in:Provinsi,Kabupaten,Kecamatan',
            'region_code' => 'required|string|max:10|unique:regions,region_code,' . $id . ',region_id',
            'parent_region_id' => 'nullable|exists:regions,region_id'
        ]);

        $region = Region::findOrFail($id);
        $region->update([
            'region_code' => $request->region_code,
            'name' => $request->region_name,
            'type' => $request->region_type,
            'parent_id' => $request->parent_region_id
        ]);

        return redirect()->back()->with('success', 'Wilayah berhasil diperbarui!');
    }

    public function deleteRegion($id)
    {
        $region = Region::findOrFail($id);

        // Check if region has users or child regions
        if ($region->users()->count() > 0 || $region->children()->count() > 0) {
            return redirect()->back()->with('error', 'Wilayah tidak dapat dihapus karena masih memiliki pengguna atau sub-wilayah!');
        }

        $region->delete();

        return redirect()->back()->with('success', 'Wilayah berhasil dihapus!');
    }

    // Detail view methods
    public function registrationDetail($id)
    {
        $user = User::with([
            'kthr.region',
            'kthr.plantSpecies',
            'pbphhProfile.materialNeeds',
            'region'
        ])
            ->whereIn('role', ['KTHR_PENYULUH', 'PBPHH'])
            ->findOrFail($id);

        return view('dinas.registration-detail', compact('user'));
    }

    public function partnershipDetail($id)
    {
        $partnership = PermintaanKerjasama::with([
            'kthr.user.region',
            'pbphhProfile.user.region',
            'pbphhProfile.materialNeeds',
            'pertemuans',
            'kesepakatanKerjasama'
        ])->findOrFail($id);

        return view('dinas.partnership-detail', compact('partnership'));
    }

    public function meetingDetail($id)
    {
        $meeting = Pertemuan::with([
            'permintaanKerjasama.kthr.user.region',
            'permintaanKerjasama.pbphhProfile.user.region'
        ])->findOrFail($id);

        return view('dinas.meeting-detail', compact('meeting'));
    }

    public function monitoring(Request $request)
    {
        // Overall Statistics
        $overallStats = [
            'total_entities' => User::whereIn('role', ['KTHR_PENYULUH', 'PBPHH'])
                ->where('approval_status', 'Approved')
                ->count(),
            'total_partnerships' => PermintaanKerjasama::count(),
            'success_rate' => $this->calculateSuccessRate(),
            'total_wood_potential' => Kthr::sum('luas_areal_ha'),
            'monthly_demand' => PbphhProfile::whereHas('materialNeeds')->get()
                ->sum(function ($pbphh) {
                    return $pbphh->materialNeeds->sum('volume_kebutuhan_m3_per_bulan');
                })
        ];

        // Regional Breakdown
        $regionalData = Region::withCount([
            'kthrs as total_kthrs',
            'pbphhs as total_pbphhs'
        ])
            ->get()
            ->map(function ($region) {
                $partnerships = PermintaanKerjasama::whereHas('kthr.user', function ($q) use ($region) {
                    $q->where('region_id', $region->region_id);
                })->count();

                $completedPartnerships = PermintaanKerjasama::whereHas('kthr.user', function ($q) use ($region) {
                    $q->where('region_id', $region->region_id);
                })->where('status', 'Selesai')->count();

                $region->total_partnerships = $partnerships;
                $region->completed_partnerships = $completedPartnerships;
                $region->success_rate = $partnerships > 0 ? round(($completedPartnerships / $partnerships) * 100, 1) : 0;

                return $region;
            });

        // Partnership Status Distribution
        $partnershipStatus = PermintaanKerjasama::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Monthly Trends
        $monthlyTrends = PermintaanKerjasama::selectRaw('
                DATE_FORMAT(created_at, "%Y-%m") as month,
                COUNT(*) as total,
                SUM(CASE WHEN status = "Selesai" THEN 1 ELSE 0 END) as completed
            ')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('dinas.monitoring', compact(
            'overallStats',
            'regionalData',
            'partnershipStatus',
            'monthlyTrends'
        ));
    }

    public function reports(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->input('date_to', now()->format('Y-m-d'));
        $reportType = $request->input('report_type', 'summary');

        $reportData = [];

        switch ($reportType) {
            case 'summary':
                $reportData = $this->generateSummaryReport($dateFrom, $dateTo);
                break;
            case 'regional':
                $reportData = $this->generateRegionalReport($dateFrom, $dateTo);
                break;
            case 'partnership':
                $reportData = $this->generatePartnershipReport($dateFrom, $dateTo);
                break;
            case 'financial':
                $reportData = $this->generateFinancialReport($dateFrom, $dateTo);
                break;
        }

        return view('dinas.reports', compact('reportData', 'dateFrom', 'dateTo', 'reportType'));
    }

    private function calculateSuccessRate()
    {
        $total = PermintaanKerjasama::count();
        $completed = PermintaanKerjasama::where('status', 'Selesai')->count();

        return $total > 0 ? round(($completed / $total) * 100, 1) : 0;
    }

    private function generateSummaryReport($dateFrom, $dateTo)
    {
        return [
            'registrations' => [
                'kthr' => User::where('role', 'KTHR_PENYULUH')
                    ->whereBetween('created_at', [$dateFrom, $dateTo])
                    ->count(),
                'pbphh' => User::where('role', 'PBPHH')
                    ->whereBetween('created_at', [$dateFrom, $dateTo])
                    ->count()
            ],
            'approvals' => [
                'kthr_approved' => User::where('role', 'KTHR_PENYULUH')
                    ->where('approval_status', 'Approved')
                    ->whereBetween('approved_at', [$dateFrom, $dateTo])
                    ->count(),
                'pbphh_approved' => User::where('role', 'PBPHH')
                    ->where('approval_status', 'Approved')
                    ->whereBetween('approved_at', [$dateFrom, $dateTo])
                    ->count()
            ],
            'partnerships' => [
                'created' => PermintaanKerjasama::whereBetween('created_at', [$dateFrom, $dateTo])->count(),
                'completed' => PermintaanKerjasama::where('status', 'Selesai')
                    ->whereBetween('updated_at', [$dateFrom, $dateTo])
                    ->count()
            ],
            'meetings' => [
                'scheduled' => Pertemuan::whereBetween('created_at', [$dateFrom, $dateTo])->count(),
                'completed' => Pertemuan::where('status', 'Selesai')
                    ->whereBetween('updated_at', [$dateFrom, $dateTo])
                    ->count()
            ]
        ];
    }

    private function generateRegionalReport($dateFrom, $dateTo)
    {
        return Region::withCount([
            'kthrs as total_kthrs',
            'pbphhs as total_pbphhs'
        ])
            ->get()
            ->map(function ($region) use ($dateFrom, $dateTo) {
                $partnerships = PermintaanKerjasama::whereHas('kthr.user', function ($q) use ($region) {
                    $q->where('region_id', $region->region_id);
                })
                    ->whereBetween('created_at', [$dateFrom, $dateTo])
                    ->count();

                $meetings = Pertemuan::whereHas('permintaanKerjasama.kthr.user', function ($q) use ($region) {
                    $q->where('region_id', $region->region_id);
                })
                    ->whereBetween('created_at', [$dateFrom, $dateTo])
                    ->count();

                $region->period_partnerships = $partnerships;
                $region->period_meetings = $meetings;

                return $region;
            });
    }

    private function generatePartnershipReport($dateFrom, $dateTo)
    {
        return [
            'by_status' => PermintaanKerjasama::selectRaw('status, COUNT(*) as count')
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray(),
            'by_month' => PermintaanKerjasama::selectRaw('
                    DATE_FORMAT(created_at, "%Y-%m") as month,
                    COUNT(*) as total,
                    SUM(CASE WHEN status = "Selesai" THEN 1 ELSE 0 END) as completed
                ')
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->groupBy('month')
                ->orderBy('month')
                ->get(),
            'top_regions' => Region::withCount([
                'kthrs' => function ($query) use ($dateFrom, $dateTo) {
                    $query->whereHas('permintaanKerjasama', function ($q) use ($dateFrom, $dateTo) {
                        $q->whereBetween('created_at', [$dateFrom, $dateTo]);
                    });
                }
            ])
                ->orderBy('kthrs_count', 'desc')
                ->limit(10)
                ->get()
        ];
    }

    private function generateFinancialReport($dateFrom, $dateTo)
    {
        $agreements = KesepakatanKerjasama::whereHas('pertemuan', function ($q) use ($dateFrom, $dateTo) {
            $q->whereBetween('actual_end_time', [$dateFrom, $dateTo]);
        })
            ->with(['pertemuan.permintaanKerjasama'])
            ->get();

        return [
            'total_agreements' => $agreements->count(),
            'average_price' => $agreements->avg('harga_per_m3'),
            'price_range' => [
                'min' => $agreements->min('harga_per_m3'),
                'max' => $agreements->max('harga_per_m3')
            ],
            'total_volume' => $agreements->sum(function ($agreement) {
                return $agreement->pertemuan->permintaanKerjasama->volume_kebutuhan_m3_per_bulan ?? 0;
            }),
            'estimated_value' => $agreements->sum(function ($agreement) {
                $volume = $agreement->pertemuan->permintaanKerjasama->volume_kebutuhan_m3_per_bulan ?? 0;
                return $volume * $agreement->harga_per_m3 * $agreement->durasi_kontrak_bulan;
            })
        ];
    }
}
