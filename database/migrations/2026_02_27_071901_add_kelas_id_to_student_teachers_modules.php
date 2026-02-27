<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student', function (Blueprint $table) {
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->onDelete('set null');
        });

        Schema::table('teachers', function (Blueprint $table) {
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->onDelete('set null');
        });

        Schema::table('modules', function (Blueprint $table) {
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('student', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
            $table->dropColumn('kelas_id');
        });

        Schema::table('teachers', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
            $table->dropColumn('kelas_id');
        });

        Schema::table('modules', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
            $table->dropColumn('kelas_id');
        });
    }
};
