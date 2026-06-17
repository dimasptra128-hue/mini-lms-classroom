<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    // Mengizinkan semua input kolom masuk (sangat aman)
    protected $guarded = [];

    // Hapus casting untuk fields yang juga memiliki relasi Eloquent
    // agar property dynamic `->tasks`, `->materials`, `->users` mengembalikan relasi,
    // bukan attribute JSON yang menimpa relasi.
    protected $casts = [];

    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('role');
    }
}