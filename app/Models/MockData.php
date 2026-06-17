<?php

namespace App\Models;

class MockData
{
    public static function getMockUser()
    {
        return (new User())->forceFill([
            'id' => 1,
            'name' => 'Reza Gunawan',
            'email' => 'test@example.com',
        ]);
    }

    public static function getMockCourses()
    {
        $currentUser = self::getMockUser();
        $currentUser->pivot = (object) ['role' => 'teacher'];

        $currentUserStudent = self::getMockUser();
        $currentUserStudent->pivot = (object) ['role' => 'student'];

        $teacherUser = (new User())->forceFill([
            'id' => 2,
            'name' => 'Pak Budi Santoso',
            'email' => 'budi@example.com',
        ]);
        $teacherUser->pivot = (object) ['role' => 'teacher'];

        $studentUser1 = (new User())->forceFill([
            'id' => 3,
            'name' => 'Siti Aminah',
            'email' => 'siti@example.com',
        ]);
        $studentUser1->pivot = (object) ['role' => 'student'];

        $studentUser2 = (new User())->forceFill([
            'id' => 4,
            'name' => 'Rahmat Hidayat',
            'email' => 'rahmat@example.com',
        ]);
        $studentUser2->pivot = (object) ['role' => 'student'];

        // Matematika
        $course1 = new Course([
            'id' => 1,
            'name' => 'Matematika',
            'subject' => 'Matematika Wajib',
            'room' => '10A',
            'code' => 'MATE10',
            'color' => '#1F7A8C',
            'icon' => 'bi-calculator',
            'teacher_name' => 'Bu Sari Dewi',
            'level' => 'Kelas 10 · Semester 2',
            'creator_id' => 1, // Reza is creator
            'progress' => 65,
            'created_at' => now()->subMonths(3),
        ]);
        $course1->users_count = 12;

        $mat1 = new Material([
            'id' => 1,
            'course_id' => 1,
            'title' => 'Bab 1: Persamaan dan Pertidaksamaan Nilai Mutlak',
            'description' => 'Materi pembelajaran tentang konsep dasar nilai mutlak, dilengkapi dengan contoh soal beserta pembahasannya. Silakan dipelajari secara mandiri.',
            'link_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'created_at' => now()->subDays(5)
        ]);
        $mat1->comments = collect([]);

        $mat2 = new Material([
            'id' => 2,
            'course_id' => 1,
            'title' => 'Bab 2: Sistem Persamaan Linear Tiga Variabel (SPLTV)',
            'description' => 'Modul lengkap mengenai cara penyelesaian SPLTV menggunakan metode eliminasi, substitusi, dan campuran.',
            'file_name' => 'Modul_SPLTV_Lengkap.pdf',
            'created_at' => now()->subHours(2)
        ]);
        $mat2->comments = collect([]);

        $task1 = new Task([
            'id' => 1,
            'course_id' => 1,
            'title' => 'Latihan Soal – Fungsi Kuadrat',
            'description' => 'Kerjakan 5 soal essay pada halaman 42 buku cetak Matematika Wajib.',
            'due_date' => 'Besok, 23:59',
            'status' => 'Belum',
            'score' => null,
            'feedback' => null,
            'created_at' => now()->subDays(1)
        ]);
        $task1->comments = collect([]);

        $task2 = new Task([
            'id' => 2,
            'course_id' => 1,
            'title' => 'Quiz – Persamaan Linear',
            'description' => 'Quiz singkat mengenai konsep persamaan linear satu variabel.',
            'due_date' => 'Dikumpulkan 3 hari lalu',
            'status' => 'Selesai',
            'score' => 88,
            'feedback' => 'Hasil sangat memuaskan! Pemahaman konsep sudah baik, teruskan.',
            'created_at' => now()->subDays(4)
        ]);
        $task2->comments = collect([]);

        $task3 = new Task([
            'id' => 3,
            'course_id' => 1,
            'title' => 'Ulangan Harian – Nilai Mutlak',
            'description' => 'Kerjakan soal ulangan harian tentang nilai mutlak dan pertidaksamaan.',
            'due_date' => 'Dikumpulkan seminggu lalu',
            'status' => 'Selesai',
            'score' => 92,
            'feedback' => 'Luar biasa! Semua soal dikerjakan dengan benar.',
            'created_at' => now()->subDays(7)
        ]);
        $task3->comments = collect([]);

        $task4 = new Task([
            'id' => 4,
            'course_id' => 1,
            'title' => 'PR – Sistem Persamaan Linear',
            'description' => 'Selesaikan 10 soal SPLTV dari buku latihan halaman 78.',
            'due_date' => 'Dikumpulkan 2 minggu lalu',
            'status' => 'Selesai',
            'score' => 75,
            'feedback' => 'Cukup baik, namun perlu lebih teliti dalam langkah eliminasi.',
            'created_at' => now()->subDays(14)
        ]);
        $task4->comments = collect([]);

        $course1->materials = collect([$mat1, $mat2]);
        $course1->tasks = collect([$task1, $task2, $task3, $task4]);
        $course1->tasks_count = 1;

        // Bahasa Inggris
        $course2 = new Course([
            'id' => 2,
            'name' => 'Bahasa Inggris',
            'subject' => 'Bahasa Inggris Peminatan',
            'room' => '10B',
            'code' => 'BING10',
            'color' => '#16a34a',
            'icon' => 'bi-translate',
            'teacher_name' => 'Pak Budi Santoso',
            'level' => 'Kelas 10 · Semester 2',
            'creator_id' => 2, // Pak Budi is creator
            'progress' => 80,
            'created_at' => now()->subMonths(2),
        ]);
        $course2->users_count = 15;

        $mat3 = new Material([
            'id' => 3,
            'course_id' => 2,
            'title' => 'Unit 1: Self-Introduction and Talking about Family',
            'description' => 'This material covers vocabulary and sentence patterns used when introducing yourself and talking about your immediate/extended family in formal situations.',
            'created_at' => now()->subDays(10)
        ]);
        $mat3->comments = collect([]);

        $task5 = new Task([
            'id' => 5,
            'course_id' => 2,
            'title' => 'Essay – My Hometown',
            'description' => 'Write a short descriptive text about your hometown (min. 150 words).',
            'due_date' => "Jum'at, 23:59",
            'status' => 'Draft',
            'score' => null,
            'feedback' => null,
            'created_at' => now()->subDays(2)
        ]);
        $task5->comments = collect([]);

        $task6 = new Task([
            'id' => 6,
            'course_id' => 2,
            'title' => 'Reading Comprehension – Unit 2',
            'description' => 'Baca teks bacaan Unit 2 dan jawab 10 pertanyaan pilihan ganda.',
            'due_date' => 'Dikumpulkan minggu lalu',
            'status' => 'Selesai',
            'score' => 80,
            'feedback' => 'Good job! Perhatikan penggunaan tenses dengan lebih cermat.',
            'created_at' => now()->subDays(7)
        ]);
        $task6->comments = collect([]);

        $course2->materials = collect([$mat3]);
        $course2->tasks = collect([$task5, $task6]);
        $course2->tasks_count = 1;

        // Set pivot relations
        $course1->users = collect([$currentUser, $studentUser1, $studentUser2]);
        $course2->users = collect([$teacherUser, $currentUserStudent, $studentUser1]);

        return collect([$course1, $course2]);
    }
}
