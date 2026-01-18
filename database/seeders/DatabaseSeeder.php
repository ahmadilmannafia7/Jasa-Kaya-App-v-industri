<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Jalankan semua seeder utama untuk inisialisasi sistem.
     */
    public function run(): void
    {
        // Jalankan RegionSeeder terlebih dahulu
        $this->call(RegionSeeder::class);

        // Lanjutkan dengan SuperAdminSeeder (bergantung pada RegionSeeder)
        $this->call(SuperAdminSeeder::class);

        // Jalankan KthrPbphhSeeder untuk membuat akun KTHR dan PBPHH
        $this->call(KthrPbphhSeeder::class);
        
        // Jalankan CdkSeeder untuk membuat akun CDK
        $this->call(CdkSeeder::class);
    }
}
