<script lang="ts" setup>
import {onMounted, ref, watch} from "vue";
import {ElMessage} from "element-plus";
// 登录相关操作
// @ts-ignore
import {useLoginStore, useisShowStore} from "@/stores/loginStore";
import {useRouter} from "vue-router";
// 侧边导航栏数据
// @ts-ignore
import {asideIcon} from "@/data/asideData";
import {el} from "element-plus/es/locale";

const router = useRouter()

type itmeList = Array<object>

// 侧边导航栏数据
interface leftobj {
	title: string,
	icon: any,
	index: string,
	itemList: itmeList
}

const asideicon = reactive<Array<leftobj>>([
	{
		title: '权限',
		icon: 'Key',
		index: '2',
		itemList: [
			{
				title: '用户列表',
				icon: 'DocumentCopy',
				index: '2-1',
				path:'UserTable'
			},
			{
				title: '资源列表',
				icon: 'Suitcase',
				index: '2-2',
				path:'ResourceTable'
			},
			{
				title: '菜单列表',
				icon: 'Menu',
				index: '2-3',
				path:'MenuTable'
			},
			{
				title: '角色列表',
				icon: 'List',
				index: '2-4',
				path:'RoleTable'
			}
		]
	},
	{
		title: '商品',
		icon: 'Goods',
		index: '3',
		itemList: [
			{
				title: '添加商品',
				icon: 'CirclePlus',
				index: '3-1'
			},
			{
				title: '商品分类',
				icon: 'Menu',
				index: '3-2'
			},
			{
				title: '品牌管理',
				icon: 'PriceTag',
				index: '3-3'
			},
			{
				title: '属性管理',
				icon: 'Memo',
				index: '3-4'
			},
			{
				title: '商品列表',
				icon: 'List',
				index: '3-5'
			}
		]
	},
	{
		title: '订单',
		icon: 'List',
		index: '4',
		itemList: [

		]
	},
	{
		title: '营销',
		icon: 'TrendCharts',
		index: '5',
		itemList: [
			{
				title: '秒杀活动列表',
				icon: 'CirclePlus',
				index: '5-1'
			},
			{
				title: '优惠券列表',
				icon: 'Menu',
				index: '5-2'
			},
			{
				title: '品牌推荐',
				icon: 'PriceTag',
				index: '5-3'
			},
			{
				title: '新品推荐',
				icon: 'Memo',
				index: '5-4'
			},
			{
				title: '人气推荐',
				icon: 'List',
				index: '5-5'
			},
			{
				title: '专题推荐',
				icon: 'CirclePlus',
				index: '5-6'
			},
			{
				title: '广告列表',
				icon: 'Menu',
				index: '5-7'
			},
			{
				title: '秒杀时间段列表',
				icon: '',
				index: '5-8'
			},
			{
				title: '秒杀活动设置商品',
				icon: '',
				index: '5-9'
			},
			{
				title: '秒杀商品列表',
				icon: '',
				index: '5-10'
			},
			{
				title: '添加优惠券',
				icon: '',
				index: '5-11'
			}



		]
	}
])

// 侧边导航栏打开与关闭数据
const isCollapse = ref(false)
// 已登录的下拉图标
const isOpenLogin = ref(false)

const a = ref('1');

// 校验是否登陆
onMounted(() => {
  if (useLoginStore().get()) {
    isOpenLogin.value = true
  }
})
// 用户名
const username = localStorage.getItem('username');

// 侧边栏收起和打开
const toggle = () => {
  isCollapse.value = !isCollapse.value
}


// 跳转登录
const Login = () => {
  if (localStorage.getItem('token') == null) {
    router.push({name: 'Login'})
  } else {

  }
}

// 路由跳转
const routerJump = (link) => {
	router.push({name: link})
}

// 退出登录
const backLogin = () => {
  localStorage.removeItem('token')
  localStorage.removeItem('username')

  ElMessage({
    message: '退出登录',
    type: 'success'
  })

  setTimeout(() => {
    router.push({name: 'Login'})
  }, 500)

}

// 点击首页跳转
const goHome = () => {
  a.value = '1'
  useisShowStore().set("1")
  router.push({name: 'HomeMain'})
}

// 路由跳转
const routerJump = (link: string, isShow: string) => {
  router.push({name: link, query: {isShow}})
}

onMounted(() => {
  const isShow = useisShowStore().get();
  if (isShow) {
    a.value = isShow;
  } else {
    a.value = '1';
  }
})

// 监听当前路由
watch(
    () => router.currentRoute.value,
    (newValue: any) => {
      const isShow = newValue.query.isShow;
      if (isShow) {
        useisShowStore().set(isShow);
      } else {
        const check = useisShowStore().get();
        if (check) {
          a.value = check
        } else {
          a.value = '1'
        }
      }
    },
    {immediate: true}
)


</script>
<template>
  <div class="common-layout">
    <el-container class="box">
      <el-header class="header">
        <div class="left">
          <div class="title">电商后台管理系统</div>
          <el-icon class="btn" @click="toggle">
            <Grid/>
          </el-icon>
        </div>
        <div class="right" @click="Login">
          <div v-show="!isOpenLogin">登录</div>
          <el-dropdown v-show="isOpenLogin" class="backLogin">
						<span class="el-dropdown-link" style="color: #fff">
							{{ username }}
							<el-icon class="el-icon--right">
								<arrow-down/>
							</el-icon>
							<template #title>首页</template>
						</el-menu-item>
						<el-sub-menu v-for="(item,index) in asideicon" :key="index" :index="item.index">
							<template #title>
								<el-icon>
									<component :is="item.icon"/>
								</el-icon>
								<span>{{ item.title }}</span>
							</template>
							<el-menu-item-group>
								<el-menu-item v-for="i in item.itemList" :index="i.index" @click="routerJump(i.path)">
									<template #title>
										<el-icon>
											<component :is="i.icon"/>
										</el-icon>
										{{ i.title }}
									</template>

								</el-menu-item>
							</el-menu-item-group>
						</el-sub-menu>
					</el-menu>
				</el-aside>
				<el-main class="main-box">
					<RouterView></RouterView>
				</el-main>
			</el-container>
		</el-container>
	</div>

        </el-aside>
        <el-main class="main-box">
          <RouterView></RouterView>
        </el-main>
      </el-container>
    </el-container>
  </div>
</template>

<style lang="scss" scoped>
* {
  user-select: none;
}

.common-layout {
  padding: 0;
  margin: 0;
  //height: 100vh;
  overflow-y: hidden;
  position: relative;
}

.header {
  background-color: #409eff;
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: space-between;


  .left {
    display: flex;
    align-items: center;
  }

  .right {
    cursor: pointer;
    display: flex;
    align-items: center;

  }

  .btn {
    background-color: transparent;
    color: #fff;
    font-size: 32px;
    cursor: pointer;
    margin-left: 10px;
  }
}

.aside-box {
  height: calc(100vh - 60px);
  background-color: #304156;
  overflow-y: auto;
}

.aside-box::-webkit-scrollbar {
  display: none;
}

.main-box {
  height: calc(100vh - 60px);

  overflow-y: auto;
}

.main-box::-webkit-scrollbar {
  display: none;
}

.el-menu-vertical-demo:not(.el-menu--collapse) {
  width: 200px;
  min-height: 400px;
}
</style>