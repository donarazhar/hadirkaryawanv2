<?php

if (!function_exists('vite_asset')) {
    /**
     * Generate Vite asset URL or fallback
     */
    function vite_asset(string $path, string $fallback = null): string
    {
        if (file_exists(public_path('build/manifest.json'))) {
            return \Illuminate\Support\Facades\Vite::asset($path);
        }

        return $fallback ? asset($fallback) : asset($path);
    }
}

if (!function_exists('use_vite')) {
    /**
     * Check if Vite is available
     */
    function use_vite(): bool
    {
        return file_exists(public_path('build/manifest.json'));
    }
}
