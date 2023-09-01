import {createApp} from 'vue'
import {createPinia} from 'pinia'

// element 组件
import ElementPlus from 'element-plus'
import 'element-plus/dist/index.css'
import zhCn from 'element-plus/dist/locale/zh-cn.mjs'
import 'dayjs/locale/zh-cn'
// @ts-ignore
import App from '@/App.vue'
import router from './router'


const app = createApp(App)
app.use(ElementPlus, {
  locale: zhCn,
})
app.use(createPinia())
app.use(router)
app.use(ElementPlus)
app.mount('#app')



