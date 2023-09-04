<template>
	<div id="main" ref="main" style="height: 600px"></div>
</template>

<script setup lang='ts'>
interface SaleMap {
	saleNum: number;
	areaName: string;
	hasTrade: number;
	__typename: string;
}

interface SalePie {
	first_cid: number;
	sale_today: string;
	sale_yesterday: string;
	sale_three_days: string;
	sale_seven_days: string;
	sale_fourteen_days: string;
	sale_thirty_days: string;
	sale_sixty_days: string;
	today_money: string;
	yesterday_money: string;
	three_days_money: string;
	seven_days_money: string;
	fourteen_days_money: string;
	thirty_days_money: string;
	sixty_days_money: string;
	name: string;
}

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
	data.saleMap = res.data.saleMap;
	// data.salePie = res.data.salePie;
})


// 地图的使用要复杂一些，需要一个 地图josn数据
// @ts-ignore
// import guagndong from '@/json/guangdong.json';
// @ts-ignore
import china from "@/data/China.json";
import * as echarts from "echarts";

const main = ref<HTMLElement | null>(null);
let myChart: echarts.ECharts;
onMounted(() => {
	myChart = echarts.init(main.value)
	// myChart?.setOption(option);
})
// 还要把地图数据进行注册
// echarts.registerMap('GD', guagndong);
echarts.registerMap("CHINA", china);

let option: echarts.EChartsOption = {
	title: {
		text: "全国销量分布图",
		sublink:
			"http://zh.wikipedia.org/wiki/%E9%A6%99%E6%B8%AF%E8%A1%8C%E6%94%BF%E5%8D%80%E5%8A%83#cite_note-12",
	},
	tooltip: {
		trigger: "item",
		formatter: "{b}<br/>{c}",
	},

	visualMap: {
		min: 0,
		max: 1000000,
		text: ["High", "Low"],
		realtime: false,
		calculable: true,
		inRange: {
			color: ["#fef0ee", "#fcaea5", "#fc6a47", "#e03c0e", "#b12d25"],
		},
	},
	series: [
		{
			name: "香港18区人口密度",
			type: "map",
			// 指定你要注册的哪个地图数据
			map: "CHINA",
			data: [],
			emphasis: {
				label: {
					show: false,
				},
			},
			// areaColor: "#09295b",
		},
	],
};

const props = defineProps<{
	data: SaleMap[]
}>();

onMounted(() => {
	console.log(props)
})

watch(
	() => props.data.length,
	() => {
		const data = props.data.map((item) => ({
			name: item.areaName,
			value: item.saleNum,
		}));
		// @ts-ignore
		option.series[0].data = data;
		myChart.setOption(option);
	}
);
</script>

<style></style>