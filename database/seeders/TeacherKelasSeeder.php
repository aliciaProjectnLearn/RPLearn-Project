<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeacherKelasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('teacher_kelas')->insert([
            [
                'teacher_id' => 1,
                'kelas_id'   => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'teacher_id' => 1,
                'kelas_id'   => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'teacher_id' => 2,
                'kelas_id'   => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}