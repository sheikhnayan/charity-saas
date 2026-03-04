/**
 * Push Notifications Manager
 * Handles Firebase Cloud Messaging for web push notifications
 */

class PushNotificationManager {
    constructor() {
        this.messaging = null;
        this.currentToken = null;
        this.isSupported = false;
        this.isSubscribed = false;
        this.userId = null;
        
        this.init();
    }

    async init() {
        try {
            // Check if notifications are supported
            if (!('Notification' in window)) {
                console.warn('This browser does not support notifications');
                return;
            }

            if (!('serviceWorker' in navigator)) {
                console.warn('This browser does not support service workers');
                return;
            }

            this.isSupported = true;

            // Get user ID from meta tag or global variable
            const userIdMeta = document.querySelector('meta[name="user-id"]');
            this.userId = userIdMeta ? userIdMeta.content : (window.userId || null);

            // Initialize Firebase
            await this.initializeFirebase();

            // Check current permission status
            this.updateUIBasedOnPermission();

        } catch (error) {
            console.error('Failed to initialize push notifications:', error);
        }
    }

    async initializeFirebase() {
        try {
            const { initializeApp, getApps } = await import('https://www.gstatic.com/firebasejs/11.9.1/firebase-app.js');
            const { getMessaging, getToken, onMessage } = await import('https://www.gstatic.com/firebasejs/11.9.1/firebase-messaging.js');

            // Get Firebase config from meta tags
            const firebaseConfig = {
                apiKey: document.querySelector('meta[name="firebase-api-key"]')?.content || '',
                authDomain: document.querySelector('meta[name="firebase-auth-domain"]')?.content || '',
                projectId: document.querySelector('meta[name="firebase-project-id"]')?.content || '',
                storageBucket: document.querySelector('meta[name="firebase-storage-bucket"]')?.content || '',
                messagingSenderId: document.querySelector('meta[name="firebase-messaging-sender-id"]')?.content || '',
                appId: document.querySelector('meta[name="firebase-app-id"]')?.content || ''
            };
            
            console.log('Firebase config loaded:', {
                apiKey: firebaseConfig.apiKey ? '✓ Present' : '✗ Missing',
                projectId: firebaseConfig.projectId
            });

            // Initialize Firebase only once
            const app = getApps().length === 0 ? initializeApp(firebaseConfig) : getApps()[0];
            this.messaging = getMessaging(app);

            // Handle foreground messages
            onMessage(this.messaging, (payload) => {
                console.log('Message received in foreground:', payload);
                this.showForegroundNotification(payload);
            });

            // Register service worker
            await this.registerServiceWorker();

        } catch (error) {
            console.error('Firebase initialization failed:', error);
            throw error;
        }
    }

    async registerServiceWorker() {
        try {
            const registration = await navigator.serviceWorker.register('/firebase-messaging-sw.js', {
                scope: '/'
            });
            console.log('Service Worker registered:', registration);
            
            // Wait for service worker to be ready
            await navigator.serviceWorker.ready;
            console.log('Service Worker is ready');
            
            return registration;
        } catch (error) {
            console.error('Service Worker registration failed:', error);
            throw error;
        }
    }

    async requestPermission() {
        try {
            const permission = await Notification.requestPermission();
            console.log('Notification permission:', permission);

            if (permission === 'granted') {
                await this.getAndSaveToken();
                this.updateUIBasedOnPermission();
                return true;
            } else if (permission === 'denied') {
                this.showPermissionDeniedMessage();
                return false;
            }
            return false;

        } catch (error) {
            console.error('Permission request failed:', error);
            return false;
        }
    }

    async getAndSaveToken() {
        try {
            const { getToken } = await import('https://www.gstatic.com/firebasejs/11.9.1/firebase-messaging.js');
            
            // Get VAPID key from meta tag
            const vapidKey = document.querySelector('meta[name="firebase-vapid-key"]')?.content;
            
            if (!vapidKey) {
                console.error('VAPID key not found in meta tags');
                return null;
            }
            
            const currentToken = await getToken(this.messaging, { vapidKey });

            if (currentToken) {
                console.log('FCM Token received:', currentToken);
                this.currentToken = currentToken;
                
                // Save token to server
                await this.saveTokenToServer(currentToken);
                this.isSubscribed = true;
                
                return currentToken;
            } else {
                console.warn('No FCM token available');
                return null;
            }

        } catch (error) {
            console.error('Failed to get FCM token:', error);
            return null;
        }
    }

