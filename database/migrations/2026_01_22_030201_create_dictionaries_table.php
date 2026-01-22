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
        Schema::create('dictionaries', function (Blueprint $table) {
            $table->id();
            // Relasi ke module (opsional, jika istilah hanya untuk modul tertentu)
            $table->foreignId('module_id')->nullable()->constrained('modules')->onDelete('cascade');
            $table->string('term'); // Istilahnya (contoh: "API")
            $table->text('definition'); // Penjelasannya
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dictionaries');
    }
};
