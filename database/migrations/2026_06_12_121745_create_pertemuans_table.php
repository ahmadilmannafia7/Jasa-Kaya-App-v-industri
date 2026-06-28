<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pertemuan', function (Blueprint $table) {
            $table->id('meeting_id');

            // Permintaan Kerjasama
            $table->unsignedBigInteger('request_id')->unique();
            $table->foreign('request_id')->references('request_id')->on('permintaan_kerjasama')->onDelete('cascade');

            // User yang Menjadwalkan
            $table->unsignedBigInteger('scheduled_by_user_id');
            $table->foreign('scheduled_by_user_id')->references('user_id')->on('users')->onDelete('cascade');

            $table->string('location');
            $table->enum('method', ['online', 'lapangan']);
            $table->timestamp('scheduled_time');
            $table->enum('status', ['Dijadwalkan', 'Berlangsung', 'Selesai', 'Dibatalkan'])->default('Dijadwalkan');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pertemuan');
    }
};
