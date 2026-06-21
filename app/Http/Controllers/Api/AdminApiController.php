<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminApiController extends Controller
{
    public function dashboard()
    {
        $usersCount = User::count();
        $coursesCount = Course::count();

        $materialsCount = Schema::hasTable('materials')
            ? DB::table('materials')->count()
            : 0;

        $tasksCount = Schema::hasTable('tasks')
            ? DB::table('tasks')->count()
            : 0;

        $recentCourses = Course::withCount('users')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $recentUsers = User::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data dashboard berhasil diambil.',
            'data' => [
                'users_count' => $usersCount,
                'courses_count' => $coursesCount,
                'materials_count' => $materialsCount,
                'tasks_count' => $tasksCount,
                'recent_courses' => $recentCourses,
                'recent_users' => $recentUsers,
            ]
        ]);
    }

    public function users()
    {
        $users = User::withCount('courses')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data pengguna berhasil diambil.',
            'data' => $users,
        ]);
    }

    public function deleteUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan.',
            ], 404);
        }

        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus akun sendiri.',
            ], 403);
        }

        $user->courses()->detach();
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus.',
        ]);
    }

    public function toggleRole($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.',
            ], 404);
        }

        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat mengubah role akun sendiri.',
            ], 403);
        }

        $user->role = $user->role === 'admin'
            ? 'student'
            : 'admin';

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Role berhasil diubah.',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
            ]
        ]);
    }

    public function courses()
    {
        $courses = Course::with([
                'users' => function ($query) {
                    $query->orderBy('name');
                }
            ])
            ->withCount('users')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data kelas berhasil diambil.',
            'data' => $courses,
        ]);
    }

    public function deleteCourse($id)
    {
        $course = Course::with([
            'materials',
            'tasks',
            'users'
        ])->find($id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas tidak ditemukan.',
            ], 404);
        }

        $course->materials()->delete();
        $course->tasks()->delete();
        $course->users()->detach();
        $course->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kelas berhasil dihapus.',
        ]);
    }

    public function kickMember($course_id, $user_id)
    {
        $course = Course::find($course_id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas tidak ditemukan.',
            ], 404);
        }

        $user = User::find($user_id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.',
            ], 404);
        }

        if ($course->creator_id == $user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Pengajar kelas tidak dapat dikeluarkan.',
            ], 400);
        }

        if (!$course->users()->where('users.id', $user_id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'User bukan anggota kelas ini.',
            ], 400);
        }

        $course->users()->detach($user_id);

        return response()->json([
            'success' => true,
            'message' => $user->name . ' berhasil dikeluarkan dari kelas.',
        ]);
    }
}
