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
          path: '/homemain',
          name: 'HomeMain',
          alias: '',
          component: () => import('@/component/HomePage/HomeMain.vue')
        },
        {
          path: '/test',
          name: 'Test',
          component: () => import('@/views/test/test.vue')
        },
        {
<<<<<<< HEAD
            path: '/a',
            name: 'A',
            component: () => import('@/views/Author/author.vue')
          },
        {
            path: '/bell',
            name: 'Bell',
            component: () => import('@/views/Bell/Bell.vue')
          },
=======
          path: '/a',
          name: 'A',
          component: () => import('@/views/Author/author.vue')
        },
        {
          path:'/orderlist',
          name:'OrderList',
          component: () => import('@/views/orderFrom/OrderList.vue')
        }
>>>>>>> 3c4321797faca0b011e756b83edbb26a69835fed
      ],
    },
    {path: '/login', name: 'Login', component: () => import('@/views/Login/Login.vue')},
  ]
})

// 路由守卫，如果没有登录不予通过
<<<<<<< HEAD
// router.beforeEach((to,from,next)=>{
//   const isLogin = localStorage.token
//   if (isLogin != null || to.name == 'Login' || to.name == 'Home'){
//     next();
//   }else{
//     ElMessage({
//       message:'请先登录',
//       type:'warning'
//     })
//     next({name:'Login'})
//   }
// })
=======
router.beforeEach((to, from, next) => {
  const isLogin = localStorage.token
  if (isLogin != null || to.name == 'Login' || to.name == 'Home') {
    next();
  } else {
    ElMessage({
      message: '请先登录',
      type: 'warning'
    })
    next({name: 'Login'})
  }
})
>>>>>>> 3c4321797faca0b011e756b83edbb26a69835fed

export default router
