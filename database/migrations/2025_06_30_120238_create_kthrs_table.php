<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('kthrs', function (Blueprint $table) {
            $table->id('kthr_id');
            $table->foreignId('registered_by_user_id')->unique()->constrained('users', 'user_id')->onDelete('cascade');
            $table->string('kthr_name');
            $table->string('ketua_ktp_path');
            $table->string('sk_register_path');
            $table->string('nama_pendamping')->nullable();
            $table->string('phone')->nullable(); 
            $table->text('alamat_sekretariat')->nullable();
            $table->decimal('coordinate_lat', 10, 8)->nullable();
            $table->decimal('coordinate_lng', 11, 8)->nullable();
            $table->float('luas_areal_ha')->nullable();
            $table->integer('jumlah_anggota')->nullable();
            $table->integer('jumlah_pertemuan_tahunan')->nullable();
            $table->string('shp_file_path')->nullable();
            $table->boolean('is_siap_mitra')->default(false);
            $table->boolean('is_siap_tebang')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('kthrs');
    }
};
