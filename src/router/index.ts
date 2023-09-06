import { createRouter, createWebHistory } from 'vue-router'
import Home from "@/views/Home/Home.vue"
import { ElMessage } from "element-plus";

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
            path: '/a',
            name: 'A',
            component: () => import('@/views/Author/author.vue')
          },
          {
          path:'addCommodity',
          
          name:'AddCommodity',
          component: () => import('@/views/Commodity/AddCommodity.vue')
      },
      {
          path:'category',
          name:'Category',
          component: () => import('@/views/Commodity/Category.vue')
      },
      {
          path:'brand',
          name:'Brand',
          component: () => import('@/views/Commodity/Brand.vue')
      },
      {
          path:'attribute',
          name:'Attribute',
          component: () => import('@/views/Commodity/Attribute.vue')
      },
      {
          path:'listOfGoods',
          name:'ListOfGoods',
          component: () => import('@/views/Commodity/ListOfGoods.vue')
      },
        {
          path: '/bell',
          name: 'Bell',
          component: () => import('@/views/Bell/Bell.vue')
        },
        {
          path: '/discount',
          name: 'Discount',
          component: () => import('@/views/Discount/Discount.vue')
        },
        {
          path: '/orderlist',
          name: 'OrderList',
          component: () => import('@/views/orderFrom/OrderList.vue')
        },
     
        {
          path: 'recommend',
          name: 'Recommend',
          component: () => import('@/views/Marketing/recommend.vue')
        },
        {
          path: 'recommendedgoods',
          name: 'Recommendedgoods',
          component: () => import('@/views/Marketing/recommendedgoods.vue')
        },
        {
          path: 'advertisingList',
          name: 'AdvertisingList',
          component: () => import('@/views/Marketing/advertisingList.vue')
        },
      ],
    },
    { path: '/login', name: 'Login', component: () => import('@/views/Login/Login.vue') },
  ]
})

// 路由守卫，如果没有登录不予通过
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

export default router
