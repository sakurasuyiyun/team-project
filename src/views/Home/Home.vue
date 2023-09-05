<script lang="ts" setup>
import { onMounted, ref } from "vue";
import { ElMessage } from "element-plus";
// 登录相关操作
// @ts-ignore
import { useLoginStore } from "@/stores/loginStore";
import { useRouter } from "vue-router";
// 侧边导航栏数据
// @ts-ignore
import {asideIcon} from "@/data/asideData";

const router = useRouter()

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
// 用户名
const username = localStorage.getItem('username');

// 侧边栏收起和打开
const toggle = () => {
	isCollapse.value = !isCollapse.value
}


// 跳转登录
const Login = () => {
	if (localStorage.getItem('token') == null) {
		router.push({ name: 'Login' })
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
		router.push({ name: 'Login' })
	}, 500)

}

// 点击首页跳转
const goHome = () => {
	router.push({name:'HomeMain'})
}
</script>
<template>
	<div class="common-layout">
		<el-container class="box">
			<el-header class="header">
				<div class="left">
					<div class="title">电商后台管理系统</div>
					<el-icon class="btn" @click="toggle">
						<Grid />
					</el-icon>
				</div>
				<div class="right" @click="Login">
					<div v-show="!isOpenLogin">登录</div>
					<el-dropdown v-show="isOpenLogin" class="backLogin">
						<span class="el-dropdown-link" style="color: #fff">
							{{ username }}
							<el-icon class="el-icon--right">
								<arrow-down />
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
					<el-menu :collapse="isCollapse" background-color="#304156" class="el-menu-vertical-demo" default-active="1"
						text-color="#fff">
						<el-menu-item index="1" @click="goHome">
							<el-icon>
								<HomeFilled />
							</el-icon>
							<template #title>首页</template>
						</el-menu-item>
						<el-sub-menu v-for="(item, index) in asideIcon" :key="index" :index="item.index">
							<template #title>
								<el-icon>
									<component :is="item.icon" />
								</el-icon>
								<span>{{ item.title }}</span>
							</template>
							<el-menu-item-group>
								<el-menu-item v-for="(i, v) in item.itemList" :index="i.index" :key="v">
									<template #title>
										<el-icon>
											<component :is="i.icon" />
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