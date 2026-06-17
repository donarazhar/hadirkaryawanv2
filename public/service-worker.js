// Service Worker untuk PWA Karyawan
// Versi: v4 — fix /panel bypass (jangan cache redirect)
const CACHE_NAME = 'presensigps-cache-v4';

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
  //    Gunakan cache: 'no-store' agar browser tidak cache redirect dari server
  if (shouldBypass(url)) {
    event.respondWith(
      fetch(request, { cache: 'no-store', redirect: 'follow' })
        .catch(() => {
          // Jika offline, tampilkan pesan bahwa panel tidak tersedia offline
          return new Response(
            '<html><body style="font-family:sans-serif;text-align:center;padding:40px">' +
            '<h2>Panel Admin</h2><p>Tidak dapat terhubung ke server. Silakan cek koneksi internet Anda.</p>' +
            '<a href="/panel" style="color:blue">Coba lagi</a></body></html>',
            { status: 503, headers: { 'Content-Type': 'text/html' } }
          );
        })
    );
    return;
  }

  // 3. Network First untuk halaman dinamis karyawan
  const networkFirstPaths = ['/dashboard', '/presensi/create', '/kalender', '/presensi/histori', '/presensi/izin'];
  const pathname = new URL(url).pathname;
  const isNetworkFirst = networkFirstPaths.some(p => pathname === p || pathname.startsWith(p + '/') || pathname.startsWith(p + '?'));

  if (isNetworkFirst || request.mode === 'navigate') {
    event.respondWith(
      fetch(request)
        .then(networkResponse => {
          // Jangan cache redirect (301, 302, 303, 307, 308)
          if (networkResponse.redirected || (networkResponse.status >= 300 && networkResponse.status < 400)) {
            return networkResponse;
          }
          // Cache hanya response sukses
          if (networkResponse.ok) {
            caches.open(CACHE_NAME).then(cache => {
              cache.put(request, networkResponse.clone());
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
          if (networkResponse.ok) {
            caches.open(CACHE_NAME).then(cache => {
              cache.put(request, networkResponse.clone());
            });
          }
          return networkResponse;
        });
      })
  );
});