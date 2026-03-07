<?php

namespace Database\Seeders;
use App\Models\Dictionary;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Kelas;
use App\Models\SubjectCategory;
use App\Models\Module;
use App\Models\ModuleContent;

use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
    $this->call([
        UserSeeder::class,
        KelasSeeder::class,
        TeacherSeeder::class,
        TeacherKelasSeeder::class,
        StudentSeeder::class,
        DictionarySeeder::class,
        SubjectCategorySeeder::class,
        ModuleSeeder::class,
        ModuleContentSeeder::class,
    ]);
    }
}
