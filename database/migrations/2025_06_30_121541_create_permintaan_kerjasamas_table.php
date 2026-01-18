<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('permintaan_kerjasama', function (Blueprint $table) {
            $table->id('request_id');
            $table->foreignId('pbphh_id')->constrained('pbphh_profiles', 'pbphh_id')->onDelete('cascade');
            $table->foreignId('kthr_id')->constrained('kthrs', 'kthr_id')->onDelete('cascade');
            $table->string('wood_type', 100);
            $table->float('monthly_volume_m3');
            $table->enum('status', ['Terkirim', 'Ditolak', 'Disetujui', 'Menunggu Jadwal', 'Dijadwalkan', 'Selesai', 'Dibatalkan'])->default('Terkirim');
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permintaan_kerjasama');
    }
};
