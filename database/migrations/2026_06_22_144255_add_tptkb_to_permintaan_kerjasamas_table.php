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
        Schema::table('permintaan_kerjasama', function (Blueprint $table) {
            $table->foreignId('tptkb_id')->nullable()
                ->constrained('tptkbs', 'tptkb_id')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permintaan_kerjasama', function (Blueprint $table) {
            $table->dropColumn('tptkb_id');
        });
    }
};
