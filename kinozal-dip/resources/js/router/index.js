import { createRouter, createWebHistory } from 'vue-router';
import IndexPage from '@/pages/client/Index.vue';
import HallPage from '@/pages/client/Hall.vue';
import PaymentPage from '@/pages/client/Payment.vue';
import TicketPage from '@/pages/client/Ticket.vue';
import AdminIndex from '@/pages/admin/Index.vue';
import AdminLogin2 from '@/pages/auth/Login.vue';
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
  { path: '/admin', name: 'Admin', component: AdminLogin2, meta: { requiresAuth: true } },
  { path: '/dashboard', component: AdminIndex, meta: { requiresAuth: true } },
  { path: '/index', component: AdminIndex, meta: { requiresAuth: true } },
  { path: '/admin/login', name: 'Login',component: AdminLogin },
  {
    path: '/logout',
    name: 'Logout',
    beforeEnter: async (to, from, next) => {
      try {
        await fetch('/logout', { method: 'POST', credentials: 'include' });
        // Очистка локальных данных
        localStorage.removeItem('access_token');
        localStorage.removeItem('user');
        
        // Возврат на главную страницу
        next('/'); // или 
        // this.$router.push('/'); // Устарелый вариант, может не сработать
      } catch (e) {
        console.error(e);
        next('/');
      }
    }
  },
  {
    path: '/movies/:date',
    name: 'movies',
    component: IndexPage,
    props: true,
    beforeEnter: (to, from, next) => {
      const date = to.params.date;
      const dateRegex = /^\d{4}-\d{2}-\d{2}$/;
      const isValidDate = dateRegex.test(date) && !isNaN(new Date(date).getTime());

      if (!isValidDate) {
        const currentDate = new Date().toISOString().split('T')[0];
        next({
          name: 'movies', 
          params: { date: currentDate }
        });
      } else {
        next();
      }
    }
  }
]

const router = createRouter({
  routes,
  history: createWebHistory(), // обновлено, было "process.env.BASE_URL" с ()
})

export default router;