import {createRouter, createWebHistory} from 'vue-router'
import Home from "@/views/Home/Home.vue"

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {path: '/', name: 'Home', component: Home},
        {path: '/login', name: 'Login', component: () => import('@/views/Login/Login.vue')},
        {path: '/test',name: 'Test', component: () => import('@/views/test/test.vue')}
    ]
})

export default router
