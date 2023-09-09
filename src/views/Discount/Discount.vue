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
          <input type="text" placeholder="活动名称" class="search-name-input" v-model="searchText">
        </div>
        <div class="search-type">
          <span>优惠券类型：</span>
          <el-select v-model="Type">
            <el-option v-for="(item1, index1) in TypeOptions" :key="index1 + '1'" :label="item1.label"
              :value="item1.value" />
          </el-select>
        </div>
        <div class="search-control">
          <el-button @click="reset">重置</el-button>
          <el-button type="primary" @click="search">查询搜索</el-button>
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
        <div class="list-addItem" @click="isShowAddBox">
          添加优惠券</div>
      </div>
    </div>
    <!-- 列表栏 -->
    <div class="list-box">
      <table class="list-table">
        <thead>
          <tr class="list-thead">
            <!-- <td style="width: 5%;" class="list-thead-item"><input type="checkbox"></td> -->
            <td style="width: 10%;" class="list-thead-item">编号</td>
            <td style="width: 13%;" class="list-thead-item">优惠券名称</td>
            <td style="width: 10%;" class="list-thead-item">优惠券类型</td>
            <td style="width: 8%;" class="list-thead-item">可使用商品</td>
            <td style="width: 8%;" class="list-thead-item">使用门槛</td>
            <td style="width: 7%;" class="list-thead-item">面值</td>
            <td style="width: 7%;" class="list-thead-item">适用平台</td>
            <td style="width: 12%;" class="list-thead-item">有效期</td>
            <td style="width: 10%;" class="list-thead-item">状态</td>
            <td style="width: 15%;" class="list-thead-item">操作</td>
          </tr>
        </thead>
        <tbody>
          <el-empty description="无" v-if="couponList.length === 0" />
          <tr class="list-tbody" v-for="(item2, index2) in couponList" :key="index2 + '2'" v-if="couponList.length > 0">
            <!-- <td style="width: 5%;" class="list-tbody-item"><input type="checkbox"></td> -->
            <td style="width: 10%;" class="list-tbody-item">{{ item2._id }}
            </td>
            <td style="width: 13%;" class="list-tbody-item">{{ item2.coupon_name }}</td>
            <td style="width: 10%;" class="list-tbody-item">{{ item2.coupon_type }}</td>
            <td style="width: 8%;" class="list-tbody-item">{{ item2.coupon_product }}</td>
            <td style="width: 8%;" class="list-tbody-item">{{ item2.coupon_sill }}</td>
            <td style="width: 7%;" class="list-tbody-item">{{ item2.coupon_price }}元</td>
            <td style="width: 7%;" class="list-tbody-item">{{ item2.coupon_scope }}</td>
            <td style="width: 12%;" class="list-tbody-item time">
              {{ formatTime(item2.coupon_start_time) }}至{{ formatTime(item2.coupon_end_time) }}
            </td>
            <td style="width: 10%;" class="list-tbody-item">
              {{ item2.coupon_status === 0 ? '未过期' : item2.coupon_status === 1 ? '已过期' : '未知状态' }}
            </td>
            <td style="width: 15%;" class="list-tbody-item control">
              <!-- <span>设置商品</span> -->
              <!-- <span class="editor">编辑</span> -->
              <span @click="deleteFKItemFunc(item2._id)">删除</span>
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
        <el-pagination background layout="prev, pager, next" :total="couponList.length" v-model:page-size="pageSize"
          :pager-count="pagerCount" v-model:current-page="currentPage" />
      </div>
      <div class="goPage">
        <span>前往</span>
        <input class="goPageInput" type="text" v-model="goPageValue" @keyup.enter="goPageFunc">
        <span>页</span>
      </div>
    </div>
    <!-- 遮罩层 -->
    <div class="mask" v-show="isAddFKItem" @click="isShowAddBox"></div>
    <!-- 添加优惠券固定定位盒子 -->
    <div class="addFKItem-box" v-show="isAddFKItem">
      <div class="addFKItem-title">
        <div>添加优惠券</div>
        <div @click="isShowAddBox">X</div>
      </div>
      <div>
        <div class="addFKItem-content">
          <div class="addFKItem-content-text">优惠券名称：</div>
          <input class="addFKItem-content-input" type="text" v-model="addFKItemObj.couponName">
        </div>
        <div class="addFKItem-content">
          <div class="addFKItem-content-text">优惠券类型：</div>
          <el-select v-model="addFKItemObj.couponType">
            <el-option v-for="(item4, index4) in FKStatusOptions" :key="index4 + '4'" :label="item4.label"
              :value="item4.value" />
          </el-select>
        </div>
        <div class="addFKItem-content">
          <div class="addFKItem-content-text">可使用商品：</div>
          <input class="addFKItem-content-input" type="text" v-model="addFKItemObj.couponProduct">
        </div>
        <div class="addFKItem-content">
          <div class="addFKItem-content-text">使用门槛：</div>
          <input class="addFKItem-content-input" type="text" v-model="addFKItemObj.couponSill">
        </div>
        <div class="addFKItem-content">
          <div class="addFKItem-content-text">面值：</div>
          <input class="addFKItem-content-input" type="text" v-model="addFKItemObj.couponPrice">
          <div>&nbsp;元</div>
        </div>
        <div class="addFKItem-content">
          <div class="addFKItem-content-text">适用平台：</div>
          <input class="addFKItem-content-input" type="text" v-model="addFKItemObj.couponScope">
        </div>
        <div class="addFKItem-content">
          <div class="addFKItem-content-text">开始时间：</div>
          <el-date-picker v-model="addFKItemObj.couponStart" type="datetime" placeholder="Select date and time" />
        </div>
        <div class="addFKItem-content">
          <div class="addFKItem-content-text">结束时间：</div>
          <el-date-picker v-model="addFKItemObj.couponEnd" type="datetime" placeholder="Select date and time" />
        </div>
        <div class="addFKItem-content">
          <div class="addFKItem-content-text">状态：</div>
          <el-select v-model="addFKItemObj.couponStatus">
            <el-option v-for="(item5, index5) in addStatusOptions" :key="index5 + '5'" :label="item5.label"
              :value="item5.value" />
          </el-select>
        </div>
        <div style="justify-content: end;" class="addFKItem-content">
          <div>
            <el-button @click="isShowAddBox">取消</el-button>
            <el-button type="primary" @click="addFKItemFunc">确认</el-button>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, onMounted } from 'vue'
