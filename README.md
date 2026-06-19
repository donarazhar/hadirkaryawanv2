# Dokumentasi Lengkap Aplikasi PresensiGPS (Hadir Karyawan v2)

Aplikasi PresensiGPS (Hadir Karyawan v2) adalah sistem informasi manajemen kehadiran karyawan berbasis web yang canggih, dibangun menggunakan framework Laravel. Aplikasi ini mengintegrasikan presensi dengan teknologi **Geolokasi (GPS)** dan **Pengenalan Wajah (Face Recognition)** untuk memastikan keaslian kehadiran karyawan di lokasi kerja.

---

## 1. Tech Stack yang Digunakan

Aplikasi ini dibangun dengan *stack* teknologi modern untuk memastikan performa, keamanan, dan skalabilitas:

- **Bahasa Pemrograman:** PHP 8.2+
- **Framework Backend:** Laravel 11.0
- **Framework Frontend:** Bootstrap 5 (via Laravel Blade Templates)
- **Asset Bundler:** Vite (pengganti Laravel Mix untuk build asset yang lebih cepat)
- **Library HTTP Client:** Axios (untuk interaksi AJAX/API di sisi klien)

## 2. Database yang Digunakan

Aplikasi ini menggunakan **Relational Database Management System (RDBMS)**. Berdasarkan konfigurasi, sistem ini mendukung berbagai database melalui Laravel Eloquent ORM:

- **Default/Development:** SQLite (terlihat di `.env.example` sebagai `DB_CONNECTION=sqlite`)
- **Production (Rekomendasi):** MySQL atau MariaDB
- **Alternatif Lain:** PostgreSQL atau SQL Server (didukung secara bawaan oleh konfigurasi `database.php`)

## 3. Teknologi Spesifik & Library yang Terintegrasi

Selain *stack* utama, aplikasi memanfaatkan berbagai teknologi dan paket eksternal (Third-Party Libraries):

- **Face-API.js:** Library JavaScript berbasis TensorFlow.js yang berjalan di *client-side* (browser) untuk mendeteksi wajah karyawan dan memverifikasi (Face Recognition) saat presensi/enrollment.
- **Laravel Sanctum:** Digunakan untuk autentikasi API dan *Single Page Application* (SPA) / pengelolaan *state* autentikasi ringan.
- **Laravel Socialite:** Integrasi *Single Sign-On* (SSO) menggunakan akun Google (Login with Google).
- **WebAuthn (FIDO2):** Teknologi autentikasi biometrik bawaan perangkat (seperti sidik jari / FaceID pada smartphone) atau *Security Key* eksternal untuk login tanpa password.
- **Barryvdh/Laravel-DomPDF:** Library untuk *generate* laporan presensi dalam bentuk file PDF.
- **Maatwebsite/Excel:** Library untuk *export* laporan presensi ke dalam format Microsoft Excel (.xlsx / .csv).
- **Leaflet.js / Google Maps API:** Digunakan untuk memvisualisasikan koordinat lokasi presensi karyawan ke dalam bentuk Map di panel Admin.

## 4. Keamanan yang Diterapkan

Sistem dirancang dengan arsitektur keamanan berlapis:

- **Autentikasi Lanjutan:** Login konvensional (Email/Password), Login via Google (OAuth), dan Login Biometrik via WebAuthn.
- **Lapis Kehadiran (Anti-Faking):** 
  - **Validasi GPS:** Memastikan koordinat latitude/longitude perangkat karyawan berada dalam radius zona kantor yang diizinkan (Geofencing).
  - **Verifikasi Wajah (Face Liveness & Recognition):** Mencegah "penitipan absen" dengan menggunakan *Face-API.js* yang memindai wajah secara *real-time* langsung dari kamera *device*.
- **Role-Based Access Control (RBAC):** Pemisahan rute, controller, dan antarmuka secara tegas antara **Admin** dan **Karyawan**.
- **Keamanan Framework (Laravel Security):** Proteksi otomatis terhadap CSRF (Cross-Site Request Forgery), XSS (Cross-Site Scripting), dan SQL Injection.
- **Enkripsi Data:** Enkripsi *password* dengan Bcrypt.

## 5. Workflow Aplikasi

### Workflow Karyawan
1. **Pendaftaran Wajah (Face Enrollment):** Karyawan yang baru terdaftar harus mendaftarkan data wajah (Enrollment) melalui kamera agar dikenali sistem.
2. **Login:** Karyawan login ke dashboard (bisa dengan sidik jari/FaceID jika WebAuthn telah diset).
3. **Presensi:** Saat jam kerja dimulai, karyawan membuka menu presensi. Sistem akan melacak lokasi (GPS) dan membuka kamera. Karyawan mengambil foto, lalu sistem memvalidasi koordinat lokasi dan mencocokkan wajah. Jika lolos, absensi tercatat.
4. **Pengajuan:** Jika berhalangan hadir, karyawan dapat mengajukan Izin/Sakit/Cuti (beserta dokumen surat dokter) melalui sistem.
5. **Monitoring:** Karyawan dapat mengecek riwayat kehadiran mereka secara mandiri.

### Workflow Admin
1. **Master Data:** Admin menyiapkan data Cabang, Departemen, Jam Kerja Shift, dan akun Karyawan.
2. **Konfigurasi Aturan:** Admin menetapkan koordinat lokasi kantor, radius jarak absensi yang valid (Geofence), serta kalender libur.
3. **Approval Pengajuan:** Admin memeriksa dan memutuskan (Approve/Reject) pengajuan Izin/Sakit/Cuti dari karyawan.
4. **Monitoring & Laporan:** Admin memantau kehadiran karyawan secara *real-time*, melihat peta letak absensi, dan mencetak laporan rekap kehadiran ke format Excel/PDF untuk kebutuhan HRD atau penggajian (Payroll).

