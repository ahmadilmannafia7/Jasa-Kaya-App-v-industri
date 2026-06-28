<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Kthr;
use App\Models\Tptkb;
use App\Models\PbphhProfile;
use App\Models\PermintaanKerjasama;
use App\Models\Pertemuan;
use App\Models\KesepakatanKerjasama;
use App\Models\Region;
use Illuminate\Support\Facades\DB;

class CdkController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $region = $this->getRegionOrAbort($user);

        // Statistik untuk dashboard
        $stats = [
            'pending_kthr_approvals' => User::where('region_id', $region->region_id)
                ->where('role', 'KTHR_PENYULUH')
                ->where('approval_status', 'Pending')
                ->count(),

            'pending_tptkb_approvals' => User::where('region_id', $region->region_id)
                ->where('role', 'TPTKB')
                ->where('approval_status', 'Pending')
                ->count(),

            'pending_pbphh_approvals' => User::where('region_id', $region->region_id)
                ->where('role', 'PBPHH')
                ->where('approval_status', 'Pending')
                ->count(),

            'partnerships_need_facilitation' => PermintaanKerjasama::where(function ($q) use ($region) {
                $q->whereHas('kthr.user', function ($query) use ($region) {
                    $query->where('region_id', $region->region_id);
                })->orWhereHas('tptkb.user', function ($query) use ($region) {
                    $query->where('region_id', $region->region_id);
                });
            })
                ->where('status', 'Disetujui')
                ->count(),

            'scheduled_meetings' => Pertemuan::whereHas('permintaanKerjasama', function ($q) use ($region) {
                $q->whereHas('kthr.user', function ($query) use ($region) {
                    $query->where('region_id', $region->region_id);
                })->orWhereHas('tptkb.user', function ($query) use ($region) {
                    $query->where('region_id', $region->region_id);
                });
            })
                ->where('status', 'Dijadwalkan')
                ->where('scheduled_time', '>', now())
                ->count(),

            'active_kthrs' => User::where('region_id', $region->region_id)
                ->where('role', 'KTHR_PENYULUH')
                ->where('approval_status', 'Approved')
                ->count(),

            'active_tptkbs' => User::where('region_id', $region->region_id)
                ->where('role', 'TPTKB')
                ->where('approval_status', 'Approved')
                ->count(),

            'active_pbphhs' => User::where('region_id', $region->region_id)
                ->where('role', 'PBPHH')
                ->where('approval_status', 'Approved')
                ->count(),
        ];

        // Tugas mendesak
        $urgentTasks = [

            'pending_approvals' => User::where('region_id', $region->region_id)
                ->whereIn('role', ['KTHR_PENYULUH', 'TPTKB'])
                ->where('approval_status', 'Pending')
                ->with(['kthr', 'tptkb'])
                ->orderBy('created_at')
                ->limit(5)
                ->get(),

            'partnerships_to_facilitate' => PermintaanKerjasama::where(function ($q) use ($region) {

                $q->whereHas('kthr.user', function ($query) use ($region) {
                    $query->where('region_id', $region->region_id);
                })

                    ->orWhereHas('tptkb.user', function ($query) use ($region) {
                        $query->where('region_id', $region->region_id);
                    });

            })
                ->where('status', 'Disetujui')
                ->with([
                    'kthr.user',
                    'tptkb.user',
                    'pbphhProfile.user'
                ])
                ->orderBy('updated_at')
                ->limit(5)
                ->get(),

            'upcoming_meetings' => Pertemuan::whereHas('permintaanKerjasama', function ($q) use ($region) {

                $q->whereHas('kthr.user', function ($query) use ($region) {
                    $query->where('region_id', $region->region_id);
                })

                    ->orWhereHas('tptkb.user', function ($query) use ($region) {
                        $query->where('region_id', $region->region_id);
                    });

            })
                ->where('status', 'Dijadwalkan')
                ->where('scheduled_time', '>', now())
                ->where('scheduled_time', '<', now()->addDays(7))
                ->with([
                    'permintaanKerjasama.kthr.user',
                    'permintaanKerjasama.tptkb.user',
                    'permintaanKerjasama.pbphhProfile.user'
                ])
                ->orderBy('scheduled_time')
                ->limit(5)
                ->get()

        ];

        return view('cdk.dashboard', compact('region', 'stats', 'urgentTasks'));
    }

    public function approvals(Request $request)
    {
        $user = Auth::user();
        $region = $this->getRegionOrAbort($user);

        $query = User::where('region_id', $region->region_id)
            ->whereIn('role', ['KTHR_PENYULUH', 'TPTKB'])
            ->with(['kthr', 'tptkb']);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('approval_status', $request->status);
        } else {
            $query->where('approval_status', 'Pending');
        }

        // Filter berdasarkan role (opsional)
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Search berdasarkan email
        if ($request->filled('search')) {
            $query->where('email', 'like', '%' . $request->search . '%');
        }

        $users = $query->orderBy('created_at')->paginate(15);

        return view('cdk.approvals', compact('users', 'region'));
    }



    public function approve(Request $request, $id)
    {
        $user = Auth::user();
        $region = $this->getRegionOrAbort($user);

        $targetUser = User::where('user_id', $id)
            ->where('region_id', $region->region_id)
            ->where('approval_status', 'Pending')
            ->firstOrFail();

        $targetUser->update([
            'approval_status' => 'Approved',
            'approved_by_user_id' => $user->user_id,
            'approved_at' => now()
        ]);

        $userType = match ($targetUser->role) {
            'KTHR_PENYULUH' => 'KTHR',
            'TPTKB' => 'TPTKB',
            'PBPHH' => 'PBPHH',
            default => 'User'
        };

        return redirect()->back()
            ->with('success', "Akun {$userType} berhasil disetujui!");
    }


    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $user = Auth::user();
        $region = $this->getRegionOrAbort($user);

        $targetUser = User::where('user_id', $id)
            ->where('region_id', $region->region_id)
            ->where('approval_status', 'Pending')
            ->firstOrFail();

        $targetUser->update([
            'approval_status' => 'Rejected',
            'approved_by_user_id' => $user->user_id,
            'approved_at' => now(),
            'rejection_reason' => $request->rejection_reason
        ]);

        $userType = match ($targetUser->role) {
            'KTHR_PENYULUH' => 'KTHR',
            'TPTKB' => 'TPTKB',
            'PBPHH' => 'PBPHH',
            default => 'User'
        };

        return redirect()->back()
            ->with('success', "Akun {$userType} berhasil ditolak!");
    }

    public function meetings(Request $request)
    {
        $user = Auth::user();
        $region = $this->getRegionOrAbort($user);

        $query = Pertemuan::whereHas('permintaanKerjasama', function ($q) use ($region) {

            $q->whereHas('kthr.user', function ($subQ) use ($region) {
                $subQ->where('region_id', $region->region_id);
            })

                ->orWhereHas('tptkb.user', function ($subQ) use ($region) {
                    $subQ->where('region_id', $region->region_id);
                });

        })->with([
                    'permintaanKerjasama.kthr.user',
                    'permintaanKerjasama.tptkb.user',
                    'permintaanKerjasama.pbphhProfile.user',
                    'kesepakatan'
                ]);

        // Search
        if ($request->filled('search')) {
            $searchTerm = $request->search;

            $query->where(function ($q) use ($searchTerm) {

                $q->whereHas('permintaanKerjasama.kthr', function ($subQ) use ($searchTerm) {
                    $subQ->where('kthr_name', 'like', "%{$searchTerm}%");
                })

                    ->orWhereHas('permintaanKerjasama.tptkb', function ($subQ) use ($searchTerm) {
                        $subQ->where('tptkb_name', 'like', "%{$searchTerm}%");
                    })

                    ->orWhereHas('permintaanKerjasama.pbphhProfile', function ($subQ) use ($searchTerm) {
                        $subQ->where('company_name', 'like', "%{$searchTerm}%");
                    })

                    ->orWhere('location', 'like', "%{$searchTerm}%")
                    ->orWhere('meeting_notes', 'like', "%{$searchTerm}%");
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter method
        if ($request->filled('method')) {
            $query->where('method', $request->method);
        }

        // Filter tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('scheduled_time', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('scheduled_time', '<=', $request->date_to);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'scheduled_time');
        $sortOrder = $request->get('sort_order', 'desc');

        if (
            in_array($sortBy, [
                'scheduled_time',
                'status',
                'method',
                'created_at'
            ])
        ) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('scheduled_time', 'desc');
        }

        // Export Excel
        if ($request->has('export') && $request->export === 'excel') {
            return $this->exportMeetingsToExcel($query);
        }

        // AJAX Auto Refresh
        if ($request->ajax()) {
            $lastCheck = $request->get('last_check', 0);

            $hasUpdates = $query->where(
                'updated_at',
                '>',
                date('Y-m-d H:i:s', $lastCheck / 1000)
            )->exists();

            return response()->json([
                'hasUpdates' => $hasUpdates
            ]);
        }

        $meetings = $query->paginate(15)->withQueryString();

        // Permintaan yang perlu dijadwalkan
        $needScheduling = PermintaanKerjasama::where(function ($q) use ($region) {

            $q->whereHas('kthr.user', function ($subQ) use ($region) {
                $subQ->where('region_id', $region->region_id);
            })

                ->orWhereHas('tptkb.user', function ($subQ) use ($region) {
                    $subQ->where('region_id', $region->region_id);
                });

        })
            ->whereIn('status', [
                'Disetujui',
                'Menunggu Jadwal'
            ])
            ->whereDoesntHave('pertemuan')
            ->with([
                'kthr.user',
                'tptkb.user',
                'pbphhProfile.user'
            ])
            ->get();

        return view(
            'cdk.meetings',
            compact(
                'meetings',
                'needScheduling',
                'region'
            )
        );
    }

    private function exportMeetingsToExcel($query)
    {
        $meetings = $query->get();

        $csvData = [];
        $csvData[] = ['No', 'KTHR', 'PBPHH', 'Tanggal & Waktu', 'Jenis', 'Lokasi', 'Status', 'Kesepakatan', 'Catatan'];

        foreach ($meetings as $index => $meeting) {
            $csvData[] = [
                $index + 1,
                $meeting->permintaanKerjasama->kthr->kthr_name ?? 'N/A',
                $meeting->permintaanKerjasama->pbphhProfile->company_name ?? 'N/A',
                $meeting->scheduled_time ? $meeting->scheduled_time->format('d/m/Y H:i') : 'N/A',
                $meeting->method ?? 'N/A',
                $meeting->location ?? 'N/A',
                $meeting->status ?? 'N/A',
                $meeting->kesepakatan ? 'Ada' : 'Belum',
                $meeting->meeting_notes ?? 'N/A'
            ];
        }

        $filename = 'daftar_pertemuan_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($csvData) {
            $file = fopen('php://output', 'w');

            // Add BOM for UTF-8
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            foreach ($csvData as $row) {
                fputcsv($file, $row, ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function getMeetingDetails($id)
    {
        $user = Auth::user();
        $region = $this->getRegionOrAbort($user);

        $meeting = Pertemuan::where('meeting_id', $id)
            ->whereHas('permintaanKerjasama', function ($q) use ($region) {

                $q->whereHas('kthr.user', function ($subQ) use ($region) {
                    $subQ->where('region_id', $region->region_id);
                })

                    ->orWhereHas('tptkb.user', function ($subQ) use ($region) {
                        $subQ->where('region_id', $region->region_id);
                    });

            })
            ->with([
                'permintaanKerjasama.kthr.user',
                'permintaanKerjasama.tptkb.user',
                'permintaanKerjasama.pbphhProfile.user',
                'kesepakatan',
                'scheduledBy'
            ])
            ->first();

        if (!$meeting) {
            return response()->json([
                'success' => false,
                'message' => 'Pertemuan tidak ditemukan'
            ], 404);
        }

        $partnerName = null;
        $partnerEmail = null;
        $partnerType = null;

        if ($meeting->permintaanKerjasama->kthr) {
            $partnerName = $meeting->permintaanKerjasama->kthr->kthr_name;
            $partnerEmail = $meeting->permintaanKerjasama->kthr->user->email ?? null;
            $partnerType = 'KTHR';
        } elseif ($meeting->permintaanKerjasama->tptkb) {
            $partnerName = $meeting->permintaanKerjasama->tptkb->tptkb_name;
            $partnerEmail = $meeting->permintaanKerjasama->tptkb->user->email ?? null;
            $partnerType = 'TPTKB';
        }

        $meetingData = [
            'meeting_id' => $meeting->meeting_id,

            'partner_type' => $partnerType,
            'partner_name' => $partnerName,
            'partner_email' => $partnerEmail,

            'pbphh_name' => $meeting->permintaanKerjasama->pbphhProfile->company_name,
            'pbphh_email' => $meeting->permintaanKerjasama->pbphhProfile->user->email,

            'scheduled_time' => $meeting->scheduled_time->format('d/m/Y H:i'),
            'scheduled_time_human' => $meeting->scheduled_time->diffForHumans(),

            'method' => $meeting->method,
            'location' => $meeting->location,
            'status' => $meeting->status,
            'status_badge' => $this->getStatusBadgeClass($meeting->status),

            'meeting_notes' => $meeting->meeting_notes,
            'meeting_summary' => $meeting->meeting_summary,

            'scheduled_by' => $meeting->scheduledBy
                ? $meeting->scheduledBy->email
                : null,

            'actual_start_time' => $meeting->actual_start_time
                ? $meeting->actual_start_time->format('d/m/Y H:i')
                : null,

            'actual_end_time' => $meeting->actual_end_time
                ? $meeting->actual_end_time->format('d/m/Y H:i')
                : null,

            'kesepakatan' => null
        ];

        if ($meeting->kesepakatan) {
            $meetingData['kesepakatan'] = [
                'agreed_price_per_m3' => number_format(
                    $meeting->kesepakatan->agreed_price_per_m3,
                    0,
                    ',',
                    '.'
                ),
                'payment_mechanism' => $meeting->kesepakatan->payment_mechanism,
                'delivery_schedule' => $meeting->kesepakatan->delivery_schedule,
                'other_notes' => $meeting->kesepakatan->other_notes,
                'durasi_kontrak_bulan' => $meeting->kesepakatan->durasi_kontrak_bulan ?? 'Tidak ditentukan',
                'final_document_path' => $meeting->kesepakatan->final_document_path
            ];
        }

        return response()->json([
            'success' => true,
            'meeting' => $meetingData
        ]);
    }

    private function getStatusBadgeClass($status)
    {
        switch ($status) {
            case 'Dijadwalkan':
                return 'bg-primary';
            case 'Berlangsung':
                return 'bg-warning';
            case 'Selesai':
                return 'bg-success';
            case 'Dibatalkan':
                return 'bg-danger';
            default:
                return 'bg-secondary';
        }
    }

    /**
     * Menghapus kesepakatan kerjasama
     * 
     * @param int $id ID kesepakatan kerjasama
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAgreement($id)
    {
        $user = Auth::user();
        $region = $this->getRegionOrAbort($user);

        $agreement = KesepakatanKerjasama::where('agreement_id', $id)
            ->whereHas('pertemuan.permintaanKerjasama', function ($q) use ($region) {

                $q->whereHas('kthr.user', function ($subQ) use ($region) {
                    $subQ->where('region_id', $region->region_id);
                })

                    ->orWhereHas('tptkb.user', function ($subQ) use ($region) {
                        $subQ->where('region_id', $region->region_id);
                    });

            })
            ->first();

        if (!$agreement) {
            return redirect()->back()
                ->with('error', 'Kesepakatan kerjasama tidak ditemukan!');
        }

        $meetingId = $agreement->meeting_id;

        DB::beginTransaction();

        try {

            // Hapus file dokumen jika ada
            if ($agreement->final_document_path) {
                Storage::delete('public/' . $agreement->final_document_path);
            }

            // Hapus kesepakatan
            $agreement->delete();

            // Kembalikan status pertemuan
            Pertemuan::where('meeting_id', $meetingId)->update([
                'status' => 'Berlangsung'
            ]);

            // Kembalikan status permintaan kerjasama
            PermintaanKerjasama::whereHas('pertemuan', function ($q) use ($meetingId) {
                $q->where('meeting_id', $meetingId);
            })->update([
                        'status' => 'Disetujui'
                    ]);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Kesepakatan kerjasama berhasil dihapus!');

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus pertemuan
     * 
     * @param int $id ID pertemuan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteMeeting($id)
    {
        $user = Auth::user();
        $region = $this->getRegionOrAbort($user);

        $meeting = Pertemuan::where('meeting_id', $id)
            ->whereHas('permintaanKerjasama', function ($q) use ($region) {

                $q->whereHas('kthr.user', function ($subQ) use ($region) {
                    $subQ->where('region_id', $region->region_id);
                })

                    ->orWhereHas('tptkb.user', function ($subQ) use ($region) {
                        $subQ->where('region_id', $region->region_id);
                    });

            })
            ->with('kesepakatan')
            ->first();

        if (!$meeting) {
            return redirect()->back()
                ->with('error', 'Pertemuan tidak ditemukan!');
        }

        // Tidak boleh menghapus pertemuan yang sudah memiliki kesepakatan
        if ($meeting->kesepakatan) {
            return redirect()->back()->with(
                'error',
                'Pertemuan tidak dapat dihapus karena memiliki kesepakatan kerjasama. Hapus kesepakatan terlebih dahulu.'
            );
        }

        $requestId = $meeting->request_id;

        DB::beginTransaction();

        try {

            // Hapus pertemuan
            $meeting->delete();

            // Kembalikan status permintaan kerjasama
            PermintaanKerjasama::where('request_id', $requestId)
                ->update([
                    'status' => 'Disetujui'
                ]);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Pertemuan berhasil dihapus!');

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function scheduleMeeting(Request $request)
    {
        $request->validate([
            'request_id' => 'required|exists:permintaan_kerjasama,request_id',
            'scheduled_time' => 'required|date|after:now',
            'meeting_type' => 'required|in:online,lapangan',
            'location' => 'required|string|max:500',
            'meeting_notes' => 'nullable|string'
        ]);

        $user = Auth::user();
        $region = $this->getRegionOrAbort($user);

        $permintaan = PermintaanKerjasama::where('request_id', $request->request_id)

            ->where(function ($q) use ($region) {

                $q->whereHas('kthr.user', function ($subQ) use ($region) {
                    $subQ->where('region_id', $region->region_id);
                })

                    ->orWhereHas('tptkb.user', function ($subQ) use ($region) {
                        $subQ->where('region_id', $region->region_id);
                    });

            })

            ->whereIn('status', [
                'Disetujui',
                'Menunggu Jadwal'
            ])
            ->firstOrFail();

        DB::beginTransaction();

        try {

            Pertemuan::create([
                'request_id' => $permintaan->request_id,
                'scheduled_by_user_id' => $user->user_id,
                'scheduled_time' => $request->scheduled_time,
                'method' => $request->meeting_type,
                'location' => $request->location,
                'meeting_notes' => $request->meeting_notes,
                'status' => 'Dijadwalkan'
            ]);

            $permintaan->update([
                'status' => 'Dijadwalkan'
            ]);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Pertemuan berhasil dijadwalkan!');

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateMeeting(Request $request, $id)
    {
        $request->validate([
            'scheduled_time' => 'required|date|after:now',
            'meeting_type' => 'required|in:online,lapangan',
            'location' => 'required|string|max:500',
            'meeting_notes' => 'nullable|string'
        ]);

        $user = Auth::user();
        $region = $this->getRegionOrAbort($user);

        $meeting = Pertemuan::where('meeting_id', $id)

            ->whereHas('permintaanKerjasama', function ($q) use ($region) {

                $q->whereHas('kthr.user', function ($subQ) use ($region) {
                    $subQ->where('region_id', $region->region_id);
                })

                    ->orWhereHas('tptkb.user', function ($subQ) use ($region) {
                        $subQ->where('region_id', $region->region_id);
                    });

            })

            ->where('status', 'Dijadwalkan')
            ->firstOrFail();

        $meeting->update([
            'scheduled_time' => $request->scheduled_time,
            'method' => $request->meeting_type,
            'location' => $request->location,
            'meeting_notes' => $request->meeting_notes
        ]);

        return redirect()->back()
            ->with('success', 'Pertemuan berhasil diperbarui!');
    }

    public function startMeeting($id)
    {
        $user = Auth::user();
        $region = $this->getRegionOrAbort($user);

        $meeting = Pertemuan::where('meeting_id', $id)

            ->whereHas('permintaanKerjasama', function ($q) use ($region) {

                $q->whereHas('kthr.user', function ($subQ) use ($region) {
                    $subQ->where('region_id', $region->region_id);
                })

                    ->orWhereHas('tptkb.user', function ($subQ) use ($region) {
                        $subQ->where('region_id', $region->region_id);
                    });

            })

            ->where('status', 'Dijadwalkan')
            ->firstOrFail();

        $meeting->update([
            'status' => 'Berlangsung',
            'actual_start_time' => now()
        ]);

        return redirect()->back()
            ->with('success', 'Pertemuan dimulai!');
    }

    public function cancelMeeting(Request $request, $id)
    {
        $request->validate([
            'cancellation_reason' => 'required|string|max:500'
        ]);

        $user = Auth::user();
        $region = $this->getRegionOrAbort($user);

        $meeting = Pertemuan::where('meeting_id', $id)

            ->whereHas('permintaanKerjasama', function ($q) use ($region) {

                $q->whereHas('kthr.user', function ($subQ) use ($region) {
                    $subQ->where('region_id', $region->region_id);
                })

                    ->orWhereHas('tptkb.user', function ($subQ) use ($region) {
                        $subQ->where('region_id', $region->region_id);
                    });

            })

            ->where('status', 'Dijadwalkan')
            ->firstOrFail();

        $meeting->update([
            'status' => 'Dibatalkan',
            'meeting_summary' => 'Pertemuan dibatalkan. Alasan: ' . $request->cancellation_reason
        ]);

        // Kembalikan status permintaan agar bisa dijadwalkan ulang
        $meeting->permintaanKerjasama->update([
            'status' => 'Menunggu Jadwal'
        ]);

        return redirect()->back()
            ->with('success', 'Pertemuan berhasil dibatalkan!');
    }

    public function completeMeeting(Request $request, $id)
    {
        $request->validate([
            'harga_per_m3' => 'required|numeric|min:1000|max:999999999',
            'mekanisme_pembayaran' => 'required|string|min:10|max:1000',
            'jadwal_pengiriman' => 'required|string|min:10|max:1000',
            'syarat_tambahan' => 'nullable|string|max:2000',
            'kualitas_spesifikasi' => 'required|string|min:10|max:1000',
            'durasi_kontrak_bulan' => 'required|integer|min:1|max:120',
            'meeting_summary' => 'required|string|min:20|max:2000',
            'final_document' => 'required|file|mimes:pdf,doc,docx|max:5120'
        ], [
            'harga_per_m3.required' => 'Harga per m³ wajib diisi',
            'harga_per_m3.numeric' => 'Harga harus berupa angka',
            'harga_per_m3.min' => 'Harga minimal Rp 1.000',
            'harga_per_m3.max' => 'Harga maksimal Rp 999.999.999',
            'mekanisme_pembayaran.required' => 'Mekanisme pembayaran wajib diisi',
            'mekanisme_pembayaran.min' => 'Mekanisme pembayaran minimal 10 karakter',
            'jadwal_pengiriman.required' => 'Jadwal pengiriman wajib diisi',
            'jadwal_pengiriman.min' => 'Jadwal pengiriman minimal 10 karakter',
            'kualitas_spesifikasi.required' => 'Kualitas & spesifikasi wajib diisi',
            'kualitas_spesifikasi.min' => 'Kualitas & spesifikasi minimal 10 karakter',
            'durasi_kontrak_bulan.required' => 'Durasi kontrak wajib diisi',
            'durasi_kontrak_bulan.min' => 'Durasi kontrak minimal 1 bulan',
            'durasi_kontrak_bulan.max' => 'Durasi kontrak maksimal 120 bulan',
            'meeting_summary.required' => 'Ringkasan pertemuan wajib diisi',
            'meeting_summary.min' => 'Ringkasan pertemuan minimal 20 karakter',
            'meeting_summary.max' => 'Ringkasan pertemuan maksimal 2000 karakter',
            'final_document.required' => 'Dokumen kerjasama wajib diunggah',
            'final_document.file' => 'Dokumen kerjasama harus berupa file',
            'final_document.mimes' => 'Format dokumen harus PDF, DOC, atau DOCX',
            'final_document.max' => 'Ukuran dokumen maksimal 5MB'
        ]);

        $user = Auth::user();
        $region = $user->region;

        $meeting = Pertemuan::where('meeting_id', $id)

            ->whereHas('permintaanKerjasama', function ($q) use ($region) {

                $q->whereHas('kthr.user', function ($subQ) use ($region) {
                    $subQ->where('region_id', $region->region_id);
                })

                    ->orWhereHas('tptkb.user', function ($subQ) use ($region) {
                        $subQ->where('region_id', $region->region_id);
                    });

            })

            ->where('status', 'Berlangsung')
            ->firstOrFail();

        // Check if agreement already exists
        $existingAgreement = KesepakatanKerjasama::where('meeting_id', $meeting->meeting_id)->first();
        if ($existingAgreement) {
            return redirect()->back()->with('error', 'Kesepakatan untuk pertemuan ini sudah ada!');
        }

        // Upload document
        $documentPath = null;
        if ($request->hasFile('final_document')) {
            $documentPath = $request->file('final_document')->store('documents/agreements', 'public');
        }

        // Complete meeting
        $meeting->update([
            'status' => 'Selesai',
            'actual_end_time' => now(),
            'meeting_summary' => $request->meeting_summary
        ]);

        // Prepare other notes with quality specs and additional terms
        $otherNotes = "Kualitas & Spesifikasi:\n" . $request->kualitas_spesifikasi . "\n\n";
        if ($request->syarat_tambahan) {
            $otherNotes .= "Syarat Tambahan:\n" . $request->syarat_tambahan;
        }

        // Create agreement
        $agreement = KesepakatanKerjasama::create([
            'meeting_id' => $meeting->meeting_id,
            'agreed_price_per_m3' => $request->harga_per_m3,
            'payment_mechanism' => $request->mekanisme_pembayaran,
            'delivery_schedule' => $request->jadwal_pengiriman,
            'other_notes' => $otherNotes,
            'durasi_kontrak_bulan' => $request->durasi_kontrak_bulan,
            'final_document_path' => $documentPath
        ]);

        // Update partnership status
        $meeting->permintaanKerjasama->update(['status' => 'Menunggu Tanda Tangan']);

        return redirect()->route('cdk.meetings')->with('success', 'Pertemuan selesai dan kesepakatan berhasil dibuat! ID Kesepakatan: ' . $agreement->agreement_id);
    }

    public function monitoring(Request $request)
    {
        $user = Auth::user();
        $region = $user->region;
        // KTHR di wilayah
        $kthrs = Kthr::whereHas('user', function ($q) use ($region) {
            $q->where('region_id', $region->region_id)
                ->where('approval_status', 'Approved');
        })
            ->with(['user', 'plantSpecies', 'permintaanKerjasama']) // ✅ Tambahkan permintaanKerjasama agar tidak null
            ->when($request->filled('kthr_search'), function ($q) use ($request) {
                $q->where('kthr_name', 'like', '%' . $request->kthr_search . '%');
            })
            ->paginate(10, ['*'], 'kthr_page');

        // TPTKB di wilayah
        $tptkbs = Tptkb::whereHas('user', function ($q) use ($region) {
            $q->where('region_id', $region->region_id)
                ->where('approval_status', 'Approved');
        })
            ->with(['user', 'materialSupplies', 'permintaanKerjasama']) // ✅ Tambahkan permintaanKerjasama agar tidak null
            ->when($request->filled('kthr_search'), function ($q) use ($request) {
                $q->where('kthr_name', 'like', '%' . $request->kthr_search . '%');
            })
            ->paginate(10, ['*'], 'kthr_page');

        // PBPHH di wilayah
        $pbphhs = PbphhProfile::whereHas('user', function ($q) use ($region) {
            $q->where('region_id', $region->region_id)
                ->where('approval_status', 'Approved');
        })
            ->with(['user', 'materialNeeds', 'permintaanKerjasama']) // ✅ Tambahkan permintaanKerjasama agar tidak null
            ->when($request->filled('pbphh_search'), function ($q) use ($request) {
                $q->where('company_name', 'like', '%' . $request->pbphh_search . '%');
            })
            ->paginate(10, ['*'], 'pbphh_page');

        // Statistik kemitraan
        $partnershipStats = [
            'total_partnerships' => PermintaanKerjasama::where(function ($query) use ($region) {
                $query->whereHas('kthr.user', function ($subQuery) use ($region) {
                    $subQuery->where('region_id', $region->region_id);
                })
                    ->orWhereHas('tptkb.user', function ($subQuery) use ($region) {
                        $subQuery->where('region_id', $region->region_id);
                    });
            })->count(),
            'active_partnerships' => PermintaanKerjasama::whereHas('kthr.user', function ($q) use ($region) {
                $q->where('region_id', $region->region_id);
            })->whereIn('status', ['Disetujui', 'Menunggu Jadwal', 'Dijadwalkan', 'Menunggu Tanda Tangan'])->count(),
            'completed_partnerships' => PermintaanKerjasama::whereHas('kthr.user', function ($q) use ($region) {
                $q->where('region_id', $region->region_id);
            })->where('status', 'Selesai')->count()
        ];

        return view('cdk.monitoring', compact('region', 'kthrs', 'pbphhs', 'partnershipStats', 'tptkbs'));
    }

    public function reports(Request $request)
    {
        $user = Auth::user();
        $region = $user->region;

        // Set default date range - last 30 days to current date
        $dateFrom = $request->input('date_from', now()->subDays(30)->format('Y-m-d'));
        $dateTo = $request->input('date_to', now()->format('Y-m-d'));

        // Ensure date range is valid
        $dateFrom = $dateFrom . ' 00:00:00';
        $dateTo = $dateTo . ' 23:59:59';

        // Laporan approval - fix date filtering
        $approvalReport = [
            'kthr_approved' => User::where('region_id', $region->region_id)
                ->where('role', 'KTHR_PENYULUH')
                ->where('approval_status', 'Approved')
                ->whereNotNull('approved_at')
                ->whereBetween('approved_at', [$dateFrom, $dateTo])
                ->count(),
            'tptkb_approved' => User::where('region_id', $region->region_id)
                ->where('role', 'TPTKB')
                ->where('approval_status', 'Approved')
                ->whereNotNull('approved_at')
                ->whereBetween('approved_at', [$dateFrom, $dateTo])
                ->count(),
            'pbphh_approved' => User::where('region_id', $region->region_id)
                ->where('role', 'PBPHH')
                ->where('approval_status', 'Approved')
                ->whereNotNull('approved_at')
                ->whereBetween('approved_at', [$dateFrom, $dateTo])
                ->count(),
            'total_rejected' => User::where('region_id', $region->region_id)
                ->where('approval_status', 'Rejected')
                ->whereNotNull('approved_at')
                ->whereBetween('approved_at', [$dateFrom, $dateTo])
                ->count()
        ];

        // Laporan pertemuan - fix date filtering
        $meetingReport = [
            'meetings_scheduled' => Pertemuan::whereHas('permintaanKerjasama.kthr.user', function ($q) use ($region) {
                $q->where('region_id', $region->region_id);
            })
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->count(),
            'meetings_completed' => Pertemuan::whereHas('permintaanKerjasama.kthr.user', function ($q) use ($region) {
                $q->where('region_id', $region->region_id);
            })
                ->where('status', 'Selesai')
                ->whereNotNull('actual_end_time')
                ->whereBetween('actual_end_time', [$dateFrom, $dateTo])
                ->count(),
            'agreements_created' => KesepakatanKerjasama::whereHas('pertemuan.permintaanKerjasama.kthr.user', function ($q) use ($region) {
                $q->where('region_id', $region->region_id);
            })
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->count()
        ];

        // Laporan kemitraan - ensure all status are included
        $partnershipReportQuery = PermintaanKerjasama::whereHas('kthr.user', function ($q) use ($region) {
            $q->where('region_id', $region->region_id);
        })
            ->selectRaw('status, COUNT(*) as count')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Initialize all possible statuses with 0 count
        $partnershipReport = [
            'Terkirim' => 0,
            'Disetujui' => 0,
            'Dijadwalkan' => 0,
            'Selesai' => 0
        ];

        // Merge actual data with initialized array
        $partnershipReport = array_merge($partnershipReport, $partnershipReportQuery);

        // Convert date back to Y-m-d format for view
        $dateFrom = substr($dateFrom, 0, 10);
        $dateTo = substr($dateTo, 0, 10);

        return view('cdk.reports', compact('region', 'approvalReport', 'meetingReport', 'partnershipReport', 'dateFrom', 'dateTo'));
    }

    /**
     * Get region relation from user or abort with 403 if not set.
     *
     * @param  mixed $user
     * @return \App\Models\Region
     */
    private function getRegionOrAbort($user)
    {
        if (!$user || !$user->region) {
            abort(403, 'Region tidak diatur untuk pengguna ini.');
        }

        return $user->region;
    }
}
