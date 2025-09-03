// добавил

import { createRouter, createWebHistory } from 'vue-router';
import IndexPage from '@/pages/client/Index.vue';
import HallPage from '@/pages/client/Hall.vue';
import PaymentPage from '@/pages/client/Payment.vue';
import TicketPage from '@/pages/client/Ticket.vue';
import AdminIndex from '@/pages/admin/Index.vue';
import AdminLogin from '@/pages/admin/Login.vue';

const routes = [
  { path: '/', component: IndexPage, name: 'Index' },
  { path: '/hall/:id', component: HallPage },
  { path: '/hall', name: 'hall',component: HallPage },
  {
    path: '/hall/:hallId/session/:sessionId',
    name: 'Hall',
    component: HallPage,
    props: true,
  },
  { path: '/payment', name: 'payment', component: PaymentPage },
  { path: '/ticket', name: 'ticket', component: TicketPage },
  // admin
  { path: '/admin', name: 'Admin', component: AdminIndex, meta: { requiresAuth: true } },
  { path: '/dashboard', component: AdminIndex, meta: { requiresAuth: true } },
  { path: '/admin/login', name: 'Login',component: AdminLogin },
  {
    path: '/logout',
    name: 'Logout',
    component: { template: '<router-view/>' }, // незаметный компонент
    beforeEnter: async (to, from, next) => {
      try {
        await fetch('/api/logout', { method: 'POST', credentials: 'include' });
        // локально очистить данные пользователя, если нужно
        // например, сбросить store или state
        window.location.href = '/'; // или роутер push к нужной странице
      } catch (e) {
        console.error(e);
        window.location.href = '/'; // fallback
      }
    }
  },
]

const router = createRouter({
  routes,
  history: createWebHistory(), // обновлено, было "process.env.BASE_URL" с ()
})

export default router;