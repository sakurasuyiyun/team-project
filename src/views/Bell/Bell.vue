<template>
  <div class="bell">
    <!-- 标题栏 -->
    <div style="position: relative;padding-bottom: 20px;">
      <span style="font-weight: bold;">首页</span>
      <span style="margin: 0 10px;">/</span>
      <span style="font-weight: bold;">营销</span>
      <span style="margin: 0 10px;">/</span>
      <span>秒杀活动列表</span>
      <div
        style="width: calc(100% + 40px);height: 1px;background-color: #f0f0f0;position: absolute;bottom: 0;left: -20px;">
      </div>
    </div>
    <!-- 搜索栏 -->
    <div style="margin-top: 25px;border: 1px solid #f0f0f0;padding: 20px;">
      <div style="display: flex;align-items: center;">
        <el-icon>
          <Search />
        </el-icon>
        <span>筛选搜索</span>
      </div>
      <div style="font-size: 14px;margin-top: 10px;margin-left: 40px;display: flex;">
        <div style="display: flex;align-items: center;">
          <span>活动名称：</span>
          <input type="text" placeholder="活动名称" style="height: 30px;padding-left: 10px;">
        </div>
        <div style="margin-left: 20px;">
          <el-button>重置</el-button>
          <el-button type="primary">查询搜索</el-button>
        </div>
      </div>
    </div>
    <!-- 列表功能栏 -->
    <div style="margin-top: 20px;border: 1px solid #f0f0f0;padding: 20px;display: flex;justify-content: space-between;">
      <div style="display: flex;align-items: center;">
        <el-icon>
          <Tickets />
        </el-icon>
        <span>数据列表</span>
      </div>
      <div style="font-size: 12px;display: flex;">
        <div style="padding: 5px 10px;border: 1px solid #ccc;border-radius: 5px;">秒杀时间段列表</div>
        <div style="padding: 5px 10px;border: 1px solid #ccc;margin-left: 20px;border-radius: 5px;">添加活动</div>
      </div>
    </div>
    <!-- 列表栏 -->
    <div style="margin-top: 20px;">
      <table style="text-align: center;font-size: 14px;border-collapse: collapse;width: 100%;">
        <thead>
          <tr style="color: #999;font-weight: bold;display: flex;">
            <td style="border: 1px solid #f0f0f0;width: 5%;padding:10px 0;"><input type="checkbox"></td>
            <td style="border: 1px solid #f0f0f0;width: 7%;padding:10px 0;">编号</td>
            <td style="border: 1px solid #f0f0f0;width: 33%;padding:10px 0;">活动标题</td>
            <td style="border: 1px solid #f0f0f0;width: 10%;padding:10px 0;">活动状态</td>
            <td style="border: 1px solid #f0f0f0;width: 10%;padding:10px 0;">开始时间</td>
            <td style="border: 1px solid #f0f0f0;width: 10%;padding:10px 0;">结束时间</td>
            <td style="border: 1px solid #f0f0f0;width: 15%;padding:10px 0;">上线/下线</td>
            <td style="border: 1px solid #f0f0f0;width: 15%;padding:10px 0;">操作</td>
          </tr>
        </thead>
        <tbody>
          <tr style="color: #999;font-weight: bold;display: flex;align-items: center;">
            <td style="border: 1px solid #f0f0f0;width: 5%;padding:10px 0;height: 37px;line-height: 37px;"><input
                type="checkbox"></td>
            <td style="border: 1px solid #f0f0f0;width: 7%;padding:10px 0;height: 37px;line-height: 37px;">102</td>
            <td style="border: 1px solid #f0f0f0;width: 33%;padding:10px 0;height: 37px;line-height: 37px;">中国医生</td>
            <td style="border: 1px solid #f0f0f0;width: 10%;padding:10px 0;height: 37px;line-height: 37px;">活动未开始</td>
            <td style="border: 1px solid #f0f0f0;width: 10%;padding:10px 0;">
              <div>2021-07-03</div>
              <div>12:00:00</div>
            </td>
            <td style="border: 1px solid #f0f0f0;width: 10%;padding:10px 0;">
              <div>2021-07-03</div>
              <div>12:00:00</div>
            </td>
            <td
              style="border: 1px solid #f0f0f0;width: 15%;padding:10px 0;height: 37px;line-height: 37px;display: flex;align-items: center;justify-content: center;">
              <el-switch v-model="isLive" />
            </td>
            <td
              style="border: 1px solid #f0f0f0;width: 15%;padding:10px 0;color: #409eff;height: 37px;line-height: 37px;">
              <span>设置商品</span>
              <span style="margin: 0 15px;">编辑</span>
              <span>删除</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- 分页栏 -->
    <div style="display: flex;justify-content: end;align-items: center;margin-top: 20px;">
      <div>共24条</div>
      <div style="margin-left: 20px;width: 100px;">
        <el-select v-model="pageSize">
          <el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value" />
        </el-select>
      </div>
      <div style="margin-left: 20px;"><el-pagination background layout="prev, pager, next" :total="50" /></div>
      <div style="margin-left: 30px;">
        <span>前往</span>
        <input style="height: 22px;padding-left: 20px;width: 30px;margin: 0 10px;" type="text" value="1">
        <span>页</span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { getFKList } from "@/api/BellPageApi";

const isLive = ref(false)
const options = [
  {
    value: 5,
    label: '5条/页',
  },
  {
    value: 6,
    label: '6条/页',
  },
  {
    value: 7,
    label: '7条/页',
  },
  {
    value: 8,
    label: '8条/页',
  },
  {
    value: 9,
    label: '9条/页',
  },
]
const pageSize = ref(options[0].value)

onMounted(() => {
  getFKList().then(res => {
    console.log(res)
  })
})
</script>

<style lang="scss" scoped>
.bell {}
</style>