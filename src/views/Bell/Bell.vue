<template>
  <div class="bell">
    <!-- 标题栏 -->
    <div class="title-box">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
        <el-breadcrumb-item>营销</el-breadcrumb-item>
        <el-breadcrumb-item>秒杀活动列表</el-breadcrumb-item>
      </el-breadcrumb>
      <div class="title-line">
      </div>
    </div>
    <!-- 搜索栏 -->
    <div class="search-box">
      <div class="search-title">
        <el-icon>
          <Search/>
        </el-icon>
        <span>筛选搜索</span>
      </div>
      <div class="search-content">
        <div class="search-name">
          <span>活动名称：</span>
          <input type="text" placeholder="活动名称" class="search-name-input" v-model="searchText">
        </div>
        <div class="search-control">
          <el-button @click="reset">重置</el-button>
          <el-button type="primary" @click="search">搜索</el-button>
        </div>
      </div>
    </div>
    <!-- 列表功能栏 -->
    <div class="list-title-box">
      <div class="list-title">
        <el-icon>
          <Tickets/>
        </el-icon>
        <span>数据列表</span>
      </div>
      <div class="list-control">
        <!-- <div class="list-addItem">秒杀时间段列表</div> -->
        <div class="list-addItem" @click="isShowAddBox">
          添加活动
        </div>
      </div>
    </div>
    <!-- 列表栏 -->
    <div class="list-box">
      <table class="list-table">
        <thead>
        <tr class="list-thead">
          <!-- <td style="width: 5%;" class="list-thead-item"><input type="checkbox"></td> -->
          <td style="width: 12%;" class="list-thead-item">编号</td>
          <td style="width: 33%;" class="list-thead-item">活动标题</td>
          <td style="width: 10%;" class="list-thead-item">活动状态</td>
          <td style="width: 10%;" class="list-thead-item">开始时间</td>
          <td style="width: 10%;" class="list-thead-item">结束时间</td>
          <td style="width: 15%;" class="list-thead-item">上线/下线</td>
          <td style="width: 15%;" class="list-thead-item">操作</td>
        </tr>
        </thead>
        <tbody>
        <el-empty description="无" v-if="FKList.length === 0"/>
        <tr class="list-tbody" v-for="(item1, index1) in FKListSelectPage" :key="index1 + '1'" v-if="FKList.length > 0">
          <!-- <td style="width: 5%;" class="list-tbody-item"><input type="checkbox"></td> -->
          <td style="width: 12%;">{{ item1._id }}</td>
          <td style="width: 33%;" class="list-tbody-item">{{ item1.title }}</td>
          <td style="width: 10%;" class="list-tbody-item">
            {{ item1.active_state === 0 ? '活动未开始' : item1.active_state === 1 ? '活动已开始' : '未知状态' }}
          </td>
          <td style="width: 10%;" class="list-tbody-item time">
            <div>{{ formatTime(item1.active_start_time) }}</div>
          </td>
          <td style="width: 10%;" class="list-tbody-item time">
            <div>{{ formatTime(item1.active_end_time) }}</div>
          </td>
          <td style="width: 15%;" class="list-tbody-item status">
            <el-switch :model-value="item1.isActive === 0 ? false : true" @click="changeStatus(item1._id)"/>
          </td>
          <td style="width: 15%;" class="list-tbody-item control">
            <!-- <span>设置商品</span> -->
            <!-- <span class="editor">编辑</span> -->
            <span @click="deleteFKItemFunc(item1._id)">删除</span>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
    <!-- 分页栏 -->
    <div class="selectPage-box">
      <div>共{{ FKList.length }}条</div>
      <!-- 选择每页几条数据 -->
      <div class="selectPageSize">
        <el-select v-model="pageSize">
          <el-option v-for="(item2, index2) in options" :key="index2 + '2'" :label="item2.label" :value="item2.value"/>
        </el-select>
      </div>
      <!-- 切换页数 -->
      <div class="selectPage">
        <el-pagination background layout="prev, pager, next" :total="FKList.length" v-model:page-size="pageSize"
                       :pager-count="pagerCount" v-model:current-page="currentPage"/>
      </div>
      <!-- 去到某页 -->
      <div class="goPage">
        <span>前往</span>
        <input class="goPageInput" type="text" v-model="goPageValue" @keyup.enter="goPageFunc">
        <span>页</span>
      </div>
    </div>
    <!-- 遮罩层 -->
    <div class="mask" v-show="isAddFKItem" @click="isShowAddBox"></div>
    <!-- 添加秒杀活动固定定位盒子 -->
    <div class="addFKItem-box" v-show="isAddFKItem">
      <div class="addFKItem-title">
        <div>添加秒杀活动</div>
        <div @click="isShowAddBox">X</div>
      </div>
      <div>
        <div class="addFKItem-content">
          <div class="addFKItem-content-text">标题：</div>
          <input class="addFKItem-content-input" type="text" v-model="addFKItemObj.title">
        </div>
        <div class="addFKItem-content">
          <div class="addFKItem-content-text">活动状态：</div>
          <el-select v-model="addFKItemObj.activeState">
            <el-option v-for="(item3, index3) in FKStatusOptions" :key="index3 + '3'" :label="item3.label"
                       :value="item3.value"/>
          </el-select>
        </div>
        <div class="addFKItem-content">
          <div class="addFKItem-content-text">开始时间：</div>
          <el-date-picker v-model="addFKItemObj.activeStartTime" type="datetime" placeholder="Select date and time"/>
        </div>
        <div class="addFKItem-content">
          <div class="addFKItem-content-text">结束时间：</div>
          <el-date-picker v-model="addFKItemObj.activeEndTime" type="datetime" placeholder="Select date and time"/>
        </div>
        <div class="addFKItem-content">
          <div class="addFKItem-content-text">是否上线：</div>
          <el-switch v-model="addFKItemObjIsActive" @change="changeAddFKItemObjIsActive"/>
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
import {ref, reactive, computed, onMounted} from 'vue'
import {getFKList, addFKItem, deleteFKItem, editFKItem} from "@/api/BellApi";
import {ElMessage} from 'element-plus';
import {useLoginStore} from '@/stores/loginStore'

