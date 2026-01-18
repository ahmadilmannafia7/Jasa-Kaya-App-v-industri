<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pbphh_profiles', function (Blueprint $table) {
            $table->id('pbphh_id');
            $table->foreignId('user_id')->unique()->constrained('users', 'user_id')->onDelete('cascade');
            $table->string('company_name');
            $table->string('nib_path');
            $table->string('sk_pbphh_path');
            $table->string('penanggung_jawab')->nullable();
            $table->string('phone')->nullable();
            $table->text('alamat_perusahaan')->nullable();
            $table->decimal('coordinate_lat', 10, 8)->nullable();
            $table->decimal('coordinate_lng', 11, 8)->nullable();
            $table->float('kapasitas_izin_produksi_m3')->nullable();
            $table->float('rencana_produksi_tahunan_m3')->nullable();
            $table->string('jenis_produk_utama')->nullable();
            $table->integer('tahun_berdiri')->nullable();
            $table->integer('jumlah_karyawan')->nullable();
            $table->string('website')->nullable();
            $table->text('deskripsi_perusahaan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pbphh_profiles');
    }
};
