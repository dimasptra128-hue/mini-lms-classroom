<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\MockData;
use App\Models\Task;
use App\Models\Course;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function store(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required',
            'file_upload' => 'nullable|file|max:10240',
        ]);

        // Parse datetime-local input (e.g. 2026-06-17T14:30) to DB-friendly string
        try {
            $due = Carbon::parse($validated['due_date']);
            $due_str = $due->toDateTimeString();
        } catch (\Exception $e) {
            $due_str = $validated['due_date'];
        }

        $fileName = null;
        $filePath = null;
        if ($request->hasFile('file_upload') && $request->file('file_upload')->isValid()) {
            $fileName = $request->file('file_upload')->getClientOriginalName();
            $storedPath = $request->file('file_upload')->store('public/task_uploads');
            $filePath = $storedPath ? str_replace('public/', '', $storedPath) : null;
        }

        Task::create([
            'course_id' => $id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'due_date' => $due_str,
            'file_name' => $fileName,
            'file_path' => $filePath,
        ]);

        return back()->with('success', 'Tugas berhasil dibuat!');
    }

    public function show($course_id, $task_id)
    {
        // Prefer real DB-backed Course/Task if present
        $course = Course::find($course_id);
        if ($course) {
            $task = Task::where('course_id', $course->id)->find($task_id);
            if (!$task) abort(404);

            $userRole = ($course->creator_id === auth()->id()) ? 'teacher' : 'student';

            $otherTasks = Task::where('course_id', $course->id)->where('id', '!=', $task->id)->take(4)->get();

            return view('task_details', [
                'course' => $course,
                'task' => $task,
                'userRole' => $userRole,
                'otherTasks' => $otherTasks
            ]);
        }

        // Fallback to mock data (legacy)
        $course = MockData::getMockCourses()->firstWhere('id', $course_id);
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
    }

    public function download($course_id, $task_id)
    {
        $course = Course::find($course_id);
        if (! $course) {
            abort(404);
        }

        $task = Task::where('course_id', $course->id)->find($task_id);
        if (! $task || ! $task->file_path) {
            abort(404);
        }

        $storagePath = 'public/' . $task->file_path;
        if (! Storage::exists($storagePath)) {
            abort(404);
        }

        return Storage::download($storagePath, $task->file_name ?: basename($task->file_path));
    }

    public function destroy($course_id, $task_id)
    {
        $course = Course::find($course_id);
        if (! $course) {
            abort(404);
        }

        if (auth()->id() !== $course->creator_id) {
            return back()->with('error', 'Hanya pengajar dapat menghapus tugas ini.');
        }

        $task = Task::where('course_id', $course->id)->find($task_id);
        if (! $task) {
            abort(404);
        }

        if ($task->file_path) {
            $storagePath = 'public/' . $task->file_path;
            if (Storage::exists($storagePath)) {
                Storage::delete($storagePath);
            }
        }

        $task->delete();
        return redirect()->route('courses.show', $course->id)->with('success', 'Tugas berhasil dihapus.');
    }

    /**
     * Student submits work for a task (optional file). Stores submission under task->submissions[user_id]
     */
    public function submit(Request $request, $course_id, $task_id)
    {
        $course = Course::find($course_id);
        if (! $course) abort(404);

        $task = Task::where('course_id', $course->id)->find($task_id);
        if (! $task) abort(404);

        $userId = auth()->id();

        $validated = $request->validate([
            'submission_file' => 'nullable|file|max:10240',
        ]);

        $fileName = null;
        $filePath = null;
        if ($request->hasFile('submission_file') && $request->file('submission_file')->isValid()) {
            $fileName = $request->file('submission_file')->getClientOriginalName();
            $storedPath = $request->file('submission_file')->store('public/submissions');
            $filePath = $storedPath ? str_replace('public/', '', $storedPath) : null;
        }

        $submissions = $task->submissions ?? [];
        $submissions[$userId] = [
            'file_name' => $fileName,
            'file_path' => $filePath,
            'submitted_at' => now()->toDateTimeString(),
            'status' => 'Selesai'
        ];

        $task->submissions = $submissions;
        $task->save();

        return back()->with('success', 'Tugas berhasil diserahkan.');
    }

    /**
     * Cancel a user's submission so they can re-submit.
     */
    public function cancelSubmission(Request $request, $course_id, $task_id)
    {
        $course = Course::find($course_id);
        if (! $course) abort(404);

        $task = Task::where('course_id', $course->id)->find($task_id);
        if (! $task) abort(404);

        $userId = auth()->id();

        $submissions = $task->submissions ?? [];
        if (! isset($submissions[$userId])) {
            return back()->with('error', 'Anda belum menyerahkan tugas ini.');
        }

        // delete uploaded file if exists
        $entry = $submissions[$userId];
        if (!empty($entry['file_path'])) {
            $storagePath = 'public/' . $entry['file_path'];
            if (Storage::exists($storagePath)) {
                Storage::delete($storagePath);
            }
        }

        unset($submissions[$userId]);
        $task->submissions = $submissions;
        $task->save();

        return back()->with('success', 'Pengiriman dibatalkan. Anda dapat mengunggah ulang sekarang.');
    }

    public function index()
    {
        // Ambil course IDs yang diikuti user yang login
        $userCourseIds = auth()->user()->courses()->pluck('courses.id')->toArray();

        // Ambil semua task dari course yang diikuti user, diurutkan by due_date
        $allTasks = Task::with('course')
            ->whereIn('course_id', $userCourseIds)
            ->orderBy('due_date', 'asc')
            ->get();

        $userId = auth()->id();

        $taskGroups = [
            [
                'label' => 'Tugas Belum Selesai',
                'badge_bg' => '#fee2e2',
                'badge_color' => '#dc2626',
                'tasks' => $allTasks->filter(function($t) use ($userId) {
                    $subs = $t->submissions ?? [];
                    if (is_string($subs)) $subs = json_decode($subs, true) ?: [];
                    $userSub = $subs[$userId] ?? null;
                    if ($userSub && data_get($userSub, 'status') === 'Selesai') return false;
                    return ($t->status ?? null) !== 'Selesai';
                })
            ],
            [
                'label' => 'Tugas Selesai',
                'badge_bg' => '#dcfce7',
                'badge_color' => '#16a34a',
                'tasks' => $allTasks->filter(function($t) use ($userId) {
                    $subs = $t->submissions ?? [];
                    if (is_string($subs)) $subs = json_decode($subs, true) ?: [];
                    $userSub = $subs[$userId] ?? null;
                    if ($userSub && data_get($userSub, 'status') === 'Selesai') return true;
                    return ($t->status ?? null) === 'Selesai';
                })
            ]
        ];
        return view('tasks', [
            'taskGroups' => $taskGroups
        ]);
    }
}
