<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Answer;

class FAQSeeder extends Seeder
{
    public function run(): void
    {
        // =========================
        // FAQ YANG SUDAH DIJAWAB
        // =========================
        $answeredFaqs = [
            [
                'title' => 'Tentang RPLearn',
                'question' => 'Apa itu RPLearn?',
                'answer' => 'RPLearn adalah platform pembelajaran untuk siswa RPL.',
            ],
            [
                'title' => 'Cara Belajar',
                'question' => 'Bagaimana cara belajar di RPLearn?',
                'answer' => 'Siswa dapat belajar melalui modul dan latihan yang tersedia.',
            ],
            [
                'title' => 'Akses',
                'question' => 'Apakah RPLearn gratis?',
                'answer' => 'Ya, RPLearn dapat digunakan secara gratis.',
            ],
        ];

        foreach ($answeredFaqs as $faq) {
            $question = Question::create([
                'student_id' => 1,
                'teacher_id' => 1,
                'module_id' => 1,
                'title' => $faq['title'],
                'question' => $faq['question'],
                'status' => 'answered',
            ]);

            Answer::create([
                'question_id' => $question->id,
                'teacher_id' => 1,
                'answer' => $faq['answer'],
            ]);
        }

        // =========================
        // PERTANYAAN BELUM DIJAWAB
        // =========================
        $unansweredQuestions = [
            [
                'title' => 'Akun',
                'question' => 'Bagaimana cara reset password?',
            ],
            [
                'title' => 'Materi',
                'question' => 'Apakah ada materi backend Laravel?',
            ],
            [
                'title' => 'Sertifikat',
                'question' => 'Apakah setelah selesai belajar dapat sertifikat?',
            ],
        ];

        foreach ($unansweredQuestions as $q) {
            Question::create([
                'student_id' => 1,
                'teacher_id' => 1,
                'module_id' => 1,
                'title' => $q['title'],
                'question' => $q['question'],
                'status' => 'pending', // atau 'unanswered'
            ]);
        }
    }
}
