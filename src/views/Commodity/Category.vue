<!-- <template>
    <div>
        <el-breadcrumb>
          <el-breadcrumb-item>首页</el-breadcrumb-item>
          <el-breadcrumb-item>商品</el-breadcrumb-item>
          <el-breadcrumb-item>商品分类</el-breadcrumb-item>
        </el-breadcrumb>
        <div class="list-box">
            <div class="title"><el-icon class="icon"><Tickets /></el-icon>数据列表</div>
            <div class="btn">添加</div>
        </div>
        <el-table :data="tableData" height="500" border  style="width: 100%">
          <el-table-column prop="_id" label="编号" />
          <el-table-column prop="category_name" label="分类名称" />
          <el-table-column prop="level" label="级别" />
          <el-table-column prop="count" label="商品数量" />
          <el-table-column prop="unit" label="数量单位" />
          <el-table-column prop="isTable" label="导航栏">
            <el-switch v-model="valueTab"/>
          </el-table-column>
          <el-table-column prop="isShow" label="是否显示" >
            <el-switch v-model="valueShow"/>
          </el-table-column>
          <el-table-column prop="sort" label="排序" />
          <el-table-column prop="setUp" label="设置" width="180">
            <el-button size="small">查看下级</el-button>
            <el-button size="small">移除商品</el-button>
          </el-table-column>
          <el-table-column prop="operate" label="操作" width="180">
            <template #default="scope">
             <el-button size="small" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
             <el-button
               size="small"
               type="danger"
               @click="handleDelete(scope.$index, scope.row)"
               >删除</el-button>
              </template>
          </el-table-column>
        </el-table>
        <div class="demo-pagination-block">
          <el-pagination
            v-model:current-page="currentPage4"
            v-model:page-size="pageSize4"
            :page-sizes="[10, 20, 30, 40]"
            :small="small"
            :disabled="disabled"
            :background="background"
            layout="total, sizes, prev, pager, next, jumper"
            :total="tableData.length"
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
// import { Timer } from '@element-plus/icons-vue'
import { ref,onMounted } from 'vue'
import {getCategory,getGoods} from "@/api/CommodityApi";

const valueShow = ref(true)
const centerDialogVisible = ref(false)
const valueTab = ref(true)
// 获取商品分类数据
onMounted(() => {
	getCategory().then(res => {
		console.log(res.data)
    tableData.value = [
      ...res.data
    ]
    console.log(tableData.value);
    
	})
})
interface User {
  _id: number
  category_name: string
  level: string
  count:number
  unit:string
  sort:number
}

let tableData = ref<Array<User>>([])
// const tableData = [
//   {
//     date: '01',
//     name: '服饰',
//     level: '一级',
//     number:10,
//     unit:'$',
//     sort:1,
//   },
// ]

const handleEdit = (index: number, row: User) => {
  console.log(index, row)
}
const handleDelete = (index: number, row: User) => {
  console.log(index, row)
  centerDialogVisible.value = true
}
const clickOk = () => {
  centerDialogVisible.value = false
}


//分页

const currentPage4 = ref(4)
const pageSize4 = ref(10)
const small = ref(false)
const background = ref(true)
const disabled = ref(false)

const handleSizeChange = (val: number) => {
  console.log(`${val} items per page`)
}
const handleCurrentChange = (val: number) => {
  console.log(`current page: ${val}`)
}
</script>

<style lang='scss' scoped>
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
        width: 50px;
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
</style> -->

<template>
  <div>
      <el-breadcrumb>
        <el-breadcrumb-item>首页</el-breadcrumb-item>
        <el-breadcrumb-item>商品</el-breadcrumb-item>
        <el-breadcrumb-item>商品分类</el-breadcrumb-item>
      </el-breadcrumb>
      <div class="list-box">
          <div class="title"><el-icon class="icon"><Tickets /></el-icon>数据列表</div>
          <div @click="dialogFormVisible = true" class="btn">添加</div>
      </div>
      <el-table :data="tableData" height="500" border  style="width: 100%">
        <el-table-column align="center" prop="_id" label="编号" />
        <el-table-column align="center" prop="category_name" label="分类名称" />
        <el-table-column align="center" prop="count" label="商品数量" />
        <el-table-column align="center" prop="operate" label="操作" width="180">
          <template #default="scope">
           <el-button
             size="small"
             type="danger"
             @click="handleDelete(scope.$index, scope.row)"
             >删除</el-button>
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

