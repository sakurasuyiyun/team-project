import {createApp} from 'vue'
import {createPinia} from 'pinia'

// element 组件
import ElementPlus from 'element-plus'
import 'element-plus/dist/index.css'
// @ts-ignore
import zhCn from 'element-plus/dist/locale/zh-cn.mjs'
import 'dayjs/locale/zh-cn'

import * as ElementPlusIconsVue from '@element-plus/icons-vue'




// @ts-ignore
import App from '@/App.vue'
import router from './router'


const app = createApp(App)


for (const [key, component] of Object.entries(ElementPlusIconsVue)) {
  app.component(key, component)
}

app.use(ElementPlus, {
  locale: zhCn,
})
app.use(createPinia())
app.use(router)
app.use(ElementPlus)
app.mount('#app')



