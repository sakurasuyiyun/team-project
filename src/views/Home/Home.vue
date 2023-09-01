<script setup lang="ts">
import Map from './compoents/Map.vue'
interface SaleMap {
	saleNum: number;
	areaName: string;
	hasTrade: number;
	__typename: string;
}

// interface SalePie {
// 	first_cid: number;
// 	sale_today: string;
// 	sale_yesterday: string;
// 	sale_three_days: string;
// 	sale_seven_days: string;
// 	sale_fourteen_days: string;
// 	sale_thirty_days: string;
// 	sale_sixty_days: string;
// 	today_money: string;
// 	yesterday_money: string;
// 	three_days_money: string;
// 	seven_days_money: string;
// 	fourteen_days_money: string;
// 	thirty_days_money: string;
// 	sixty_days_money: string;
// 	name: string;
// }

interface IHomeData {
	// salePie: SalePie[];
	saleMap: SaleMap[];
}

import {ref, onMounted, watch, reactive} from "vue";
import axios from "axios";

const data = reactive<IHomeData>({
	// salePie: [],
	saleMap: []
})

axios.get('http://kumanxuan1.f3322.net:8360/admin/stat').then(res => {
	data.saleMap = res.data.data.saleMap;
	// data.salePie = res.data.data.salePie;
})
</script>

<template>
  <div>船新项目</div>
	<Map :data="data.saleMap"></Map>
</template>

<style scoped>

</style>