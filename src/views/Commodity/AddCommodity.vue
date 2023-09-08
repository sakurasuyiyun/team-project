<template>
    <div>
        <el-breadcrumb>
          <el-breadcrumb-item>首页</el-breadcrumb-item>
          <el-breadcrumb-item>商品</el-breadcrumb-item>
          <el-breadcrumb-item>添加商品</el-breadcrumb-item>
        </el-breadcrumb>

        <div class="steps-box">
            <div class="steps-box1">
                <div class="title-box">填写商品相关信息</div>
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

                <img  v-if="show" class="imgs" :src="url" alt="" />
               </el-form-item>
               <el-form-item label="商品分类" prop="category">
                <el-select v-model="ruleForm.category"  placeholder="选择商品分类">
                   <el-option v-for="(item,index) in filterCat" :label="item.text" :value="item.value" />
                 </el-select>
               </el-form-item>
               <el-form-item label="商品名称" prop="name">
                 <el-input v-model="ruleForm.name" />
               </el-form-item>
               <el-form-item label="副标题" prop="subheading">
                 <el-input v-model="ruleForm.subheading" />
               </el-form-item>
               <el-form-item label="商品品牌" prop="brand">
                <!-- <el-input v-model="ruleForm.brand" /> -->
                <el-select v-model="ruleForm.brand"  placeholder="选择商品品牌">
                  <el-option v-for="(item,index) in shops" :label="item.text" :value="item.value" />
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
            </div>
            <el-dialog
  v-model="centerDialogVisible"
  title="Warning"
  width="30%"
  align-center
>
  <span>是否添加该商品</span>
  <template #footer>
    <span class="dialog-footer">
      <el-button @click="centerDialogVisible = false">取消</el-button>
      <el-button type="primary" @click="clickOk">确定</el-button>
    </span>
  </template>
</el-dialog>

        </div>
    </div>
</template>

<script setup lang="ts">
import { ref,reactive,onMounted,toRefs} from 'vue'
import { FormInstance, FormRules,ElMessage  } from 'element-plus'
import {getCategory,getShops,uploadFile,addProduct,delProduct} from "@/api/CommodityApi";
import {useLoginStore} from '@/stores/loginStore';


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
//获取所有品牌
let shops = ref([])
onMounted(() => {
	getShops().then(res => {
    let a= res.data.map(item => ({ text: item.shop_name, value: item.shop_name,id:item._id }))
    shops.value = a
		//  console.log(res.data)
    
	})
})
  

interface RuleForm {
  //商品分类
  //名称
  name?: string
  //分类
  category?: string
  //副标题
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

}
const formSize = ref('default')
const ruleFormRef = ref<FormInstance>()
let ruleForm = reactive<RuleForm>({
  name: '',
  category: '',
  subheading:'',
  brand:'',
  itemPrice:'0',
  inventory:'0',
  desc: '',
  // shopId:'1',
})

//规则
const rules = reactive<FormRules<RuleForm>>({
    //商品名称规则
  name: [
    { required: true, message: '请编辑商品名称', trigger: 'blur' },
    { min: 3, max: 10, message: '长度为3-10位', trigger: 'blur' },
  ],
    //商品副标题称规则
    subheading: [
    { required: true, message: '请编辑商品名称', trigger: 'blur' },
    { min: 3, max: 10, message: '长度为3-10位', trigger: 'blur' },
  ],
  //售价
  // itemPrice: [{validator:checkPrice,trigger: 'blur' }],
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
//添加商品
const b = {
  productName: '',
  productPrice:'',
  shopId:0,
  productCategory:'',
  token: useLoginStore().get(),
}
const submitForm = async (formEl: FormInstance | undefined) => {
  if (!formEl) return
  await formEl.validate((valid:boolean, fields:any) => {
    if (valid) {

      console.log(filterCat.value.filter(item=>item.value == ruleForm. category)[0].id);
      
      
      b.productName = ruleForm.name

      b.productPrice = ruleForm.itemPrice

      b.shopId = shops.value.filter(item=>item.value==ruleForm.brand)[0].id

      b.productCategory = filterCat.value.filter(item=>item.value == ruleForm. category)[0].id

      centerDialogVisible.value = true

      console.log(b);
      

    } else {
      console.log('error submit!', fields)
    }
  })
}
const centerDialogVisible = ref(false)
const clickOk = () => {
  if(isFile){
    

   addProduct(b).then(res=>{
    console.log(res);
    let type:any
    if(res.msg == '添加商品成功'){
      // 清空文件
  const fileInput = document.getElementById('fileInput') as HTMLInputElement;
  fileInput.value = '';
      show.value = false
      centerDialogVisible.value = false
let emptyForm = reactive<RuleForm>({
          name: '',
          category: '',
          subheading:'',
          brand:'',
          itemPrice:'0',
          inventory:'0',
          desc: '',
        })
   Object.assign(ruleForm, emptyForm);
      type = 'success'
    }else{
      type = 'error'
    }
    ElMessage({
    message: res.msg,
    type: type,
  })
  })
// console.log('121');
  }else{
    ElMessage('请选择图片')
  }


}

let url = ref<any>('')
let show = ref<any>(false)
let isFile = reactive<any>(false)
//添加图片
const file =(e: Event)=>{
  console.log(e);
  const formData = new FormData()

  formData.append('file', e.target.files[0])

   const reader = new FileReader();

  reader.onload = (event) => {

   url.value = event.target.result;
    
    show.value = true
    
  };
  // console.log(formData.get('file'));
  reader.readAsDataURL(e.target.files[0]);

  uploadFile(formData).then(res=>{
    console.log(res);
    isFile = true
  })
}



</script>

<style lang="scss" scoped>
.steps-box{
    position: relative;
    height: 600px;
    width: 600px;
    margin:20px auto;
    box-shadow: 0 0 5px rgba(170,170,170, .9);
    .steps-box1{
        width: 580px;
        left: 10px;
        top: 20px;
        position: absolute;
        .imgs{
          position: absolute;
          right: 80px;
          top: -20px;
          width: 100px;
          height: 100px;
        }
        ::v-deep .el-step__icon {
           height: 18px;
            width: 18px;
        }
        ::v-deep .el-step__title{
           font-size: 13px;
         }
         .title-box{
          margin: auto;
          width: 150px;
          text-align: center;
          // background-color: aqua;
          margin-bottom: 30px;
         }
    }
   
}

</style>
