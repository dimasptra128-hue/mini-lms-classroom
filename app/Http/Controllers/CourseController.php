<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Material;
use App\Models\Task;
//use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index()
    {
        // Ambil hanya kelas yang dibuat oleh user atau yang diikuti user
        $user = auth()->user();
        $userId = $user->id;

        $courses = Course::with('creator') // ← tambah ini
            ->withCount(['users', 'tasks'])
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id)
                    ->orWhereHas('users', function ($q) use ($user) {
                        $q->where('users.id', $user->id);
                    });
            })
            ->get();
        
        foreach ($courses as $course) {

        // Jika user adalah pembuat kelas
        if ($course->creator_id == $userId) {
            $course->pending_tasks_count = 0;
            continue;
        }

        $pendingTasks = Task::where('course_id', $course->id)
            ->get()
            ->filter(function ($task) use ($userId) {
                $submissions = $task->submissions ?? [];

                if (is_string($submissions)) {
                    $submissions = json_decode($submissions, true) ?: [];
                }

                $submission = $submissions[$userId] ?? null;

                return !(
                    $submission &&
                    ($submission['status'] ?? null) === 'Selesai'
                );
            });

        $course->pending_tasks_count = $pendingTasks->count();
    }

        return view('kelas', compact('courses'));
    }

    public function show($id)
    {
        // 1. Ambil data kelas beserta relasi
        $course = Course::with([
            'creator',
            'materials',
            'tasks',
            'users'
        ])->find($id);

        // Cek apakah kelas ditemukan
        if (!$course) {
            abort(404, 'Kelas tidak ditemukan');
        }

        // 2. Validasi akses
        $isMember = $course->users()
            ->where('users.id', auth()->id())
            ->exists();

        if (
            !$isMember &&
            $course->creator_id !== auth()->id() &&
            auth()->user()->role !== 'admin'
        ) {
            abort(403, 'Anda bukan anggota kelas ini.');
        }

        // 3. Tentukan role user saat ini
        $userRole =
            ($course->creator_id === auth()->id())
            ? 'teacher'
            : 'student';

        // 4. Ambil pengajar & siswa
        $teachers = $course->users()
            ->wherePivot('role', 'teacher')
            ->get();

        $students = $course->users()
            ->wherePivot('role', 'student')
            ->get();

        // Fallback teacher
        if ($teachers->isEmpty()) {
            $creator = \App\Models\User::find($course->creator_id);

            if ($creator) {
                $teachers = collect([$creator]);
            } else {
                $teachers = collect([
                    (object)[
                        'name' => $course->teacher_name,
                        'email' => '',
                    ]
                ]);
            }
        }

        // 5. Ambil materi & tugas
        $materialModels = Material::where(
            'course_id',
            $course->id
        )
            ->latest()
            ->get();

        $taskModels = Task::where(
            'course_id',
            $course->id
        )
            ->orderBy('due_date')
            ->get();

        $upcomingTasks = $taskModels->take(3);

        // 6. Bangun feed alur kelas
        $feedItems = collect();

        foreach ($materialModels as $material) {
            $feedItems->push([
                'id' => $material->id,
                'type' => 'materi',
                'title' => 'Membagikan Materi Baru',
                'content' => $material->title,
                'desc' => \Illuminate\Support\Str::limit(
                    $material->description,
                    100
                ),
                'date' => $material->created_at,
                'icon' => 'bi-file-earmark-text-fill',
                'color' => $course->color,
                'bg' => $course->color . '15',
            ]);
        }

        foreach ($taskModels as $task) {
            $feedItems->push([
                'id' => $task->id,
                'type' => 'tugas',
                'title' => 'Membuat Tugas Baru',
                'content' => $task->title,
                'desc' => \Illuminate\Support\Str::limit(
                    $task->description,
                    100
                ),
                'date' => $task->created_at,
                'icon' => 'bi-clipboard-check-fill',
                'color' => '#f59e0b',
                'bg' => '#f59e0b15',
            ]);
        }

        $feedItems = $feedItems
            ->sortByDesc('date')
            ->values();

        // 7. Kirim ke view
        return view(
            'kelas_details',
            compact(
                'course',
                'userRole',
                'teachers',
                'students',
                'upcomingTasks',
                'feedItems',
                'taskModels',
                'materialModels'
            )
        );
    }
    public function create()
    {
        return view('kelas.create');
    }

    public function join()
    {
        $user = auth()->user();

        $code = request()->input('kode_kelas');
        if (!$code) {
            return redirect()->route('courses')->with('info', 'Kode kelas tidak disediakan.');
        }

        // Normalisasi kode (uppercase)
        $code = strtoupper(trim($code));

        $course = Course::where('code', $code)->first();

        if (!$course) {
            return redirect()->route('courses')->with('info', 'Kode kelas tidak ditemukan.');
        }

        // Cek apakah user sudah tergabung
        $already = $course->users()->where('users.id', $user->id)->exists();
        if ($already) {
            return redirect()->route('courses')->with('info', 'Kamu sudah tergabung di kelas ini.');
        }

        // Attach user ke kelas dengan peran student
        $course->users()->attach($user->id, ['role' => 'student']);

        return redirect()->route('courses')->with('success', 'Berhasil bergabung dengan kelas!');
    }

    public function destroy($id)
{
    // 1. Cari data kelasnya
    $course = Course::find($id);

    if (!$course) {
        return redirect()->to('/kelas')->with('error', 'Kelas tidak ditemukan.');
    }

    // 2. KUNCI UTAMA: Pastikan baris ini ada untuk menghapus data dari database!
    $course->delete();

    // 3. Kembalikan ke halaman daftar kelas dengan notifikasi sukses
    return redirect()->to('/kelas')->with('success', 'Kelas berhasil dihapus permanen.');
}

    public function kick($course_id, $user_id)
    {
        $course = Course::findOrFail($course_id);

        // Hanya pembuat kelas atau admin
        if (
            $course->creator_id !== auth()->id() &&
            auth()->user()->role !== 'admin'
        ) {
            abort(403, 'Tidak memiliki izin.');
        }

        // Tidak boleh kick guru/pembuat kelas
        if ($user_id == $course->creator_id) {
            return back()->with(
                'error',
                'Pengajar tidak dapat dikeluarkan.'
            );
        }

        // Cek apakah user anggota kelas
        $member = $course->users()
            ->where('users.id', $user_id)
            ->exists();

        if (!$member) {
            return back()->with(
                'error',
                'Anggota tidak ditemukan.'
            );
        }

        // Keluarkan dari kelas
        $course->users()->detach($user_id);

        return back()->with(
            'success',
            'Siswa berhasil dikeluarkan!'
        );
    }

    public function leave($course_id)
    {
        $course = Course::find($course_id);

        if (!$course) {
            return redirect()->route('courses')
                ->with('error', 'Kelas tidak ditemukan.');
        }

        // Jangan izinkan creator keluar dari kelasnya sendiri
        if ($course->creator_id == auth()->id()) {
            return redirect()->route('courses.show', $course_id)
                ->with('error', 'Pengajar tidak dapat keluar dari kelas yang dibuatnya.');
        }

        $course->users()->detach(auth()->id());

        return redirect()->route('courses')
            ->with('success', 'Berhasil keluar dari kelas!');
    }

    public function storeComment(Request $request, $course_id, $type, $item_id)
    {
        $request->validate([
            'body' => 'required|string|max:2000',
            'reply_to' => 'nullable|integer',
        ]);

        $course = Course::find($course_id);
        if (! $course) {
            abort(404, 'Kelas tidak ditemukan');
        }

        if ($type === 'tasks') {
            $item = Task::where('course_id', $course->id)->find($item_id);
        } elseif ($type === 'materials') {
            $item = Material::where('course_id', $course->id)->find($item_id);
        } else {
            $item = null;
        }

        if (! $item) {
            abort(404, 'Item tidak ditemukan');
        }

        $comments = $item->comments;
        if (is_string($comments)) {
            $comments = json_decode($comments, true) ?: [];
        }
        if (! is_array($comments)) {
            $comments = [];
        }

        $nextId = collect($comments)->pluck('id')->filter()->max() ?: 0;
        $nextId++;

        $newComment = [
            'id' => $nextId,
            'user_id' => auth()->id(),
            'body' => $request->input('body'),
            'reply_to' => $request->input('reply_to'),
            'created_at' => now()->toDateTimeString(),
        ];

        $comments[] = $newComment;
        $item->comments = $comments;
        $item->save();

        return back()->with('success', 'Komentar berhasil dikirim!');
    }

    public function store(Request $request)
    {
    // 1. Validasi input form modal
    $request->validate([
        'name' => 'required|string|max:255',
    ]);

    // 2. Simpan ke database menggunakan nama kolom bahasa Inggris resmi
    $course = \App\Models\Course::create([
        'name'         => $request->name,
        'subject'      => $request->subject,
        'room'         => $request->room,
        'level'        => $request->tahun_ajaran, // Memetakan tahun ajaran (misal: "2026/2027") ke kolom level
        'color'        => $request->warna_tema ?? '#1F7A8C',
        'icon'         => 'bi-journals', // Icon default jika dari form modal tidak menginput icon
        'code'         => $request->code ?? strtoupper(substr(md5(time()), 0, 6)),
        'teacher_name' => auth()->user() ? auth()->user()->name : 'Pengajar',
        'creator_id'   => auth()->id() ?? 1,
        'materials'    => [], // Di-inisialisasi dengan array kosong json
        'tasks'        => [], // Di-inisialisasi dengan array kosong json
        'users'        => [], // Di-inisialisasi dengan array kosong json
    ]);

    // Jika ada user yang login, tambahkan ke pivot sebagai teacher
    if (auth()->check()) {
        $course->users()->attach(auth()->id(), ['role' => 'teacher']);
    }

    // 3. Kembalikan ke halaman daftar kelas
    return redirect()->to('/kelas')->with('success', 'Kelas baru berhasil dibuat!');
}
    public function edit(Course $course)
    {
        // DIUBAH: mengarah ke folder 'kelas' dan file 'edit.blade.php' (atau sejenisnya jika ada)
        return view('kelas.edit', compact('course'));
    }
}
