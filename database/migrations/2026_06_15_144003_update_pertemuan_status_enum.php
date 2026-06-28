<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE pertemuan MODIFY COLUMN status ENUM('Dijadwalkan', 'Berlangsung', 'Selesai', 'Dibatalkan') DEFAULT 'Dijadwalkan'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE pertemuan MODIFY COLUMN status ENUM('Dijadwalkan', 'Selesai', 'Dibatalkan') DEFAULT 'Dijadwalkan'");
    }
};
