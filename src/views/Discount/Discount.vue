<template>
  <div class="discount">
    <!-- 标题栏 -->
    <div style="position: relative;padding-bottom: 20px;">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
        <el-breadcrumb-item>营销</el-breadcrumb-item>
        <el-breadcrumb-item>优惠券列表</el-breadcrumb-item>
      </el-breadcrumb>
      <div
        style="width: calc(100% + 40px);height: 1px;background-color: #f0f0f0;position: absolute;bottom: 0;left: -20px;">
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">

</script>

<style lang="scss" scoped>
.discount {}
</style>