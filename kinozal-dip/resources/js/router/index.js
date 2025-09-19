import { createRouter, createWebHistory } from 'vue-router';
import IndexPage from '@/pages/client/Index.vue';
import HallPage from '@/pages/client/Hall.vue';
import PaymentPage from '@/pages/client/Payment.vue';
import TicketPage from '@/pages/client/Ticket.vue';
import AdminIndex from '@/pages/admin/Index.vue';
import AdminLogin2 from '@/pages/auth/Login.vue';
import AdminLogin from '@/pages/admin/Login.vue';

const routes = [
  { path: '/', component: IndexPage, name: 'Index', 
    props: true, 
    params: {
      date: String,
      validator: (value) => /^\d{4}-\d{2}-\d{2}$/.test(value),
    }, 
    meta: { requiresAuth: false },
  },
  // {
  //   path: '/index',
  //   name: 'home',
  //   component: IndexPage,
  //   props: true
  // },
  { path: '/hall/:id', component: HallPage },
  { path: '/hall', name: 'hallmain',component: HallPage },
  {
    path: '/hall/:hallId/session/:sessionId',
    name: 'Hall',
    component: HallPage,
    props: true,
  },
  { path: '/payment', name: 'payment', component: PaymentPage, props: true },
  { path: '/ticket', name: 'ticket', component: TicketPage, props: true },
  // admin
  { path: '/admin', name: 'Admin', component: AdminLogin2, meta: { requiresAuth: true } },
  
  // попытка сделать авторизацию через localStorage
  // { path: '/admin', name: 'Admin', component: AdminIndex, 
  //   beforeEnter: (to, from, next) => {
  //     if(localStorage.getItem('auth')) {
  //       next();
  //     } else {
  //       next({ name: 'AdminLogin2' });
  //     }
  //   },
  // },

  { path: '/dashboard', name: 'dashboard', component: AdminIndex, meta: { requiresAuth: true } },
  { path: '/admin/index', name: 'admin.index',component: AdminIndex, meta: { requiresAuth: true } },
  { path: '/admin/login', name: 'Login',component: AdminLogin, meta: { requiresAuth: true } }, // возможно не нужна meta: { requiresAuth: true }
  { path: '/login', component: AdminLogin2, name: 'AdminLogin2'},
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
    name: 'MoviesByDate',
    component: IndexPage,
    props: true,
    beforeEnter: (to, from, next) => {
      const date = to.params.date;
      const dateRegex = /^\d{4}-\d{2}-\d{2}$/;
      const isValidDate = dateRegex.test(date) && !isNaN(new Date(date).getTime());

      if (!isValidDate) {
        const currentDate = new Date().toISOString().split('T')[0];
        next({
          name: 'MoviesByDate', 
          params: { date: currentDate }
        });
      } else {
        next();
      }
    }
  }
]

const router = createRouter({
  history: createWebHistory(), // обновлено, было "process.env.BASE_URL" с ()
  routes,
})

export default router;