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
        Schema::create('tptkb_material_supplies', function (Blueprint $table) {
            $table->id('supply_id');
            $table->foreignId('tptkb_id')->constrained('tptkbs', 'tptkb_id')->onDelete('cascade');
            $table->string('supply_kayu', 100);
            $table->enum('tipe', ['Kayu', 'Bukan Kayu']);
            $table->integer('jumlah');
            $table->string('gambar_supply_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tptkb_material_supplies');
    }
};
