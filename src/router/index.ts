import {createRouter, createWebHistory} from 'vue-router'
import Home from "@/views/Home/Home.vue"

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/', name: 'Home', component: Home,
      children: [
        {
          path: 'homemain',
          name: 'Home',
          alias: '',
          component: () => import('@/component/HomePage/HomeMain.vue')
        },
        {
          path: '/test',
          name: 'Test',
          component: () => import('@/views/test/test.vue')
        },
        {
            path: '/a',
            name: 'A',
            component: () => import('@/views/Author/author.vue')
          },
      ]
    },
    {path: '/login', name: 'Login', component: () => import('@/views/Login/Login.vue')},

  ]
})

export default router
