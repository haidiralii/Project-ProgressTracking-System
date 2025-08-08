# Nama aplikasi web "ProTrack" (Progress Tracking)

## Langkah menjalankan project
1. Jalankan `composer install`
2. Jalankan `npm install && npm run dev` (jika pakai Vite)
3. Salin `.env.example` ke `.env`, lalu sesuaikan konfigurasi DB
4. Jalankan `php artisan key:generate`
5. Jalankan `php artisan migrate`
6. Jalankan server: `php artisan serve`

## CATATAN
- Saat pembuatan akun di halaman register, role sudah pasti sebagai admin.
- Jika ingin membuat akun dengan role lain (Director atau Operator), maka harus login sebagai admin terlebih dahulu dan masuk ke menu "User" lalu membuat akun dengan role yang diinginkan.

# Hak akses masing-masing role
- Admin: Mengelola proyek, job, aktivitas, user, dan laporan.
- Director: Melihat progress proyek dan job yang ditugaskan ke operator, dan laporan.
<<<<<<< HEAD
- Operator: Melihat proyek yang tersedia, mengelola job yang ditugaskan, mencatat aktivitas dan melihat laporan masing-masing.
=======
- Operator: Melihat proyek yang tersedia, mengelola job yang ditugaskan, mencatat aktivitas dan melihat laporan masing-masing.
>>>>>>> d11332e (commit 1: Laravel ProTrack App)
