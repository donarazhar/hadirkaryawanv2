<?php

use Illuminate\Support\Facades\Storage;

/**
 * Navigation Helper Functions
 * Laravel 11 Compatible
 */

if (!function_exists('is_active_menu')) {
    /**
     * Check if menu is active based on route patterns
     *
     * @param string|array $routes
     * @param string $class
     * @return string
     */
    function is_active_menu($routes, string $class = 'active'): string
    {
        $routes = is_array($routes) ? $routes : [$routes];

        foreach ($routes as $route) {
            if (request()->routeIs($route . '*')) {
                return $class;
            }
        }

        return '';
    }
}

if (!function_exists('bottom_nav_active')) {
    /**
     * Get active class for bottom navigation
     *
     * @param string $menu
     * @return string
     */
    function bottom_nav_active(string $menu): string
    {
        $patterns = [
            'dashboard' => ['dashboard'],
            'histori' => ['histori', 'gethistori'],
            'presensi' => ['presensi'],
            'izin' => ['izin', 'buatizin', 'storeizin', 'showact', 'cekizin'],
            'profile' => ['profile', 'editprofile', 'updateprofile']
        ];

        if (!isset($patterns[$menu])) {
            return '';
        }

        foreach ($patterns[$menu] as $pattern) {
            if (request()->routeIs($pattern . '*')) {
                return $menu === 'presensi' ? 'active-camera' : 'active';
            }
        }

        return '';
    }
}

if (!function_exists('user_avatar')) {
    /**
     * Get user avatar URL
     *
     * @param string|null $foto
     * @param string|null $nama
     * @return string
     */
    function user_avatar(?string $foto, ?string $nama = 'User'): string
    {
        if ($foto && Storage::disk('public')->exists('uploads/karyawan/' . $foto)) {
            return Storage::url('uploads/karyawan/' . $foto);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($nama) . '&background=0053C5&color=fff&size=200';
    }
}

if (!function_exists('format_tanggal_indonesia')) {
    /**
     * Format tanggal ke bahasa Indonesia
     *
     * @param string $date
     * @param bool $showDay
     * @return string
     */
    function format_tanggal_indonesia(string $date, bool $showDay = true): string
    {
        $hari = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];

        $bulan = [
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];

        $timestamp = strtotime($date);
        $namaHari = $hari[date('l', $timestamp)];
        $tanggal = date('d', $timestamp);
        $namaBulan = $bulan[(int)date('n', $timestamp)];
        $tahun = date('Y', $timestamp);

        if ($showDay) {
            return "{$namaHari}, {$tanggal} {$namaBulan} {$tahun}";
        }

        return "{$tanggal} {$namaBulan} {$tahun}";
    }
}
