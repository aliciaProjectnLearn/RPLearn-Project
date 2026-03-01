<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubjectCategory;

class SubjectCategorySeeder extends Seeder
{
    public function run(): void
    {
        SubjectCategory::insert([
            ['subject' => 'Web Development', 'created_at' => now(), 'updated_at' => now()],
            ['subject' => 'Mobile Development', 'created_at' => now(), 'updated_at' => now()],
            ['subject' => 'UI/UX Design', 'created_at' => now(), 'updated_at' => now()],
            ['subject' => 'Database', 'created_at' => now(), 'updated_at' => now()],
            ['subject' => 'Basic Programming', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}