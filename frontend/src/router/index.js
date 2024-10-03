import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('../views/HomeView.vue')
    },
    {
      path: '/sign_in',
      name: 'sing_in',
      component: () => import('../views/Auth/SingIn.vue')
    },
    {
      path: '/sign_up',
      name: 'sign_up',
      component: () => import('../views/Auth/SingUp.vue')
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: () => import('../views/Auth/Dashboard.vue')
    },
  ]
})

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token')

  if (!token) {
    if (to.name === 'sing_in' || to.name === 'sign_up' || to.name === 'home') {
      return next()
    } else {
      return next({ name: 'sing_in' })
    }
  }

  if(to.name === 'sing_in' || to.name === 'sign_up' && token) {
    return next({ name: 'dashboard' })
  }

  next()

})

export default router
