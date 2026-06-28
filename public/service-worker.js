// Service Worker untuk PWA Karyawan
// Versi: v5 — fix /panel bypass (jangan cache redirect) dan login navigation
const CACHE_NAME = 'presensigps-cache-v5';

const urlsToCache = [
  '/login',
  '/manifest.json',
  '/assets/css/inc/bootstrap/bootstrap.min.css',
  '/assets/js/lib/bootstrap.min.js',
  '/assets/img/pwa-icon.png'
];

// Paths yang harus di-bypass total — tidak boleh dihandle oleh SW sama sekali
// Termasuk semua sub-path dari /panel
const BYPASS_PREFIXES = ['/panel'];

// Cek apakah URL harus di-bypass
function shouldBypass(url) {
  const pathname = new URL(url).pathname;
  return BYPASS_PREFIXES.some(prefix => pathname === prefix || pathname.startsWith(prefix + '/') || pathname.startsWith(prefix + '?'));
}

// Install Service Worker
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => cache.addAll(urlsToCache))
      .then(() => self.skipWaiting())
  );
});

// Activate Service Worker — hapus cache lama
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames
          .filter(name => name !== CACHE_NAME)
          .map(name => caches.delete(name))
      );
    }).then(() => self.clients.claim())
  );
});

// Fetch handler
self.addEventListener('fetch', event => {
  const request = event.request;
  const url = request.url;

  // 1. Hanya handle HTTP/HTTPS
  if (!url.startsWith('http')) return;

  // 2. Bypass total untuk /panel dan semua sub-path-nya
  if (shouldBypass(url)) {
    // Return tanpa event.respondWith agar request ditangani oleh browser secara native.
    // Ini memperbaiki masalah redirect error di admin panel.
    return;
  }

  // 3. Network First untuk halaman dinamis (termasuk navigasi HTML dan /login)
  const networkFirstPaths = ['/login', '/dashboard', '/presensi/create', '/kalender', '/presensi/histori', '/presensi/izin'];
  const pathname = new URL(url).pathname;
  const isNetworkFirst = networkFirstPaths.some(p => pathname === p || pathname.startsWith(p + '/') || pathname.startsWith(p + '?'));

  // Semua request mode 'navigate' (HTML Document) harus Network First agar redirect server (misal login -> dashboard) berjalan normal!
  if (isNetworkFirst || request.mode === 'navigate' || request.headers.get('accept').includes('text/html')) {
    event.respondWith(
      fetch(request)
        .then(networkResponse => {
          // Jangan cache redirect (301, 302, 303, 307, 308)
          if (networkResponse.redirected || (networkResponse.status >= 300 && networkResponse.status < 400)) {
            return networkResponse;
          }
          // Cache hanya response sukses
          if (networkResponse.ok && networkResponse.type === 'basic') {
            const responseToCache = networkResponse.clone();
            caches.open(CACHE_NAME).then(cache => {
              cache.put(request, responseToCache);
            });
          }
          return networkResponse;
        })
        .catch(() => caches.match(request))
    );
    return;
  }

  // 4. Cache First untuk aset statis
  event.respondWith(
    caches.match(request)
      .then(cached => {
        if (cached) return cached;
        return fetch(request).then(networkResponse => {
          // Jangan cache redirect
          if (networkResponse.ok && networkResponse.type === 'basic') {
            const responseToCache = networkResponse.clone();
            caches.open(CACHE_NAME).then(cache => {
              cache.put(request, responseToCache);
            });
          }
          return networkResponse;
        });
      })
  );
});