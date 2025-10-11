// Ganti versi setiap kali kamu update file (misal v3, v4, dst)
const CACHE_NAME = 'info-masjid-v2';
const FILES_TO_CACHE = [
  './',
  './index.html',
  './manifest.json',
  './icon-192.png',
  './icon-512.png'
];

// 🔹 Saat service worker di-install
self.addEventListener('install', (event) => {
  console.log("🟢 Service Worker: install");
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      console.log("📦 Menyimpan file ke cache:", CACHE_NAME);
      return cache.addAll(FILES_TO_CACHE);
    })
  );
  // Supaya langsung aktif tanpa tunggu reload
  self.skipWaiting();
});

// 🔹 Saat diaktifkan - hapus cache versi lama
self.addEventListener('activate', (event) => {
  console.log("🟡 Service Worker: activate");
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames
          .filter((name) => name !== CACHE_NAME)
          .map((name) => {
            console.log("🗑️ Hapus cache lama:", name);
            return caches.delete(name);
          })
      );
    })
  );
  // Segera ambil alih halaman aktif
  self.clients.claim();
});

// 🔹 Intersepsi semua permintaan
self.addEventListener('fetch', (event) => {
  event.respondWith(
    caches.match(event.request).then((response) => {
      // Jika ada di cache, gunakan itu; kalau tidak, ambil dari jaringan
      return response || fetch(event.request);
    })
  );
});