<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PbphhPartnership;
use App\Models\PbphhProfile;

class PbphhPartnershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get sample PBPHH profiles (assuming at least 2 exist)
        $pbphhProfiles = PbphhProfile::whereHas('user', function ($q) {
            $q->where('approval_status', 'Approved');
        })->take(3)->get();

        if ($pbphhProfiles->count() < 2) {
            $this->command->warn('Need at least 2 approved PBPHH to seed partnerships.');
            return;
        }

        $pbphh1 = $pbphhProfiles[0];
        $pbphh2 = $pbphhProfiles[1];
        $pbphh3 = $pbphhProfiles->count() > 2 ? $pbphhProfiles[2] : null;

        // Sample Partnership 1: Pasokan Material (Terkirim)
        PbphhPartnership::create([
            'requester_pbphh_id' => $pbphh1->pbphh_id,
            'partner_pbphh_id' => $pbphh2->pbphh_id,
            'partnership_type' => 'Pasokan Material',
            'description' => 'Kami membutuhkan pasokan kayu jati kualitas A+ untuk produksi furniture premium. Volume dibutuhkan secara konsisten setiap bulan.',
            'material_type' => 'Kayu Jati',
            'volume_needed_m3' => 150.00,
            'duration_months' => '12 bulan',
            'status' => 'Terkirim',
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(2)
        ]);

        // Sample Partnership 2: Joint Venture (Disetujui)
        PbphhPartnership::create([
            'requester_pbphh_id' => $pbphh2->pbphh_id,
            'partner_pbphh_id' => $pbphh1->pbphh_id,
            'partnership_type' => 'Joint Venture',
            'description' => 'Proposal kerjasama joint venture untuk ekspansi pasar ekspor ke Eropa. Kami memiliki koneksi distributor, partner memiliki kapasitas produksi.',
            'material_type' => null,
            'volume_needed_m3' => null,
            'duration_months' => '24 bulan',
            'status' => 'Disetujui',
            'approved_at' => now()->subDays(1),
            'created_at' => now()->subDays(5),
            'updated_at' => now()->subDays(1)
        ]);

        // Sample Partnership 3: Kapasitas Produksi (Dalam Negosiasi)
        if ($pbphh3) {
            PbphhPartnership::create([
                'requester_pbphh_id' => $pbphh1->pbphh_id,
                'partner_pbphh_id' => $pbphh3->pbphh_id,
                'partnership_type' => 'Kapasitas Produksi',
                'description' => 'Kami memiliki order besar yang melebihi kapasitas produksi. Membutuhkan partner untuk mengerjakan 30% dari total order.',
                'material_type' => 'Furniture',
                'volume_needed_m3' => 200.00,
                'duration_months' => '6 bulan',
                'status' => 'Dalam Negosiasi',
                'approved_at' => now()->subDays(3),
                'negotiation_notes' => 'Partner menawarkan kapasitas 250 m³/bulan dengan harga Rp 2.5 juta per m³. Perlu diskusi lebih lanjut mengenai timeline pengiriman.',
                'created_at' => now()->subWeeks(1),
                'updated_at' => now()->subHours(12)
            ]);
        }

        // Sample Partnership 4: Distribusi (Aktif)
        if ($pbphh3) {
            PbphhPartnership::create([
                'requester_pbphh_id' => $pbphh2->pbphh_id,
                'partner_pbphh_id' => $pbphh3->pbphh_id,
                'partnership_type' => 'Distribusi',
                'description' => 'Kerjasama distribusi produk furniture ke wilayah Jawa Timur. Partner bertanggung jawab atas logistik dan penjualan.',
                'material_type' => null,
                'volume_needed_m3' => null,
                'duration_months' => '18 bulan',
                'status' => 'Aktif',
                'approved_at' => now()->subMonths(2),
                'started_at' => now()->subMonth(1),
                'negotiation_notes' => 'Kesepakatan profit sharing 60:40. Target penjualan 500 unit per bulan.',
                'created_at' => now()->subMonths(3),
                'updated_at' => now()->subMonth(1)
            ]);
        }

        // Sample Partnership 5: Ditolak
        PbphhPartnership::create([
            'requester_pbphh_id' => $pbphh1->pbphh_id,
            'partner_pbphh_id' => $pbphh2->pbphh_id,
            'partnership_type' => 'Lainnya',
            'description' => 'Proposal kerjasama berbagi teknologi pengolahan kayu modern.',
            'material_type' => null,
            'volume_needed_m3' => null,
            'duration_months' => null,
            'status' => 'Ditolak',
            'rejection_reason' => 'Maaf, saat ini kami sedang fokus mengembangkan teknologi internal dan belum siap untuk berbagi dengan pihak eksternal.',
            'created_at' => now()->subMonths(1),
            'updated_at' => now()->subWeeks(3)
        ]);

        $this->command->info('PBPHH Partnerships seeded successfully!');
    }
}
