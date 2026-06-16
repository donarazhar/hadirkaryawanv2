const CACHE_NAME = 'presensigps-cache-v2';
const urlsToCache = [
  '/login',
  '/manifest.json',
  '/assets/css/inc/bootstrap/bootstrap.min.css',
  '/assets/js/lib/bootstrap.min.js',
  '/assets/img/pwa-icon.png'
];

// Paths yang TIDAK boleh di-intercept oleh service worker (admin panel)
const BYPASS_PATHS = ['/panel'];

// Install Service Worker
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        return cache.addAll(urlsToCache);
      })
  );
  self.skipWaiting();
});

// Activate Service Worker
self.addEventListener('activate', event => {
  const cacheWhitelist = [CACHE_NAME];
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheWhitelist.indexOf(cacheName) === -1) {
            return caches.delete(cacheName);
          }
        })
      );
    }).then(() => self.clients.claim())
  );
});

// Fetch: bypass admin panel routes sepenuhnya
self.addEventListener('fetch', event => {
  const url = new URL(event.request.url);

  // Jika request menuju /panel, langsung teruskan ke network (bypass cache)
  const isBypass = BYPASS_PATHS.some(path => url.pathname.startsWith(path));
  if (isBypass) {
    event.respondWith(fetch(event.request));
    return;
  }

  // Untuk route lainnya: cache-first strategy
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        return response || fetch(event.request);
      })
  );
});
