import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: import('../views/HomeView.vue')
    },
    {
      path: '/sign_in',
      name: 'sing_in',
      component: import('../views/Auth/SingIn.vue')
    },
    {
      path: '/sign_up',
      name: 'sign_up',
      component: import('../views/Auth/SingUp.vue')
    },
  ]
})

export default router