<el-dialog v-model="dialogFormVisible" title="">
    <el-form :model="form">
      <el-form-item label="分类名" :label-width="formLabelWidth">
        <el-input v-model="form.name" autocomplete="off" />
      </el-form-item>
      
    </el-form>
    <template #footer>
      <span class="dialog-footer">
        <el-button @click="dialogFormVisible = false">取消</el-button>
        <el-button type="primary" @click="submitForm">
          确定
        </el-button>
      </span>
    </template>
  </el-dialog>
  </div>
</template>

<script setup lang="ts">
// import { Timer } from '@element-plus/icons-vue'
import {get,post} from "@/utils/normalRequest";
import { ElMessage } from 'element-plus'
import { ref,onMounted,watch,reactive } from 'vue'
import {getCategory,addProductCategory,delProductCategory} from "@/api/CommodityApi";
import {useLoginStore} from '@/stores/loginStore'
import { el } from 'element-plus/es/locale';
// import { number } from 'echarts';
// const dialogTableVisible = ref(false)
const dialogFormVisible = ref(false)
const formLabelWidth = '140px'

const form = reactive({
  name: '',
})
const valueShow = ref(true)
const centerDialogVisible = ref(false)
const valueTab = ref(true)
const a = {
      categoryName: '',
      token: useLoginStore().get()
    }
interface User {
_id: number
category_name: string
count:number
hasChildren?: boolean
children?: User[]
}

let tableData = ref<Array<User>>([])
let tableData1 = ref<Array<User>>([])
// 获取商品分类数据
onMounted(() => {
getCategory().then(res => {
  console.log(res.data)
  tableData1.value = [
    ...res.data
  ]
})
})

//添加商品分类
const submitForm = async () => {
  if(form.name.trim()){
    console.log(form.name);
    dialogFormVisible.value = false;

    let name = form.name


    a.categoryName = name

    addProductCategory(a).then(res =>{

      console.log(res);
      getCategory().then(res => {
        tableData1.value = [...res.data]
        currentPage.value =1
      })
    })
    // const addProductCategory =  get('/api/addProductCategory',{token1,name})

  }else{
    ElMessage('请输入分类名')
  }
}



watch([tableData1], ([tableDataValue]) => {

tableData.value = tableData1.value.slice(0,pageSize.value)

});

//点击删除
let id =ref<string>()
const handleDelete = (index: number, row: User) => {
// console.log(index, row._id)
id.value = row._id.toString()

centerDialogVisible.value = true
}

const b = {
  token: useLoginStore().get(),
  id:''
}
//点击确定
const clickOk = () => {

  console.log(id.value);
  
  b.id = id.value

  delProductCategory(b).then(res=>{
    console.log(res);
    getCategory().then(res => {
        tableData1.value = [...res.data]
        currentPage.value =1
      })
  })
  centerDialogVisible.value = false
}
//分页

const currentPage = ref(1)
const pageSize = ref(10)
const small = ref(false)
const background = ref(true)
const disabled = ref(false)

//分页条数改变时
const handleSizeChange = (val: number) => {

pageSize.value = val

console.log(pageSize.value);

tableData.value = tableData1.value.slice(val*(currentPage.value-1),currentPage.value*val)

}
//页数改变时
const handleCurrentChange = (val: number) => {

currentPage.value =val

console.log(pageSize.value);

tableData.value = tableData1.value.slice(pageSize.value*(val-1),pageSize.value*val)

}
</script>

<style lang='scss' scoped>
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
      width: 50px;
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