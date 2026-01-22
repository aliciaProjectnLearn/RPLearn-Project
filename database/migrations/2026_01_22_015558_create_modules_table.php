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
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('grade_category_id')->constrained('grade_categories')->onDelete('cascade');
            $table->foreignId('subject_category_id')->constrained('subject_categories')->onDelete('cascade');
            $table->string('title');
            $table->text('desc');
            $table->enum('track', ['FE', 'BE']);
            $table->boolean('is_published')->default(false);
            $table->integer('like')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
