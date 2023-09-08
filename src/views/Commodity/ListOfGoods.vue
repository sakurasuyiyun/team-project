<template>
    <div>
        <el-breadcrumb>
          <el-breadcrumb-item>首页</el-breadcrumb-item>
          <el-breadcrumb-item>商品</el-breadcrumb-item>
          <el-breadcrumb-item>商品列表</el-breadcrumb-item>
        </el-breadcrumb>
        <div class="list-box">
            <div class="title"><el-icon class="icon"><Tickets /></el-icon>数据列表</div>
            <div class="mt-4">
              <el-input v-model="input1" placeholder="类型名/店铺名">
                <template #append>
                    <el-button @click="searchGoods(input1)" :icon="Search" />
                  
                </template>
              </el-input>
            </div>
            <div @click="clearFilter" class="btn">清空搜索</div>
        </div>
        <el-table ref="tableRef"   :data="tableData" height="500" border  style="width: 100%">
          <el-table-column align="center" prop="product_id" label="编号" />
          <el-table-column align="center" label="图片">
            <template #default="scope">
              <el-image style="width: 100px; height: 100px" :src="scope.row.product_img" fit="contain" />
            </template>
          </el-table-column>
          <el-table-column align="center" prop="product_name" label="商品名称" />

          <el-table-column align="center"  prop="category_name" label="类型" >
            <template #default="scope">
              <el-tag>{{ scope.row.category_name }}</el-tag>
            </template>
          </el-table-column>

          <el-table-column  align="center" prop="product_price" label="价格(元)" />

          <el-table-column align="center" prop="shop_name" label="店铺" >
            <template #default="scope">
              <el-tag>{{ scope.row.shop_name }}</el-tag>
            </template>
          </el-table-column>

          <el-table-column align="center" label="操作" >
            <template #default="scope">
              <el-button size="small" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
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
            :total="length"
            @size-change="handleSizeChange"
            @current-change="handleCurrentChange"
          />
        </div>
        <!-- 删除弹出框 -->
        <el-dialog
         v-model="centerDialogVisible"
         title="Warning"
         width="30%"
         align-center
       >
         <span>请确认是否删除</span>
         <template #footer>
           <span class="dialog-footer">
             <el-button @click="centerDialogVisible = false">取消</el-button>
             <el-button type="primary" @click="clickOk">
               确定
             </el-button>
           </span>
         </template>
      </el-dialog>

      <!-- 修改弹出框 -->
      <el-dialog v-model="dialogFormVisible" title="修改商品">
        <el-form
                 ref="ruleFormRef"
                 :model="ruleForm"
                 :rules="rules"
                 label-width="120px"
                 class="demo-ruleForm"
                 :size="formSize"
                 status-icon
               >
               <el-form-item label="选择图片" prop="">
                <input id="fileInput" type="file" @change="file"/>
                <img v-if="show" class="auto-img" :src="url" alt="">
               </el-form-item>
               <el-form-item label="商品分类" prop="category">
                <el-select v-model="ruleForm.category"  placeholder="选择商品分类">
                   <el-option v-for="(item,index) in filterCat" :key="index" :label="item.text" :value="item.value" />
                 </el-select>
               </el-form-item>
               <el-form-item label="商品名称" prop="name">
                 <el-input v-model="ruleForm.name" />
               </el-form-item>
               <el-form-item label="是否上架" prop="subheading">
                <el-select v-model="ruleForm.isShow"  placeholder="1上架0不上架">
                   <el-option label="0" value="0" />
                   <el-option label="1" value="1" />
                 </el-select>
               </el-form-item>
               <el-form-item label="商品品牌" prop="brand">
                <!-- <el-input v-model="ruleForm.brand" /> -->
                <el-select v-model="ruleForm.brand"  placeholder="选择商品品牌">
                  <el-option v-for="(item,index) in filterShop" :label="item.text" :value="item.value" />
                </el-select>
               </el-form-item>
               <el-form-item label="商品介绍" prop="desc">
                 <el-input v-model="ruleForm.desc" type="textarea" />
               </el-form-item>
               <el-form-item label="商品售价" prop="itemPrice">
                 <el-input v-model.number="ruleForm.itemPrice" />
               </el-form-item>

               <el-form-item label="库存" prop="inventory">
                 <el-input v-model="ruleForm.inventory" />
               </el-form-item>

               <el-form-item>
                 <el-button type="primary" @click="submitForm (ruleFormRef)">确定</el-button>
               </el-form-item>
            </el-form>
  </el-dialog>
    </div>
