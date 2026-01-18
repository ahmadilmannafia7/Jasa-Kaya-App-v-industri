<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('kthr_plant_species', function (Blueprint $table) {
            $table->string('gambar_tegakan_path')->nullable()->change();
        });
    }

    public function down(): void {
        Schema::table('kthr_plant_species', function (Blueprint $table) {
            $table->string('gambar_tegakan_path')->nullable(false)->change();
        });
    }
};