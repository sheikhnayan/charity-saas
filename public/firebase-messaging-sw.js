// Firebase Cloud Messaging Service Worker
// This file MUST be at the root of your public directory

importScripts('https://www.gstatic.com/firebasejs/11.9.1/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/11.9.1/firebase-messaging-compat.js');
importScripts('/firebase-config.js');

// Initialize Firebase in the service worker
firebase.initializeApp(self.firebaseConfig);

const messaging = firebase.messaging();

// Handle background messages
messaging.onBackgroundMessage((payload) => {
    console.log('[firebase-messaging-sw.js] Received background message:', payload);
    
    const notificationTitle = payload.notification?.title || payload.data?.title || 'New Notification';
    const notificationOptions = {
        body: payload.notification?.body || payload.data?.body || '',
        icon: payload.notification?.icon || payload.data?.icon || '/images/icon-192x192.png',
        badge: '/images/icon-72x72.png',
        tag: payload.data?.tag || 'notification',
        data: payload.data || {},
        requireInteraction: payload.data?.requireInteraction === 'true',
        actions: []
    };

    // Add custom actions based on notification type
    if (payload.data?.type) {
        switch(payload.data.type) {
            case 'donation':
                notificationOptions.actions = [
                    { action: 'view', title: 'View Details', icon: '/images/view-icon.png' },
                    { action: 'close', title: 'Dismiss', icon: '/images/close-icon.png' }
                ];
                break;
            case 'auction_outbid':
                notificationOptions.actions = [
                    { action: 'bid', title: 'Place Bid', icon: '/images/bid-icon.png' },
                    { action: 'view', title: 'View Auction', icon: '/images/view-icon.png' }
                ];
                break;
            case 'goal_reached':
                notificationOptions.actions = [
                    { action: 'share', title: 'Share', icon: '/images/share-icon.png' },
                    { action: 'view', title: 'View Campaign', icon: '/images/view-icon.png' }
                ];
                break;
        }
    }

    return self.registration.showNotification(notificationTitle, notificationOptions);
});

// Handle notification clicks
self.addEventListener('notificationclick', (event) => {
    console.log('[Service Worker] Notification click received:', event);
    
    event.notification.close();
    
    let url = '/';
    
    // Determine URL based on notification type and data
    if (event.notification.data) {
        const data = event.notification.data;
        
        switch(event.action) {
            case 'view':
                if (data.type === 'donation' && data.donation_id) {
                    url = `/admin/transactions`;
                } else if (data.type === 'auction_outbid' && data.auction_id) {
                    url = `/auction/${data.auction_id}`;
                } else if (data.type === 'goal_reached' && data.campaign_id) {
                    url = `/campaign/${data.campaign_id}`;
                } else if (data.url) {
                    url = data.url;
                }
                break;
            case 'bid':
                if (data.auction_id) {
                    url = `/auction/${data.auction_id}#bid-form`;
                }
                break;
            case 'share':
                if (data.campaign_id) {
                    // Open share dialog
                    url = `/campaign/${data.campaign_id}?share=true`;
                }
                break;
            default:
                // Default action (clicking notification body)
                if (data.url) {
                    url = data.url;
                } else if (data.type === 'donation') {
                    url = '/admin/transactions';
                } else if (data.type === 'auction_outbid' && data.auction_id) {
                    url = `/auction/${data.auction_id}`;
                } else if (data.type === 'goal_reached' && data.campaign_id) {
                    url = `/campaign/${data.campaign_id}`;
                }
        }
    }
    
    // Open the URL in a new window or focus existing window
    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true })
            .then((clientList) => {
                // Check if there's already a window open with this URL
                for (let client of clientList) {
                    if (client.url.includes(url) && 'focus' in client) {
                        return client.focus();
                    }
                }
                // Open new window if no matching window found
                if (clients.openWindow) {
                    return clients.openWindow(url);
                }
            })
    );
});

// PWA install prompt
self.addEventListener('install', (event) => {
    console.log('[Service Worker] Installing...');
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    console.log('[Service Worker] Activating...');
    event.waitUntil(clients.claim());
});

// Basic offline caching for PWA
const CACHE_NAME = 'fundably-v1';
const urlsToCache = [
    '/',
    '/offline.html',
    '/css/app.css',
    '/js/app.js',
    '/images/icon-192x192.png'
];

self.addEventListener('fetch', (event) => {
    // Only cache GET requests
    if (event.request.method !== 'GET') return;
    
    event.respondWith(
        caches.match(event.request)
            .then((response) => {
                // Cache hit - return response
                if (response) {
                    return response;
                }
                return fetch(event.request);
            })
            .catch(() => {
                // If both cache and network fail, show offline page
                return caches.match('/offline.html');
            })
    );
});
