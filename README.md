# Sistem Akademik Sederhana

## 📌 Fitur
- **Authentication**
  - Login / Logout untuk admin & student.
- **Admin**
  - Manage Courses: tambah, edit, hapus course dengan validasi input.
  - Manage Students: tambah, edit, hapus student dengan validasi input.
- **Student**
  - Melihat daftar course.
  - Enroll course lewat checklist (multi-select).
  - Validasi: tidak bisa double-enroll course yang sama.
  - Melihat daftar course yang sudah diambil (My Courses).
- **UI/UX**
  - Sidebar menu aktif sesuai halaman.
  - Validasi form → error message + border merah.
  - Konfirmasi sebelum delete (modal menampilkan nama + SKS/NIM).
  - Interaksi tanpa refresh (JS DOM + fetch).

## 🛠️ Teknologi
- **Backend**: CodeIgniter 4 (PHP 8)
- **Database**: MySQL
- **Frontend**: Bootstrap 5, Bootstrap Icons
- **JavaScript**: DOM API (`getElementById`, `querySelector`, `createElement`), Event Handling (`addEventListener`), Async (`fetch`, `setTimeout`)

## 📷 Screenshot
> (tambahkan gambar hasil uji coba)
1. Halaman Login  
2. Dashboard Admin  
3. Manage Courses (Add/Edit/Delete)  
4. Manage Students (Add/Edit/Delete)  
5. Dashboard Student  
6. Daftar Course + Checklist Enroll  
7. My Courses (daftar course yang sudah diambil)  

## 📖 Lesson Learned
- Belajar menghubungkan validasi sisi server & sisi klien (form dengan error message).
- Belajar manipulasi DOM (membuat elemen checklist dinamis dari data database).
- Memahami perbedaan synchronous vs asynchronous (validasi langsung vs fetch API).
- Membuat UI lebih ramah dengan modal konfirmasi & update dinamis tanpa reload.

## 🔗 Repository
[Link GitHub Repository] https://github.com/zahraldila/Proyek3-Mission4.git
