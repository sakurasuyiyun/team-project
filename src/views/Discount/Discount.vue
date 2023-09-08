<template>
  <div class="discount">
    <!-- 标题栏 -->
    <div class="title-box">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
        <el-breadcrumb-item>营销</el-breadcrumb-item>
        <el-breadcrumb-item>优惠券列表</el-breadcrumb-item>
      </el-breadcrumb>
      <div class="title-line">
      </div>
    </div>
    <!-- 搜索栏 -->
    <div class="search-box">
      <div class="search-title">
        <el-icon>
          <Search />
        </el-icon>
        <span>筛选搜索</span>
      </div>
      <div class="search-content">
        <div class="search-name">
          <span>优惠券名称：</span>
          <input type="text" placeholder="活动名称" class="search-name-input">
        </div>
        <div class="search-type">
          <span>优惠券类型：</span>
          <el-select v-model="Type">
            <el-option v-for="(item1, index1) in TypeOptions" :key="index1 + '1'" :label="item1.label"
              :value="item1.value" />
          </el-select>
        </div>
        <div class="search-control">
          <el-button>重置</el-button>
          <el-button type="primary">查询搜索</el-button>
        </div>
      </div>
    </div>
    <!-- 列表功能栏 -->
    <div class="list-title-box">
      <div class="list-title">
        <el-icon>
          <Tickets />
        </el-icon>
        <span>数据列表</span>
      </div>
      <div class="list-control">
        <div class="list-addItem" @click="addCouponItem">
          添加优惠券</div>
      </div>
    </div>
    <!-- 列表栏 -->
    <div class="list-box">
      <table class="list-table">
        <thead>
          <tr class="list-thead">
            <td style="width: 5%;" class="list-thead-item"><input type="checkbox"></td>
            <td style="width: 5%;" class="list-thead-item">编号</td>
            <td style="width: 10%;" class="list-thead-item">优惠券名称</td>
            <td style="width: 10%;" class="list-thead-item">优惠券类型</td>
            <td style="width: 10%;" class="list-thead-item">可使用商品</td>
            <td style="width: 10%;" class="list-thead-item">使用门槛</td>
            <td style="width: 5%;" class="list-thead-item">面值</td>
            <td style="width: 5%;" class="list-thead-item">适用平台</td>
            <td style="width: 15%;" class="list-thead-item">有效期</td>
            <td style="width: 10%;" class="list-thead-item">状态</td>
            <td style="width: 15%;" class="list-thead-item">操作</td>
          </tr>
        </thead>
        <tbody>
          <tr class="list-tbody" v-for="(item2, index2) in couponList" :key="index2 + '2'">
            <td style="width: 5%;" class="list-tbody-item"><input type="checkbox"></td>
            <td style="width: 5%;" class="list-tbody-item">{{ item2._id }}
            </td>
            <td style="width: 10%;" class="list-tbody-item">{{ item2.coupon_name }}</td>
            <td style="width: 10%;" class="list-tbody-item">{{ item2.coupon_type }}</td>
            <td style="width: 10%;" class="list-tbody-item">{{ item2.coupon_product }}</td>
            <td style="width: 10%;" class="list-tbody-item">{{ item2.coupon_sill }}</td>
            <td style="width: 5%;" class="list-tbody-item">{{ item2.coupon_price }}元</td>
            <td style="width: 5%;" class="list-tbody-item">{{ item2.coupon_scope }}</td>
            <td style="width: 15%;" class="list-tbody-item time">
              {{ formatTime(item2.coupon_start_time) }}至{{ formatTime(item2.coupon_end_time) }}
            </td>
            <td style="width: 10%;" class="list-tbody-item">
              {{ item2.coupon_status === 0 ? '未过期' : item2.coupon_status === 1 ? '已过期' : '未知状态' }}
            </td>
            <td style="width: 15%;" class="list-tbody-item control">
              <span>设置商品</span>
              <span class="editor">编辑</span>
              <span>删除</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- 分页栏 -->
    <div class="selectPage-box">
      <div>共{{ couponList.length }}条</div>
      <div class="selectPageSize">
        <el-select v-model="pageSize">
          <el-option v-for="(item3, index3) in options" :key="index3 + '3'" :label="item3.label" :value="item3.value" />
        </el-select>
      </div>
      <div class="selectPage">
        <el-pagination background layout="prev, pager, next" :total="couponList.length" :page-size="pageSize"
          :pager-count="pagerCount" />
      </div>
      <div class="goPage">
        <span>前往</span>
        <input class="goPageInput" type="text" value="1">
        <span>页</span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'