</template>

<script setup lang="ts">
import { ref,onMounted,watch,computed ,reactive} from 'vue'
import {getGoods,getShops,getCategory,delProduct,editProductImg,editProduct} from "@/api/CommodityApi";

import { Search } from '@element-plus/icons-vue'
import  { ElMessage,FormInstance, FormRules  } from 'element-plus'
import {useLoginStore} from '@/stores/loginStore';
import { ru } from 'element-plus/es/locale';

const small = ref(false)
const background = ref(false)
const disabled = ref(false)
const centerDialogVisible = ref(false)
const currentPage = ref(1)
const pageSize = ref(10)
const input1 = ref('')
const length = ref<any>('0')
const dialogFormVisible = ref(false)

interface RuleForm {
  //商品分类
  //名称
  name?: string
  //分类
  category?: string
  //副标题
  isShow:number
  subheading?:string
  //品牌
  brand?:string
  //售价
  itemPrice?:string
  //库存
  inventory?:string|number
  //介绍
  desc?: string

  //店铺id
  shopId?:string

  //商品id
  productId?:string

}
const formSize = ref('default')
const ruleFormRef = ref<FormInstance>()
let ruleForm = reactive<RuleForm>({
  name: '',
  category: '',
  subheading:'',
  brand:'',
  itemPrice:'',
  inventory:'',
  desc: '',
  productId:'',
  isShow:0,
})
//规则
const rules = reactive<FormRules<RuleForm>>({
    //商品名称规则
  // name: [
  //   { required: true, message: '请编辑商品名称', trigger: 'blur' },
  //   { min: 3, max: 10, message: '长度为3-10位', trigger: 'blur' },
  // ],

  //商品分类选择规则
  category: [
  {
      required: true,
      message: '请选择商品分类',
      trigger: 'change',
    }
  ],
  //商品品牌选择规则
  brand: [
  {
      required: true,
      message: '请选择商品品牌',
      trigger: 'change',
    }
  ],
})



//搜索商品

const searchGoods = (val:string)=>{
if(val.trim()){
  console.log('12');
  let reg = new RegExp(val)

  tableData.value = tableData1.value.filter(item=>reg.test(item.shop_name)==true || reg.test(item.category_name)==true)

  length.value = tableData.value.length
  // console.log();
  
}else{
  ElMessage('请输入搜素内容')
}

}
const goodsList = computed(()=>{
  
})
// 获取店铺
let filterShop = ref([])
onMounted(() => {
	getShops().then(res => {
    // console.log(res.data);
    
    let a = res.data
    
    let b = a.map(item => ({ text: item.shop_name, value: item.shop_name,id:item._id }))

    filterShop.value = b;

    // console.log(b);
    
	})
})

// 获取商品分类数据
let filterCat = ref([])
onMounted(() => {
	getCategory().then(res => {
		// console.log(res.data)
    let c= res.data.map(item => ({ text: item.category_name, value: item.category_name,id:item._id }))

    filterCat.value  = c
    // console.log(filterCat.value);
    
	})
})
interface User {
  product_id: string
  product_name: string
  product_img:string
  category_name:string
  product_price:any
  shop_name:string
  product_inventory:number
  isShow:number
}

let tableData = ref<Array<User>>([])
let tableData1 = ref<Array<User>>([])

  // 获取商品数据
onMounted(() => {
	getGoods().then(res => {
    tableData1.value = res.data.sort((a:any,b:any)=>a.product_id-b.product_id)
    length.value = tableData1.value.length
	})
})

watch([tableData1], ([tableDataValue]) => {

  // console.log(tableData1.value);
  
  // tableData.value = tableData1.value.slice(0,pageSize.value)
  tableData.value = tableData1.value.slice(pageSize.value*(currentPage.value-1),pageSize.value*currentPage.value)
  
});

