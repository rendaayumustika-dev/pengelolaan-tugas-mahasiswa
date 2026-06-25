# TODO_DB_FIX (courses table missing)

- [ ] Pastikan file `.env` benar-benar ada (bukan hanya `.env.example`).
- [ ] Pastikan `DB_CONNECTION/DB_HOST/DB_PORT/DB_DATABASE` menunjuk database yang sama dengan error: `pengelolaan-tugas-mahasiswa`.
- [ ] Jalankan migrasi: `php artisan migrate --force`.
- [ ] Verifikasi tabel: `courses` muncul di MySQL.
- [ ] Jalankan seeding: `php artisan db:seed --force` (agar ada course demo).
- [ ] Test Postman lagi endpoint `courses`.

