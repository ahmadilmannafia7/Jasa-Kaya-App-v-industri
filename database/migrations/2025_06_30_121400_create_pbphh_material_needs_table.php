<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pbphh_material_needs', function (Blueprint $table) {
            $table->id('need_id');
            $table->foreignId('pbphh_id')->constrained('pbphh_profiles', 'pbphh_id')->onDelete('cascade');
            $table->string('jenis_kayu', 100); // Ganti dari 'jenis_material' ke 'jenis_kayu' sesuai form
            $table->enum('tipe', ['Kayu', 'Bukan Kayu']);
            $table->float('kebutuhan_bulanan_m3');
            $table->string('spesifikasi_tambahan')->nullable(); // Tambahkan jika digunakan di form
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pbphh_material_needs');
    }
};
