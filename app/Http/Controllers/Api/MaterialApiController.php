<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Material;
use Illuminate\Http\Request;

class MaterialApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
                'message' => 'Kelas tidak ditemukan.'
            ], 404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'link_url' => 'nullable|url|max:2048',
            'file_upload' => 'nullable|file|max:10240',
        ]);

        $fileName = null;
        $filePath = null;

        if ($request->hasFile('file_upload')) {

            $file = $request->file('file_upload');

            if ($file->isValid()) {

                $fileName = $file->getClientOriginalName();

                $storedPath = $file->store('public/materials');

                $filePath = str_replace('public/', '', $storedPath);
            }
        }

        $material = Material::create([
            'course_id' => $id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'link_url' => $validated['link_url'] ?? null,
            'file_name' => $fileName,
            'file_path' => $filePath,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Materi berhasil ditambahkan.',
            'data' => $material
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($course_id, $material_id)
    {
        $course = Course::find($course_id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas tidak ditemukan.'
            ], 404);
        }

        $material = Material::where('course_id', $course->id)
            ->find($material_id);

        if (!$material) {
            return response()->json([
                'success' => false,
                'message' => 'Materi tidak ditemukan.'
            ], 404);
        }

        $otherMaterials = Material::where('course_id', $course->id)
            ->where('id', '!=', $material->id)
            ->take(4)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Detail materi berhasil diambil.',
            'data' => [
                'material' => $material,
                'other_materials' => $otherMaterials
            ]
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
    public function destroy($course_id, $material_id)
    {
        $course = Course::find($course_id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas tidak ditemukan.'
            ], 404);
        }

        $material = Material::where('course_id', $course->id)
            ->find($material_id);

        if (!$material) {
            return response()->json([
                'success' => false,
                'message' => 'Materi tidak ditemukan.'
            ], 404);
        }

        $material->delete();

        return response()->json([
            'success' => true,
            'message' => 'Materi berhasil dihapus.'
        ], 200);
    }
}
