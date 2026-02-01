/**
 * Service Worker for Image Caching
 * 
 * This service worker caches images for offline access and faster loading
 */

const CACHE_NAME = 'tsm-theme-images-v1';
const IMAGE_CACHE_DURATION = 7 * 24 * 60 * 60 * 1000; // 7 days in milliseconds

// Install event - cache critical images
self.addEventListener('install', function(event) {
  event.waitUntil(
    caches.open(CACHE_NAME).then(function(cache) {
      // Cache can be populated with critical images here if needed
      return cache.addAll([]);
    })
  );
  self.skipWaiting();
});

// Activate event - clean up old caches
self.addEventListener('activate', function(event) {
  event.waitUntil(
    caches.keys().then(function(cacheNames) {
      return Promise.all(
        cacheNames.map(function(cacheName) {
          if (cacheName !== CACHE_NAME) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
  return self.clients.claim();
});

// Fetch event - serve from cache, fallback to network
self.addEventListener('fetch', function(event) {
  const url = new URL(event.request.url);
  
  // Only cache image requests
  if (event.request.destination === 'image' || 
      /\.(jpg|jpeg|png|gif|webp|svg|ico)$/i.test(url.pathname)) {
    
    event.respondWith(
      caches.match(event.request).then(function(response) {
        // Return cached version if available and not expired
        if (response) {
          const cachedDate = response.headers.get('sw-cached-date');
          if (cachedDate) {
            const cacheAge = Date.now() - parseInt(cachedDate, 10);
            if (cacheAge < IMAGE_CACHE_DURATION) {
              return response;
            }
          } else {
            // If no date header, assume it's fresh (for existing cached items)
            return response;
          }
        }
        
        // Fetch from network
        return fetch(event.request).then(function(response) {
          // Don't cache if not successful
          if (!response || response.status !== 200 || response.type !== 'basic') {
            return response;
          }
          
          // Clone the response
          const responseToCache = response.clone();
          
          // Add cache date header
          const headers = new Headers(responseToCache.headers);
          headers.set('sw-cached-date', Date.now().toString());
          
          // Cache the image
          caches.open(CACHE_NAME).then(function(cache) {
            cache.put(event.request, new Response(responseToCache.body, {
              status: responseToCache.status,
              statusText: responseToCache.statusText,
              headers: headers
            }));
          });
          
          return response;
        }).catch(function() {
          // If network fails and we have a cached version, return it even if expired
          return caches.match(event.request);
        });
      })
    );
  }
});
