<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\ModuleContent;

class ModuleContentSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil module berdasarkan title (lebih aman daripada hardcode ID)
        $laravelModule = Module::where('title', 'Dasar Laravel')->first();
        $figmaModule   = Module::where('title', 'UI/UX dengan Figma')->first();
        $apiModule     = Module::where('title', 'API dengan Laravel')->first();

        if ($laravelModule) {
            ModuleContent::create([
                'module_id' => $laravelModule->id,
                'title' => 'Instalasi Laravel',
                'content' => 'Cara install Laravel menggunakan Composer.',
                'order' => 1,
                'video_url' => 'https://youtube.com/example1',
                'file_path' => null,
            ]);

            ModuleContent::create([
                'module_id' => $laravelModule->id,
                'title' => 'Routing Dasar',
                'content' => 'Memahami konsep routing di Laravel.',
                'order' => 2,
                'video_url' => null,
                'file_path' => 'files/routing.pdf',
            ]);
        }

        if ($figmaModule) {
            ModuleContent::create([
                'module_id' => $figmaModule->id,
                'title' => 'Pengenalan Figma',
                'content' => 'Mengenal tools dan interface Figma.',
                'order' => 1,
                'video_url' => 'https://youtube.com/example2',
                'file_path' => null,
            ]);
        }

        if ($apiModule) {
            ModuleContent::create([
                'module_id' => $apiModule->id,
                'title' => 'Membuat REST API',
                'content' => 'Cara membuat REST API sederhana di Laravel.',
                'order' => 1,
                'video_url' => null,
                'file_path' => 'files/rest-api.pdf',
            ]);
        }
    }
}