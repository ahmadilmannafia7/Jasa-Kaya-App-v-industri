<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class CdkSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk membuat akun CDK untuk setiap wilayah.
     */
    public function run(): void
    {
        // Tambahkan Sumenep ke tabel regions jika belum ada
        $sumenepExists = DB::table('regions')->where('region_code', 'SNP')->exists();
        
        if (!$sumenepExists) {
            DB::table('regions')->insert([
                'region_code' => 'SNP',
                'name' => 'Sumenep',
                'type' => 'Kabupaten',
                'parent_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
        // Ambil semua region_id dari database berdasarkan region_code
        $regions = DB::table('regions')
            ->whereIn('region_code', ['MLG', 'BJN', 'JBR', 'PCT', 'MDN', 'TGL', 'NGJ', 'LMJ', 'BWI', 'SNP'])
            ->pluck('region_id', 'region_code')
            ->toArray();
        
        // Buat akun CDK untuk setiap wilayah
        foreach ($regions as $code => $regionId) {
            // Buat akun user untuk CDK
            $cdkUserId = DB::table('users')->insertGetId([
                'email' => 'cdk.' . strtolower($code) . '@jasakaya.id',
                'password_hash' => Hash::make('password123'),
                'role' => 'CDK',
                'region_id' => $regionId,
                'approval_status' => 'Approved',
                'approved_by_user_id' => 1, // Disetujui oleh Super Admin
                'approved_at' => now(),
                'rejection_reason' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}