<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Material;
use App\Models\Task;
use Illuminate\Http\Request;

class CourseApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        $courses = Course::with('creator')
            ->withCount(['users', 'tasks'])
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id)
                    ->orWhereHas('users', function ($q) use ($user) {
                        $q->where('users.id', $user->id);
                    });
            })
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data kelas berhasil diambil.',
            'data' => $courses,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'room' => 'nullable|string|max:255',
            'tahun_ajaran' => 'nullable|string|max:100',
            'warna_tema' => 'nullable|string|max:20',
        ]);

        $course = Course::create([
            'name'         => $request->name,
            'subject'      => $request->subject,
            'room'         => $request->room,
            'level'        => $request->tahun_ajaran,
            'color'        => $request->warna_tema ?? '#1F7A8C',
            'icon'         => 'bi-journals',
            'code'         => $request->code ?? strtoupper(substr(md5(time()), 0, 6)),
            'teacher_name' => auth()->user()?->name ?? 'Pengajar',
            'creator_id' => auth()->id(),
            'materials'    => [],
            'tasks'        => [],
            'users'        => [],
        ]);

        if (auth()->check()) {
            $course->users()->attach(auth()->id(), [
                'role' => 'teacher'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Kelas berhasil dibuat.',
            'data' => $course,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $course = Course::with([
            'creator',
            'materials',
            'tasks',
            'users'
        ])->find($id);

        // Cek kelas ada atau tidak
        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas tidak ditemukan.',
            ], 404);
        }

        // Validasi akses
        $isMember = $course->users()
            ->where('users.id', auth()->id())
            ->exists();

        if (
            !$isMember &&
            $course->creator_id !== auth()->id() &&
            auth()->user()->role !== 'admin'
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Anda bukan anggota kelas ini.',
            ], 403);
        }

        // Ambil teacher
        $teachers = $course->users()
            ->wherePivot('role', 'teacher')
            ->get();

        // Fallback kalau teacher kosong
        if ($teachers->isEmpty() && $course->creator) {
            $teachers = collect([$course->creator]);
        }

        // Ambil student
        $students = $course->users()
            ->wherePivot('role', 'student')
            ->get();

        // Ambil materi & tugas
        $materials = Material::where(
            'course_id',
            $course->id
        )
        ->latest()
        ->get();

        $tasks = Task::where(
            'course_id',
            $course->id
        )
        ->orderBy('due_date')
        ->get();

        return response()->json([
            'success' => true,
            'message' => 'Detail kelas berhasil diambil.',
            'data' => [
                'course' => $course,
                'teachers' => $teachers,
                'students' => $students,
                'materials' => $materials,
                'tasks' => $tasks,
            ],
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return response()->json([
        'success' => false,
        'message' => 'Fitur update belum tersedia.',
        ], 501);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas tidak ditemukan.',
            ], 404);
        }

        $course->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kelas berhasil dihapus.',
        ], 200);
    }

    public function join(Request $request)
    {
        $request->validate([
            'kode_kelas' => 'required|string|max:20'
        ]);

        $user = auth()->user();

        $code = strtoupper(trim($request->kode_kelas));

        $course = Course::where('code', $code)->first();

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Kode kelas tidak ditemukan.'
            ], 404);
        }

        if ($course->users()->where('users.id', $user->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'User sudah bergabung di kelas ini.'
            ], 400);
        }

        $course->users()->attach($user->id, [
            'role' => 'student'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil bergabung ke kelas.'
        ], 200);
    }

    public function leave($course_id)
    {
        $course = Course::find($course_id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas tidak ditemukan.'
            ], 404);
        }

        if (!$course->users()->where('users.id', auth()->id())->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda bukan anggota kelas ini.'
            ], 400);
        }

        $course->users()->detach(auth()->id());

        return response()->json([
            'success' => true,
            'message' => 'Berhasil keluar dari kelas.'
        ], 200);
    }

    public function kick($course_id, $user_id)
    {
        $course = Course::find($course_id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas tidak ditemukan.'
            ], 404);
        }

        if (!$course->users()->where('users.id', $user_id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan di kelas ini.'
            ], 404);
        }

        $course->users()->detach($user_id);

        return response()->json([
            'success' => true,
            'message' => 'Anggota berhasil dikeluarkan.'
        ], 200);
    }
    
    public function storeComment(Request $request, $course_id, $type, $item_id)
    {
        $request->validate([
            'body' => 'required|string|max:2000',
            'reply_to' => 'nullable|integer',
        ]);

        $course = Course::find($course_id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas tidak ditemukan.',
            ], 404);
        }

        // Validasi tipe item
        if (!in_array($type, ['tasks', 'materials'])) {
            return response()->json([
                'success' => false,
                'message' => 'Tipe item tidak valid.',
            ], 400);
        }

        // Cari item berdasarkan tipe
        if ($type === 'tasks') {
            $item = Task::where('course_id', $course_id)
                ->find($item_id);
        } else {
            $item = Material::where('course_id', $course_id)
                ->find($item_id);
        }

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Item tidak ditemukan.',
            ], 404);
        }

        // Ambil komentar lama
        $comments = $item->comments;

        if (is_string($comments)) {
            $comments = json_decode($comments, true) ?: [];
        }

        if (!is_array($comments)) {
            $comments = [];
        }

        // Generate ID komentar baru
        $nextId = collect($comments)
            ->pluck('id')
            ->filter()
            ->max() ?: 0;

        $nextId++;

        // Data komentar baru
        $newComment = [
            'id' => $nextId,
            'user_id' => auth()->id(),
            'body' => $request->body,
            'reply_to' => $request->reply_to,
            'created_at' => now()->toDateTimeString(),
        ];

        // Simpan komentar
        $comments[] = $newComment;

        $item->comments = $comments;
        $item->save();

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil dikirim.',
            'data' => $newComment,
        ], 201);
    }
}
