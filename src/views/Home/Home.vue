<script setup lang="ts">
import Map from '@/component/HomePage/Map.vue'
import Pie from '@/component/HomePage/Pie.vue'
import {onMounted, reactive, ref} from "vue";
import {getData} from "@/api/HomePageApi";
import {getSalePie} from "@/api/HomePageApi";
import {ElMessage} from "element-plus";
import {login} from "@/api/loginApi";
import {useLoginStore} from "@/stores/loginStore";
// 渲染图表
import 'echarts/lib/chart/pie'
import 'echarts/lib/component/title'
import 'echarts/lib/component/legend'


import {
  Document,
  Menu as IconMenu,
  Location,
  Setting, Expand, DArrowLeft,
} from '@element-plus/icons-vue'
import {useRouter} from "vue-router";

const data = reactive<IHomeData>({
  salePie: [],
  saleMap: []
})

const router = useRouter()

// 获取城市数据
onMounted(() => {
  console.log(getData().then(res => {
    console.log(res)
    // @ts-ignore
    data.saleMap = res.data.data.saleMap
	  // @ts-ignore
	  data.salePie = res.data.data.salePie
  }))
})

// 获取销量数据
onMounted(() => {
  getSalePie().then(res => {
    console.log(res)

  }).catch(err => {
    console.log(err)
  })
})

// 校验是否登陆
onMounted(() => {
  if (useLoginStore().get()) {
    ElMessage({
      message: `欢迎你，${localStorage.getItem('username')}`,
      type: 'success',
    })
	  isOpenLogin.value = true
  }
})


// 侧边导航栏打开与关闭数据
const isCollapse = ref(false)
// 已登录的下拉图标
const isOpenLogin = ref(false)



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
const Login = () =>{
	if (localStorage.getItem('token')==null){
		router.push({name: 'Login'})
	}else{

	}
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
					{{username!=null ? username : '登录'}}&nbsp;
					<el-icon v-show="isOpenLogin"><ArrowDown /></el-icon>
					<div class="backLogin" ref="back">退出登录</div>
				</div>
			</el-header>
			<el-container>
				<el-aside class="aside-box" width="auto">
					<el-menu
						default-active="1"
						class="el-menu-vertical-demo"
						:collapse="isCollapse"
						@open="handleOpen"
						@close="handleClose"
						background-color="#304156"
						text-color="#fff"
					>
						<el-menu-item index="1">
							<el-icon>
								<HomeFilled/>
							</el-icon>
							<template #title>首页</template>
						</el-menu-item>
						<el-sub-menu index="2">
							<template #title>
								<el-icon>
									<Postcard/>
								</el-icon>
								<span>权限</span>
							</template>
							<el-menu-item-group title="Group One">
								<el-menu-item index="1-1">item one</el-menu-item>
								<el-menu-item index="1-2">item two</el-menu-item>
							</el-menu-item-group>
						</el-sub-menu>
						<el-sub-menu index="3">
							<template #title>
								<el-icon>
									<Goods/>
								</el-icon>
								<span>商品</span>
							</template>
						</el-sub-menu>
						<el-sub-menu index="4">
							<template #title>
								<el-icon>
									<List/>
								</el-icon>
								<span>订单</span>
							</template>
						</el-sub-menu>
						<el-sub-menu index="5">
							<template #title>
								<el-icon>
									<TrendCharts/>
								</el-icon>
								<span>营销</span>
							</template>
						</el-sub-menu>
					</el-menu>
				</el-aside>
				<el-main class="main-box">
					<Pie :data="data.salePie"></Pie>
					<Map :data="data.saleMap"></Map>
				</el-main>
			</el-container>
		</el-container>
	</div>

</template>

<style lang="scss" scoped>
*{
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


	.left{
		display: flex;
		align-items: center;
	}

	.right{
		cursor: pointer;
		display: flex;
		align-items: center;

		.backLogin{
			position: absolute;
			display: none;
			top: 60px;
			background-color: #409eff;
			padding: 10px 3px;
			transition: top .3s;
		}
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
.main-box::-webkit-scrollbar{
	display: none;
}
.el-menu-vertical-demo:not(.el-menu--collapse) {
  width: 200px;
  min-height: 400px;
}
</style>