<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();

        // 1. Statistik Dasar
        $coursesCount = $user->courses()->count();
        $studentCourseIds = $user->courses()->wherePivot('role', 'student')->pluck('courses.id');
        $userId = $user->id;

        $tasksCount = \App\Models\Task::whereIn('course_id', $studentCourseIds)
            ->get()
            ->filter(function ($task) use ($userId) {
                $subs = $task->submissions ?? [];
                if (is_string($subs)) {
                    $subs = json_decode($subs, true) ?: [];
                }
                $userSubmission = $subs[$userId] ?? null;
                return !(
                    $userSubmission &&
                    ($userSubmission['status'] ?? null) === 'Selesai'
                );
            })->count();
        // 2. Rata-rata Nilai — ambil dari database
        $averageGrade = null;

        // Jika user adalah pengajar di setidaknya satu kelas, ambil rata-rata nilai dari tugas yang dia buat
        $isTeacher = $user->courses()->wherePivot('role', 'teacher')->exists();
        if ($isTeacher) {
            $teacherTasks = \App\Models\Task::whereHas('course', function($q) use ($user) {
                $q->where('creator_id', $user->id);
            })->whereNotNull('score')->get();

            if ($teacherTasks->isNotEmpty()) {
                $averageGrade = round($teacherTasks->avg('score'));
            }
        } else {
            $studentCourseIds = $user->courses()
                ->wherePivot('role', 'student')
                ->pluck('courses.id');

            $tasks = \App\Models\Task::whereIn('course_id', $studentCourseIds)->get();

            $scores = [];

            foreach ($tasks as $task) {

                $subs = $task->submissions ?? [];

                if (is_string($subs)) {
                    $subs = json_decode($subs, true) ?: [];
                }

                $userSubmission = $subs[$user->id] ?? null;

                if (
                    $userSubmission &&
                    isset($userSubmission['score']) &&
                    is_numeric($userSubmission['score'])
                ) {
                    $scores[] = $userSubmission['score'];
                }
            }

            if (!empty($scores)) {
                $averageGrade = round(array_sum($scores) / count($scores));
            }
        }

        // 3. Aktivitas Terbaru: fallback aman berdasarkan tugas milik user
        $recentTasks = Task::whereHas('course', function ($q) use ($user) { $q->where('creator_id', $user->id);
        })
        ->latest()
        ->get()
        ->map(function ($task) {
            return [
                'title' => 'Tugas: ' . $task->title,
                'time' => $task->created_at->diffForHumans(),
                'icon' => 'bi-file-earmark-text',
                'color_bg' => '#dcfce7',
                'text_color' => 'text-success',
                'badge' => 'Tugas',
                'badge_bg' => '#f0fdf4',
                'badge_color' => '#16a34a',
                'created_at' => $task->created_at,
            ];
        });

        $recentMaterials = Material::whereHas('course', function ($q) use ($user) {
            $q->where('creator_id', $user->id);
        })
        ->latest()
        ->get()
        ->map(function ($material) {
            return [
                'title' => 'Materi: ' . $material->title,
                'time' => $material->created_at->diffForHumans(),
                'icon' => 'bi-book',
                'color_bg' => '#dbeafe',
                'text_color' => 'text-primary',
                'badge' => 'Materi',
                'badge_bg' => '#eff6ff',
                'badge_color' => '#2563eb',
                'created_at' => $material->created_at,
            ];
        });

        $recentActivities = $recentTasks
            ->concat($recentMaterials)
            ->sortByDesc('created_at')
            ->take(5)
            ->values();

        return view('dashboard', compact('coursesCount', 'tasksCount', 'averageGrade', 'recentActivities'));
    }

    public function settings()
    {
        return view('settings');
    }

    public function updateSettings(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $avatarPath;
        }

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi lama tidak sesuai.']);
        }

        $user->password = Hash::make($validated['password']);
        $user->save();

        return back()->with('password_success', 'Kata sandi berhasil diubah.');
    }

    public function report()
    {
        $user = auth()->user();
        $userId = $user->id;

        // Ambil semua kelas yang diikuti user (sebagai student)
        $studentCourses = $user->courses()
            ->wherePivot('role', 'student')
            ->get();

        $studentCourseIds = $studentCourses->pluck('id')->toArray();
        $tasks = \App\Models\Task::whereIn('course_id', $studentCourseIds)->get();

        $totalTasks = $tasks->count();

        $getUserSubmission = function ($task) use ($userId) {
            $submissions = $task->submissions ?? [];
            if (is_string($submissions)) {
                $submissions = json_decode($submissions, true) ?: [];
            }
            return $submissions[$userId] ?? null;
        };

        $totalSelesai = $tasks->filter(function ($task) use ($getUserSubmission) {
            $submission = $getUserSubmission($task);
            if ($submission && data_get($submission, 'status') === 'Selesai') {
                return true;
            }
            return ($task->status ?? null) === 'Selesai';
        })->count();

        $gradedScores = $tasks->map(function ($task) use ($getUserSubmission) {
            $submission = $getUserSubmission($task);
            if ($submission && isset($submission['score']) && is_numeric($submission['score'])) {
                return (float) $submission['score'];
            }
            if (isset($task->score) && is_numeric($task->score)) {
                return (float) $task->score;
            }
            return null;
        })->filter(function ($score) {
            return is_numeric($score);
        })->values();

        $totalGraded = $gradedScores->count();
        $overallAvg = $totalGraded > 0 ? round($gradedScores->avg(), 1) : null;

        $overallGrade = '-';
        $overallGradeColor = '#94a3b8';
        if ($overallAvg !== null) {
            if ($overallAvg >= 90) {
                $overallGrade = 'A';
                $overallGradeColor = '#16a34a';
            } elseif ($overallAvg >= 80) {
                $overallGrade = 'B';
                $overallGradeColor = '#1F7A8C';
            } elseif ($overallAvg >= 70) {
                $overallGrade = 'C';
                $overallGradeColor = '#b45309';
            } elseif ($overallAvg >= 60) {
                $overallGrade = 'D';
                $overallGradeColor = '#f59e0b';
            } else {
                $overallGrade = 'E';
                $overallGradeColor = '#dc2626';
            }
        }

        // Hitung setiap kelas secara langsung dari data yang dimiliki siswa
        $courses = $studentCourses->load('tasks');
        if ($courses->isEmpty()) {
            return view('report', [
                'overallAvg' => $overallAvg,
                'overallGrade' => $overallGrade,
                'overallGradeColor' => $overallGradeColor,
                'totalTasks' => $totalTasks,
                'totalSelesai' => $totalSelesai,
                'totalGraded' => $totalGraded,
                'reportData' => []
            ]);
        }

        $reportData = [];
        foreach ($courses as $course) {
            $courseTasks = $course->tasks;
            if (is_string($courseTasks)) {
                $courseTasks = collect(json_decode($courseTasks, true) ?: []);
            }
            if ($courseTasks === null) {
                $courseTasks = collect([]);
            }

            // For report, we need to use the actual Task model from DB, not JSON
            $courseTasksDB = \App\Models\Task::where('course_id', $course->id)->get();

            $courseTotalTasks = $courseTasksDB->count();
            $courseSelesai = $courseTasksDB->filter(function ($task) use ($userId) {
                $submissions = $task->submissions ?? [];
                if (is_string($submissions)) {
                    $submissions = json_decode($submissions, true) ?: [];
                }
                $submission = $submissions[$userId] ?? null;
                if ($submission && data_get($submission, 'status') === 'Selesai') {
                    return true;
                }
                return false;
            })->count();

            $courseGradedScores = $courseTasksDB->map(function ($task) use ($userId) {
                $submissions = $task->submissions ?? [];
                if (is_string($submissions)) {
                    $submissions = json_decode($submissions, true) ?: [];
                }
                $submission = $submissions[$userId] ?? null;
                if ($submission && isset($submission['score']) && is_numeric($submission['score'])) {
                    return (float) $submission['score'];
                }
                return null;
            })->filter(function ($score) {
                return is_numeric($score);
            })->values();

            $courseGraded = $courseGradedScores->count();
            $courseAvgScore = $courseGraded > 0 ? round($courseGradedScores->avg(), 1) : null;
            $courseMaxScore = $courseGraded > 0 ? $courseGradedScores->max() : null;
            $courseMinScore = $courseGraded > 0 ? $courseGradedScores->min() : null;

            $courseAvgGrade = '-';
            $courseAvgColor = '#94a3b8';
            if ($courseAvgScore !== null) {
                if ($courseAvgScore >= 90) {
                    $courseAvgGrade = 'A';
                    $courseAvgColor = '#16a34a';
                } elseif ($courseAvgScore >= 80) {
                    $courseAvgGrade = 'B';
                    $courseAvgColor = '#1F7A8C';
                } elseif ($courseAvgScore >= 70) {
                    $courseAvgGrade = 'C';
                    $courseAvgColor = '#b45309';
                } elseif ($courseAvgScore >= 60) {
                    $courseAvgGrade = 'D';
                    $courseAvgColor = '#f59e0b';
                } else {
                    $courseAvgGrade = 'E';
                    $courseAvgColor = '#dc2626';
                }
            }

            $progress = $courseTotalTasks > 0 ? round(($courseSelesai / $courseTotalTasks) * 100) : 0;

            // Transform tasks to include submission data for display in report
            $tasksWithSubmissions = $courseTasksDB->map(function ($task) use ($userId) {
                $submissions = $task->submissions ?? [];
                if (is_string($submissions)) {
                    $submissions = json_decode($submissions, true) ?: [];
                }
                $submission = $submissions[$userId] ?? null;
                
                // Create a new object combining task and submission data
                $taskData = $task->toArray();
                if ($submission) {
                    $taskData['score'] = $submission['score'] ?? null;
                    $taskData['feedback'] = $submission['feedback'] ?? null;
                    $taskData['status'] = data_get($submission, 'status', $task->status);
                }
                
                // Add accessor attributes
                $taskData['status_bg'] = $task->status_bg;
                $taskData['status_color'] = $task->status_color;
                $taskData['status_icon'] = $task->status_icon;
                $taskData['score_color'] = $task->score_color;
                
                // Keep the original task model for method calls like scoreGrade()
                $taskData['_model'] = $task;
                
                return (object)$taskData;
            });

            $reportData[] = (object)[
                'course' => $course,
                'tasks' => $tasksWithSubmissions,
                'allTasks' => $courseTotalTasks,
                'selesai' => $courseSelesai,
                'graded' => $courseGraded,
                'avgScore' => $courseAvgScore,
                'avgScoreGrade' => $courseAvgGrade,
                'avgScoreColor' => $courseAvgColor,
                'maxScore' => $courseMaxScore,
                'minScore' => $courseMinScore,
                'progress' => $progress,
            ];
        }

        return view('report', [
            'overallAvg' => $overallAvg,
            'overallGrade' => $overallGrade,
            'overallGradeColor' => $overallGradeColor,
            'totalTasks' => $totalTasks,
            'totalSelesai' => $totalSelesai,
            'totalGraded' => $totalGraded,
            'reportData' => $reportData
        ]);
    }
}
