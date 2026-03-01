<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        Student::create([
            'user_id'  => 1,
            'nis'      => '20260001',
            'name'     => 'Andi Saputra',
            'kelas_id' => 1,
        ]);

        Student::create([
            'user_id'  => 2,
            'nis'      => '20260002',
            'name'     => 'Budi Santoso',
            'kelas_id' => 1,
        ]);

        Student::create([
            'user_id'  => 3,
            'nis'      => '20260003',
            'name'     => 'Citra Lestari',
            'kelas_id' => 2,
        ]);
        Student::create([
            'user_id'  => 4,
            'nis'      => '20260004',
            'name'     => 'Dewi Putri',
            'kelas_id' => 2,
        ]);
        Student::create([
            'user_id'  => 5,
            'nis'      => '20260005',
            'name'     => 'Eko Prasetyo',
            'kelas_id' => 3,
        ]);
    }
}