<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('module_content', function (Blueprint $table) {
        $table->string('video_url')->nullable()->after('content'); // Untuk link YouTube
        $table->string('file_path')->nullable()->after('video_url'); // Untuk path file PDF
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('module_contents', function (Blueprint $table) {
            //
        });
    }
};
