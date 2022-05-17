import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

import mainLayout from "~/layouts/main";
import loginPage from "~/pages/login.vue";

import adminLayout from "~/layouts/admin";
import adminIndexPage from "~/pages/admin/index.vue";
import adminBalancePage from "~/pages/admin/balance.vue";
import adminTokensPage from "~/pages/admin/tokens.vue";

const router = new VueRouter({
  mode: 'history',
  routes: [
    {
      path: '/login',
      component: mainLayout,
      children: [
        {
          path: '/',
          name: 'login',
          component: loginPage,
        },
      ]
    },
    {
      path: '/admin',
      component: adminLayout,
      children: [
        {
          path: '/',
          name: 'admin.index',
          component: adminIndexPage,
        },
        {
          path: 'balance',
          name: 'admin.balance',
          component: adminBalancePage,
        },
        {
          path: 'tokens',
          name: 'admin.tokens',
          component: adminTokensPage,
        },
      ]
    },
  ]
});

export default router;
