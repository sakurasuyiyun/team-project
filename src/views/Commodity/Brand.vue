<template>
    <div>
        <el-breadcrumb>
          <el-breadcrumb-item>首页</el-breadcrumb-item>
          <el-breadcrumb-item>商品</el-breadcrumb-item>
          <el-breadcrumb-item>品牌管理</el-breadcrumb-item>
        </el-breadcrumb>
        <div class="list-box">
          <div class="title"><el-icon class="icon"><Tickets /></el-icon>数据列表</div>
          <div @click="dialogFormVisible = true" class="btn">添加</div>
      </div>
      <el-table  :data="tableData" height="500" border  style="width: 100%">
          <el-table-column align="center" prop="_id" label="编号" />
          <el-table-column align="center" prop="shop_name" label="类型名称" />

          <el-table-column align="center" prop="isShow" label="是否展示(0/1)" />

          <el-table-column align="center" prop="operate" label="操作" >

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
        <el-dialog v-model="dialogFormVisible" title="">
          <el-form
            ref="ruleFormRef"
            :model="ruleForm"
            :rules="rules"
            label-width="120px"
            class="demo-ruleForm"
            :size="formSize"
            status-icon
          >
            <el-form-item label="输入名字" prop="name">
              <el-input v-model="ruleForm.name" />
            </el-form-item>
            <el-form-item label="选择是否展示" prop="region">
              <el-select v-model="ruleForm.region" placeholder="1展示0不展示">
                <el-option label="0" value="0" />
                <el-option label="1" value="1" />
              </el-select>
            </el-form-item>
            <el-form-item>
              <el-button type="primary" @click="submitForm(ruleFormRef)">
                确定
              </el-button>
              <el-button @click="resetForm(ruleFormRef)">清空</el-button>
            </el-form-item>
          </el-form>
        </el-dialog>

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
import {getShops,addShop,delShop} from "@/api/CommodityApi";
import { ref,onMounted,watch,reactive } from 'vue';
import type { FormInstance, FormRules } from 'element-plus';
import {useLoginStore} from '@/stores/loginStore';


const centerDialogVisible = ref(false)
const dialogFormVisible = ref(false)

interface User {
  _id: string|number
  shop_name: string

  isShow:number

}
interface RuleForm {
  name: string
  region: string|number
}
const formSize = ref('default')
const ruleFormRef = ref<FormInstance>()
const ruleForm = reactive<RuleForm>({
  name: '',
  region: '',
})
const rules = reactive<FormRules<RuleForm>>({
  name: [
    { required: true, message: 'Please input Activity name', trigger: 'blur' },
    { min: 1, max: 10, message: '1-10位', trigger: 'blur' },
  ],
  region: [
    {
      required: true,
      message: '请选择是否显示',
      trigger: 'change',
    },
  ],
})
//添加
const b = {
  shopName: '',
  token: useLoginStore().get(),
  isShow:0
}
const submitForm = async (formEl: FormInstance | undefined) => {
  if (!formEl) return
  await formEl.validate((valid:any, fields:any) => {
    if (valid) {
      dialogFormVisible.value = false

      // console.log('submit!',ruleForm)

      console.log('shopName==>',ruleForm.name);
      console.log('isShow==>',Number(ruleForm.region));
      
      b.isShow = Number(ruleForm.region);

      b.shopName = ruleForm.name;

      addShop(b).then(res=>{
        console.log(res);

        getShops().then(res=>{
        tableData1.value = res.data;
        currentPage.value =1
      })
      
      })

      // ruleForm.name = ''
      // ruleForm.region = ''
    } else {
      console.log('error submit!', fields)
    }
  })
}
const resetForm = (formEl: FormInstance | undefined) => {
  if (!formEl) return
  formEl.resetFields()
}
let a = ref<string|number>('')
//删除
const handleDelete = (index: number, row: User) => {
  console.log(index, row)
  centerDialogVisible.value = true
  
 a.value = row._id
}

//点击确定
const c = {
  shopId: '',
  token: useLoginStore().get(),
}
const clickOk = () => {

c.shopId = a.value.toString()

//删除
 delShop(c).then(res=>{
        console.log(res); 

        getShops().then(res=>{
    tableData1.value = res.data
    currentPage.value =1
  })
      })
centerDialogVisible.value = false
}
let tableData = ref<Array<User>>([])
let tableData1 = ref<Array<User>>([])

  //获取品牌
onMounted(()=>{
  getShops().then(res=>{
    console.log(res);
    tableData1.value = res.data
    
  })
})
watch([tableData1], ([tableDataValue]) => {

tableData.value = tableData1.value.slice(0,pageSize.value)

});



const currentPage = ref(1)
const pageSize = ref(10)
const small = ref(false)
const background = ref(true)
const disabled = ref(false)
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