// import '../css/admin/css/normalize.css';
// import '../css/admin/css/styles.css';
// import '../css/app.css';
// import '../css/client/css/normalize.css';
// import '../css/client/css/styles.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { initializeTheme } from './composables/useAppearance';
import axios from 'axios';

import router from './router'; // добавил

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// Общие настройки
axios.defaults.baseURL = import.meta.env.VITE_API_URL || 'https://127.0.0.1:8000';

axios.interceptors.request.use(config => {
  // Например, добавлять токен авторизации из хранилища
  const token = localStorage.getItem('token'); // или через Vuex/Pinia
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
}, error => {
  return Promise.reject(error);
});

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(router) // добавил для новых машрутов кинотеатра
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on page load...
initializeTheme();
