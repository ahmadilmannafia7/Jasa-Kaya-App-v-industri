<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('permintaan_kerjasama', function (Blueprint $table) {
            $table->text('additional_notes')->nullable()->after('monthly_volume_m3');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permintaan_kerjasama', function (Blueprint $table) {
            $table->dropColumn('additional_notes');
        });
    }
};
