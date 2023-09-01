<script setup lang="ts">
import Map from '@/component/HomePage/Map.vue'
import {onMounted, reactive, ref} from "vue";
import {getData} from "@/api/HomePageApi";
import {ElMessage} from "element-plus";
import {login} from "@/api/loginApi";
import {useLoginStore} from "@/stores/loginStore";
import {
  Document,
  Menu as IconMenu,
  Location,
  Setting, Expand, DArrowLeft,
} from '@element-plus/icons-vue'

const data = reactive<IHomeData>({
  salePie: [],
  saleMap: []
})

onMounted(() => {
  console.log(getData().then(res => {
    console.log(res)
    // @ts-ignore
    data.salePie = res.data.data.salePie
    // @ts-ignore
    data.saleMap = res.data.data.saleMap
  }))
})

// 校验是否登陆
onMounted(() => {
  if (useLoginStore().get()) {
    ElMessage({
      message: '您看起来还没有登陆',
      type: 'error',
    })
  }
})

// 模拟登陆
onMounted(() => {
  login().then(res => {
    console.log(res)
    if (res.data?.errno === 0) {
      useLoginStore().set(res.data.token)
    }
  }).catch(err => {
    console.log(err)
  })
})

// data数据
const isCollapse = ref(false)

const handleOpen = (key: string, keyPath: string[]) => {
  console.log(key, keyPath)
}
const handleClose = (key: string, keyPath: string[]) => {
  console.log(key, keyPath)
}

const toggle = () => {
  isCollapse.value = !isCollapse.value
}
</script>
<template>
	<div class="common-layout">
		<el-container class="box">
			<el-header class="header">
				<div class="title">电商后台管理系统</div>
				<el-icon class="btn" @click="toggle">
					<Grid/>
				</el-icon>
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
}

.header {
  background-color: #409eff;
  color: #fff;
  display: flex;
  align-items: center;


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

.el-menu-vertical-demo:not(.el-menu--collapse) {
  width: 200px;
  min-height: 400px;
}
</style>