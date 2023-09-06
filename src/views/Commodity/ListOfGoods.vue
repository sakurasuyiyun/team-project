<template>
    <div>
        <el-breadcrumb>
          <el-breadcrumb-item>首页</el-breadcrumb-item>
          <el-breadcrumb-item>商品</el-breadcrumb-item>
          <el-breadcrumb-item>商品列表</el-breadcrumb-item>
        </el-breadcrumb>
        <div class="list-box">
            <div class="title"><el-icon class="icon"><Tickets /></el-icon>数据列表</div>
            <div @click="clearFilter" class="btn">清空筛选条件</div>
        </div>
        <el-table ref="tableRef"  :data="tableData" height="500" border  style="width: 100%">
          <el-table-column align="center" prop="_id" label="编号" />
          <el-table-column align="center" label="图片">
            <template #default="scope">
              <el-image style="width: 100px; height: 100px" :src="scope.row.product_img" fit="contain" />
            </template>
          </el-table-column>
          <el-table-column align="center" prop="product_name" label="商品名称" />

          <el-table-column align="center" :filters="filterCat"
            :filter-method="filterTaps"
            filter-placement="bottom-end" prop="shop_name" label="类型" >
            <template #default="scope">
              <el-tag
                :type="scope.row.tag === 'Home' ? '' : 'success'"
                disable-transitions
                >{{ scope.row.category_name }}</el-tag
              >
            </template>
          </el-table-column>

          <el-table-column sortable align="center" prop="product_price" label="价格(元)" />

          <el-table-column align="center" :filters="filterShop"
            :filter-method="filterShops"
            filter-placement="bottom-end" prop="shop_name" label="店铺" >
            <template #default="scope">
              <el-tag
                :type="scope.row.tag === 'Home' ? '' : 'success'"
                disable-transitions
                >{{ scope.row.shop_name }}</el-tag
              >
            </template>
          </el-table-column>
          <el-table-column align="center" label="操作" >
            <template #default="scope">
        <el-button
          size="small"
          type="danger"
          @click="handleDelete(scope.$index, scope.row)"
          >删除</el-button
        >
      </template>
          </el-table-column>
        </el-table>
        <div class="demo-pagination-block">
          <el-pagination
            v-model:current-page="currentPage"
            v-model:page-size="pageSize"
            :page-sizes="[10, 20, 30, 40]"
            :small="small"
            :disabled="disabled"
            :background="background"
            layout="total, sizes, prev, pager, next, jumper"
            :total="tableData1.length"
            @size-change="handleSizeChange"
            @current-change="handleCurrentChange"
          />
        </div>
        <el-dialog
         v-model="centerDialogVisible"
         title="Warning"
         width="30%"
         align-center
       >
         <span>请确认是否取消</span>
         <template #footer>
           <span class="dialog-footer">
             <el-button @click="centerDialogVisible = false">取消</el-button>
             <el-button type="primary" @click="clickOk">
               确定
             </el-button>
           </span>
         </template>
      </el-dialog>
    </div>
</template>

<script setup lang="ts">
import { ref,onMounted,watch } from 'vue'
import {getGoods,getShops,getCategory,delProduct} from "@/api/CommodityApi";
import type { TableInstance } from 'element-plus'
import {useLoginStore} from '@/stores/loginStore';
const small = ref(false)
const background = ref(false)
const disabled = ref(false)
const centerDialogVisible = ref(false)
const currentPage = ref(1)
const pageSize = ref(10)

const filterShops = (value: string, row: User) => {
  return row.shop_name === value
}
const filterTaps = (value: string, row: User) => {
  return row.category_name=== value
}

// 获取店铺
let filterShop = ref([])
onMounted(() => {
	getShops().then(res => {
    let a = res.data
    
    let b = a.map(item => ({ text: item.shop_name, value: item.shop_name }))

    filterShop.value = b;

    // console.log(b);
    
	})
})

// 获取商品分类数据
let filterCat = ref([])
onMounted(() => {
	getCategory().then(res => {
		console.log(res.data)
    let c= res.data.map(item => ({ text: item.category_name, value: item.category_name }))

    filterCat.value  = c
    console.log(filterCat.value);
    
	})
})
interface User {
  _id: string
  product_name: string
  product_img:string
  category_name:string
  category_price:string
  shop_name:string
}

let tableData = ref<Array<User>>([])
let tableData1 = ref<Array<User>>([])

  // 获取商品数据
onMounted(() => {
	getGoods().then(res => {
		console.log(res.data)
    tableData1.value = res.data
	})
})

watch([tableData1], ([tableDataValue]) => {

  tableData.value = tableData1.value.slice(0,pageSize.value)

});

//编辑
//   const handleEdit = (index: number, row: User) => {
//   console.log(index, row)
// }
//删除
const handleDelete = (index: number, row: User) => {
  console.log(index, row)
  centerDialogVisible.value = true
}
const b = {
  productId: '',
  token: useLoginStore().get(),
}
//点击确定
const clickOk = () => {
  let token = JSON.parse(localStorage.getItem('token')as string)
  // delProduct(token,)
  centerDialogVisible.value = false
}
//清空筛选条件
const tableRef = ref<TableInstance>()
const clearFilter = () => {
  tableRef.value!.clearFilter()
}

//分页条数改变时
const handleSizeChange = (val: number) => {

  pageSize.value = val

  tableData.value = tableData1.value.slice(val*(currentPage.value-1),currentPage.value*val)
  
}
//页数改变时
const handleCurrentChange = (val: number) => {

  currentPage.value =val

  tableData.value = tableData1.value.slice(pageSize.value*(val-1),pageSize.value*val)

}
</script>

<style lang="scss" scoped>
.list-box{
  margin-bottom: 10px;
    margin-top: 10px;
    height: 50px;
    box-shadow: 0 0 5px rgba(170,170,170, .9);
    display: flex;
    line-height: 50px;
    justify-content: space-between;
    .title{
        margin-left: 10px;
        display: flex;
        .icon{
            display: block;
            margin-top: 18px;
            margin-right: 5px;
        }
    }
    .btn{
        height: 30px;
        margin-right: 10px;
        line-height: 30px;
        width: 100px;
        text-align: center;
        margin-top: 10px;
        box-shadow: 0 0 2px rgba(170,170,170, .9);
    }
}
.demo-pagination-block{
    margin-top: 10px;
    display: flex;
    justify-content: flex-end;
}
</style>