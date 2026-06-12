<?php

// Define inline mock models so that we don't need database tables or custom files in app/Models/
namespace App\Models {
    class Course {
        public $id, $name, $subject, $room, $code, $color, $icon, $teacher_name, $level, $creator_id, $progress, $users_count, $tasks_count, $materials, $tasks, $users, $created_at;
        public function __construct($attributes = []) {
            foreach ($attributes as $key => $val) {
                $this->$key = $val;
            }
        }
    }
    class Material {
        public $id, $course_id, $title, $description, $link_url, $file_name, $created_at, $comments;
        public function __construct($attributes = []) {
            foreach ($attributes as $key => $val) {
                $this->$key = $val;
            }
        }
    }
    class Task {
        public $id, $course_id, $title, $description, $due_date, $status, $score, $feedback, $created_at, $comments, $course;
        public function __construct($attributes = []) {
            foreach ($attributes as $key => $val) {
                $this->$key = $val;
            }
        }
        public function scoreGrade(): string {
            if ($this->score === null) return '-';
            if ($this->score >= 90) return 'A';
            if ($this->score >= 80) return 'B';
            if ($this->score >= 70) return 'C';
            if ($this->score >= 60) return 'D';
            return 'E';
        }
        public function getStatusBgAttribute() {
            $map = ['Selesai' => '#dcfce7', 'Draft' => '#fef3c7', 'Belum' => '#fee2e2'];
            return $map[$this->status] ?? '#fee2e2';
        }
        public function getStatusColorAttribute() {
            $map = ['Selesai' => '#16a34a', 'Draft' => '#b45309', 'Belum' => '#dc2626'];
            return $map[$this->status] ?? '#dc2626';
        }
        public function getStatusIconAttribute() {
            $map = ['Selesai' => 'bi-check-circle-fill', 'Draft' => 'bi-pencil-square', 'Belum' => 'bi-clock-fill'];
            return $map[$this->status] ?? 'bi-clock-fill';
        }
        public function getScoreColorAttribute() {
            if ($this->score === null) return '#94a3b8';
            if ($this->score >= 90) return '#16a34a';
            if ($this->score >= 80) return '#1F7A8C';
            if ($this->score >= 70) return '#b45309';
            return '#dc2626';
        }
        public function __get($key) {
            $method = 'get' . str_replace('_', '', ucwords($key, '_')) . 'Attribute';
            if (method_exists($this, $method)) {
                return $this->$method();
            }
            return null;
        }
    }
}

namespace {
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Auth;
    use App\Models\User;
    use App\Models\Course;
    use App\Models\Material;
    use App\Models\Task;

    // Mensimulasikan user terautentikasi secara global untuk kemudahan slicing (bypass DB auth)
    if (!request()->is('login') && !request()->is('register') && !request()->is('forgot-password')) {
        Auth::setUser((new User())->forceFill([
            'id' => 1,
            'name' => 'Reza Gunawan',
            'email' => 'test@example.com',
        ]));
    }

    // Helper untuk merakit Mock data Kelas
    if (!function_exists('getMockCourses')) {
        function getMockCourses() {
            $currentUser = (new User())->forceFill([
                'id' => 1,
                'name' => 'Reza Gunawan',
                'email' => 'test@example.com',
            ]);
            $currentUser->pivot = (object) ['role' => 'teacher'];

            $currentUserStudent = (new User())->forceFill([
                'id' => 1,
                'name' => 'Reza Gunawan',
                'email' => 'test@example.com',
            ]);
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

    // Helper untuk merakit Mock User utama
    if (!function_exists('getMockUser')) {
        function getMockUser() {
            return (new User())->forceFill([
                'id' => 1,
                'name' => 'Reza Gunawan',
                'email' => 'test@example.com',
            ]);
        }
    }

    // Redirect beranda ke dashboard
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    // Rute Otentikasi (Mock tampilan guest)
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', function () {
        return redirect()->route('dashboard')->with('success', 'Selamat datang kembali!');
    });

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

    Route::post('/register', function () {
        return redirect()->route('dashboard')->with('success', 'Pendaftaran berhasil!');
    });

    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');

