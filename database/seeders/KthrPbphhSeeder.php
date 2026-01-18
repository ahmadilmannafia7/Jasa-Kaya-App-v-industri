<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class KthrPbphhSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk membuat akun KTHR dan PBPHH untuk setiap wilayah.
     */
    public function run(): void
    {
        // Ambil semua region kabupaten dari database
        $regions = DB::table('regions')
            ->where('type', 'Kabupaten')
            ->get();

        foreach ($regions as $region) {
            // Buat akun user untuk KTHR
            $kthrUserId = DB::table('users')->insertGetId([
                'email' => 'kthr.' . strtolower($region->region_code) . '@jasakaya.id',
                'password_hash' => Hash::make('password123'),
                'role' => 'KTHR_PENYULUH',
                'region_id' => $region->region_id,
                'approval_status' => 'Approved',
                'approved_by_user_id' => 1, // Disetujui oleh Super Admin
                'approved_at' => now(),
                'rejection_reason' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Buat profil KTHR
            $kthrId = DB::table('kthrs')->insertGetId([
                'registered_by_user_id' => $kthrUserId,
                'region_id' => $region->region_id,
                'kthr_name' => 'KTHR ' . $region->name,
                'ketua_ktp_path' => 'documents/ktp/kthr_' . strtolower($region->region_code) . '.jpg',
                'sk_register_path' => 'documents/sk/kthr_' . strtolower($region->region_code) . '.pdf',
                'nama_pendamping' => 'Pendamping ' . $region->name,
                'phone' => '08' . rand(1000000000, 9999999999),
                'alamat_sekretariat' => 'Jl. Hutan Rakyat No. ' . rand(1, 100) . ', ' . $region->name,
                'coordinate_lat' => rand(-8.5, -7.5) + (rand(0, 1000) / 1000),
                'coordinate_lng' => rand(110.5, 114.5) + (rand(0, 1000) / 1000),
                'luas_areal_ha' => rand(50, 500),
                'jumlah_anggota' => rand(20, 100),
                'jumlah_pertemuan_tahunan' => rand(4, 12),
                'shp_file_path' => 'documents/shp/kthr_' . strtolower($region->region_code) . '.zip',
                'is_siap_mitra' => true,
                'is_siap_tebang' => rand(0, 1) == 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Tambahkan jenis tanaman untuk KTHR
            $jenisTanaman = ['Jati', 'Sengon', 'Mahoni', 'Akasia', 'Pinus'];
            $tipeTanaman = ['Kayu', 'Bukan Kayu'];
            
            // Tambahkan 2-3 jenis tanaman untuk setiap KTHR
            $jumlahJenisTanaman = rand(2, 3);
            $selectedJenisTanaman = array_rand($jenisTanaman, $jumlahJenisTanaman);
            
            foreach ((array)$selectedJenisTanaman as $index) {
                DB::table('kthr_plant_species')->insert([
                    'kthr_id' => $kthrId,
                    'jenis_tanaman' => $jenisTanaman[$index],
                    'tipe' => $tipeTanaman[array_rand($tipeTanaman)],
                    'jumlah_pohon' => rand(1000, 5000),
                    'tahun_tanam' => rand(2015, 2022),
                    'gambar_tegakan_path' => 'plant_images/' . strtolower($jenisTanaman[$index]) . '_' . rand(1, 10) . '.jpg',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Buat akun user untuk PBPHH
            $pbphhUserId = DB::table('users')->insertGetId([
                'email' => 'pbphh.' . strtolower($region->region_code) . '@jasakaya.id',
                'password_hash' => Hash::make('password123'),
                'role' => 'PBPHH',
                'region_id' => $region->region_id,
                'approval_status' => 'Approved',
                'approved_by_user_id' => 1, // Disetujui oleh Super Admin
                'approved_at' => now(),
                'rejection_reason' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Buat profil PBPHH
            $pbphhId = DB::table('pbphh_profiles')->insertGetId([
                'user_id' => $pbphhUserId,
                'company_name' => 'PT Kayu Makmur ' . $region->name,
                'nib_path' => 'documents/nib/pbphh_' . strtolower($region->region_code) . '.pdf',
                'sk_pbphh_path' => 'documents/sk/pbphh_' . strtolower($region->region_code) . '.pdf',
                'penanggung_jawab' => 'Direktur ' . $region->name,
                'phone' => '08' . rand(1000000000, 9999999999),
                'alamat_perusahaan' => 'Jl. Industri No. ' . rand(1, 100) . ', ' . $region->name,
                'coordinate_lat' => rand(-8.5, -7.5) + (rand(0, 1000) / 1000),
                'coordinate_lng' => rand(110.5, 114.5) + (rand(0, 1000) / 1000),
                'kapasitas_izin_produksi_m3' => rand(1000, 5000),
                'rencana_produksi_tahunan_m3' => rand(800, 4000),
                'jenis_produk_utama' => ['Furniture', 'Plywood', 'Pulp', 'Veneer'][array_rand(['Furniture', 'Plywood', 'Pulp', 'Veneer'])],
                'tahun_berdiri' => rand(1990, 2015),
                'jumlah_karyawan' => rand(50, 500),
                'website' => 'https://kayumakmur' . strtolower($region->region_code) . '.co.id',
                'deskripsi_perusahaan' => 'PT Kayu Makmur ' . $region->name . ' adalah perusahaan pengolahan hasil hutan yang berkomitmen untuk keberlanjutan dan kualitas produk.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Tambahkan kebutuhan material untuk PBPHH
            $jenisKayu = ['Jati', 'Sengon', 'Mahoni', 'Akasia', 'Pinus'];
            $tipeKayu = ['Kayu', 'Bukan Kayu'];
            
            // Tambahkan 2-3 jenis kebutuhan material untuk setiap PBPHH
            $jumlahJenisKayu = rand(2, 3);
            $selectedJenisKayu = array_rand($jenisKayu, $jumlahJenisKayu);
            
            foreach ((array)$selectedJenisKayu as $index) {
                DB::table('pbphh_material_needs')->insert([
                    'pbphh_id' => $pbphhId,
                    'jenis_kayu' => $jenisKayu[$index],
                    'tipe' => $tipeKayu[array_rand($tipeKayu)],
                    'kebutuhan_bulanan_m3' => rand(50, 300),
                    'spesifikasi_tambahan' => 'Diameter minimal ' . rand(15, 30) . ' cm, panjang ' . rand(2, 4) . ' meter',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}