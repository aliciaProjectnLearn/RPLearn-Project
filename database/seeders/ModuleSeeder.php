<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;

class ModuleSeeder extends Seeder
{
    public function run(): void
    {
        Module::create([
            'teacher_id' => 6,
            'grade_category_id' => null,
            'subject_category_id' => 1,
            'title' => 'Dasar Laravel',
            'desc' => 'Belajar Laravel dari instalasi sampai CRUD.',
            'track' => 'BE',
            'is_published' => true,
            'like' => 10,
            'kelas_id' => 1,
        ]);

        Module::create([
            'teacher_id' => 6,
            'grade_category_id' => null,
            'subject_category_id' => 2,
            'title' => 'UI/UX dengan Figma',
            'desc' => 'Belajar desain aplikasi pakai Figma.',
            'track' => 'FE',
            'is_published' => true,
            'like' => 5,
            'kelas_id' => 2,
        ]);

        Module::create([
            'teacher_id' => 6,
            'grade_category_id' => null,
            'subject_category_id' => 1,
            'title' => 'API dengan Laravel',
            'desc' => 'Membuat REST API di Laravel.',
            'track' => 'BE',
            'is_published' => false,
            'like' => 0,
            'kelas_id' => 1,
        ]);
    }
}