<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('regions')->insert([
            [
                'region_id' => 1,
                'region_code' => 'JATIM',
                'name' => 'Jawa Timur',
                'type' => 'PROVINSI',
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'region_id' => 2,
                'region_code' => 'PCT',
                'name' => 'Pacitan',
                'type' => 'Kabupaten',
                'parent_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'region_id' => 3,
                'region_code' => 'MDN',
                'name' => 'Madiun',
                'type' => 'Kabupaten',
                'parent_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'region_id' => 4,
                'region_code' => 'TGL',
                'name' => 'Trenggalek',
                'type' => 'Kabupaten',
                'parent_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'region_id' => 5,
                'region_code' => 'MLG',
                'name' => 'Malang',
                'type' => 'Kabupaten',
                'parent_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'region_id' => 6,
                'region_code' => 'NGJ',
                'name' => 'Nganjuk',
                'type' => 'Kabupaten',
                'parent_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'region_id' => 7,
                'region_code' => 'BJN',
                'name' => 'Bojonegoro',
                'type' => 'Kabupaten',
                'parent_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'region_id' => 8,
                'region_code' => 'LMJ',
                'name' => 'Lumajang',
                'type' => 'Kabupaten',
                'parent_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'region_id' => 9,
                'region_code' => 'JBR',
                'name' => 'Jember',
                'type' => 'Kabupaten',
                'parent_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'region_id' => 10,
                'region_code' => 'BWI',
                'name' => 'Banyuwangi',
                'type' => 'Kabupaten',
                'parent_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'region_id' => 11,
                'region_code' => 'SNP',
                'name' => 'Sumenep',
                'type' => 'Kabupaten',
                'parent_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
