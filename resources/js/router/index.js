import Signup from '@/components/auth/Signup.vue';
import Login from '@/components/auth/Login.vue';
import Home from '@/components/Home.vue';

import { createRouter, createWebHistory } from 'vue-router';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
        path: "/",
        name: "home",
        meta: { title: 'TRI P2P Wallet' },
        component: Home,
    },
    {
        path: "/signup",
        name: "signup",
        meta: { title: 'Signup form' },
        component: Signup,
    },
    {
      path: "/login",
      name: "login",
      meta: { title: 'Login form' },
      component: Login,
    },
    {
        path: "/dashboard",
        name: "dashboard",
        meta: { title: 'Dashboard' },
        component: () => import('@/components/Dashboard.vue'),
        redirect: {name: "dashboard.statistics"},
        children: [
          {
            path: "statistics",
            name: "dashboard.statistics",
            meta: { title: 'Statistics' },
            component: () => import('@/components/Statistics.vue'),
          },
          {
            path: "transactions",
            name: "dashboard.transactions",
            meta: { title: 'Transactions' },
            component: () => import('@/components/Transactions.vue'),
          },
        ]
      }
  ]
})

export default router