## 6. Fitur Lengkap & Fungsinya

### Modul Karyawan
- **Dashboard:** Menampilkan ringkasan presensi hari ini, info jam kerja, dan rekap bulan berjalan.
- **Presensi Online (Face & GPS):** Melakukan absen masuk/pulang dengan validasi lokasi serta pengenalan wajah.
- **Face Enrollment:** Mendaftarkan dan memperbarui *template* deteksi wajah pengguna.
- **Pengajuan Izin / Sakit / Cuti:** Form pengajuan ketidakhadiran dengan opsi unggah dokumen bukti pendukung.
- **Histori Presensi:** Rekap absensi personal per bulan/periode.
- **Profile & WebAuthn Setting:** Pengaturan akun, ganti password, dan sinkronisasi perangkat biometrik untuk *login*.

### Modul Admin
- **Dashboard Admin:** Menampilkan metrik dan statistik jumlah karyawan hadir, izin, sakit, alpa, dan terlambat secara *real-time*.
- **Kelola Karyawan:** Menambah, mengubah, menonaktifkan, atau *reset password* akun karyawan.
- **Kelola Cabang & Departemen:** Manajemen struktur organisasi dan lokasi-lokasi kerja.
- **Kelola Jam Kerja & Shift:** Menentukan jam masuk/pulang, batas keterlambatan, dan *mapping* pola shift kerja.
- **Konfigurasi Lokasi (Geofence):** Menentukan *latitude*, *longitude*, dan radius aman (dalam hitungan meter).
- **Kelola Hari Libur:** Menginput jadwal libur/cuti bersama agar tidak dihitung alpa oleh sistem.
- **Approval Izin/Sakit/Cuti:** Mengelola semua pengajuan karyawan dan status persetujuannya.
- **Monitoring Peta (Show Map):** Melacak secara tepat di mana titik koordinat absen masing-masing karyawan dilakukan.
- **Laporan & Rekapitulasi:** Ekstraksi data presensi ke Excel (.xlsx) atau file PDF siap cetak.
- **Activity Logs:** Riwayat aktivitas log (audit trail) untuk memantau perubahan data apa pun di dalam sistem.

## 7. Langkah Implementasi ke Dunia Nyata (Deployment Guide)

Jika Anda ingin mengimplementasikan aplikasi ini di *server production* untuk digunakan langsung oleh karyawan, ikuti panduan *step-by-step* berikut:

### Persiapan Server (Requirements)
- OS: Ubuntu 22.04 LTS / 24.04 LTS (Direkomendasikan)
- Web Server: Nginx atau Apache
- Database: MySQL 8+ atau PostgreSQL 14+
- PHP: Versi 8.2+ beserta ekstensi esensial (BCMath, Ctype, PDO, MBString, XML, OpenSSL, GD/Imagick).
- Composer 2.x & Node.js LTS

### Panduan Instalasi & Deploy

1. **Clone & Pindahkan Repository:**
   ```bash
   git clone <url-repo-anda> /var/www/presensigps
   cd /var/www/presensigps
   ```

2. **Install Dependensi:**
   ```bash
   # Install dependensi PHP (tanpa paket development)
   composer install --optimize-autoloader --no-dev
   
   # Install & Build dependensi JS/CSS
   npm install
   npm run build
   ```

3. **Konfigurasi Lingkungan (.env):**
   ```bash
   cp .env.example .env
   nano .env
   ```
   *Ubah bagian berikut yang paling penting:*
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `APP_URL=https://presensi.perusahaananda.com` (**WAJIB HTTPS** agar browser mengizinkan akses Kamera & API GPS)
   - Konfigurasikan koneksi `DB_CONNECTION=mysql`, `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.
   - Konfigurasi `GOOGLE_CLIENT_ID` dan `SECRET` jika ingin menggunakan Login Google SSO.

4. **Generate Key & Migrasi Database:**
   ```bash
   php artisan key:generate
   php artisan migrate --force
   ```

5. **Symlink Storage (Sangat Penting):**
   Digunakan agar file foto (profil, foto wajah, file surat dokter) bisa diakses lewat web.
   ```bash
   php artisan storage:link
   ```

6. **Konfigurasi SSL HTTPS (Wajib):**
   WebRTC (Akses Kamera) dan HTML5 Geolocation akan secara otomatis diblokir oleh browser (Chrome/Safari) jika tidak menggunakan protokol HTTPS yang valid.
   Gunakan Let's Encrypt (Certbot):
   ```bash
   sudo certbot --nginx -d presensi.perusahaananda.com
   ```

7. **Konfigurasi Hak Akses (Permissions):**
   Beri *write permission* ke folder log dan storage agar aplikasi bisa menyimpan file dan log error.
   ```bash
   sudo chown -R www-data:www-data /var/www/presensigps
   sudo chmod -R 775 /var/www/presensigps/storage
   sudo chmod -R 775 /var/www/presensigps/bootstrap/cache
   ```

8. **Optimasi Framework:**
   Cache konfigurasi untuk mempercepat load aplikasi.
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

9. **Go Live & Setup Master Data:**
   - Akses aplikasi di browser.
   - Login dengan akun Super Admin (Buat menggunakan Seeder jika belum ada).
   - Di Panel Admin, daftarkan **Cabang**, **Departemen**, **Jam Kerja**, serta **Konfigurasi Koordinat & Radius Lokasi Kantor**.
   - Input/import data Karyawan.
   - Minta karyawan untuk melakukan pendaftaran wajah pertama kali (**Face Enrollment**) saat mereka berada di dalam radius kantor.

---
*Dokumentasi digenerate berdasarkan struktur *source code* dan fungsionalitas aplikasi.*
