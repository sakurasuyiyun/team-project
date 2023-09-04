<script lang="ts" setup>
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

const router = useRouter()


const data = reactive<IHomeData>({
	salePie: [],
	saleMap: []
})


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



</script>
<template>
	<div>123123</div>


</template>

<!--<style lang="scss" scoped>-->
<!--* {-->
<!--	user-select: none !important;-->
<!--}-->

<!--.common-layout {-->
<!--	padding: 0;-->
<!--	margin: 0;-->
<!--	//height: 100vh;-->
<!--	overflow-y: hidden;-->
<!--	position: relative;-->
<!--}-->

<!--.header {-->
<!--	background-color: #409eff;-->
<!--	color: #fff;-->
<!--	display: flex;-->
<!--	align-items: center;-->
<!--	justify-content: space-between;-->


<!--	.left {-->
<!--		display: flex;-->
<!--		align-items: center;-->
<!--	}-->

<!--	.right {-->
<!--		cursor: pointer;-->
<!--		display: flex;-->
<!--		align-items: center;-->

<!--	}-->

<!--	.btn {-->
<!--		background-color: transparent;-->
<!--		color: #fff;-->
<!--		font-size: 32px;-->
<!--		cursor: pointer;-->
<!--		margin-left: 10px;-->
<!--	}-->
<!--}-->

<!--.aside-box {-->
<!--	background-color: #304156;-->
<!--	overflow-y: auto;-->
<!--}-->

<!--.main-box {-->
<!--	height: calc(100vh - 60px);-->

<!--	overflow-y: auto;-->
<!--}-->

<!--.main-box::-webkit-scrollbar {-->
<!--	display: none;-->
<!--}-->

<!--.el-menu-vertical-demo:not(.el-menu&#45;&#45;collapse) {-->
<!--	width: 200px;-->
<!--	min-height: 400px;-->
<!--}-->
<!--</style>-->