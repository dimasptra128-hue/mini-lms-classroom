<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    // Mengizinkan semua kolom diisi secara fleksibel oleh Seeder
    protected $guarded = [];

    // Relasi balik ke Course (Many to One)
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}