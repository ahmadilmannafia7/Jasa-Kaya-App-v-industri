<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('kthr_plant_species', function (Blueprint $table) {
            $table->id('plant_id');
            $table->foreignId('kthr_id')->constrained('kthrs', 'kthr_id')->onDelete('cascade');
            $table->string('jenis_tanaman', 100);
            $table->enum('tipe', ['Kayu', 'Bukan Kayu']);
            $table->integer('jumlah_pohon');
            $table->integer('tahun_tanam');
            $table->string('gambar_tegakan_path');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('kthr_plant_species');
    }
};