    async saveTokenToServer(token) {
        try {
            if (!this.userId) {
                console.warn('No user ID available, skipping token save');
                return;
            }

            const response = await fetch('/api/notifications/save-token', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({
                    token: token,
                    device_type: 'web',
                    browser: this.getBrowserInfo()
                })
            });

            if (!response.ok) {
                throw new Error('Failed to save token to server');
            }

            const data = await response.json();
            console.log('Token saved to server:', data);

        } catch (error) {
            console.error('Failed to save token to server:', error);
        }
    }

    async unsubscribe() {
        try {
            if (!this.currentToken) {
                console.warn('No token to unsubscribe');
                return;
            }

            // Delete token from server
            await fetch('/api/notifications/delete-token', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({
                    token: this.currentToken
                })
            });

            // Delete token from Firebase
            const { deleteToken } = await import('https://www.gstatic.com/firebasejs/11.9.1/firebase-messaging.js');
            await deleteToken(this.messaging);

            this.currentToken = null;
            this.isSubscribed = false;
            this.updateUIBasedOnPermission();

            console.log('Unsubscribed from push notifications');

        } catch (error) {
            console.error('Failed to unsubscribe:', error);
        }
    }

    showForegroundNotification(payload) {
        const title = payload.notification?.title || payload.data?.title || 'New Notification';
        const options = {
            body: payload.notification?.body || payload.data?.body || '',
            icon: payload.notification?.icon || '/images/icon-192x192.png',
            badge: '/images/icon-72x72.png',
            tag: payload.data?.tag || 'notification',
            data: payload.data,
            requireInteraction: false
        };

        // Show browser notification
        if (Notification.permission === 'granted') {
            new Notification(title, options);
        }

        // Also show in-app notification
        this.showInAppNotification(title, options.body, payload.data);
    }

    showInAppNotification(title, body, data = {}) {
        // Create toast notification element
        const toast = document.createElement('div');
        toast.className = 'push-notification-toast';
        toast.innerHTML = `
            <div class="push-notification-header">
                <img src="/images/icon-72x72.png" alt="Icon" class="push-notification-icon">
                <strong>${this.escapeHtml(title)}</strong>
                <button class="push-notification-close">&times;</button>
            </div>
            <div class="push-notification-body">${this.escapeHtml(body)}</div>
            ${data.url ? `<a href="${data.url}" class="push-notification-action">View Details</a>` : ''}
        `;

        // Add styles if not already present
        if (!document.getElementById('push-notification-styles')) {
            const style = document.createElement('style');
            style.id = 'push-notification-styles';
            style.textContent = `
                .push-notification-toast {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    max-width: 400px;
                    background: white;
                    border-radius: 8px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                    padding: 16px;
                    z-index: 999999;
                    animation: slideInRight 0.3s ease-out;
                }
                .push-notification-header {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    margin-bottom: 8px;
                }
                .push-notification-icon {
                    width: 24px;
                    height: 24px;
                    border-radius: 4px;
                }
                .push-notification-close {
                    margin-left: auto;
                    background: none;
                    border: none;
                    font-size: 24px;
                    cursor: pointer;
                    color: #666;
                }
                .push-notification-body {
                    color: #666;
                    margin-bottom: 12px;
                }
                .push-notification-action {
                    display: inline-block;
                    padding: 6px 12px;
                    background: #667eea;
                    color: white;
                    text-decoration: none;
                    border-radius: 4px;
                    font-size: 14px;
                }
                @keyframes slideInRight {
                    from { transform: translateX(400px); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
            `;
            document.head.appendChild(style);
        }

        document.body.appendChild(toast);

        // Auto remove after 5 seconds
        setTimeout(() => {
            toast.style.animation = 'slideInRight 0.3s ease-out reverse';
            setTimeout(() => toast.remove(), 300);
        }, 5000);

        // Close button
        toast.querySelector('.push-notification-close').addEventListener('click', () => {
            toast.remove();
        });
    }

    updateUIBasedOnPermission() {
        const permission = Notification.permission;
        const subscribeBtn = document.getElementById('push-subscribe-btn');
        const unsubscribeBtn = document.getElementById('push-unsubscribe-btn');
        const statusText = document.getElementById('push-status-text');

        if (subscribeBtn) {
            subscribeBtn.style.display = permission === 'granted' ? 'none' : 'inline-block';
        }

        if (unsubscribeBtn) {
            unsubscribeBtn.style.display = permission === 'granted' ? 'inline-block' : 'none';
        }

        if (statusText) {
            if (permission === 'granted') {
                statusText.textContent = 'Push notifications enabled';
                statusText.className = 'text-success';
            } else if (permission === 'denied') {
                statusText.textContent = 'Push notifications blocked';
                statusText.className = 'text-danger';
            } else {
                statusText.textContent = 'Push notifications not enabled';
                statusText.className = 'text-muted';
            }
        }
    }

    showPermissionDeniedMessage() {
        alert('Notifications are blocked. Please enable them in your browser settings to receive updates about donations, auctions, and goals.');
    }

    getBrowserInfo() {
        const ua = navigator.userAgent;
        let browser = 'Unknown';
        
        if (ua.includes('Chrome')) browser = 'Chrome';
        else if (ua.includes('Firefox')) browser = 'Firefox';
        else if (ua.includes('Safari')) browser = 'Safari';
        else if (ua.includes('Edge')) browser = 'Edge';
        
        return browser;
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Public API
    async subscribe() {
        return await this.requestPermission();
    }

    async unsubscribeUser() {
        return await this.unsubscribe();
    }

    getStatus() {
        return {
            isSupported: this.isSupported,
            isSubscribed: this.isSubscribed,
            permission: Notification.permission,
            token: this.currentToken
        };
    }
}

// Initialize globally
window.pushNotificationManager = new PushNotificationManager();
