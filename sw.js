const CACHE_NAME = 'robotics-v1';
const urlsToCache = [
  '/',
  '/index.html',
  '/style.css',
  '/scripts.js',
  '/icons/icon-192x192.jpg'
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => cache.addAll(urlsToCache))
  );
});

self.addEventListener('fetch', (event) => {
  event.respondWith(
    caches.match(event.request)
      .then((response) => response || fetch(event.request))
  );
});