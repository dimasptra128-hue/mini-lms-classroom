<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MockData;
use App\Models\User;
use App\Models\Course;
use App\Models\Material;
use App\Models\Task;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil data mock asli, lalu paksa konversi total menjadi Array PHP murni
        $rawCourses = MockData::getMockCourses();
        $mockCourses = json_decode(json_encode($rawCourses), true);

        // 2. Masukkan semua User unik terlebih dahulu agar tidak terjadi error Foreign Key
        $usersData = [
            ['id' => 1, 'name' => 'Reza Gunawan', 'email' => 'test@example.com'],
            ['id' => 2, 'name' => 'Pak Budi Santoso', 'email' => 'budi@example.com'],
            ['id' => 3, 'name' => 'Siti Aminah', 'email' => 'siti@example.com'],
            ['id' => 4, 'name' => 'Rahmat Hidayat', 'email' => 'rahmat@example.com'],
        ];

        foreach ($usersData as $u) {
            User::updateOrCreate(
                ['id' => $u['id']],
                [
                    'name' => $u['name'],
                    'email' => $u['email'],
                    'password' => Hash::make('password'), // Password bawaan untuk login testing
                    'role' => $u['role'] ?? 'student',
                ]
            );
        }

        // Pastikan akun admin default tersedia
        $this->call(\Database\Seeders\AdminUserSeeder::class);

        // 3. Looping dan simpan data Course beserta relasinya (Materials, Tasks, Users)
        foreach ($mockCourses as $courseData) {
            
            // Pisahkan data relasi array agar tidak ikut masuk ke kolom utama tabel 'courses'
            $materials = $courseData['materials'] ?? [];
            $tasks = $courseData['tasks'] ?? [];
            $users = $courseData['users'] ?? [];

            // Bersihkan isi array courseData dari key relasi agar tidak memicu error 'column not found'
            unset($courseData['materials'], $courseData['tasks'], $courseData['users']);

            // Simpan atau update data Course ke database
            $course = Course::updateOrCreate(
                ['id' => $courseData['id']],
                $courseData
            );

            // Simpan data Materials yang terikat pada course ini
            foreach ($materials as $materialData) {
                // Pastikan foreign key terikat ke id course yang benar
                $materialData['course_id'] = $course->id;
                
                // Hapus key komentar/comments jika data tersebut berupa array/object kompleks yang belum ada kolomnya
                unset($materialData['comments']);

                Material::updateOrCreate(
                    ['id' => $materialData['id']],
                    $materialData
                );
            }

            // Simpan data Tasks yang terikat pada course ini
            foreach ($tasks as $taskData) {
                // Pastikan foreign key terikat ke id course yang benar
                $taskData['course_id'] = $course->id;

                // Pastikan field JSON 'comments' diconvert ke string/json jika tipenya array kompleks
                if (isset($taskData['comments']) && is_array($taskData['comments'])) {
                    $taskData['comments'] = json_encode($taskData['comments']);
                }
                
                // Hilangkan instansiasi objek relasi berulang jika ada di dalam mock
                unset($taskData['course']);

                Task::updateOrCreate(
                    ['id' => $taskData['id']],
                    $taskData
                );
            }

            // Sinkronisasi hubungan Many-to-Many ke tabel pivot (course_user) beserta rolenya
            foreach ($users as $userData) {
                // Ambil role dari data pivot bawaan mock, default ke student
                $role = $userData['pivot']['role'] ?? 'student';
                
                // Sambungkan id user ke id course melalui tabel pivot Eloquent
                $course->users()->syncWithoutDetaching([
                    $userData['id'] => ['role' => $role]
                ]);
            }
        }
    }
}