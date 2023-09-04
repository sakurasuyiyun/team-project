<template>
  <div id="main" ref="main" style="height: 600px"></div>
</template>

<script setup lang='ts'>
import {onMounted, ref, watch} from "vue";
// @ts-ignore
import china from "@/JSON/China2.json";
import * as echarts from "echarts";

const main = ref<HTMLElement | null>(null);
let myChart: echarts.ECharts;
onMounted(() => {
  myChart = echarts.init(main.value);
  // myChart?.setOption(option);
});

// 还要把地图数据进行注册
// echarts.registerMap('GD', guagndong);
// @ts-ignore
echarts.registerMap("CHINA", china);

// @ts-ignore
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
      // name: "香港18区人口密度",
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

// 接收父组件传递的数据
const props = defineProps<{
  data: SaleMap[]
}>();

// 监听数据变化
watch(
    () => props.data.length,
    () => {
      // @ts-ignore
      const data = props.data.map((item) => ({
        name: item.areaName,
        value: item.saleNum,
      }));
	    console.log(data)
	    // @ts-ignore
	    option.series[0].data = data;
      myChart.setOption(option);
    }
);
</script>

<style></style>