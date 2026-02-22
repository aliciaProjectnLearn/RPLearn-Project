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
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel modules
            $table->foreignId('module_id')->constrained('modules')->cascadeOnDelete();

            // Relasi ke pembuat modul (mengikuti pola di Module.php yang merujuk ke tabel users)
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();

            // Kolom fitur sesuai Card 14
            $table->enum('status', ['pending', 'approved', 'rejected', 'revisi'])->default('pending');
            $table->text('comment')->nullable(); // Komentar dari admin
            $table->timestamp('approved_at')->nullable(); // Waktu di-approve

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approvals');
    }
};