// 【从数据库获取数据并渲染到页面相关】
// 秒杀活动列表
let FKList = ref<Array<{
  active_start_time: String,
  active_end_time: String,
  active_state: Number,
  isActive: Number,
  title: String,
  _id: Number,
}>>([])
// Switch按钮接收真布尔值进行双向绑定，表示上线状态
const activeBoo = ref(true)
// Switch按钮接收假布尔值进行双向绑定，表示下线状态
const unactiveBoo = ref(false)
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

const changeData = reactive({
  token: useLoginStore().get(),
  id: ''
})

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
// 秒杀活动列表偏移量
const offset = computed(() => {
  return pageSize.value * (currentPage.value - 1)
})
// 当前页所显示的数据
const FKListSelectPage = computed(() => {
  // console.log(currentPage.value, pageSize.value, offset.value);
  return FKList.value.filter((item, index) => {
    return index >= offset.value && index < pageSize.value * currentPage.value
  })
})

// 【模糊搜索相关】
// 搜索的文本
const searchText = ref('')
// 秒杀活动列表(备份)
let FKListCopy = ref<Array<{
  active_start_time: String,
  active_end_time: String,
  active_state: Number,
  isActive: Number,
  title: String,
  _id: Number,
}>>([])
// 进行模糊搜索
const search = () => {
  if (searchText.value === '') {
    return;
  }
  const regExp = new RegExp(searchText.value);
  FKList.value = FKListCopy.value.filter(item => {
    return item.title.match(regExp)?.length
  })
  console.log(FKList.value);
}
// 重置搜索
const reset = () => {
  searchText.value = ''
  FKList.value = FKListCopy.value
  console.log(FKList.value);
}

