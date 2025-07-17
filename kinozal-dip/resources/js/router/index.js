// добавил

import { createRouter, createWebHistory } from 'vue-router';
import IndexPage from '@/pages/client/Index.vue';
import HallPage from '@/pages/client/Hall.vue';
import PaymentPage from '@/pages/client/Payment.vue';
import TicketPage from '@/pages/client/Ticket.vue';
import AdminIndex from '@/pages/admin/Index.vue';
import AdminLogin from '@/pages/admin/Login.vue';

const routes = [
  { path: '/', component: IndexPage },
  { path: '/hall/:id', component: HallPage },
  { path: '/payment', component: PaymentPage },
  { path: '/ticket', component: TicketPage },
  // admin
  { path: '/admin', component: AdminIndex, meta: { requiresAuth: true } },
  { path: '/admin/login', component: AdminLogin },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router;