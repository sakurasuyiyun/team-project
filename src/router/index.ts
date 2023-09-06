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
            path: '/usertable',
            name: 'UserTable',
            component: () => import('@/views/Author/UserTable.vue')
          },
        {
            path: '/resourcetable',
            name: 'ResourceTable',
            component: () => import('@/views/Author/ResourceTable.vue')
          },
        {
            path: '/menutable',
            name: 'MenuTable',
            component: () => import('@/views/Author/MenuTable.vue')
          },
        {
            path: '/roletable',
            name: 'RoleTable',
            component: () => import('@/views/Author/RoleTable.vue')
          },
      ],
    },
    {path: '/login', name: 'Login', component: () => import('@/views/Login/Login.vue')},
    {path: '/test2', name: 'Test2', component: () => import('@/views/test/test2.vue')},
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
