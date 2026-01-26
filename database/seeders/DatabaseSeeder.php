<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use App\Models\GradeCategory;
use App\Models\SubjectCategory;
use App\Models\Module;
use App\Models\ModuleContent;
use Illuminate\Support\Facades\Hash;
use App\Models\Dictionary;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seeder User (Guru & Siswa)
        $guru = User::create([
            'username' => 'guru_rpl',
            'password' => Hash::make('password123'),
            'role' => 'guru',
        ]);

        $userSiswa = User::create([
            'username' => 'siswa_rpl',
            'password' => Hash::make('password123'),
            'role' => 'siswa',
        ]);

        // 2. Seeder Student
        Student::create([
            'user_id' => $userSiswa->id,
            'nis' => '12345678',
            'name' => 'Budi Santoso',
            'kelas' => 'XII RPL 1',
        ]);

        // 3. Seeder Kategori Kelas & Mapel
        $grade = GradeCategory::create([
            'grade' => 'Kelas 12'
        ]);

        $subject = SubjectCategory::create([
            'subject' => 'Web Development'
        ]);

        // 4. Seeder Modul Pembelajaran
        $module = Module::create([
            'teacher_id' => $guru->id,
            'grade_category_id' => $grade->id,
            'subject_category_id' => $subject->id,
            'title' => 'Belajar Laravel Dasar',
            'desc' => 'Modul pengenalan framework Laravel untuk pemula.',
            'track' => 'BE',
            'is_published' => true,
            'like' => 10,
        ]);

        // 5. Seeder Isi Materi
        ModuleContent::create([
            'module_id' => $module->id,
            'title' => 'Instalasi Laravel',
            'content' => 'Langkah-langkah instalasi Laravel menggunakan composer...',
        ]);

        $this->call([
            DictionarySeeder::class,
        ]);
    }
}
