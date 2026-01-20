/**
 * Service Worker Registration
 * Registers the service worker for PWA functionality
 */

if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    const swUrl = '/sw.js';
    
    navigator.serviceWorker
      .register(swUrl)
      .then((registration) => {
        console.log('[PWA] Service Worker registered successfully:', registration.scope);

        // Check for updates periodically
        setInterval(() => {
          registration.update();
        }, 60000); // Check every minute

        // Handle updates
        registration.addEventListener('updatefound', () => {
          const newWorker = registration.installing;

          newWorker.addEventListener('statechange', () => {
            if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
              // New service worker available
              console.log('[PWA] New service worker available');
              
              // Optionally show update notification to user
              if (confirm('A new version of the app is available. Would you like to update?')) {
                newWorker.postMessage({ action: 'skipWaiting' });
                window.location.reload();
              }
            }
          });
        });
      })
      .catch((error) => {
        console.error('[PWA] Service Worker registration failed:', error);
      });

    // Handle service worker messages
    navigator.serviceWorker.addEventListener('message', (event) => {
      console.log('[PWA] Message from service worker:', event.data);
      
      if (event.data && event.data.action === 'reload') {
        window.location.reload();
      }
    });

    // Handle controller change (service worker activated)
    let refreshing = false;
    navigator.serviceWorker.addEventListener('controllerchange', () => {
      if (!refreshing) {
        refreshing = true;
        console.log('[PWA] New service worker activated, reloading page...');
        window.location.reload();
      }
    });
  });
} else {
  console.warn('[PWA] Service Workers are not supported in this browser');
}

