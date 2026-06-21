import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp, router } from '@inertiajs/vue3'; // <-- Added router here
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const appName = 'Sadman Cart';

// Track page views on every Inertia navigation transition
router.on('navigate', (event) => {
    if (typeof window.fbq === 'function') {
        window.fbq('track', 'PageView');
    }
});

createInertiaApp({
    title: (title) => title ? `${title} - ${appName}` : appName,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue);
            
        app.mixin({
            methods: {
                $t(key) {
                    return this.$page.props.translations?.[key] || key;
                }
            }
        });

        app.mount(el);
    },
    progress: {
        color: '#4f46e5',
        showSpinner: true,
    },
});