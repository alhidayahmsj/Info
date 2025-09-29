self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open('info-masjid-v1').then((cache) => {
      return cache.addAll([
        './',
        './index.html',
        './manifest.json',
        './icon-192.png',
        './icon-512.png'
      ]);
    })
  );
  console.log("✅ Service Worker terpasang");
});

self.addEventListener('fetch', (event) => {
  event.respondWith(
    caches.match(event.request).then((response) => {
      return response || fetch(event.request);
    })
  );
});