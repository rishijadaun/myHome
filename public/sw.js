// SpaceSeeks Progressive Web App (PWA) Service Worker
const CACHE_NAME = 'spaceseeks-pwa-v3';
const ASSETS_TO_CACHE = [
  '/',
  '/manifest.json',
  '/images/favicon.png',
  '/images/spaceseeks-logo.png',
  '/images/icon-192.png',
  '/images/apple-touch-icon.png'
];

// Install Event - Pre-cache core shell
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(ASSETS_TO_CACHE).catch(() => {});
    })
  );
  self.skipWaiting();
});

// Activate Event - Clean up old caches
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) => {
      return Promise.all(
        keys.map((key) => {
          if (key !== CACHE_NAME) {
            return caches.delete(key);
          }
        })
      );
    })
  );
  self.clients.claim();
});

// Fetch Event - Network First with Cache Fallback for dynamic pages
self.addEventListener('fetch', (event) => {
  // Only handle HTTP & HTTPS GET requests (ignore chrome-extension://, moz-extension://, data:, blob:)
  if (event.request.method !== 'GET') return;
  if (!event.request.url.startsWith('http://') && !event.request.url.startsWith('https://')) return;

  const url = new URL(event.request.url);

  // Allow Vite build assets and scripts to load directly via browser module loader (prevents cross-world mismatch)
  if (url.pathname.startsWith('/build/') || event.request.destination === 'script') {
    return;
  }

  // For static assets (images, fonts, styles), use Cache First
  if (
    url.pathname.startsWith('/images/') ||
    url.hostname.includes('fonts.gstatic.com') ||
    url.hostname.includes('cdnjs.cloudflare.com')
  ) {
    event.respondWith(
      caches.match(event.request).then((cachedResponse) => {
        if (cachedResponse) return cachedResponse;
        return fetch(event.request).then((networkResponse) => {
          if (networkResponse && networkResponse.status === 200 && networkResponse.type === 'basic') {
            const responseClone = networkResponse.clone();
            caches.open(CACHE_NAME).then((cache) => {
              try {
                cache.put(event.request, responseClone);
              } catch(e) {}
            });
          }
          return networkResponse;
        }).catch(() => cachedResponse);
      })
    );
    return;
  }

  // For HTML navigation pages, use Network First, fallback to cache
  if (event.request.mode === 'navigate') {
    event.respondWith(
      fetch(event.request)
        .then((networkResponse) => {
          if (networkResponse && networkResponse.status === 200 && networkResponse.type === 'basic') {
            const responseClone = networkResponse.clone();
            caches.open(CACHE_NAME).then((cache) => {
              try {
                cache.put(event.request, responseClone);
              } catch(e) {}
            });
          }
          return networkResponse;
        })
        .catch(() => caches.match(event.request))
    );
  }
});
