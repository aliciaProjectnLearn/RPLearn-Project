<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dictionary;

class DictionarySeeder extends Seeder
{
    public function run(): void
    {
        $dictionaries = [
            [
                'term' => 'API',
                'definition' => 'Application Programming Interface, mekanisme yang memungkinkan dua aplikasi saling berkomunikasi.'
            ],
            [
                'term' => 'Array',
                'definition' => 'Struktur data yang digunakan untuk menyimpan sekumpulan nilai dalam satu variabel.'
            ],
            [
                'term' => 'CSS',
                'definition' => 'Cascading Style Sheets, digunakan untuk mengatur tampilan dan layout halaman web.'
            ],
            [
                'term' => 'Database',
                'definition' => 'Sekumpulan data yang disimpan secara terstruktur dan dapat diakses serta dikelola dengan mudah.'
            ],
            [
                'term' => 'Framework',
                'definition' => 'Kerangka kerja yang membantu developer dalam membangun aplikasi secara lebih cepat dan terstruktur.'
            ],
            [
                'term' => 'HTML',
                'definition' => 'HyperText Markup Language, bahasa markup untuk membuat struktur halaman web.'
            ],
            [
                'term' => 'JavaScript',
                'definition' => 'Bahasa pemrograman yang digunakan untuk membuat halaman web menjadi interaktif.'
            ],
            [
                'term' => 'Laravel',
                'definition' => 'Framework PHP berbasis MVC yang digunakan untuk pengembangan aplikasi web.'
            ],
            [
                'term' => 'MySQL',
                'definition' => 'Sistem manajemen basis data relasional yang menggunakan bahasa SQL.'
            ],
            [
                'term' => 'Variable',
                'definition' => 'Wadah untuk menyimpan nilai yang dapat digunakan dan diubah selama program berjalan.'
            ],
        ];

        Dictionary::insert($dictionaries);
    }
}
