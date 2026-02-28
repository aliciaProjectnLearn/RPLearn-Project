<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modul_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('modules')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('student')->onDelete('cascade');
            $table->timestamp('viewed_at')->useCurrent();
            $table->timestamps();

            // ✅ Unique constraint: satu siswa hanya tercatat sekali per modul
            $table->unique(['module_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modul_views');
    }
};
