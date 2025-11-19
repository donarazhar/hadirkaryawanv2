# Hadir Karyawan v2

Aplikasi Hadir Karyawan v2 adalah sistem informasi manajemen kehadiran karyawan berbasis web yang dibangun menggunakan framework Laravel. Aplikasi ini memungkinkan karyawan untuk melakukan absensi (presensi) secara online menggunakan teknologi geolokasi untuk memastikan kehadiran di lokasi yang telah ditentukan.

## Fitur Utama

-   **Dashboard Karyawan:** Halaman utama bagi karyawan untuk melihat ringkasan kehadiran dan informasi penting lainnya.
-   **Presensi Online:** Karyawan dapat melakukan absensi masuk dan pulang melalui aplikasi.
-   **Validasi Geolokasi:** Sistem akan memvalidasi lokasi karyawan saat melakukan absensi untuk memastikan mereka berada di area kantor atau lokasi yang diizinkan.
-   **Histori Kehadiran:** Karyawan dapat melihat riwayat absensi mereka.
-   **Pengajuan Izin dan Cuti:** Karyawan dapat mengajukan izin sakit atau cuti melalui aplikasi.
-   **Panel Admin:**
    -   Manajemen data karyawan
    -   Manajemen data departemen dan cabang
    -   Konfigurasi jam kerja
    -   Monitoring kehadiran karyawan secara real-time
    -   Laporan kehadiran (harian, bulanan, rekapitulasi)
    -   Persetujuan pengajuan izin dan cuti

## Teknologi yang Digunakan

-   **Backend:** PHP, Laravel
-   **Frontend:** HTML, CSS, JavaScript, Blade (template engine Laravel)
-   **Database:** MySQL/MariaDB
-   **Manajemen Dependensi:** Composer (PHP), NPM (JavaScript)

## Panduan Instalasi

1.  **Clone repository:**
    ```bash
    git clone https://github.com/donarazhar/hadirkaryawanv2.git
    cd hadirkaryawanv2
    ```

2.  **Install dependensi:**
    ```bash
    composer install
    npm install
    ```

3.  **Setup file `.env`:**
    -   Salin file `.env.example` menjadi `.env`.
    -   ```bash
        cp .env.example .env
        ```
    -   Konfigurasikan koneksi database Anda (DB_DATABASE, DB_USERNAME, DB_PASSWORD).

4.  **Generate application key:**
    ```bash
    php artisan key:generate
    ```

5.  **Jalankan migrasi database:**
    ```bash
    php artisan migrate
    ```

6.  **(Opsional) Jalankan seeder untuk data dummy:**
    ```bash
    php artisan db:seed
    ```

7.  **Jalankan server development:**
    ```bash
    php artisan serve
    npm run dev
    ```

Aplikasi sekarang akan berjalan di `http://localhost:8000`.
