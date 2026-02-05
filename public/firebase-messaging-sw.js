importScripts('https://www.gstatic.com/firebasejs/10.0.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.0.0/firebase-messaging-compat.js');

const firebaseConfig = {
  apiKey: 'AIzaSyByoL8iPjlkVpctypejghFLnhzOjwP4g9Y',
  authDomain: 'kandura-store.firebaseapp.com',
  projectId: 'kandura-store',
  storageBucket: 'kandura-store.firebasestorage.app',
  messagingSenderId: '990745065212',
  appId: '1:990745065212:web:d6027cafedb6aa9d86c979',
  measurementId: 'G-276FYB2V2M'
};

firebase.initializeApp(firebaseConfig);

const messaging = firebase.messaging();

messaging.onBackgroundMessage(payload => {
  console.log('[SW] 📨 Background message received:', payload);

  const notificationTitle = payload.notification?.title || payload.data?.title || 'New Notification';
  const notificationOptions = {
    body: payload.notification?.body || payload.data?.body || '',
    icon: payload.notification?.icon || payload.data?.icon || '/firebase-logo.png',
    badge: '/firebase-logo.png',
    data: payload.data || {}
  };

  console.log('[SW] 🔔 Showing notification:', notificationTitle, notificationOptions);

  return self.registration
    .showNotification(notificationTitle, notificationOptions)
    .then(() => {
      console.log('[SW] ✅ Notification shown successfully');
    })
    .catch(err => {
      console.error('[SW] ❌ Failed to show notification:', err);
    });
});

// Handle notification clicks
self.addEventListener('notificationclick', event => {
  console.log('[SW] 🖱️ Notification clicked:', event.notification.title);
  event.notification.close();

  // Focus or open the app window
  event.waitUntil(
    clients.matchAll({ type: 'window', includeUncontrolled: true }).then(clientList => {
      for (let client of clientList) {
        if (client.url === '/' && 'focus' in client) {
          return client.focus();
        }
      }
      if (clients.openWindow) {
        return clients.openWindow('/');
      }
    })
  );
});
