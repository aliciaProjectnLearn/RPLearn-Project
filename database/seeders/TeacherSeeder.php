<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        Teacher::create([
            'user_id' => 6,
            'name'    => 'Dwi Putri Handayani',
            'nip'     => '19870001',
        ]);

        Teacher::create([
            'user_id' => 7,
            'name'    => 'Afika Awwaliyah Rahman',
            'nip'     => '19870002',
        ]);
    }
}