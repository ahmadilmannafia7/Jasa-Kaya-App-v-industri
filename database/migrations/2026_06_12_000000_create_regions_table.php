<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id('region_id');
            $table->string('region_code', 10)->unique();
            $table->string('name');
            $table->enum('type', ['CDK', 'PROVINSI', 'Kabupaten']);
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('regions', 'region_id')
                ->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regions');
    }
};
