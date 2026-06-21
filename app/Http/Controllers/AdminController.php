<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
// use App\Models\Material;
// use App\Models\Task;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Total Statistik Ringkas
        $usersCount = User::count();
        $coursesCount = Course::count();
        
        // Menghitung total materi dan tugas dengan aman
        $materialsCount = Schema::hasTable('materials') ? DB::table('materials')->count() : 0;
        $tasksCount = Schema::hasTable('tasks') ? DB::table('tasks')->count() : 0;

        // Ambil 5 Kelas Terbaru beserta jumlah anggota
        $recentCourses = Course::withCount('users')->orderBy('created_at', 'desc')->take(5)->get();

        // Ambil 5 Pengguna Baru
        $recentUsers = User::orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact(
            'usersCount', 
            'coursesCount', 
            'materialsCount', 
            'tasksCount', 
            'recentCourses', 
            'recentUsers'
        ));
    }

    public function users()
    {
        // 1. Ambil semua data user dari database, diurutkan dari yang terbaru
            $users = User::withCount('courses')->orderBy('created_at', 'desc')->get();

        // 2. Kirim data ke file Blade admin
        // Sesuaikan 'admin.users' dengan lokasi folder view Anda (misal: admin/users.blade.php)
        return view('admin.users', compact('users'));
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        if (! $user) {
            return back()->with('error', 'Pengguna tidak ditemukan.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun Anda sendiri.');
        }

        $user->courses()->detach();
        $user->delete();

        return back()->with('success', 'User berhasil dihapus!');
    }

    public function toggleRole($id)
    {
        $user = User::find($id);

        if (!$user) {
            return back()->with('error', 'User tidak ditemukan.');
        }

        // Jangan sampai admin mengubah role dirinya sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat mengubah role akun sendiri.');
        }

        $user->role = $user->role === 'admin'
            ? 'student'
            : 'admin';

        $user->save();

        return back()->with('success', 'Role berhasil diubah.');
    }

    public function courses()
    {
        // Ambil semua data kelas beserta user yang terhubung dan jumlah anggota
        $courses = Course::with(['users' => function ($query) {
            $query->orderBy('name');
        }])->withCount('users')->get();

        // Kirim variabel $courses ke dalam view admin
        return view('admin.kelas', compact('courses'));
    }

    public function deleteCourse($id)
    {
        $course = Course::with(['materials', 'tasks', 'users'])->find($id);
        if (! $course) {
            return back()->with('error', 'Kelas tidak ditemukan.');
        }

        // hapus data terkait agar tidak meninggalkan relasi sisa
        $course->materials()->delete();
        $course->tasks()->delete();
        $course->users()->detach();
        $course->delete();

        return back()->with('success', 'Kelas berhasil dihapus oleh admin!');
    }

    public function kickMember($course_id, $user_id)
    {
        $course = Course::find($course_id);

        if (!$course) {
            return back()->with('error', 'Kelas tidak ditemukan.');
        }

        $user = User::find($user_id);

        if (!$user) {
            return back()->with('error', 'User tidak ditemukan.');
        }

        // Jangan keluarkan creator kelas
        if ($course->creator_id == $user_id) {
            return back()->with('error', 'Pengajar kelas tidak dapat dikeluarkan.');
        }

        // Pastikan user memang anggota kelas
        if (!$course->users()->where('users.id', $user_id)->exists()) {
            return back()->with('error', 'User bukan anggota kelas ini.');
        }

        $course->users()->detach($user_id);

        return back()->with('success', $user->name . ' berhasil dikeluarkan dari kelas.');
    }
}
