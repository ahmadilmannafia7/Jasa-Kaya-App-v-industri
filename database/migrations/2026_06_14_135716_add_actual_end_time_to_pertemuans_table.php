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
        Schema::table('pertemuan', function (Blueprint $table) {
            $table->timestamp('actual_start_time')->nullable()->after('scheduled_time');
            $table->timestamp('actual_end_time')->nullable()->after('actual_start_time');
            $table->text('meeting_notes')->nullable()->after('actual_end_time');
            $table->text('meeting_summary')->nullable()->after('meeting_notes');
            $table->enum('meeting_type', ['Online', 'Offline'])->default('Offline')->after('method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pertemuan', function (Blueprint $table) {
            $table->dropColumn(['actual_start_time', 'actual_end_time', 'meeting_notes', 'meeting_summary', 'meeting_type']);
        });
    }
};
