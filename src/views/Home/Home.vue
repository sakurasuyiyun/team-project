<script setup lang="ts">
import Map from '@/component/HomePage/Map.vue'
import {onMounted, reactive} from "vue";
import {getData} from "@/api/HomePageApi";

const data = reactive<IHomeData>({
  salePie: [],
  saleMap: []
})

onMounted(() => {
  console.log(getData().then(res => {
    console.log(res)
    data.salePie = res.data.data.salePie
    data.saleMap = res.data.data.saleMap
  }))
})

import {
	Document,
	Menu as IconMenu,
	Location,
	Setting,
} from '@element-plus/icons-vue'

const isCollapse = ref(true)
const handleOpen = (key: string, keyPath: string[]) => {
	console.log(key, keyPath)
}
const handleClose = (key: string, keyPath: string[]) => {
	console.log(key, keyPath)
}
</script>

<template>
	<div class="common-layout">
		<el-container>
			<el-header class="header">
				<div class="title">后台管理系统</div>
				<el-radio-group v-model="isCollapse" style="margin-bottom: 20px">
					<el-radio-button :label="false">expand</el-radio-button>
					<el-radio-button :label="true">collapse</el-radio-button>
				</el-radio-group>
			</el-header>
			<el-container>
				<el-aside class="aside-box" width="auto">

					<el-menu
						default-active="2"
						class="el-menu-vertical-demo"
						:collapse="isCollapse"
						@open="handleOpen"
						@close="handleClose"
						background-color="#304156"
						text-color="#fff"
					>
						<el-sub-menu index="1">
							<template #title>
								<el-icon><location /></el-icon>
								<span>Navigator One</span>
							</template>
							<el-menu-item-group>
								<template #title><span>Group One</span></template>
								<el-menu-item index="1-1">item one</el-menu-item>
								<el-menu-item index="1-2">item two</el-menu-item>
							</el-menu-item-group>
							<el-menu-item-group title="Group Two">
								<el-menu-item index="1-3">item three</el-menu-item>
							</el-menu-item-group>
							<el-sub-menu index="1-4">
								<template #title><span>item four</span></template>
								<el-menu-item index="1-4-1">item one</el-menu-item>
							</el-sub-menu>
						</el-sub-menu>
						<el-menu-item index="2">
							<el-icon><icon-menu /></el-icon>
							<template #title>Navigator Two</template>
						</el-menu-item>
						<el-menu-item index="3" disabled>
							<el-icon><document /></el-icon>
							<template #title>Navigator Three</template>
						</el-menu-item>
						<el-menu-item index="4">
							<el-icon><setting /></el-icon>
							<template #title>Navigator Four</template>
						</el-menu-item>
					</el-menu>
				</el-aside>
				<el-main>

					<Map :data="data.saleMap"></Map>

				</el-main>
			</el-container>
		</el-container>
	</div>

</template>

<style lang="scss" scoped>
.header{
	background-color: #409eff;
	color: #fff;

}
.aside-box{
	background-color: #304156;
}
.el-menu-vertical-demo:not(.el-menu--collapse) {
	width: 300px;
	min-height: 400px;
}
</style>