// 【添加秒杀活动相关】
// 是否显示遮罩层和添加秒杀活动盒子
let isAddFKItem = ref(false)
// 活动状态的可选项
const FKStatusOptions = [
  {
    value: 0,
    label: '活动未开始',
  },
  {
    value: 1,
    label: '活动已开始',
  },
]
// 是否上线
let addFKItemObjIsActive = ref(false)
// 添加秒杀活动的表单（isActive不是双向数据绑定）
let addFKItemObj = reactive({
  token: useLoginStore().get(),
  title: '',
  activeState: FKStatusOptions[0].value,
  activeStartTime: '',
  activeEndTime: '',
  isActive: 0
})
// 显示或隐藏添加秒杀活动盒子并重置添加秒杀活动的表单数据
const isShowAddBox = () => {
  isAddFKItem.value = !isAddFKItem.value;
  addFKItemObj = reactive({
    token: useLoginStore().get(),
    title: '',
    activeState: FKStatusOptions[0].value,
    activeStartTime: '',
    activeEndTime: '',
    isActive: 0
  })
  addFKItemObjIsActive = ref(false)
}
// 改变添加秒杀活动的是否上线
const changeAddFKItemObjIsActive = () => {
  if (addFKItemObjIsActive.value) {
    addFKItemObj.isActive = 1
  } else {
    addFKItemObj.isActive = 0
  }
}
// 添加秒杀活动
const addFKItemFunc = () => {
  if (addFKItemObj.title === '' || addFKItemObj.activeStartTime === '' || addFKItemObj.activeEndTime === '') {
    console.log('有选项为空！');
    ElMessage.error('有选项为空！');
    return
  }
  const obj = {...addFKItemObj}
  const startTime = new Date(obj.activeStartTime).getTime() / 1000;
  const endTime = new Date(obj.activeEndTime).getTime() / 1000;
  if (endTime <= startTime) {
    console.log('时间有误！');
    ElMessage.error('时间有误！');
    return
  }
  obj.activeStartTime = startTime + ''
  obj.activeEndTime = endTime + ''
  console.log(obj);
  addFKItem(obj).then((res) => {
    console.log(res)
    ElMessage({
      message: '添加秒杀活动成功！',
      type: 'success',
    })
    // 隐藏添加秒杀活动的盒子
    isAddFKItem.value = false
    // 重置添加秒杀活动的表单数据
    addFKItemObj = reactive({
      token: useLoginStore().get(),
      title: '',
      activeState: FKStatusOptions[0].value,
      activeStartTime: '',
      activeEndTime: '',
      isActive: 0
    })
    addFKItemObjIsActive = ref(false)
    getFKList().then((res) => {
      console.log(res.data)
      if (!res.data) {
        res.data = []
      }
      // 赋值给data里的变量FKList
      FKList.value = res.data
      FKListCopy.value = res.data
    }).catch(err => {
      console.log(err);
    })
  }).catch(err => {
    console.log(err);
  })
}
// 删除秒杀活动
const deleteFKItemFunc = (_id: Number) => {
  deleteFKItem({
    token: useLoginStore().get(),
    id: _id
  }).then((res) => {
    console.log(res)
    ElMessage({
      message: '删除秒杀活动成功！',
      type: 'success',
    })
    getFKList().then((res) => {
      console.log(res.data)
      // 赋值给data里的变量FKList
      if (!res.data) {
        res.data = []
      }
      FKList.value = res.data
      FKListCopy.value = res.data
    }).catch(err => {
      console.log(err);
    })
  }).catch(err => {
    console.log(err);
  })
}

// 【去到某页相关】
// 切换页数时跟着改变，去到某页时反向修改切换的页数
const goPageValue = ref('1')
const goPageFunc = (e: any) => {
  if (Number(goPageValue.value) > Math.ceil(FKList.value.length / pageSize.value)) {
    goPageValue.value = Math.ceil(FKList.value.length / pageSize.value) + ''
  }
  if (Number(goPageValue.value) < 1) {
    goPageValue.value = '1'
  }
  currentPage.value = Number(goPageValue.value)
  e.target.blur()
}

// 钩子函数
onMounted(() => {
  getFKList().then((res) => {
    console.log(res.data)
    // 赋值给data里的变量FKList
    FKList.value = res.data
    // 当数据为空时，方便添加盒子的显示问题，目前使用固定top和固定高度解决
    // FKList.value = []
    FKListCopy.value = res.data
  }).catch(err => {
    console.log(err);
  })
})

const changeStatus = (id) => {
  changeData.id = id
  editFKItem(changeData).then((res) => {
    getFKList().then(res => {
      FKList.value = [...res.data]
      // 当数据为空时，方便添加盒子的显示问题，目前使用固定top和固定高度解决
      // FKList.value = []
      FKListCopy.value = [...res.data]
    })
    ElMessage.success(res!.msg)
  })
}

</script>

<style lang="scss" scoped>
.bell {

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

          &.status {
            display: flex;
            align-items: center;
            justify-content: center;
          }

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
    height: 380px;
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

      .addFKItem-content-text {
        width: 80px;
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