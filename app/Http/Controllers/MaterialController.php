<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Material;
use App\Models\MockData;

class MaterialController extends Controller
{
    public function store(Request $request, $id)
    {
        $course = Course::find($id);
        if (! $course) {
            abort(404, 'Kelas tidak ditemukan.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'link_url' => 'nullable|url|max:2048',
            'file_upload' => 'nullable|file|max:10240',
        ]);

        $fileName = null;
        $filePath = null;
        if ($request->hasFile('file_upload') && $request->file('file_upload')->isValid()) {
            $fileName = $request->file('file_upload')->getClientOriginalName();
            $storedPath = $request->file('file_upload')->store('public/materials');
            $filePath = $storedPath ? str_replace('public/', '', $storedPath) : null;
        }

        Material::create([
            'course_id' => $id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'link_url' => $validated['link_url'] ?? null,
            'file_name' => $fileName,
            'file_path' => $filePath,
        ]);

        return back()->with('success', 'Materi berhasil dibagikan!');
    }

    public function show($course_id, $material_id)
    {
        $course = Course::find($course_id);

        if ($course) {
            $material = Material::where('course_id', $course->id)->find($material_id);
            if (!$material) {
                abort(404);
            }

            $userRole = ($course->creator_id === auth()->id()) ? 'teacher' : 'student';
            $otherMaterials = Material::where('course_id', $course->id)
                ->where('id', '!=', $material->id)
                ->take(4)
                ->get();

            return view('material_details', [
                'course' => $course,
                'material' => $material,
                'userRole' => $userRole,
                'otherMaterials' => $otherMaterials
            ]);
        }

        $course = MockData::getMockCourses()->firstWhere('id', $course_id);
        if (!$course) {
            abort(404);
        }

        $material = $course->materials->firstWhere('id', $material_id);
        if (!$material) {
            abort(404);
        }

        $userRole = ($course->creator_id === 1) ? 'teacher' : 'student';
        $otherMaterials = $course->materials->where('id', '!=', $material->id)->take(4);

        return view('material_details', [
            'course' => $course,
            'material' => $material,
            'userRole' => $userRole,
            'otherMaterials' => $otherMaterials
        ]);
    }

    public function destroy($course_id, $material_id)
    {
        $course = Course::find($course_id);

        if (! $course) {
            abort(404, 'Kelas tidak ditemukan.');
        }

        $material = Material::where('course_id', $course->id)->find($material_id);
        if (! $material) {
            abort(404, 'Materi tidak ditemukan.');
        }

        $material->delete();

        return redirect()->route('courses.show', $course->id)->with('success', 'Materi berhasil dihapus dari kelas.');
    }
}
