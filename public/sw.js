// sw.js - versi lama, diarahkan ke service-worker.js
// File ini dibiarkan kosong/redirect agar tidak konflik
// Service worker utama ada di /service-worker.js

const CACHE_NAME = 'presensigps-cache-v4';
const BYPASS_PREFIXES = ['/panel'];

function shouldBypass(url) {
  const pathname = new URL(url).pathname;
  return BYPASS_PREFIXES.some(prefix =>
    pathname === prefix ||
    pathname.startsWith(prefix + '/') ||
    pathname.startsWith(prefix + '?')
  );
}

self.addEventListener('install', event => {
  self.skipWaiting();
});

self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(names => Promise.all(names.map(n => caches.delete(n))))
      .then(() => self.clients.claim())
  );
});

self.addEventListener('fetch', event => {
  const url = event.request.url;
  if (!url.startsWith('http')) return;

  if (shouldBypass(url)) {
    event.respondWith(fetch(event.request, { cache: 'no-store', redirect: 'follow' }));
    return;
  }

  event.respondWith(
    caches.match(event.request).then(r => r || fetch(event.request))
  );
});
