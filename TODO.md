# TODO - Fix tabel courses tidak masuk database

## 1) Investigasi
- [x] Baca migrasi `courses` dan `tasks`
- [x] Baca `CourseController`, `Course` model, dan relasi yang terkait

## 2) Validasi environment/migrasi dieksekusi
- [x] Penyebab ditemukan: Laravel tidak bisa jalan (butuh `vendor/`), sehingga migrasi tidak dieksekusi
- [ ] Perbaiki dengan cara menurunkan requirement PHP di dependency agar bisa diinstall dengan PHP 8.3
- [ ] Jalankan `composer install` dan `php artisan migrate --force`


## 3) Verifikasi hasil
- [ ] Cek tabel `courses` muncul di database
- [ ] Cek endpoint `GET /api/courses` mengembalikan data untuk user yang login

