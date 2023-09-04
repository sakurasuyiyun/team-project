import {createRouter, createWebHistory} from 'vue-router'
import Home from "@/views/Home/Home.vue"
import {ElMessage} from "element-plus";

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
      ],
    },
    {path: '/login', name: 'Login', component: () => import('@/views/Login/Login.vue')},
  ]
})

// 路由守卫，如果没有登录不予通过
router.beforeEach((to,from,next)=>{
  const isLogin = localStorage.token
  if (isLogin != null || to.name == 'Login' || to.name == 'Home'){
    next();
  }else{
    ElMessage({
      message:'请先登录',
      type:'warning'
    })
    next({name:'Login'})
  }
})

export default router
