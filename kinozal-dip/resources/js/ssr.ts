import { createInertiaApp } from '@inertiajs/vue3';
import createServer from '@inertiajs/vue3/server';
import { renderToString } from '@vue/server-renderer';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createSSRApp, DefineComponent, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import axios from 'axios';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';


// Общие настройки
axios.defaults.baseURL = import.meta.env.VITE_API_URL || 'https://api.example.com';

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


createServer((page) =>
    createInertiaApp({
        page,
        render: renderToString,
        title: (title) => `${title} - ${appName}`,
        resolve: resolvePage,
        setup: ({ App, props, plugin }) =>
            createSSRApp({ render: () => h(App, props) })
                .use(plugin)
                .use(ZiggyVue, {
                    ...page.props.ziggy,
                    location: new URL(page.props.ziggy.location),
                }),
    }),
    { cluster: true },
);

function resolvePage(name: string) {
    const pages = import.meta.glob<DefineComponent>('./pages/**/*.vue');

    return resolvePageComponent<DefineComponent>(`./pages/${name}.vue`, pages);
}
