<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tptkbs', function (Blueprint $table) {
            $table->id('tptkb_id');
            $table->foreignId('registered_by_user_id')->unique()->constrained('users', 'user_id')->onDelete('cascade');
            $table->string('tptkb_name');
            $table->string('ketua_ktp_path');
            $table->string('sk_tptkb_path');
            $table->string('nama_pendamping_tptkb')->nullable();
            $table->string('phone')->nullable(); 
            $table->text('alamat_tptkb')->nullable();
            $table->decimal('coordinate_lat', 10, 8)->nullable();
            $table->decimal('coordinate_lng', 11, 8)->nullable();
            $table->float('luas_areal_ha')->nullable();
            $table->boolean('is_siap_mitra')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('tptkbs');
    }
};
