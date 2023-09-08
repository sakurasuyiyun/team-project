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
        {
          path: 'addCommodity',
          name: 'AddCommodity',
          component: () => import('@/views/Commodity/AddCommodity.vue')
        },
        {
          path: 'category',
          name: 'Category',
          component: () => import('@/views/Commodity/Category.vue')
        },
        {
          path: 'brand',
          name: 'Brand',
          component: () => import('@/views/Commodity/Brand.vue')
        },
        {
          path: 'attribute',
          name: 'Attribute',
          component: () => import('@/views/Commodity/Attribute.vue')
        },
        {
          path: 'listOfGoods',
          name: 'ListOfGoods',
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
          path: '/salelist',
          name: 'saleList',
          component: () => import('@/views/orderFrom/salesList.vue')
        },
        {
          path: 'recommend',
          name: 'Recommend',
          component: () => import('@/views/Marketing/Recommend.vue')
        },
        {
          path: 'recommendedgoods',
          name: 'Recommendedgoods',
          component: () => import('@/views/Marketing/Recommendedgoods.vue')
        },
        {
          path: 'advertisingList',
          name: 'AdvertisingList',
          component: () => import('@/views/Marketing/advertisingList.vue')
        },
        {
          path: '/orderset',
          name: 'orderSet',
          component: () => import('@/views/orderFrom/orderSet.vue')
        },
        {
          path: '/reasonforreturn',
          name: 'reasonForReturn',
          component: () => import('@/views/orderFrom/reasonForReturn.vue')
        }
      ],
    },
    {path: '/login', name: 'Login', component: () => import('@/views/Login/Login.vue')},
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
