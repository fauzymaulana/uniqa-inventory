/**
 * Uniqa Inventory – Service Worker
 * Supports offline caching and background sync for POS transactions.
 */

const CACHE_NAME = 'uniqa-pos-v1';
const STATIC_ASSETS = [
    '/cashier/pos',
    '/cashier/dashboard',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js',
];

// Install: pre-cache static assets
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return Promise.allSettled(STATIC_ASSETS.map((url) => cache.add(url)));
        })
    );
    self.skipWaiting();
});

// Activate: clean up old caches
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(
                keys.filter((key) => key !== CACHE_NAME).map((key) => caches.delete(key))
            )
        )
    );
    self.clients.claim();
});

// Fetch: network-first with cache fallback
self.addEventListener('fetch', (event) => {
    const url = new URL(event.request.url);

    // Skip non-GET and cross-origin API/auth requests
    if (event.request.method !== 'GET') return;
    if (url.pathname.startsWith('/api/') && !url.pathname.startsWith('/api/sync-transactions')) return;

    // Skip storage files – served directly by the web server, not the app
    if (url.pathname.startsWith('/storage/')) return;

    // Skip auth-protected admin pages to avoid stale cache issues with dynamic content
    if (url.pathname.startsWith('/admin/')) return;

    event.respondWith(
        fetch(event.request)
            .then((response) => {
                // Cache successful GET responses for app pages
                if (response.ok && (url.origin === self.location.origin || STATIC_ASSETS.includes(url.href))) {
                    const clone = response.clone();
                    caches.open(CACHE_NAME).then((cache) => cache.put(event.request, clone));
                }
                return response;
            })
            .catch(() => caches.match(event.request))
    );
});

// Background Sync: flush pending offline transactions
self.addEventListener('sync', (event) => {
    if (event.tag === 'sync-transactions') {
        event.waitUntil(syncPendingTransactions());
    }
});

async function syncPendingTransactions() {
    // Signal all clients to attempt sync
    const clients = await self.clients.matchAll({ type: 'window' });
    clients.forEach((client) => client.postMessage({ type: 'SYNC_REQUESTED' }));
}
