<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Kthr;
use App\Models\PbphhProfile;
use App\Models\PermintaanKerjasama;

class HomeController extends Controller
{
    public function index()
    {
        // Statistik untuk homepage
        $stats = [
            'total_kthr' => Kthr::whereHas('user', function ($query) {
                $query->where('approval_status', 'Approved');
            })->count(),
            'total_pbphh' => PbphhProfile::whereHas('user', function ($query) {
                $query->where('approval_status', 'Approved');
            })->count(),
            'total_partnerships' => PermintaanKerjasama::where('status', 'Selesai')->count(),
            'active_requests' => PermintaanKerjasama::whereIn('status', ['Terkirim', 'Disetujui', 'Dijadwalkan'])->count()
        ];

        // Data lokasi KTHR dengan informasi lengkap
        $kthrLokasi = Kthr::select(
                'kthr_name as nama', 
                'coordinate_lat as lat', 
                'coordinate_lng as lng',
                'nama_pendamping',
                'phone',
                'alamat_sekretariat as alamat',
                'luas_areal_ha',
                'jumlah_anggota',
                'is_siap_mitra',
                'is_siap_tebang'
            )
            ->whereNotNull('coordinate_lat')
            ->whereNotNull('coordinate_lng')
            ->whereHas('user', function ($query) {
                $query->where('approval_status', 'Approved');
            })
            ->with(['user:user_id,email', 'region:region_id,region_name'])
            ->get()
            ->map(function ($item) {
                $item->type = 'KTHR';
                $item->email = $item->user ? $item->user->email : null;
                $item->region_name = $item->region ? $item->region->region_name : null;
                return $item;
            });

        // Data lokasi PBPHH dengan informasi lengkap
        $pbphhLokasi = PbphhProfile::select(
                'company_name as nama', 
                'coordinate_lat as lat', 
                'coordinate_lng as lng',
                'penanggung_jawab',
                'phone',
                'alamat_perusahaan as alamat',
                'kapasitas_izin_produksi_m3',
                'rencana_produksi_tahunan_m3'
            )
            ->whereNotNull('coordinate_lat')
            ->whereNotNull('coordinate_lng')
            ->whereHas('user', function ($query) {
                $query->where('approval_status', 'Approved');
            })
            ->with(['user:user_id,email'])
            ->get()
            ->map(function ($item) {
                $item->type = 'PBPHH';
                $item->email = $item->user ? $item->user->email : null;
                return $item;
            });

        // Gabungkan keduanya
        $lokasi = $kthrLokasi->concat($pbphhLokasi);

        return view('home.index', compact('stats', 'lokasi'));
    }


    public function about()
    {
        // Data untuk halaman tentang
        $features = [
            [
                'icon' => 'fas fa-shield-alt',
                'title' => 'Terverifikasi & Aman',
                'description' => 'Semua KTHR dan industri telah melalui proses verifikasi ketat oleh CDK dan Dinas Provinsi'
            ],
            [
                'icon' => 'fas fa-handshake',
                'title' => 'Fasilitasi Profesional',
                'description' => 'CDK memfasilitasi pertemuan dan negosiasi untuk memastikan kesepakatan yang adil'
            ],
            [
                'icon' => 'fas fa-chart-line',
                'title' => 'Transparansi Penuh',
                'description' => 'Sistem tracking lengkap dari permintaan hingga penandatanganan kontrak'
            ],
            [
                'icon' => 'fas fa-leaf',
                'title' => 'Berkelanjutan',
                'description' => 'Mendukung pengelolaan hutan yang berkelanjutan dan ramah lingkungan'
            ]
        ];

        return view('home.about', compact('features'));
    }

    public function process()
    {
        // Data alur proses untuk infografis
        $processSteps = [
            [
                'step' => 1,
                'title' => 'Pendaftaran',
                'description' => 'KTHR/Industri mendaftar dengan dokumen lengkap',
                'icon' => 'fas fa-user-plus',
                'color' => 'primary'
            ],
            [
                'step' => 2,
                'title' => 'Verifikasi',
                'description' => 'CDK/Dinas Provinsi melakukan verifikasi dokumen',
                'icon' => 'fas fa-check-circle',
                'color' => 'info'
            ],
            [
                'step' => 3,
                'title' => 'Profil Lengkap',
                'description' => 'Melengkapi data profil dan kebutuhan',
                'icon' => 'fas fa-edit',
                'color' => 'warning'
            ],
            [
                'step' => 4,
                'title' => 'Pencarian Mitra',
                'description' => 'Industri mencari dan mengajukan kemitraan',
                'icon' => 'fas fa-search',
                'color' => 'secondary'
            ],
            [
                'step' => 5,
                'title' => 'Fasilitasi CDK',
                'description' => 'CDK menjadwalkan dan memfasilitasi pertemuan',
                'icon' => 'fas fa-calendar-alt',
                'color' => 'info'
            ],
            [
                'step' => 6,
                'title' => 'Kesepakatan',
                'description' => 'Penandatanganan kontrak kemitraan',
                'icon' => 'fas fa-handshake',
                'color' => 'success'
            ]
        ];

        return view('home.process', compact('processSteps'));
    }

    public function contact()
    {
        $contacts = [
            [
                'title' => 'Kantor Pusat',
                'address' => 'Jl. Gatot Subroto No. 123, Jakarta Selatan',
                'phone' => '+62 21 1234 5678',
                'email' => 'info@jasakaya.go.id'
            ],
            [
                'title' => 'Divisi KTHR',
                'address' => 'Gedung Manggala Wanabakti',
                'phone' => '+62 21 8765 4321',
                'email' => 'kthr@jasakaya.go.id'
            ],
            [
                'title' => 'Divisi Industri',
                'address' => 'Gedung Manggala Wanabakti',
                'phone' => '+62 21 9876 5432',
                'email' => 'industri@jasakaya.go.id'
            ]
        ];

        return view('home.contact', compact('contacts'));
    }
}
