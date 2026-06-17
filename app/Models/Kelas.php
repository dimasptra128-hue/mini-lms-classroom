<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    public $id, $name, $subject, $room, $code, $color, $icon, $teacher_name, $level, $creator_id, $progress, $users_count, $tasks_count, $materials, $tasks, $users, $created_at;
    public function __construct($attributes = [])
    {
        foreach ($attributes as $key => $value) {
            $this->$key = $val;
        }
    }
    use HasFactory;
    protected $fillable = ['name', 'subject', 'room', 'code', 'color', 'icon', 'level', 'creator_id'];
    public function materials()
    {
        return $this->hasMany(Material::class);
    }
}
