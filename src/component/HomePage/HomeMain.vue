<script lang="ts" setup>
import Map from '@/component/HomePage/Map.vue'
import Pie from '@/component/HomePage/Pie.vue'
import {onMounted, reactive, ref} from "vue";
import {getData} from "@/api/HomePageApi";
import {getSalePie} from "@/api/HomePageApi";
import {ElMessage} from "element-plus";
import {useLoginStore} from "@/stores/loginStore";

const data = reactive<IHomeData>({
	salePie: [],
	saleMap: []
})

// 获取城市数据
onMounted(() => {
	getData().then(res => {
		console.log(res)
		// @ts-ignore
		data.saleMap = res.data.data.saleMap
		// @ts-ignore
		data.salePie = res.data.data.salePie
	})
})

onMounted(() => {
	getSalePie().then(res => {
		console.log(res)

	}).catch(err => {
		console.log(err)
	})
})


</script>

<template>
	<div class="box">
		<Pie :data="data.salePie"></Pie>
		<Map :data="data.saleMap"> </Map>
	</div>
</template>

<style lang="scss" scoped>

</style>