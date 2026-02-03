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
        // --- 1. Seeder User (Guru & Siswa) ---
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

        $admin = User::create([
            'username' => 'Admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // --- 2. Seeder Student ---
        Student::create([
            'user_id' => $userSiswa->id,
            'nis' => '12345678',
            'name' => 'Budi Santoso',
            'kelas' => 'XII RPL 1',
        ]);

        // --- 3. Seeder Kategori Kelas ---
        $g10 = GradeCategory::create(['grade' => 'Kelas 10']);
        $g11 = GradeCategory::create(['grade' => 'Kelas 11']);
        $g12 = GradeCategory::create(['grade' => 'Kelas 12']);

        // --- 4. Seeder Kategori Mapel ---
        $sWeb = SubjectCategory::create(['subject' => 'Web Development']);
        $sDesign = SubjectCategory::create(['subject' => 'UI/UX Design']);
        $sMobile = SubjectCategory::create(['subject' => 'Mobile App']);

        // --- 5. Seeder Modul Pembelajaran (Bervariasi) ---

        // Modul 1: Kelas 12 - Web (Data yang kamu buat tadi)
        $m1 = Module::create([
            'teacher_id' => $guru->id,
            'grade_category_id' => $g12->id,
            'subject_category_id' => $sWeb->id,
            'title' => 'Belajar Laravel Dasar',
            'desc' => 'Modul pengenalan framework Laravel untuk pemula dari nol sampai mahir.',
            'track' => 'BE',
            'is_published' => true,
            'like' => 15,
        ]);

        // Modul 2: Kelas 10 - UI/UX (Hanya Video)
        $m2 = Module::create([
            'teacher_id' => $guru->id,
            'grade_category_id' => $g10->id,
            'subject_category_id' => $sDesign->id,
            'title' => 'Dasar Desain Figma',
            'desc' => 'Mengenal tools Figma untuk membuat interface aplikasi yang cantik.',
            'track' => 'FE',
            'is_published' => true,
            'like' => 8,
        ]);

        // Modul 3: Kelas 11 - Mobile (Hanya PDF)
        $m3 = Module::create([
            'teacher_id' => $guru->id,
            'grade_category_id' => $g11->id,
            'subject_category_id' => $sMobile->id,
            'title' => 'React Native Foundation',
            'desc' => 'Cara membuat aplikasi Android dan iOS dengan satu codebase JavaScript.',
            'track' => 'FE',
            'is_published' => true,
            'like' => 12,
        ]);

        // --- 6. Seeder Isi Materi (Dengan YouTube & PDF) ---

        // Konten Modul 1 (Laravel) - Lengkap YouTube + PDF
        ModuleContent::create([
            'module_id' => $m1->id,
            'title' => 'Instalasi & Struktur Folder',
            'content' => 'Video ini menjelaskan cara instalasi Laravel 11 terbaru.',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'file_path' => 'modul/laravel-docs.pdf'
        ]);

        // Konten Modul 2 (Figma) - Hanya YouTube
        ModuleContent::create([
            'module_id' => $m2->id,
            'title' => 'Membuat Frame & Shape',
            'content' => 'Langkah awal membuat workspace di Figma.',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        ]);

        // Konten Modul 3 (Mobile) - Hanya PDF
        ModuleContent::create([
            'module_id' => $m3->id,
            'title' => 'Konsep Props & State',
            'content' => 'Materi mendalam mengenai data flow di React Native.',
            'file_path' => 'modul/react-native-guide.pdf'
        ]);

        $this->call([
            DictionarySeeder::class,
        ]);
    }
}
