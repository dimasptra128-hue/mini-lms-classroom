<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    // 1. Mengizinkan Eloquent mengelola semua kolom database secara fleksibel
    protected $guarded = [];

    // 2. Jika di database kolom comments disimpan sebagai format JSON, ini akan otomatis mengubahnya jadi array PHP
    protected $casts = [
        'comments' => 'array',
        'submissions' => 'array',
    ];

    // ==========================================
    //  KODE BUATAN TEMAN TIM (LOGIKA BISNIS & VIEW)
    // ==========================================

    /**
     * Konversi nilai angka menjadi huruf Grade (A-E)
     */
    public function scoreGrade(): string
    {
        if ($this->score === null) return '-';
        if ($this->score >= 90) return 'A';
        if ($this->score >= 80) return 'B';
        if ($this->score >= 70) return 'C';
        if ($this->score >= 60) return 'D';
        return 'E';
    }

    /**
     * Mendapatkan warna background Badge Status (Selesai, Draft, Belum)
     * Pemanggilan di Blade: $task->status_bg
     */
    public function getStatusBgAttribute()
    {
        $map = ['Selesai' => '#dcfce7', 'Draft' => '#fef3c7', 'Belum' => '#fee2e2'];
        return $map[$this->status] ?? '#fee2e2';
    }

    /**
     * Mendapatkan warna teks/icon Status
     * Pemanggilan di Blade: $task->status_color
     */
    public function getStatusColorAttribute()
    {
        $map = ['Selesai' => '#16a34a', 'Draft' => '#b45309', 'Belum' => '#dc2626'];
        return $map[$this->status] ?? '#dc2626';
    }

    /**
     * Mendapatkan class icon Bootstrap Icons berdasarkan status tugas
     * Pemanggilan di Blade: $task->status_icon
     */
    public function getStatusIconAttribute()
    {
        $map = ['Selesai' => 'bi-check-circle-fill', 'Draft' => 'bi-pencil-square', 'Belum' => 'bi-clock-fill'];
        return $map[$this->status] ?? 'bi-clock-fill';
    }

    /**
     * Mendapatkan warna teks berdasarkan besaran skor nilai mahasiswa
     * Pemanggilan di Blade: $task->score_color
     */
    public function getScoreColorAttribute()
    {
        if ($this->score === null) return '#94a3b8';
        if ($this->score >= 90) return '#16a34a';
        if ($this->score >= 80) return '#1F7A8C';
        if ($this->score >= 70) return '#b45309';
        return '#dc2626';
    }

    // ==========================================
    //  SISTEM RELASI DATABASE (ELOQUENT)
    // ==========================================

    /**
     * Relasi balik ke Course (Many Tasks belong to One Course)
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get all student submissions for this task with user data
     */
    public function getStudentSubmissionsAttribute()
    {
        $submissionsRaw = $this->submissions ?? [];
        if (is_string($submissionsRaw)) {
            $submissionsRaw = json_decode($submissionsRaw, true) ?: [];
        }

        $result = [];
        foreach ($submissionsRaw as $userId => $submission) {
            $user = User::find($userId);
            $result[] = array_merge(['user_id' => $userId, 'user' => $user], $submission);
        }
        return collect($result);
    }
}