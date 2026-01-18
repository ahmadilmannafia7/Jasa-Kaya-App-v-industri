<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder {
    public function run(): void {
        DB::table('users')->insert([
            [
                'email' => 'dinas.dishutjatim@gmail.com',
                'password_hash' => Hash::make('12345678'),
                'role' => 'DINAS_PROVINSI',
                'region_id' => 1,
                'approval_status' => 'Approved',
                'approved_by_user_id' => null,
                'approved_at' => now(),
                'rejection_reason' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