import { getCouponList, addCouponList, deleteCouponList } from "@/api/DiscountApi";
import { ElMessage } from 'element-plus';
import { useLoginStore } from '@/stores/loginStore'

// 【从数据库获取数据并渲染到页面相关】
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

// （分页相关）
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
// 超过多少页折叠
const pagerCount = ref(5);
// 当前页数
const currentPage = ref(1)
// 优惠券列表偏移量
const offset = computed(() => {
  return pageSize.value * (currentPage.value - 1)
})
// 当前页所显示的数据
const couponListSelectPage = computed(() => {
  // console.log(currentPage.value, pageSize.value, offset.value);
  return couponList.value.filter((item, index) => {
    return index >= offset.value && index < pageSize.value * currentPage.value
  })
})

// 【模糊搜索相关】
// 搜索的文本
const searchText = ref('')
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
  },
  {
    value: 3,
    label: '平台赠券',
  },
  {
    value: 4,
    label: '注册赠券',
  }
]
// 默认优惠券类型
const Type = ref(TypeOptions[0].value)
// 优惠券列表(备份)
let couponListCopy = ref<Array<{
  _id: Number,
  coupon_name: String,
  coupon_type: String,
  coupon_product: String,
  coupon_sill: String,
  coupon_price: String,
  coupon_scope: String,
  coupon_start_time: String,
  coupon_end_time: String,
  coupon_status: Number,
}>>([])
// 进行模糊搜索
const search = () => {
  // if (searchText.value === '') {
  //   return;
  // }
  const regExp = new RegExp(searchText.value);
  couponList.value = couponListCopy.value.filter(item => {
    return item.coupon_name.match(regExp)?.length
  })
  if (TypeOptions[Type.value].label === '全部') {
    couponList.value = couponListCopy.value
  } else {
    couponList.value = couponListCopy.value.filter(item => {
      return item.coupon_type === TypeOptions[Type.value].label
    })
  }
  console.log(couponList.value);
}
// 重置搜索
const reset = () => {
  searchText.value = ''
  Type.value = TypeOptions[0].value
  couponList.value = couponListCopy.value
  console.log(couponList.value);
}