let category = reactive<any>('')
let brand = reactive<any>('')
let show1 = reactive<any>('')
let price = reactive<any>('')
//编辑
  const handleEdit = (index: number, row: User) => {
  console.log(index, row)
  category = row.category_name
  brand = row.shop_name
  show1 = row.isShow
  price = row.product_price

  show.value = false
  ruleForm.name=row.product_name,
  ruleForm.category= row.category_name,
  ruleForm.subheading='',
  ruleForm.brand=row.shop_name,
  ruleForm.itemPrice=row.product_price,
  ruleForm.inventory=row.product_inventory,
  ruleForm.desc= '',
  ruleForm.productId=row.product_id,
  ruleForm.isShow=row.isShow,

  dialogFormVisible.value = true
}
//添加图片
let isAdd = reactive<any>(false)
let url = ref<any>('')
  let show = ref<any>(false)
let formData = new FormData()
const file =(e: Event)=>{
  console.log(e);
  
  formData.append('file', e.target.files[0])

  console.log(formData.get('file'));
  const reader = new FileReader();
  reader.onload = (event) => {

url.value = event.target.result;
 
 show.value = true
 
};
reader.readAsDataURL(e.target.files[0])
  editProductImg(formData,ruleForm.productId).then(res=>{
    isAdd = true
    console.log(res);
  })
}
//确定

const submitForm = async (formEl: FormInstance | undefined) => {
  if (!formEl) return
  await formEl.validate((valid:boolean, fields:any) => {
    if (valid) {
      // 清空文件
      
  const fileInput = document.getElementById('fileInput') as HTMLInputElement;
  fileInput.value = '';
      const a = {
        product_id:ruleForm.productId,
        product_name:ruleForm.name,
        product_inventory:ruleForm.inventory,
        product_price:ruleForm.itemPrice,
        product_audit_status:Number(ruleForm.isShow),
        shop_id:filterShop.value.filter(item=>item.value==ruleForm.brand)[0].id,
        product_category:filterCat.value.filter(item=>item.value == ruleForm.category)[0].id,
        token: useLoginStore().get(),
      }
      console.log(a);
      console.log(ruleForm);
      if( a.product_name == ruleForm.name && a.product_inventory == ruleForm.inventory && category == ruleForm.category && brand == ruleForm.brand && show1 == ruleForm.isShow && price == ruleForm.itemPrice && !isAdd
        ){
          ElMessage({
        message: '您没有没有修改任何数据',
        type: 'success',
          })
          dialogFormVisible.value = false
        return
        }

      editProduct(a).then(res=>{
        console.log(res);
         isAdd = false
        let type
        if(res.msg == '修改成功'){
          getGoods().then(res => {
    tableData1.value = res.data.sort((a:any,b:any)=>a.product_id-b.product_id)
    length.value = tableData1.value.length
	})
      type = 'success'
        }else{
          type = 'error'
        }
        ElMessage({
        message: res.msg,
        type: type,
          })
        })
        dialogFormVisible.value = false
    } else {
      console.log('error submit!', fields)
    }
  })
}

//删除
const handleDelete = (index: number, row: User) => {
  console.log(index, row)

  b.productId = row.product_id

  console.log(b);

  centerDialogVisible.value = true
}
const b = {
  productId: '',
  token: useLoginStore().get(),
}
//点击确定删除
const clickOk = () => {
  // delProduct(token,)
  centerDialogVisible.value = false

  delProduct(b).then(res=>{
    console.log(res);

    let type:any
    if(res.msg == '删除成功'){
       getGoods().then(res => {
		// console.log(res.data)
    
    tableData1.value = res.data.sort((a:any,b:any)=>a.product_id-b.product_id)

    length.value = tableData1.value.length
    // currentPage.value =1
	})
      type = 'success'
    }else{
      type = 'error'
    }
    ElMessage({
    message: res.msg,
    type: type,
  })
   
  })
}
//清空筛选条件
// const tableRef = ref<TableInstance>()
const clearFilter = () => {
  // tableRef.value!.clearFilter()
  input1.value = ''
  tableData.value = tableData1.value.slice(0,pageSize.value)

  length.value = tableData1.value.length
  // currentPage.value =1
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
.auto-img{
  position: absolute;
          right: 0px;
          top: -10px;
          width: 100px;
          height: 100px;
}
</style>