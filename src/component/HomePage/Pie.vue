<template>
	<div>
		<el-radio-group v-model="key" size="large">
			<el-radio-button label="sale_">销量</el-radio-button>
			<el-radio-button label="_money">销售额</el-radio-button>
		</el-radio-group>
	</div>
	<div id="main" ref="pie" class="Pie" style="height: 400px"></div>
</template>

<script lang="ts" setup>
import {onMounted, ref, watch} from "vue";
import * as echarts from "echarts";
import 'echarts/lib/chart/pie'
import 'echarts/lib/component/title'
import 'echarts/lib/component/legend'

type EChartsOption = echarts.EChartsOption;
const props = defineProps<{ data: SalePie[] }>();
const pie = ref<HTMLDivElement | null>(null);
const key = ref("sale_");
// const main = ref<HTMLElement |null>(null)
let option: EChartsOption = {
	tooltip: {
		trigger: 'item'
	},
	legend: {
		top: '5%',
		right: '10%',
		orient: 'vertical',
		height: 100
	},
	series: [
		{
			right: "45%",
			type: 'pie',
			radius: ["30%", "50%"],
			avoidLabelOverlap: true,
			label: {
				show: true,
				position: 'outside'
			},
			emphasis: {
				label: {
					show: true,
					fontSize: 20,
					fontWeight: 'bold',
					formatter: "{b}\n{d}%"
				}
			},
			labelLine: {
				show: true, //控制线条显示
				length: 20,
				length2: 70,
			},
			data: [],
		}
	]

}


let myChart: echarts.ECharts;
onMounted(() => {
	console.log(myChart)
	if (pie.value) {
		myChart = echarts.init(pie.value)
		console.log('11111', myChart)
	}
})

// 监听数据变化
watch(
	() => props.data.length,
	() => {
		const data = props.data.map((item) => ({
			name: item.name,
			value: item.sale_fourteen_days,
		}))
		// @ts-ignore
		option.series[0].data = data
		myChart.setOption(option)
	}
);
const day = ref("seven_days")
watch([key, day], () => {
	const name = key.value == "sale_" ? key.value + day.value : day.value + key.value
	const data = props.data.map(item=>({
		name:item.name,
		// @ts-ignore
		value:item[name]
	}));
	(option.series as any[])[0].data = data as any
	myChart.setOption(option)
})


</script>

<style lang="scss" scoped>

</style>

