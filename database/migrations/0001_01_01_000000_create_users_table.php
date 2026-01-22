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
        // Gabungkan hanya menjadi satu Schema::create untuk users
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Primary Key: Bigint
            $table->string('username')->unique(); // Username: String
            $table->string('password'); // Password: Hash
            $table->enum('role', ['siswa', 'guru']); // Role: Enum

            // Opsional: Tetap gunakan email jika ingin fitur Reset Password Laravel
            // $table->string('email')->unique();

            $table->rememberToken();
            $table->timestamps(); // Mencakup Created_at
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
