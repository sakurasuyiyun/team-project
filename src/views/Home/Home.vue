<script lang="ts" setup>
import {onMounted, reactive, ref} from "vue";
import {getData} from "@/api/HomePageApi";
import {getSalePie} from "@/api/HomePageApi";
import {ElMessage} from "element-plus";
import {login} from "@/api/loginApi";
import {useLoginStore} from "@/stores/loginStore";


import {useRouter} from "vue-router";
import {Goods} from "@element-plus/icons-vue";

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
				index: '2-1'
			},
			{
				title: '资源列表',
				icon: 'Suitcase',
				index: '2-2'
			},
			{
				title: '菜单列表',
				icon: 'Menu',
				index: '2-3'
			},
			{
				title: '角色列表',
				icon: 'List',
				index: '2-4'
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


// 校验是否登陆
onMounted(() => {
	if (useLoginStore().get()) {
		isOpenLogin.value = true
	}
})


const handleOpen = (key: string, keyPath: string[]) => {
	console.log(key, keyPath)
}
const handleClose = (key: string, keyPath: string[]) => {
	console.log(key, keyPath)
}

const username = localStorage.getItem('username');

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
          </span>
						<template #dropdown>
							<el-dropdown-menu @click="backLogin">
								<el-dropdown-item>退出登录</el-dropdown-item>
							</el-dropdown-menu>
						</template>
					</el-dropdown>
				</div>
			</el-header>
			<el-container>
				<el-aside class="aside-box" width="auto">
					<el-menu
						:collapse="isCollapse"
						background-color="#304156"
						class="el-menu-vertical-demo"
						default-active="1"
						text-color="#fff"
						@close="handleClose"
						@open="handleOpen"
					>
						<el-menu-item index="1">
							<el-icon>
								<HomeFilled/>
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
								<el-menu-item v-for="i in item.itemList" :index="i.index">
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

</template>

<style lang="scss" scoped>
* {
	user-select: none !important;
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