// 【添加优惠券相关】
// 是否显示遮罩层和添加优惠券盒子
let isAddFKItem = ref(false)
// 优惠券类型的可选项
const FKStatusOptions = [
  {
    value: 0,
    label: '全场赠券',
  },
  {
    value: 1,
    label: '购物赠券',
  },
  {
    value: 2,
    label: '平台赠券',
  },
  {
    value: 3,
    label: '注册赠券',
  }
]
// 是否过期
const addStatusOptions = [
  {
    value: 0,
    label: '未过期',
  },
  {
    value: 1,
    label: '已过期',
  },
]
// 添加优惠券的表单（isActive不是双向数据绑定）
// coupon_name: String,
// coupon_type: String,
// coupon_product: String,
// coupon_sill: String,
// coupon_price: String,
// coupon_scope: String,
// coupon_start_time: String,
// coupon_end_time: String,
// coupon_status: Number,
let addFKItemObj = reactive({
  token: useLoginStore().get(),
  couponName: '',
  couponType: FKStatusOptions[0].value || '',
  couponProduct: '',
  couponSill: '',
  couponPrice: '',
  couponScope: '',
  couponStart: '',
  couponEnd: '',
  couponStatus: addStatusOptions[0].value
})
// 显示或隐藏添加优惠券盒子并重置添加优惠券的表单数据
const isShowAddBox = () => {
  isAddFKItem.value = !isAddFKItem.value;
  addFKItemObj = reactive({
    token: useLoginStore().get(),
    couponName: '',
    couponType: FKStatusOptions[0].value,
    couponProduct: '',
    couponSill: '',
    couponPrice: '',
    couponScope: '',
    couponStart: '',
    couponEnd: '',
    couponStatus: addStatusOptions[0].value
  })
}
// 添加优惠券
const addFKItemFunc = () => {
  if (addFKItemObj.couponName === '' || addFKItemObj.couponProduct === '' || addFKItemObj.couponSill === '' || addFKItemObj.couponPrice === '' || addFKItemObj.couponScope === '' || addFKItemObj.couponStart === '' || addFKItemObj.couponEnd === '') {
    console.log('有选项为空！');
    ElMessage.error('有选项为空！');
    return
  }
  const obj = { ...addFKItemObj }
  const startTime = new Date(obj.couponStart).getTime() / 1000;
  const endTime = new Date(obj.couponEnd).getTime() / 1000;
  if (endTime <= startTime) {
    console.log('时间有误！');
    ElMessage.error('时间有误！');
    return
  }
  obj.couponStart = startTime + ''
  obj.couponEnd = endTime + ''
  obj.couponType = FKStatusOptions[addFKItemObj.couponType as number].label
  console.log(obj);
  addCouponList(obj).then((res) => {
    console.log(res)
    // 隐藏添加优惠券的盒子
    isAddFKItem.value = false
    // 重置添加优惠券的表单数据
    addFKItemObj = reactive({
      token: useLoginStore().get(),
      couponName: '',
      couponType: FKStatusOptions[0].value,
      couponProduct: '',
      couponSill: '',
      couponPrice: '',
      couponScope: '',
      couponStart: '',
      couponEnd: '',
      couponStatus: addStatusOptions[0].value
    })
    getCouponList().then((res) => {
      console.log(res.data)
      if (!res.data) {
        res.data = []
      }
      // 解构赋值给data里的变量couponList和couponListCopy
      couponList.value = [...res.data]
      couponListCopy.value = [...res.data]
    }).catch(err => {
      console.log(err);
    })
    ElMessage.success(res!.msg)
  }).catch(err => {
    console.log(err);
  })
}
// 删除优惠券
const deleteFKItemFunc = (_id: Number) => {
  deleteCouponList({
    token: useLoginStore().get(),
    id: _id
  }).then((res) => {
    console.log(res)
    getCouponList().then((res) => {
      console.log(res.data)
      if (!res.data) {
        res.data = []
      }
      // 解构赋值给data里的变量couponList和couponListCopy
      couponList.value = [...res.data]
      couponListCopy.value = [...res.data]
    }).catch(err => {
      console.log(err);
    })
    ElMessage.success(res!.msg)
  }).catch(err => {
    console.log(err);
  })
}

// 【去到某页相关】
// 切换页数时跟着改变，去到某页时反向修改切换的页数
const goPageValue = ref('1')
const goPageFunc = (e: any) => {
  if (Number(goPageValue.value) > Math.ceil(couponList.value.length / pageSize.value)) {
    goPageValue.value = Math.ceil(couponList.value.length / pageSize.value) + ''
  }
  if (Number(goPageValue.value) < 1) {
    goPageValue.value = '1'
  }
  currentPage.value = Number(goPageValue.value)
  e.target.blur()
}

// 钩子函数
onMounted(() => {
  getCouponList().then(res => {
    console.log(res.data);
    if (!res.data) {
      res.data = []
    }
    // 解构赋值给data里的变量couponList和couponListCopy
    couponList.value = [...res.data]
    couponListCopy.value = [...res.data]
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
        cursor: pointer;
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

            >span {
              cursor: pointer;
            }

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

  .mask {
    position: fixed;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    z-index: 1;
    background-color: rgba($color: #000, $alpha: .5);
  }

  .addFKItem-box {
    position: fixed;
    width: 80%;
    height: 580px;
    top: 50%;
    left: 50%;
    z-index: 2;
    background-color: #fff;
    transform: translate(-50%, -50%);
    box-sizing: border-box;
    padding: 20px 30px;


    .addFKItem-title {
      display: flex;
      justify-content: space-between;
      font-weight: bold;
      font-size: 18px;
    }

    .addFKItem-content {
      display: flex;
      margin-top: 15px;
      align-items: center;

      .addFKItem-content-text {
        width: 110px;
      }

      .addFKItem-content-input {
        height: 30px;
        line-height: 30px;
        padding: 0 5px;
        width: 206px;
      }
    }
  }
}
</style>