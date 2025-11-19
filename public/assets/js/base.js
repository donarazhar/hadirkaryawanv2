/**
 * Base JavaScript
 * Sistem Presensi YPI Al Azhar
 */

(function() {
    'use strict';

    // ===== APP CONFIG =====
    const APP_CONFIG = {
        name: 'Sistem Presensi',
        version: '1.0.0',
        author: 'YPI Al Azhar'
    };

    // ===== CLOCK =====
    function updateClock() {
        const now = new Date();
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                       'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        const dayName = days[now.getDay()];
        const day = now.getDate();
        const month = months[now.getMonth()];
        const year = now.getFullYear();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');

        // Update clock elements if exist
        const clockElement = document.getElementById('clock');
        if (clockElement) {
            clockElement.textContent = `${hours}:${minutes}:${seconds}`;
        }

        const dateElement = document.getElementById('date');
        if (dateElement) {
            dateElement.textContent = `${dayName}, ${day} ${month} ${year}`;
        }
    }

    // Update clock every second
    setInterval(updateClock, 1000);
    updateClock();

    // ===== ONLINE/OFFLINE STATUS =====
    window.addEventListener('online', function() {
        if (typeof showToast === 'function') {
            showToast('Koneksi internet kembali', 'success');
        }
    });

    window.addEventListener('offline', function() {
        if (typeof showToast === 'function') {
            showToast('Tidak ada koneksi internet', 'warning');
        }
    });

    // ===== BATTERY STATUS (if supported) =====
    if ('getBattery' in navigator) {
        navigator.getBattery().then(function(battery) {
            const batteryElement = document.getElementById('battery-status');
            if (batteryElement) {
                const level = Math.round(battery.level * 100);
                batteryElement.textContent = level + '%';
                
                if (level < 20) {
                    batteryElement.style.color = '#ef4444';
                }
            }
        });
    }

    // ===== PREVENT CONTEXT MENU (Optional - for security) =====
    // document.addEventListener('contextmenu', function(e) {
    //     e.preventDefault();
    // });

    // ===== CONSOLE BRANDING =====
    console.log('%c' + APP_CONFIG.name, 'color: #0053C5; font-size: 24px; font-weight: bold;');
    console.log('%cVersion: ' + APP_CONFIG.version, 'color: #64748b; font-size: 12px;');
    console.log('%c' + APP_CONFIG.author, 'color: #64748b; font-size: 12px;');

    // ===== PAGE VISIBILITY =====
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            console.log('Page hidden');
        } else {
            console.log('Page visible');
        }
    });

    // ===== INIT COMPLETE =====
    console.log('%c✓ Base.js loaded', 'color: #10b981; font-weight: bold;');

    // Dispatch custom event
    window.dispatchEvent(new Event('appReady'));

})();