import { getCouponList } from "@/api/DiscountApi";

// 变量
// 每页显示的数据条数的可选项
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
// 每页数量
const pageSize = ref(options[0].value)
// 优惠券列表
let couponList = ref<Array<{
  coupon_end_time: String,
  coupon_name: String,
  coupon_price: String,
  coupon_product: String,
  coupon_scope: String,
  coupon_sill: String,
  coupon_start_time: String,
  coupon_status: Number,
  coupon_type: String,
  _id: Number
}>>([])
// 超过多少页折叠
const pagerCount = ref(5);
// 优惠券类型的可选项
const TypeOptions = [
  {
    value: 0,
    label: '全部',
  },
  {
    value: 1,
    label: '全场赠券',
  },
  {
    value: 2,
    label: '购物赠券',
  }
]
// 默认优惠券类型
const Type = ref(TypeOptions[0].value)


// 函数
// 处理数据,时间戳转xxxx-xx-xx xx-xx-xx
const formatTime = (data: any) => {
  const date = new Date(data * 1000); // 将时间戳转换为毫秒
  let year = date.getFullYear();
  let month: any = date.getMonth() + 1;
  month = month < 10 ? '0' + month : month
  let day: any = date.getDate();
  day = day < 10 ? '0' + day : day
  let hour: any = date.getHours();
  hour = hour < 10 ? '0' + hour : hour
  let minute: any = date.getMinutes();
  minute = minute < 10 ? '0' + minute : minute
  let second: any = date.getSeconds();
  second = second < 10 ? '0' + second : second
  return `${year}-${month}-${day} ${hour}:${minute}:${second}`
}
// 添加优惠券
const addCouponItem = () => {

}

// 钩子函数
onMounted(() => {
  getCouponList().then(res => {
    console.log(res.data);
    // 赋值给data里的变量 couponList
    couponList.value = res.data
  })
})
</script>

<style lang="scss" scoped>
.discount {
  .title-box {
    position: relative;
    padding-bottom: 20px;

    .title-line {
      width: calc(100% + 40px);
      height: 1px;
      background-color: #f0f0f0;
      position: absolute;
      bottom: 0;
      left: -20px;
    }
  }

  .search-box {
    margin-top: 25px;
    border: 1px solid #f0f0f0;
    padding: 20px;

    .search-title {
      display: flex;
      align-items: center;
    }

    .search-content {
      font-size: 14px;
      margin-top: 10px;
      margin-left: 40px;
      display: flex;

      .search-name {
        display: flex;
        align-items: center;

        .search-name-input {
          height: 30px;
          padding-left: 10px;
        }
      }

      .search-type {
        margin-left: 20px;
      }

      .search-control {
        margin-left: 20px;
      }
    }
  }

  .list-title-box {
    margin-top: 20px;
    border: 1px solid #f0f0f0;
    padding: 20px;
    display: flex;
    justify-content: space-between;

    .list-title {
      display: flex;
      align-items: center;
    }

    .list-control {
      font-size: 12px;
      display: flex;

      .list-addItem {
        padding: 5px 10px;
        border: 1px solid #ccc;
        margin-left: 20px;
        border-radius: 5px;
      }
    }

  }

  .list-box {
    margin-top: 20px;

    .list-table {
      text-align: center;
      font-size: 14px;
      border-collapse: collapse;
      width: 100%;

      .list-thead {
        color: #999;
        font-weight: bold;
        display: flex;

        .list-thead-item {
          border: 1px solid #f0f0f0;
          padding: 10px 0;
        }
      }

      .list-tbody {
        color: #999;
        font-weight: bold;
        display: flex;
        align-items: center;

        .list-tbody-item {
          border: 1px solid #f0f0f0;
          padding: 10px 0;
          height: 37px;
          line-height: 37px;

          &.time {
            line-height: 18.5px;
          }

          &.control {
            color: #409eff;

            .editor {
              margin: 0 15px;
            }
          }
        }

      }
    }
  }

  .selectPage-box {
    display: flex;
    justify-content: end;
    align-items: center;
    margin-top: 20px;

    .selectPageSize {
      margin-left: 20px;
      width: 100px;
    }

    .selectPage {
      margin-left: 20px;
    }

    .goPage {
      margin-left: 30px;

      .goPageInput {
        height: 22px;
        padding-left: 20px;
        width: 30px;
        margin: 0 10px;
      }
    }
  }
}
</style>