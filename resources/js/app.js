import './bootstrap';
import './post-editor';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('toastManager', () => ({
    toasts: [],

    show(detail = {}) {
        const id = Date.now() + Math.random();

        this.toasts.push({
            id,
            title: detail.title ?? '알림',
            message: detail.message ?? '',
            visible: true,
        });

        window.setTimeout(() => {
            const target = this.toasts.find((toast) => toast.id === id);

            if (target) {
                target.visible = false;
            }
        }, 3000);

        window.setTimeout(() => {
            this.toasts = this.toasts.filter((toast) => toast.id !== id);
        }, 3400);
    },
}));

Alpine.data('homePresence', (config = {}) => ({
    total: config.total ?? 0,
    users: config.users ?? 0,
    guests: config.guests ?? 0,

    init() {
        window.addEventListener('presence-updated', (event) => {
            this.total = event.detail?.total ?? 0;
            this.users = event.detail?.users ?? 0;
            this.guests = event.detail?.guests ?? 0;
        });
    },
}));

const startPresenceHeartbeat = () => {
    if (! window.Presence?.heartbeatUrl) {
        return;
    }

    const ping = async () => {
        try {
            const response = await window.fetch(window.Presence.heartbeatUrl, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                },
                credentials: 'same-origin',
            });

            if (! response.ok) {
                return;
            }

            const data = await response.json();

            window.dispatchEvent(new CustomEvent('presence-updated', {
                detail: data,
            }));
        } catch (error) {
            console.error('접속자 수 갱신 실패:', error);
        }
    };

    ping();
    window.setInterval(ping, 30000);
};

if (window.Laravel?.userId) {
    window.Echo.private(`App.Models.User.${window.Laravel.userId}`)
        .notification((notification) => {
            window.dispatchEvent(new CustomEvent('toast', {
                detail: {
                    title: notification.title,
                    message: notification.message,
                },
            }));    
        });
}

Alpine.start();
startPresenceHeartbeat();
