<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('kesepakatan_kerjasama', function (Blueprint $table) {
            $table->id('agreement_id');
            $table->foreignId('meeting_id')->unique()->constrained('pertemuan', 'meeting_id')->onDelete('cascade');
            $table->decimal('agreed_price_per_m3', 15, 2);
            $table->text('payment_mechanism')->nullable();
            $table->text('delivery_schedule')->nullable();
            $table->text('other_notes')->nullable();
            $table->string('final_document_path')->nullable();
            $table->timestamp('signed_by_kthr_at')->nullable();
            $table->timestamp('signed_by_pbphh_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('kesepakatan_kerjasama');
    }
};
