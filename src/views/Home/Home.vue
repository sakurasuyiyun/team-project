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
</script>

<template>
  <Map :data="data.saleMap"></Map>
</template>

<style scoped>

</style>