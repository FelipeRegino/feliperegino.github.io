const VERSION = "1.0.1";

const APP_CACHE_NAME = 'felipe-regino-app';
const STATIC_CACHE_NAME = 'felipe-regino-static';


const CACHE_STATIC = [
    '/assets/css/main.css',
    '/assets/img/icons/icon-72x72.png',
    '/assets/img/icons/icon-96x96.png',
    '/assets/img/icons/icon-128x128.png',
    '/assets/img/icons/icon-144x144.png',
    '/assets/img/icons/icon-152x152.png',
    '/assets/img/icons/icon-192x192.png',
    '/assets/img/icons/icon-384x384.png',
    '/assets/img/icons/icon-512x512.png',
    '/assets/img/logo.jpg',
 ];

 const CACHE_APP = [
    '/',
    '/404',
    '/about/',
    '/tags/',
    '/posts/',
    '/resolvendo-problema-do-jQuery-InputMask-no-Mobile/',
    '/motivacao-do-blog/',
    '/como-transformar-um-site-no-github-pages-feito-com-jekyll-em-uma-pwa/',
 ];

self.addEventListener('install',function(e){
    e.waitUntil(
        Promise.all([
            caches.open(STATIC_CACHE_NAME),
            caches.open(APP_CACHE_NAME),
            self.skipWaiting()
          ]).then(function(storage){
            var static_cache = storage[0];
            var app_cache = storage[1];
            return Promise.all([
              static_cache.addAll(CACHE_STATIC),
              app_cache.addAll(CACHE_APP)]);
        })
    );
});

self.addEventListener('activate', function(e) {
    e.waitUntil(
        Promise.all([
            self.clients.claim(),
            caches.keys().then(function(cacheNames) {
                return Promise.all(
                    cacheNames.map(function(cacheName) {
                        if (cacheName !== APP_CACHE_NAME && cacheName !== STATIC_CACHE_NAME) {
                            console.log('deleting',cacheName);
                            return caches.delete(cacheName);
                        }
                    })
                );
            })
        ])
    );
});

this.addEventListener('fetch', function(event) {
  var response;
  event.respondWith(caches.match(event.request)
    .then(function (match) {
      return match || fetch(event.request);
    }).catch(function() {
      return fetch(event.request);
    })
    .then(function(r) {
      response = r;
      caches.open(APP_CACHE_NAME).then(function(cache) {
        cache.put(event.request, response);
      });
      return response.clone();
    })
  );
});
