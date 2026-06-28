<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('email')->unique();
            $table->string('password_hash');
            $table->enum('role', ['PBPHH', 'KTHR_PENYULUH', 'CDK', 'DINAS_PROVINSI']);
            $table->foreignId('region_id')->constrained('regions', 'region_id')->onDelete('cascade');
            $table->enum('approval_status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->foreignId('approved_by_user_id')->nullable()->constrained('users', 'user_id')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('users');
    }
};