    Route::post('/logout', function () {
        return redirect()->route('login')->with('success', 'Anda telah keluar.');
    })->name('logout');

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard', [
            'coursesCount' => 2,
            'tasksCount' => 2,
            'recentActivities' => [
                [
                    'color_bg' => '#ceeaf0',
                    'icon' => 'bi-calculator',
                    'text_color' => 'text-primary',
                    'title' => 'Bu Sari Dewi membagikan materi baru: Bab 2: Sistem Persamaan Linear Tiga Variabel (SPLTV)',
                    'time' => '1 jam yang lalu',
                    'badge_bg' => '#ceeaf0',
                    'badge_color' => '#1F7A8C',
                    'badge' => 'Materi'
                ],
                [
                    'color_bg' => '#fee2e2',
                    'icon' => 'bi-file-earmark-check',
                    'text_color' => 'text-danger',
                    'title' => 'Pak Budi Santoso memposting tugas baru: Essay – My Hometown',
                    'time' => '3 jam yang lalu',
                    'badge_bg' => '#fee2e2',
                    'badge_color' => '#dc2626',
                    'badge' => 'Tugas'
                ]
            ]
        ]);
    })->name('dashboard');

    // Daftar Kelas Saya (My Courses)
    Route::get('/kelas', function () {
        return view('kelas', [
            'courses' => getMockCourses()
        ]);
    })->name('courses');

    // Detail Kelas
    Route::get('/kelas/{id}', function ($id) {
        $course = getMockCourses()->firstWhere('id', $id);
        if (!$course) abort(404);
        
        $userRole = ($course->creator_id === 1) ? 'teacher' : 'student';
        
        $teachers = $course->users->filter(fn($u) => $u->pivot->role === 'teacher');
        $students = $course->users->filter(fn($u) => $u->pivot->role === 'student');

        // Pre-calculate upcoming tasks
        $upcomingTasks = $course->tasks->filter(fn($t) => $t->status !== 'Selesai')->take(2);

        // Pre-calculate stream activity feed items
        $feedItems = collect();
        foreach($course->materials as $mat) {
            $feedItems->push([
                'id' => $mat->id,
                'type' => 'materi',
                'title' => $course->teacher_name . ' membagikan materi baru',
                'content' => $mat->title,
                'desc' => $mat->description,
                'date' => $mat->created_at,
                'icon' => 'bi-file-earmark-text',
                'bg' => '#ceeaf0',
                'color' => '#1F7A8C'
            ]);
        }
        foreach($course->tasks as $tsk) {
            $feedItems->push([
                'id' => $tsk->id,
                'type' => 'tugas',
                'title' => $course->teacher_name . ' memposting tugas baru',
                'content' => $tsk->title,
                'desc' => $tsk->description,
                'date' => $tsk->created_at,
                'icon' => 'bi-clipboard-check',
                'bg' => '#fee2e2',
                'color' => '#dc2626'
            ]);
        }
        $feedItems = $feedItems->sortByDesc('date');

        return view('kelas_details', [
            'course' => $course,
            'userRole' => $userRole,
            'teachers' => $teachers,
            'students' => $students,
            'upcomingTasks' => $upcomingTasks,
            'feedItems' => $feedItems
        ]);
    })->name('courses.show');

    // Mock Aksi (Buat Kelas, Gabung Kelas, Hapus, etc.)
    Route::post('/kelas/create', function () {
        return redirect()->route('courses')->with('success', 'Kelas berhasil dibuat!');
    })->name('courses.create');

    Route::post('/kelas/join', function () {
        return redirect()->route('courses')->with('success', 'Berhasil bergabung dengan kelas!');
    })->name('courses.join');

    Route::delete('/kelas/{id}', function () {
        return redirect()->route('courses')->with('success', 'Kelas berhasil dihapus!');
    })->name('courses.destroy');

    Route::post('/kelas/{id}/materials', function () {
        return back()->with('success', 'Materi berhasil dibagikan!');
    })->name('materials.store');

    Route::post('/kelas/{id}/tasks', function () {
        return back()->with('success', 'Tugas berhasil dibuat!');
    })->name('tasks.store');

    // Detail Materi
    Route::get('/kelas/{course_id}/materials/{material_id}', function ($course_id, $material_id) {
        $course = getMockCourses()->firstWhere('id', $course_id);
        if (!$course) abort(404);
        
        $material = $course->materials->firstWhere('id', $material_id);
        if (!$material) abort(404);
        
        $userRole = ($course->creator_id === 1) ? 'teacher' : 'student';

        $otherMaterials = $course->materials->where('id', '!=', $material->id)->take(4);

        return view('material_details', [
            'course' => $course,
            'material' => $material,
            'userRole' => $userRole,
            'otherMaterials' => $otherMaterials
        ]);
    })->name('materials.show');

    // Detail Tugas
    Route::get('/kelas/{course_id}/tasks/{task_id}', function ($course_id, $task_id) {
        $course = getMockCourses()->firstWhere('id', $course_id);
        if (!$course) abort(404);
        
        $task = $course->tasks->firstWhere('id', $task_id);
        if (!$task) abort(404);
        
        $userRole = ($course->creator_id === 1) ? 'teacher' : 'student';

        $otherTasks = $course->tasks->where('id', '!=', $task->id)->take(4);

        return view('task_details', [
            'course' => $course,
            'task' => $task,
            'userRole' => $userRole,
            'otherTasks' => $otherTasks
        ]);
    })->name('tasks.show');

    Route::post('/kelas/{course_id}/{type}/{item_id}/comments', function () {
        return back()->with('success', 'Komentar berhasil dikirim!');
    })->name('comments.store');

    Route::post('/kelas/{course_id}/kick/{user_id}', function () {
        return back()->with('success', 'Siswa berhasil dikeluarkan!');
    })->name('courses.kick');

    Route::post('/kelas/{course_id}/leave', function () {
        return redirect()->route('courses')->with('success', 'Berhasil keluar dari kelas!');
    })->name('courses.leave');

    // Classwork / Daftar Tugas Keseluruhan
    Route::get('/tasks', function () {
        $courses = getMockCourses();
        $allTasks = collect();
        foreach ($courses as $c) {
            foreach ($c->tasks as $t) {
                $t->course = $c;
                $allTasks->push($t);
            }
        }
        $taskGroups = [
            [
                'label' => 'Tugas Belum Selesai',
                'badge_bg' => '#fee2e2',
                'badge_color' => '#dc2626',
                'tasks' => $allTasks->filter(fn($t) => $t->status !== 'Selesai')
            ],
            [
                'label' => 'Tugas Selesai',
                'badge_bg' => '#dcfce7',
                'badge_color' => '#16a34a',
                'tasks' => $allTasks->filter(fn($t) => $t->status === 'Selesai')
            ]
        ];
        return view('tasks', [
            'taskGroups' => $taskGroups
        ]);
    })->name('tasks');

    // Settings
    Route::get('/settings', function () {
        return view('settings');
    })->name('settings');

    // Report / Rekap Nilai
    Route::get('/report', function () {
        $courses = getMockCourses();
        
        $overallAvg = 83.75;
        $overallGrade = 'B';
        $overallGradeColor = '#1F7A8C';

        $course1 = $courses->firstWhere('id', 1);
        $course2 = $courses->firstWhere('id', 2);

        $reportData = [
            (object)[
                'course' => $course1,
                'tasks' => $course1->tasks,
                'allTasks' => 4,
                'selesai' => 3,
                'graded' => 3,
                'avgScore' => 85,
                'avgScoreGrade' => 'B',
                'avgScoreColor' => '#1F7A8C',
                'maxScore' => 92,
                'minScore' => 75,
                'progress' => 75,
            ],
            (object)[
                'course' => $course2,
                'tasks' => $course2->tasks,
                'allTasks' => 2,
                'selesai' => 1,
                'graded' => 1,
                'avgScore' => 80,
                'avgScoreGrade' => 'B',
                'avgScoreColor' => '#16a34a',
                'maxScore' => 80,
                'minScore' => 80,
                'progress' => 50,
            ]
        ];

        return view('report', [
            'overallAvg' => $overallAvg,
            'overallGrade' => $overallGrade,
            'overallGradeColor' => $overallGradeColor,
            'totalTasks' => 6,
            'totalSelesai' => 4,
            'totalGraded' => 4,
            'reportData' => $reportData
        ]);
    })->name('report');

    // Admin Control Panel
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', function () {
            $courses = getMockCourses();
            
            $currentUser = getMockUser();
            $currentUser->created_at = now()->subYear();
            
            $teacherUser = (new User())->forceFill([
                'id' => 2,
                'name' => 'Pak Budi Santoso',
                'email' => 'budi@example.com',
                'created_at' => now()->subMonths(2)
            ]);
            
            $studentUser1 = (new User())->forceFill([
                'id' => 3,
                'name' => 'Siti Aminah',
                'email' => 'siti@example.com',
                'created_at' => now()->subMonth()
            ]);

            return view('admin.dashboard', [
                'usersCount' => 4,
                'coursesCount' => 2,
                'materialsCount' => 3,
                'tasksCount' => 6,
                'recentCourses' => $courses,
                'recentUsers' => collect([$currentUser, $teacherUser, $studentUser1])
            ]);
        })->name('admin.dashboard');

        Route::get('/users', function () {
            $currentUser = getMockUser();
            
            $teacherUser = (new User())->forceFill([
                'id' => 2,
                'name' => 'Pak Budi Santoso',
                'email' => 'budi@example.com',
                'created_at' => now()->subMonths(2)
            ]);
            $teacherUser->courses_count = 1;

            $studentUser1 = (new User())->forceFill([
                'id' => 3,
                'name' => 'Siti Aminah',
                'email' => 'siti@example.com',
                'created_at' => now()->subMonth()
            ]);
            $studentUser1->courses_count = 2;

            $studentUser2 = (new User())->forceFill([
                'id' => 4,
                'name' => 'Rahmat Hidayat',
                'email' => 'rahmat@example.com',
                'created_at' => now()->subDays(15)
            ]);
            $studentUser2->courses_count = 1;

            $currentUser->courses_count = 2;
            $currentUser->created_at = now()->subYear();

            return view('admin.users', [
                'users' => collect([$currentUser, $teacherUser, $studentUser1, $studentUser2])
            ]);
        })->name('admin.users');

        Route::delete('/users/{id}', function () {
            return back()->with('success', 'User berhasil dihapus!');
        })->name('admin.users.delete');

        Route::post('/users/{id}/toggle-role', function () {
            return back()->with('success', 'Peran user berhasil diubah!');
        })->name('admin.users.toggle-role');

        Route::get('/kelas', function () {
            return view('admin.kelas', [
                'courses' => getMockCourses()
            ]);
        })->name('admin.courses');

        Route::delete('/kelas/{id}', function () {
            return back()->with('success', 'Kelas berhasil dihapus oleh admin!');
        })->name('admin.courses.delete');

        Route::delete('/kelas/{course_id}/kick/{user_id}', function () {
            return back()->with('success', 'Anggota berhasil dikeluarkan oleh admin!');
        })->name('admin.courses.kick');
    });
}
