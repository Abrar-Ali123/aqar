import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true
});

// الاستماع لإشعارات المنتجات الجديدة
Echo.channel('products')
    .listen('.new-product', (e) => {
        // إظهار الإشعار
        showNotification(e.message, {
            body: e.message,
            icon: '/images/logo.png',
            badge: '/images/badge.png',
            data: {
                productId: e.product.id
            },
            actions: [
                {
                    action: 'view',
                    title: 'عرض المنتج'
                }
            ]
        });
    });

// دالة إظهار الإشعارات
function showNotification(title, options) {
    // التحقق من دعم الإشعارات
    if (!("Notification" in window)) {
        console.log("This browser does not support notifications");
        return;
    }

    // طلب إذن لإظهار الإشعارات
    if (Notification.permission === "granted") {
        createNotification(title, options);
    } else if (Notification.permission !== "denied") {
        Notification.requestPermission().then(permission => {
            if (permission === "granted") {
                createNotification(title, options);
            }
        });
    }
}

// دالة إنشاء الإشعار
function createNotification(title, options) {
    const notification = new Notification(title, options);

    notification.onclick = function(event) {
        event.preventDefault();
        if (event.action === 'view') {
            window.open(`/products/${event.notification.data.productId}`, '_blank');
        }
        notification.close();
    }
}

// تسجيل Service Worker للإشعارات في الخلفية
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js').then(registration => {
            console.log('ServiceWorker registration successful');
        }).catch(err => {
            console.log('ServiceWorker registration failed: ', err);
        });
    });
}
