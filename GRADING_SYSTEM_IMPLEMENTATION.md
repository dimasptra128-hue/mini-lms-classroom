# Sistem Grading & Progress Report - Implementation Guide

## 📋 Fitur yang Telah Diimplementasikan

### 1. **Guru dapat Menilai Hasil Pekerjaan Siswa**
   - **File:** `/resources/views/task_submissions.blade.php` (NEW)
   - **Route:** `GET /kelas/{course_id}/tasks/{task_id}/submissions` → `tasks.showSubmissions`
   - **Tampilan:** Halaman kelola penilaian dengan:
     - Daftar semua siswa dalam kelas
     - Status submission (Dikumpulkan / Belum)
     - Tangal pengumpulan
     - Tombol unduh file submission
     - Form modal untuk memberikan nilai (0-100) dan feedback
     - Summary stats: Total siswa, sudah dikumpulkan, sudah dinilai

### 2. **Guru Melihat Siapa Saja yang Sudah Mengumpulkan atau Belum**
   - Pada halaman submissions:
     - Badge "Dikumpulkan" (hijau) atau "Belum" (merah) untuk setiap siswa
     - Daftar file yang bisa diunduh
     - Timestamp pengumpulan
   - Pada halaman task details untuk teacher:
     - Tombol "Kelola Penilaian" untuk akses halaman submissions

### 3. **Data Nilai Masuk ke Database**
   - **Struktur:** Disimpan dalam kolom JSON `tasks.submissions`
   - **Format:**
     ```json
     {
       "user_id": {
         "submitted_at": "2026-06-19 10:30:00",
         "file_path": "submissions/...",
         "status": "Selesai",
         "score": 85,
         "feedback": "Baik, tapi perlu lebih detail",
         "graded_at": "2026-06-19 11:00:00"
       }
     }
     ```
   - **Controller:** `TaskController@grade()` memproses dan menyimpan nilai

### 4. **Nilai Muncul di Dashboard Siswa**
   - File: `/resources/views/dashboard.blade.php`
   - Menampilkan:
     - Rata-rata nilai dari semua tugas yang sudah dinilai
     - Card dengan badge predikat (A/B/C/D/E)

### 5. **Nilai Muncul di Report Siswa**
   - File: `/resources/views/report.blade.php`
   - Menampilkan per mata pelajaran/kelas:
     - **Total Tugas:** Jumlah seluruh tugas yang diberikan
     - **Selesai:** Jumlah tugas yang sudah dikumpulkan
     - **Progress Bar:** Visual penyelesaian (%)
     - **Nilai Rata-rata:** Per kelas dengan predikat (A-E)
     - **Nilai Tertinggi & Terendah:** Per kelas
     - **Tabel Detail:** Setiap tugas dengan:
       - Judul tugas
       - Deadline
       - Status pengerjaan
       - **Nilai** (dari submission)
       - **Predikat** (A-E)
       - **Feedback guru**

### 6. **Siswa Melihat Nilai & Feedback di Task Details**
   - File: `/resources/views/task_details.blade.php`
   - Pada section "Your Work" setelah pengumpulan:
     - Menampilkan nilai (besar, warna sesuai range)
     - Badge predikat (A-E)
     - Catatan/feedback dari guru
     - Status: "Menunggu penilaian dari guru" jika belum dinilai

---

## 🔧 Files Modified

1. **app/Http/Controllers/TaskController.php**
   - Added: `showSubmissions()` - Menampilkan halaman kelola penilaian
   - Added: `grade()` - Proses penyimpanan nilai & feedback

2. **app/Http/Controllers/DashboardController.php**
   - Updated: `report()` - Parse submission scores dari JSON

3. **app/Models/Task.php**
   - Added: `getStudentSubmissionsAttribute()` - Helper untuk akses submissions
   - Updated: `$casts` - Ensure submissions di-cast sebagai array

4. **routes/web.php**
   - Added: `GET /kelas/{course_id}/tasks/{task_id}/submissions` → `tasks.showSubmissions`
   - Added: `POST /kelas/{course_id}/tasks/{task_id}/grade/{student_id}` → `tasks.grade`

5. **resources/views/task_submissions.blade.php** (NEW)
   - Halaman kelola penilaian untuk guru
   - Tabel siswa dengan status submission
   - Modal form untuk memberikan nilai

6. **resources/views/task_details.blade.php**
   - Added: "Kelola Penilaian" button untuk teacher
   - Added: Score display section untuk student

7. **resources/views/dashboard.blade.php**
   - Already shows average grade

8. **resources/views/report.blade.php**
   - Already shows progress per course
   - Now properly displays submission scores

---

## 🚀 Cara Menggunakan

### **Guru: Memberikan Nilai**
1. Buka halaman tugas → Klik "Kelola Penilaian"
2. Lihat daftar siswa + status submission mereka
3. Klik tombol "Nilai" / "Edit" untuk siswa tersebut
4. Masukkan nilai (0-100) dan feedback (opsional)
5. Klik "Simpan Nilai" → Data masuk ke database

### **Siswa: Melihat Nilai**
1. Dashboard → Lihat "Rata-rata Nilai"
2. Halaman tugas yang sudah dikumpulkan → Lihat nilai & feedback guru
3. Report → Lihat progress per mata pelajaran dengan detail

---

## 📊 Database Structure

Column: `tasks.submissions` (JSON)
```
{
  "1": {
    "submitted_at": "2026-06-19 09:30:00",
    "file_name": "answer.pdf",
    "file_path": "submissions/...",
    "status": "Selesai",
    "score": 85,
    "feedback": "Bagus, perlu lebih teliti",
    "graded_at": "2026-06-19 10:00:00"
  },
  "2": { ... }
}
```

---

## ✅ Testing Checklist

- [ ] Guru bisa membuka halaman submissions
- [ ] Daftar siswa tampil dengan status
- [ ] File submission bisa diunduh
- [ ] Form nilai bisa dibuka via modal
- [ ] Nilai bisa disimpan (0-100)
- [ ] Feedback bisa disimpan
- [ ] Nilai muncul di submission list
- [ ] Nilai muncul di task_details siswa
- [ ] Nilai muncul di dashboard (rata-rata)
- [ ] Progress bar muncul di report
- [ ] Predikat (A-E) muncul di report
- [ ] Feedback guru muncul di report

---

## 🎯 Next Steps (Optional Enhancements)

- [ ] Export report ke PDF
- [ ] Statistik lebih detail per siswa
- [ ] Email notifikasi saat nilai diberikan
- [ ] Rubrik penilaian berbasis kriteria
- [ ] Analisis progress/tren nilai
