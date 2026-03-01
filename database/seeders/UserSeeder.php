<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'username' => 'andi',
            'password' => 'password123',
            'role'     => 'siswa',
        ]);

        User::create([
            'username' => 'budi',
            'password' => 'password123',
            'role'     => 'siswa',
        ]);

        User::create([
            'username' => 'citra',
            'password' => 'password123',
            'role'     => 'siswa',
        ]);
        User::create([
            'username' => 'dewi',
            'password' => 'password123',
            'role'     => 'siswa',
        ]);
        User::create([
            'username' => 'eko',
            'password' => 'password123',
            'role'     => 'siswa',
        ]);

        // Tambahan akun guru & admin biar sistem lengkap
        User::create([
            'username' => 'Bu Dwi',
            'password' => 'password123',
            'role'     => 'guru',
        ]);
        User::create([
            'username' => 'Bu Afika',
            'password' => 'password123',
            'role'     => 'guru',
        ]);

        User::create([
            'username' => 'admin1',
            'password' => 'password123',
            'role'     => 'admin',
        ]);
    }
}