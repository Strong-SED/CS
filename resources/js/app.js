import './bootstrap';
import '../css/app.css';

import { ZiggyVue } from 'ziggy-js';
import { Ziggy } from './ziggy.js';


import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import AppLayout from '@/Layouts/AppLayout.vue';

createInertiaApp({
    resolve: (name) => {
        const page = resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue')
        );

        return page.then((module) => {
            if (module.default.layout === undefined) {
                module.default.layout = AppLayout;
            }
            return module;
        });
    },
    setup({ el, App, props, plugin }) {
        try {
            createApp({ render: () => h(App, props) })
                .use(plugin)
                .use(ZiggyVue, Ziggy)
                .mount(el);
        } catch (error) {
            console.error('Inertia setup error:', error);
        }
    },
});
