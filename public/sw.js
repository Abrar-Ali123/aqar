self.addEventListener('push', function(event) {
    const options = {
        body: event.data.text(),
        icon: '/images/logo.png',
        badge: '/images/badge.png',
        vibrate: [100, 50, 100],
        data: {
            dateOfArrival: Date.now(),
            primaryKey: 1
        },
        actions: [
            {
                action: 'explore',
                title: 'عرض المزيد',
                icon: '/images/checkmark.png'
            },
            {
                action: 'close',
                title: 'إغلاق',
                icon: '/images/xmark.png'
            },
        ]
    };

    event.waitUntil(
        self.registration.showNotification('عقار', options)
    );
});

self.addEventListener('notificationclick', function(event) {
    event.notification.close();

    if (event.action === 'explore') {
        clients.openWindow("/products");
    }
});

// تخزين مؤقت للملفات الثابتة
const CACHE_NAME = 'aqar-static-v1';
const urlsToCache = [
    '/',
    '/css/app.css',
    '/js/app.js',
    '/images/logo.png',
    '/images/badge.png',
    '/offline.html'
];

self.addEventListener('install', function(event) {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(function(cache) {
                return cache.addAll(urlsToCache);
            })
    );
});

self.addEventListener('fetch', function(event) {
    event.respondWith(
        caches.match(event.request)
            .then(function(response) {
                if (response) {
                    return response;
                }
                return fetch(event.request)
                    .then(function(response) {
                        if(!response || response.status !== 200 || response.type !== 'basic') {
                            return response;
                        }

                        const responseToCache = response.clone();

                        caches.open(CACHE_NAME)
                            .then(function(cache) {
                                cache.put(event.request, responseToCache);
                            });

                        return response;
                    })
                    .catch(function() {
                        return caches.match('/offline.html');
                    });
            })
    );
});
