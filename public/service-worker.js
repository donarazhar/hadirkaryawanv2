// Service Worker untuk PWA
const CACHE_NAME = 'presensigps-cache-v1';
const urlsToCache = [
  '/',
  '/manifest.json',
  '/assets/css/inc/bootstrap/bootstrap.min.css',
  '/assets/js/lib/bootstrap.min.js',
  '/assets/img/pwa-icon.png'
];

// Install Service Worker
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => cache.addAll(urlsToCache))
    );
});

// Activate Service Worker
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheName !== CACHE_NAME) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});

// Fetch
self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => response || fetch(event.request))
    );
});