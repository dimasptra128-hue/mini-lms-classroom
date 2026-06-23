<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil ID kelas yang diikuti user yang sedang login
        $userCourseIds = auth()->user()
            ->courses()
            ->pluck('courses.id')
            ->toArray();

        // Ambil semua tugas dari kelas yang diikuti
        $allTasks = Task::with('course')
            ->whereIn('course_id', $userCourseIds)
            ->orderBy('due_date', 'asc')
            ->get();

        $userId = auth()->id();

        $unfinishedTasks = $allTasks->filter(function ($task) use ($userId) {

            $submissions = $task->submissions ?? [];

            if (is_string($submissions)) {
                $submissions = json_decode($submissions, true) ?: [];
            }

            if (!is_array($submissions)) {
                $submissions = [];
            }

            $userSubmission = $submissions[$userId] ?? null;

            if ($userSubmission && data_get($userSubmission, 'status') === 'Selesai') {
                return false;
            }

            return ($task->status ?? null) !== 'Selesai';
        })->values();

        $finishedTasks = $allTasks->filter(function ($task) use ($userId) {

            $submissions = $task->submissions ?? [];

            if (is_string($submissions)) {
                $submissions = json_decode($submissions, true) ?: [];
            }

            if (!is_array($submissions)) {
                $submissions = [];
            }

            $userSubmission = $submissions[$userId] ?? null;

            if ($userSubmission && data_get($userSubmission, 'status') === 'Selesai') {
                return true;
            }

            return ($task->status ?? null) === 'Selesai';
        })->values();

        return response()->json([
            'success' => true,
            'message' => 'Daftar tugas berhasil diambil.',
            'data' => [
                'unfinished_tasks' => $unfinishedTasks,
                'finished_tasks' => $finishedTasks,
            ],
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas tidak ditemukan.',
            ], 404);
        }

        // Hanya creator / admin yang boleh membuat tugas
        if (
            $course->creator_id !== auth()->id() &&
            auth()->user()->role !== 'admin'
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak memiliki izin membuat tugas.',
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required',
            'file_upload' => 'nullable|file|max:10240',
        ]);

        // Parse tanggal
        try {
            $due = Carbon::parse($validated['due_date']);
            $due_str = $due->toDateTimeString();
        } catch (\Exception $e) {
            $due_str = $validated['due_date'];
        }

        $fileName = null;
        $filePath = null;

        // Upload file
        if (
            $request->hasFile('file_upload') &&
            $request->file('file_upload')->isValid()
        ) {

            $file = $request->file('file_upload');

            $fileName =
                $file->getClientOriginalName();

            $filePath =
                $file->store(
                    'task_uploads',
                    'public'
                );
        }

        $task = Task::create([
            'course_id' => $course->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'due_date' => $due_str,
            'file_name' => $fileName,
            'file_path' => $filePath,
        ]);

        // Refresh biar relasi ikut
        $task->load('course');

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dibuat.',
            'data' => $task,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($course_id, $task_id)
    {
        // Ambil course + creator
        $course = Course::with(['creator', 'users'])
            ->find($course_id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas tidak ditemukan.',
            ], 404);
        }

        // Ambil task berdasarkan kelas
        $task = Task::where('course_id', $course->id)
            ->firstWhere('id', $task_id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Tugas tidak ditemukan.',
            ], 404);
        }

        // Cek apakah user anggota kelas
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

        // Role user
        $userRole =
            ($course->creator_id === auth()->id())
            ? 'teacher'
            : 'student';

        // Tugas lain
        $otherTasks = Task::where('course_id', $course->id)
            ->where('id', '!=', $task->id)
            ->latest()
            ->take(4)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Detail tugas berhasil diambil.',
            'data' => [
                'course' => [
                    'id' => $course->id,
                    'name' => $course->name,
                    'color' => $course->color,
                    'teacher' => [
                        'id' => $course->creator?->id,
                        'name' => $course->creator?->name,
                        'avatar' => $course->creator?->avatar
                            ? asset('storage/'.$course->creator->avatar)
                            : null,
                    ],
                ],

                'task' => [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'due_date' => $task->due_date,
                    'file_name' => $task->file_name,
                    'file_path' => $task->file_path,
                    'file_url' => $task->file_path
                        ? asset('storage/' . $task->file_path)
                        : null,
                ],

                'userRole' => $userRole,

                'otherTasks' => $otherTasks,
            ],
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($course_id, $task_id)
    {
        $course = Course::find($course_id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas tidak ditemukan.',
            ], 404);
        }

        // Hanya creator / guru / admin yang boleh hapus
        if (
            auth()->id() !== $course->creator_id &&
            auth()->user()->role !== 'admin'
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pengajar dapat menghapus tugas ini.',
            ], 403);
        }

        $task = Task::where('course_id', $course->id)
            ->find($task_id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Tugas tidak ditemukan.',
            ], 404);
        }

        // Hapus file lampiran tugas
        if (!empty($task->file_path)) {
            $storagePath = 'public/' . $task->file_path;

            if (Storage::exists($storagePath)) {
                Storage::delete($storagePath);
            }
        }

        // Hapus seluruh file submission siswa (kalau ada)
        $submissions = $task->submissions ?? [];

        if (is_string($submissions)) {
            $submissions = json_decode($submissions, true) ?: [];
        }

        foreach ($submissions as $submission) {
            if (!empty($submission['file_path'])) {
                $submissionPath = 'public/' . $submission['file_path'];

                if (Storage::exists($submissionPath)) {
                    Storage::delete($submissionPath);
                }
            }
        }

        // Hapus task
        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dihapus.',
        ], 200);
    }

    public function download($course_id, $task_id)
    {
        $course = Course::find($course_id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas tidak ditemukan.',
            ], 404);
        }

        $task = Task::where('course_id', $course->id)
            ->find($task_id);

        if (!$task || !$task->file_path) {
            return response()->json([
                'success' => false,
                'message' => 'File tugas tidak tersedia.',
            ], 404);
        }

        if (!Storage::disk('public')->exists($task->file_path)) {
            return response()->json([
                'success' => false,
                'message' => 'File tidak ditemukan di storage.',
                'path' => $task->file_path
            ], 404);
        }

        return Storage::disk('public')->download(
            $task->file_path,
            $task->file_name
        );
    }

    public function submit(Request $request, $course_id, $task_id)
    {
        $course = Course::find($course_id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas tidak ditemukan.',
            ], 404);
        }

        $task = Task::where('course_id', $course->id)
            ->find($task_id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Tugas tidak ditemukan.',
            ], 404);
        }

        if ($course->creator_id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Pengajar tidak dapat mengumpulkan tugas.',
            ], 403);
        }

        $userId = auth()->id();

        $request->validate([
            'submission_file' => 'nullable|file|max:10240',
        ]);

        $submissions = $task->submissions ?? [];

        if (is_string($submissions)) {
            $submissions = json_decode($submissions, true) ?: [];
        }

        if (!is_array($submissions)) {
            $submissions = [];
        }

        $fileName = null;
        $filePath = null;

        if (
            $request->hasFile('submission_file') &&
            $request->file('submission_file')->isValid()
        ) {

            // Hapus file lama jika user pernah submit
            if (
                isset($submissions[$userId]['file_path']) &&
                Storage::disk('public')->exists($submissions[$userId]['file_path'])
            ) {
                Storage::disk('public')->delete(
                    $submissions[$userId]['file_path']
                );
            }

            $file = $request->file('submission_file');

            $fileName = $file->getClientOriginalName();

            $filePath = $file->store(
                'submissions',
                'public'
            );
        }

        $submissions[$userId] = [
            'file_name' => $fileName,
            'file_path' => $filePath,
            'submitted_at' => now()->toDateTimeString(),
            'status' => 'Selesai',
            'score' => $submissions[$userId]['score'] ?? null,
            'feedback' => $submissions[$userId]['feedback'] ?? null,
        ];

        $task->submissions = $submissions;
        $task->save();

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil diserahkan.',
            'data' => $submissions[$userId],
        ], 201);
    }

    public function cancelSubmission(Request $request, $course_id, $task_id)
    {
        $course = Course::find($course_id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas tidak ditemukan.',
            ], 404);
        }

        $task = Task::where('course_id', $course->id)
            ->find($task_id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Tugas tidak ditemukan.',
            ], 404);
        }

        $userId = auth()->id();

        $submissions = $task->submissions ?? [];

        // Jika submissions tersimpan sebagai JSON string
        if (is_string($submissions)) {
            $submissions = json_decode($submissions, true) ?: [];
        }

        if (!is_array($submissions)) {
            $submissions = [];
        }

        if (!isset($submissions[$userId])) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum menyerahkan tugas ini.',
            ], 400);
        }

        // Hapus file yang telah diupload jika ada
        $entry = $submissions[$userId];

        if (
            !empty($entry['file_path']) &&
            Storage::disk('public')->exists($entry['file_path'])
        ) {
            Storage::disk('public')
                ->delete($entry['file_path']);
        }

        // Hapus data submission user
        unset($submissions[$userId]);

        $task->submissions = $submissions;
        $task->save();

        return response()->json([
            'success' => true,
            'message' => 'Pengiriman dibatalkan. Anda dapat mengunggah ulang sekarang.',
        ], 200);
    }

    public function showSubmissions($course_id, $task_id)
    {
        $course = Course::find($course_id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas tidak ditemukan.',
            ], 404);
        }

        // Pastikan hanya pengajar yang bisa melihat
        if (auth()->id() !== $course->creator_id) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pengajar dapat melihat penilaian.',
            ], 403);
        }

        $task = Task::where('course_id', $course->id)
            ->find($task_id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Tugas tidak ditemukan.',
            ], 404);
        }

        // Ambil seluruh siswa dalam kelas
        $students = $course->users()
            ->wherePivot('role', 'student')
            ->get();

        // Normalisasi submissions
        $submissions = $task->submissions ?? [];

        if (is_string($submissions)) {
            $submissions = json_decode($submissions, true) ?: [];
        }

        if (!is_array($submissions)) {
            $submissions = [];
        }

        // Hitung jumlah submit dan yang sudah dinilai
        $submittedCount = count($submissions);

        $gradedCount = 0;

        foreach ($submissions as $data) {
            if (isset($data['score']) && $data['score'] !== null) {
                $gradedCount++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Data pengumpulan tugas berhasil diambil.',
            'data' => [
                'course' => $course,

                'task' => $task,

                'students' => $students->map(function ($student) {
                    return [
                        'id' => $student->id,
                        'name' => $student->name,
                        'email' => $student->email,
                        'avatar' => $student->avatar
                            ? asset('storage/' . $student->avatar)
                            : null,
                    ];
                }),
                'submissions' => $submissions,
                'total_students' => $students->count(),
                'submitted_count' => $submittedCount,
                'graded_count' => $gradedCount,
            ],
        ], 200);
    }

    public function grade(Request $request, $course_id, $task_id, $student_id)
    {
        $course = Course::find($course_id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas tidak ditemukan.',
            ], 404);
        }

        // Pastikan hanya pengajar yang dapat memberi nilai
        if (auth()->id() !== $course->creator_id) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pengajar dapat memberikan penilaian.',
            ], 403);
        }

        $task = Task::where('course_id', $course->id)
            ->find($task_id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Tugas tidak ditemukan.',
            ], 404);
        }

        $validated = $request->validate([
            'score' => 'required|integer|min:0|max:100',
            'feedback' => 'nullable|string|max:1000',
        ]);

        // Ambil data submissions
        $submissions = $task->submissions ?? [];

        if (is_string($submissions)) {
            $submissions = json_decode($submissions, true) ?: [];
        }

        if (!is_array($submissions)) {
            $submissions = [];
        }

        // Pastikan siswa sudah submit tugas
        if (!isset($submissions[$student_id])) {
            return response()->json([
                'success' => false,
                'message' => 'Submission tidak ditemukan untuk siswa ini.',
            ], 404);
        }

        // Simpan nilai
        $submissions[$student_id]['score'] = (int) $validated['score'];
        $submissions[$student_id]['feedback'] = $validated['feedback'] ?? null;
        $submissions[$student_id]['graded_at'] = now()->toDateTimeString();

        $task->submissions = $submissions;
        $task->save();

        return response()->json([
            'success' => true,
            'message' => 'Nilai berhasil disimpan.',
            'data' => $submissions[$student_id],
        ], 200);
    }
}
