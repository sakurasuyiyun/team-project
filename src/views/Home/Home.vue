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


// 侧边导航栏数据
interface leftobj{
	title:string,
	icon:any,
	index:string
}
const asideicon = reactive<Array<leftobj>>([
	{
		title:'权限',
		icon: 'Postcard',
		index:'2'
	},
	{
		title:'商品',
		icon:'Goods',
		index:'3'
	},
	{
		title:'订单',
		icon:'List',
		index:'4'
	},
	{
		title:'营销',
		icon:'TrendCharts',
		index:'5'
	}
])

// 侧边导航栏打开与关闭数据
const isCollapse = ref(false)
// 已登录的下拉图标
const isOpenLogin = ref(false)


// 校验是否登陆
onMounted(() => {
	if (useLoginStore().get()) {
		// ElMessage({
		// 	message: `欢迎你，${localStorage.getItem('username')}`,
		// 	type: 'success',
		// })
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
const backLogin = () =>{
	localStorage.removeItem('token')
	localStorage.removeItem('username')

	ElMessage({
		message:'退出登录',
		type:'success'
	})

	setTimeout(()=>{
		router.push({name:'Login'})
	},500)

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
              {{username}}
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
						<el-sub-menu :index="item.index" v-for="(item,index) in asideicon" :key="index">
							<template #title>
								<el-icon>
									<component :is="item.icon" />
								</el-icon>
								<span>{{ item.title }}</span>
							</template>
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
	background-color: #304156;
	overflow-y: auto;
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