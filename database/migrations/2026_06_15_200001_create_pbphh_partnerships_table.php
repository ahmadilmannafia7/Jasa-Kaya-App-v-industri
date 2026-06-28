<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pbphh_partnerships', function (Blueprint $table) {
            $table->id('partnership_id');
            $table->foreignId('requester_pbphh_id')->constrained('pbphh_profiles', 'pbphh_id')->onDelete('cascade');
            $table->foreignId('partner_pbphh_id')->constrained('pbphh_profiles', 'pbphh_id')->onDelete('cascade');
            $table->enum('partnership_type', [
                'Pasokan Material',
                'Kapasitas Produksi',
                'Joint Venture',
                'Distribusi',
                'Lainnya'
            ])->default('Pasokan Material');
            $table->text('description'); // Deskripsi detail tentang kemitraan yang diinginkan
            $table->string('material_type', 100)->nullable(); // Jenis material jika tipe Pasokan Material
            $table->float('volume_needed_m3')->nullable(); // Volume yang dibutuhkan
            $table->string('duration_months', 50)->nullable(); // Durasi kemitraan (contoh: "12 bulan", "2 tahun")
            $table->enum('status', [
                'Terkirim',
                'Ditolak',
                'Disetujui',
                'Dalam Negosiasi',
                'Kesepakatan Dibuat',
                'Aktif',
                'Selesai',
                'Dibatalkan'
            ])->default('Terkirim');
            $table->text('rejection_reason')->nullable();
            $table->text('negotiation_notes')->nullable(); // Catatan selama negosiasi
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();

            // Index untuk performa query
            $table->index(['requester_pbphh_id', 'status']);
            $table->index(['partner_pbphh_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pbphh_partnerships');
    }
};
