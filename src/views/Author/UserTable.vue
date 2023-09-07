<template>
  
  <div id="user">
    <div class="pageselect">
      <span>首页</span> /
      <span>权限</span> /
      用户列表
    </div>
    <div class="seacrcbigbox wrappper">
      <div class="searchtop">
        <div class="searchtitle">
          <el-icon style="vertical-align: middle">
            <Search/>
          </el-icon>
          <span>搜索</span>
        </div>
        <button class="reset" @click="searchreset">重置</button>
      </div>
      <div class="searchbottom">
        <div class="searchbox">
          <span>账号/姓名:</span>
          <input type="text" class="search" v-model="searchvalue">
        </div>
        <button class="btn-search" @click="search">搜索</button>
      </div>
    </div>
    <div class="addusertable wrappper">
      <button class="btn-add" @click="OpenMask">添加</button>
    </div>


    <div class="table" v-if="isShow">
      <el-table :data="UserData1.value" border style="width: 100%" size="large" height="450" v-loading="loading" element-loading-text="Loading..." >
        <el-table-column fixed prop="_id" label="编号" width="80"/>
        <el-table-column prop="username" label="账号"/>
        <el-table-column prop="user_nickname" label="姓名"/>
        <el-table-column prop="email" label="邮箱" />
        <el-table-column prop="create_at" label="添加时间" width="200"/>
        <el-table-column prop="last_login" label="最后登录" width="200"/>
        <el-table-column label="是否启用">
          <template #default="scope">
            <el-switch :value="scope.row.user_enable==1?true:false" @click="isupdate(scope.$index, scope.row)"/>
            <!-- <el-switch v-model="value2" v-else="user_enable==0" /> -->
            <!-- <span>{{UserData.value.data[0].user_enable}}</span> -->
          </template>
        </el-table-column>
        <el-table-column label="操作" >
          <template #default="scope">
            <el-button size="small" @click="OpenMask1(scope.$index, scope.row)" v-for="button in buttons"
                       :key="button.text"
                       :type="button.type"
                       text
            >分配角色
            </el-button
            >
            <!-- handleDelete(scope.$index, scope.row) -->
            <el-button size="small" v-for="button in buttons"
                       :key="button.text"
                       :type="button.type"
                       text
                       @click="OpenMask2(scope.$index, scope.row)">编辑
            </el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <!-- 遮罩 -->
    <div class="mask" v-if="isOpenMask==1">
      <div class="maskbox">
        <div class="masktop">
          <h1 class="masktitle">添加用户</h1>
          <div class="del" @click="CloseMask">×</div>
        </div>
        <!-- 表单主体 -->
        <el-form ref="ruleFormRef"
                 :model="ruleForm"
                 :rules="rules"
                 label-width="120px"
                 class="demo-ruleForm"
                 :size="formSize"
                 status-icon>
          <el-form-item label="账号:" prop="username">
            <el-input v-model="ruleForm.username" placeholder="请输入账号" style="width: 94%"/>
          </el-form-item>
          <el-form-item label="姓名:" prop="user_nickname">
            <el-input v-model="ruleForm.user_nickname" placeholder="请输入姓名" style="width: 94%"/>
          </el-form-item>
          <el-form-item label="邮箱:" prop="email">
            <el-input v-model="ruleForm.email" type="email" placeholder="请输入邮箱" style="width: 94%"/>
          </el-form-item>
          <el-form-item label="密码:" prop="password">
            <el-input v-model="ruleForm.password" type="password" placeholder="请输入密码" style="width: 94%"/>
          </el-form-item>

          <el-form-item label="是否启用:" prop="isEnable">
            <el-radio-group v-model="ruleForm.isEnable">
              <el-radio label="1" />
              <el-radio label="0" />
            </el-radio-group>
          </el-form-item>
        </el-form>
        <div class="maskbottom">
          <div class="cancel" @click="resetForm(ruleFormRef)">取消</div>
          <div class="create" @click="submitForm(ruleFormRef,ruleForm)">确定</div>
        </div>
      </div>
    </div>


    <div class="mask1 mask" v-if="isOpenMask1==1">
      <div class="maskbox">
        <div class="masktop">
          <h1 class="masktitle">分配角色</h1>
          <div class="del" @click="CloseMask1">×</div>
        </div>
        <!-- 表单主体 -->
        <!-- <template> -->

        <span class="spantitle">分配权限</span>
        <el-select v-model="value" class="m-2 table2" placeholder="请选择权限">
          <el-option
              v-for="item in options"
              :key="item.value"
              :label="item.label"
              :value="item.value"
          />
        </el-select>

        <!-- </template> -->
        <div class="maskbottom">
          <!-- <div class="cancel" @click="resetForm1(ruleFormRef)">取消</div> -->
          <!-- <div class="create" @click="onSubmit1()">确定</div> -->
          <el-button type="primary"  class="cancel"  @click="resetForm1(power)">取消</el-button>
          <el-button type="primary"  class="create"  @click="onSubmit1()">确定</el-button>
        </div>
      </div>
    </div>

    <div class="mask2 mask" v-if="isOpenMask2==1">
      <div class="maskbox">
        <div class="masktop">
          <h1 class="masktitle">编辑用户</h1>
          <div class="del" @click="CloseMask2">×</div>
        </div>
        <!-- 表单主体 -->
        <el-form ref="ruleFormRef"
                 :model="edit"
                 :rules="rules1"
                 label-width="120px"
                 class="demo-ruleForm"
                 :size="formSize"

                 status-icon>
          <el-form-item label="旧用户名:" prop="oldUserName">
            <el-input v-model="edit.oldUserName" placeholder="请输入账号" style="width: 94%" disabled/>
          </el-form-item>
          <el-form-item label="新用户名:" prop="userName">
            <el-input v-model="edit.userName" placeholder="请输入新账号，请输入3-16位字母、数字" style="width: 94%"/>
          </el-form-item>
          <el-form-item label="用户昵称:" prop="nickName">
            <el-input v-model="edit.nickName" placeholder="请输入新昵称，请输入3-16位字母、数字" style="width: 94%"/>
          </el-form-item>
          <el-form-item label="用户邮箱:" prop="email">
            <el-input v-model="edit.email" type="email" placeholder="请输入邮箱" style="width: 94%"/>
          </el-form-item>
          <el-form-item label="用户密码:" prop="password">
            <el-input v-model="edit.password" type="email" placeholder="如有需要请输入3-16位数字或字母组成的新密码,支持字母数字下划线组合,否则输入旧密码" style="width: 94%"/>
          </el-form-item>


          <el-form-item label="是否启用:" prop="isEnable">
            <el-radio-group v-model="a">
            <template #default="scope">
              <el-radio label="是"/>
              <el-radio label="否"/>

              </template>
            </el-radio-group>
          </el-form-item>
        </el-form>
        <div class="maskbottom">
          <!-- <div class="cancel" @click="resetForm2(ruleFormRef)">取消</div> -->
          <!-- <div class="create" @click="onSubmit2(edit)">确定</div> -->
           <el-button type="primary"  class="cancel"  @click="resetForm2(edit)">取消</el-button>
           <el-button type="primary"  class="create"  @click="onSubmit2(edit)">确定</el-button>
        </div>
      </div>
    </div>

    <!-- 尾页 -->
    <!-- <div class="demo-pagination-block">

       <el-pagination
          v-model:current-page="currentPage4"
          v-model:page-size="pageSize4"
          :page-sizes="[10, 20, 30, 40]"
          :small="small"
          :disabled="disabled"
          :background="background"
          layout="total, sizes, prev, pager, next, jumper"
          :total="DataCount"
          @size-change="handleSizeChange"
          @current-change="handleCurrentChange"
       />
     </div> -->

    <div class="demo-pagination-block">
      <!-- <div class="demonstration">All combined</div> -->
      <el-pagination
          v-model:current-page="currentPage4"
          v-model:page-size="pageSize4"
          :page-sizes="[5, 10, 20, 30]"
          :small="small"
          :disabled="disabled"
          :background="background"
          layout="total, sizes, prev, pager, next, jumper"
          :total="DataCount"
          @size-change="handleSizeChange"
          @current-change="handleCurrentChange"
      />
    </div>
  </div>
 

</template>

<script setup lang="ts">
import {toRaw} from 'vue'
import { ElMessage } from 'element-plus'
import { ElLoading } from 'element-plus'

let loading = ref("")

const active = reactive({})
const isupdate = (index, row) => {
  console.log("index,row");
  console.log(index, row);
  console.log("row.user_enable", row.user_enable);
  active.token = useLoginStore().get();
  active.username = row.username;
  if (row.user_enable == 1) {
    active.isEnable = 0;
  } else {
    active.isEnable = 1
  }
  isShow.value = false
  console.log("active", toRaw(active));
  activateUsersFn(toRaw(active))
  resetdraw()
loading.value = true
 console.log("loading",loading.value);
 setTimeout(() => {
    loading.value = false
 }, 2000);

}

const isShow = ref(false)


import {ref} from 'vue'

const value = ref('')
const a = ref('')
const power = {}

const options = [
  //  {
  //    value: '',
  //    label: '',
  //  },
  {
    value: '1',
    label: '超级管理员',
  },
  {
    value: '2',
    label: '权限管理员',
  },
  {
    value: '3',
    label: '商品管理员',
  },
  {
    value: '4',
    label: '订单管理员',
  },
  {
    value: '5',
    label: '营销管理员',
  },
  {
    value: '6',
    label: '普通用户',
  },
]

const edit = reactive<RuleForm>({
  oldUserName: '',

  userName: '',
  nickName: '',
  email: '',
  password: '',

  isEnable: '',

})
let radio1 = reactive('')

function onSubmit1() {

  console.log("value", value);
  console.log("value", value._value);
  options.forEach(function (item, index) {
    console.log(item, index);
    console.log(item.label);
    console.log(item.label == value._value);
    if (item.label == value._value || item.value == value._value) {
      power.author = item.value
      power.token = useLoginStore().get()
    }


  });
  console.log("power", power);


  editUserAuthorFn(power)
  console.log('submit!')
  isOpenMask1.value = false;
  // alert("分配角色成功成功")
   ElMessage({
    message: '分配权限成功',
    type: 'success',
  })
  resetdraw()
}

// 编辑提交
function onSubmit2() {
  console.log('submit!')
  

  if (a.value==1||a.value=="是") {
    
    edit.isEnable=1
  }else if(a.value==0||a.value=="否"){
    
    edit.isEnable=0
  }
  console.log("edit.isEnable",edit.isEnable);
  
  console.log("edit", edit);
  edit.token = useLoginStore().get()
  editUserInfoFn(edit)

  isOpenMask2.value = false;

  console.log("ruleFormRef1",ruleFormRef1);

  if (edit.email) {
     ruleFormRef1.email=true
  }
  if (edit.nickName) {
     ruleFormRef1.nickName=true
  }
  

  if (
    ruleFormRef1.userName==true&&
    ruleFormRef1.nickName==true&&
    ruleFormRef1.password==true&&
    ruleFormRef1.email==true

  ) {
       ElMessage({
        message: '编辑成功',
        type: 'success',
      })

      resetdraw()
      
    } else {
      ElMessage({
        message: '编辑失败',
        type: 'error',
      })
      return false
    }
  // alert("编辑成功")
 
  
}


const resetForm1 = (formEl: FormInstance | undefined) => {
  console.log("formEl",formEl);
  
  // if (!formEl) return
  // formEl.resetFields()
    ElMessage({
    message: '分配权限取消成功',
    type: 'success',
  })
  console.log("是否执行");
  
  CloseMask1();
}
const resetForm2 = (formEl: FormInstance | undefined) => {
  // if (!formEl) return
  // formEl.resetFields()
   ElMessage({
    message: '编辑取消成功',
    type: 'success',
  })
  CloseMask2();
}


let isOpenMask1 = ref(false)

const CloseMask1 = () => {
  isOpenMask1.value = false;
}
const OpenMask1 = (index, row) => {
  console.log("index, row");
  console.log(index, row);
  isOpenMask1.value = true;
  power.author = row.character;
  power.username = row.username;
  console.log("power", power);
  value.value = options[power.author - 1].label
  console.log("value.value", value.value);
 

}

// mask2
let isOpenMask2 = ref(false)

const CloseMask2 = () => {
  isOpenMask2.value = false;
}
const OpenMask2 = (index, row) => {
  console.log("index, row");
  console.log(index, row);

  edit.oldUserName = row.username//旧用户名
  edit.userName = "",//新用户名
      edit.nickName = row.user_nickname
  edit.token = useLoginStore().get()
  edit.email = row.email
  edit.password = ""
  if (row.user_enable == "是") {
    row.user_enable = 1
  } else if (row.user_enable == "否") {
    row.user_enable = 0
  }
  console.log("row.user_enable", row.user_enable);

  edit.isEnable = row.user_enable
  if (edit.isEnable==1||edit.isEnable=="是") {
    a.value = "是"
    edit.isEnable=1
  }else if(edit.isEnable==0||edit.isEnable=="否"){
    a.value="否"
    edit.isEnable=0
  }
  // a.value = edit.isEnable.toString()
  console.log('aaaaa', a.value);
   radio1=row.user_enable
   console.log("radio1",radio1);
   
  console.log("edit.isEnable",edit.isEnable);
  
  isOpenMask2.value = true;
  console.log("edit", edit);

}
//  let edit: any = reactive<Array<any>>([])

const formatDate = (date, fmt) => {
  if (/(y+)/.test(fmt)) {
    fmt = fmt.replace(RegExp.$1, (date.getFullYear() + '').substr(4 - RegExp.$1.length))
  }
  let o = {
    'M+': date.getMonth() + 1,
    'd+': date.getDate(),
    'h+': date.getHours(),
    'm+': date.getMinutes(),
    's+': date.getSeconds()
  }
  for (let k in o) {
    if (new RegExp(`(${k})`).test(fmt)) {
      let str = o[k] + ''
      fmt = fmt.replace(RegExp.$1, (RegExp.$1.length === 1) ? str : padLeftZero(str))
    }
  }
  console.log("fmt", fmt);

  return fmt
}
const padLeftZero = (str) => {
  return ('00' + str).substr(str.length)
}
const currentPage4 = ref(1)
const pageSize4 = ref(5)
const small = ref(false)
const background = ref(true)
const disabled = ref(false)

const handleSizeChange = (val: number) => {
  const databackUp = toRaw(UserData.value)
  const res = databackUp.data
  let newData = [...res]
  UserData1.value = [...newData.slice((currentPage4.value - 1) * pageSize4.value, currentPage4.value * pageSize4.value)];
}
const handleCurrentChange = (val: number) => {
  console.log(currentPage4.value, pageSize4.value)
  const databackUp = toRaw(UserData.value)
  const res = databackUp.data
  let newData = [...res]
  UserData1.value = [...newData.slice((currentPage4.value - 1) * pageSize4.value, currentPage4.value * pageSize4.value)];
}

const buttons = [
  {type: 'primary', text: 'primary'},//#5a9cf8

] as const


const formSize = ref('default')
const ruleFormRef = reactive<FormInstance>({
  username:false,
  user_nickname:false,
  email:false,
  password:false,
  isEnable:false
  
})
const ruleForm = reactive<RuleForm>({
  name: '',

  username: '',
  user_nickname: '',
  email: '',
  password: '',

  isEnable: '',
  desc: '',
})

const ruleFormRef1 = reactive<FormInstance>({
  userName:false,
  nickName:false,
  password:false,
  email:false

})




// 验证新用户名
const veriftuserName = (rule: any, value: any, callback: any) => {
  console.log("value",value);
  console.log("rule",rule);
  
  let ispassword=new RegExp(/\w{3,16}$/);
        let result=ispassword.test(value);
        console.log("正则result",result);
  
  if (value === '') {
    callback(new Error('新用户名不能为空'))
    ruleFormRef1.userName=false;
  } else {
    if (!result) {
       callback(new Error('新用户名请输入3-16位的字母或数字,支持字母数字下划线组合'))
        ruleFormRef1.userName=false;
    }
    else{
      ruleFormRef1.userName=true;
    }
   
  }
}
const veriftuserName1 = (rule: any, value: any, callback: any) => {
  console.log("value",value);
  console.log("rule",rule);
  
  let ispassword=new RegExp(/\w{3,16}$/);
        let result=ispassword.test(value);
        console.log("正则result",result);
  
  if (value === '') {
    callback(new Error('新用户名不能为空'))
    ruleFormRef.userName=false;
  } else {
    if (!result) {
       callback(new Error('新用户名请输入3-16位的字母或数字,支持字母数字下划线组合'))
        ruleFormRef.userName=false;
    }
    else{
      ruleFormRef.userName=true;
    }
   
  }
}
// 验证新昵称
const veriftnickName = (rule: any, value: any, callback: any) => {
  console.log("value",value);
  console.log("rule",rule);
  
  let ispassword=new RegExp(/\w{3,16}$/);
        let result=ispassword.test(value);
        console.log("正则result",result);
  
  if (value === '') {
    callback(new Error('新昵称不能为空'))
    ruleFormRef1.nickName=false;
  } else {
    if (!result) {
       callback(new Error('新昵称请输入3-16位的字母或数字,支持字母数字下划线组合'))
        ruleFormRef1.nickName=false;
    }
    else{
      ruleFormRef1.nickName=true;
    }
   
  }
  console.log("ruleFormRef1",ruleFormRef1);
  
}
const veriftnickName1 = (rule: any, value: any, callback: any) => {
  console.log("value",value);
  console.log("rule",rule);
  
  let ispassword=new RegExp(/\w{3,16}$/);
        let result=ispassword.test(value);
        console.log("正则result",result);
  
  if (value === '') {
    callback(new Error('新昵称不能为空'))
    ruleFormRef.nickName=false;
  } else {
    if (!result) {
       callback(new Error('新昵称请输入3-16位的字母或数字,支持字母数字下划线组合'))
        ruleFormRef.nickName=false;
    }
    else{
      ruleFormRef.nickName=true;
    }
   
  }
  console.log("ruleFormRef1",ruleFormRef1);
  
}


// 密码验证规程
const validatePass = (rule: any, value: any, callback: any) => {
  console.log("value",value);
  console.log("rule",rule);
  
  let ispassword=new RegExp(/\w{3,16}$/);
        let result=ispassword.test(value);
        console.log("正则result",result);
  
  if (value === '') {
    callback(new Error('密码不能为空'))
    ruleFormRef1.password=false;
  } else {
    if (!result) {
       callback(new Error('请输入3-16位的密码,密码支持字母数字下划线组合'))
       ruleFormRef1.password=false;
    }else{
      ruleFormRef1.password=true;
    }
   
  }
}
const validatePass1 = (rule: any, value: any, callback: any) => {
  console.log("value",value);
  console.log("rule",rule);
  
  let ispassword=new RegExp(/\w{3,16}$/);
        let result=ispassword.test(value);
        console.log("正则result",result);
  
  if (value === '') {
    callback(new Error('密码不能为空'))
    ruleFormRef.password=false;
  } else {
    if (!result) {
       callback(new Error('请输入3-16位的密码,密码支持字母数字下划线组合'))
       ruleFormRef.password=false;
    }else{
      ruleFormRef.password=true;
    }
   
  }
}
// 验证邮箱
const verifyemail = (rule: any, value: any, callback: any) =>{
    console.log("value",value);
  console.log("rule",rule);
  
  let ispassword=new RegExp(/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/);
        let result=ispassword.test(value);
        console.log("正则result",result);
  
  if (value === '') {
    callback(new Error('邮箱不能为空'))
    
    
    
      ruleFormRef1.email=false;
    
    
  } else {
    if (!result) {
       callback(new Error('邮箱格式错误,请输入正确邮箱, 一个或多个数字或字母 + @ +一个或多个数字或字母+ “.”和单词字符和-的组合'))
         ruleFormRef1.email=false;
    }else{
      ruleFormRef1.email=true;
    }
   
  }
}
const verifyemail1 = (rule: any, value: any, callback: any) =>{
    console.log("value",value);
  console.log("rule",rule);
  
  let ispassword=new RegExp(/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/);
        let result=ispassword.test(value);
        console.log("正则result",result);
  
  if (value === '') {
    callback(new Error('邮箱不能为空'))
    
    
    
      ruleFormRef.email=false;
    
    
  } else {
    if (!result) {
       callback(new Error('邮箱格式错误,请输入正确邮箱, 一个或多个数字或字母 + @ +一个或多个数字或字母+ “.”和单词字符和-的组合'))
         ruleFormRef.email=false;
    }else{
      
    }
   ruleFormRef.email=true;
  }
}

// 验证单选
const verifyisEnable1 = (rule: any, value: any, callback: any) =>{
    console.log("value",value);
  console.log("rule",rule);
  
  let ispassword=new RegExp(/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/);
        let result=ispassword.test(value);
        console.log("正则result",result);
  
  if (value === '') {
    callback(new Error('请选择是否启用'))
    
    
    
      ruleFormRef.isEnable=false;
    
    
  } else {
    
    ruleFormRef.isEnable=true;
  }
}


const rules1 = reactive<FormRules<RuleForm>>({

  oldUserName: [
    {required: true, message: 'Please input Activity name', trigger: 'blur'},
    {min: 3, max: 5, message: '请输入1-16为字母、数字或下划线', trigger: 'blur'},
  ],
  userName: [
    // {required: true, message: '新用户名不能为空', trigger: 'blur'},
    // {min: 3, max: 16, message: '请输入3-16位字母、数字或下划线', trigger: 'blur'},
    { validator: veriftuserName, trigger: 'blur' }
  ],
  nickName: [
    // {required: true, message: '新昵称不能为空', trigger: 'blur'},
    // {min: 3, max: 16, message: '请输入3-16位字母、数字或下划线', trigger: 'blur'},
     { validator: veriftnickName, trigger: 'blur' }
 ],
  email: [
    // {required: true, message: 'Please input Activity name', trigger: 'blur'},
    // {min: 3, max: 5, message: 'Length should be 3 to 5', trigger: 'blur'},
    { validator: verifyemail, trigger: 'blur' }
  ],
  password: [
    // {required: true, message: '密码不能为空', trigger: 'blur'},
    { validator: validatePass, trigger: 'blur' }
    // {min: 3, max: 5, message: '请输入3-16位数字或字母组成的新密码,否则输入旧密码', trigger: 'blur'},
  ],
  isEnable: [
    {
      required: true,
      message: 'Please select activity resource',
      trigger: 'change',
    },
  ]


})
const rules = reactive<FormRules<RuleForm>>({
  name: [
    {required: true, message: 'Please input Activity name', trigger: 'blur'},
    {min: 3, max: 5, message: 'Length should be 3 to 5', trigger: 'blur'},
  ],
  username: [
    // {required: true, message: 'Please input Activity name', trigger: 'blur'},
    // {min: 3, max: 5, message: 'Length should be 3 to 5', trigger: 'blur'},
    { validator: veriftuserName1, trigger: 'blur' }
  ],
  user_nickname: [
    // {required: true, message: 'Please input Activity name', trigger: 'blur'},
    // {min: 3, max: 5, message: 'Length should be 3 to 5', trigger: 'blur'},
    { validator: veriftnickName1, trigger: 'blur' }
  ],
  email: [
    // {required: true, message: 'Please input Activity name', trigger: 'blur'},
    // {min: 3, max: 5, message: 'Length should be 3 to 5', trigger: 'blur'},
    { validator: verifyemail1, trigger: 'blur' }
  ],
  password: [
    // {required: true, message: 'Please input Activity name', trigger: 'blur'},
    // {min: 3, max: 5, message: 'Length should be 3 to 5', trigger: 'blur'},
    { validator: validatePass1, trigger: 'blur' }
  ],
  isEnable: [
    // {
    //   required: true,
    //   message: 'Please select activity resource',
    //   trigger: 'change',
    // },
    { validator: verifyisEnable1, trigger: 'change' }
  ],
  desc: [
    {required: true, message: 'Please input activity form', trigger: 'blur'},
  ],

})
let searchvalue = ref('')
onMounted(() => {
  console.log("searchvalue", searchvalue)
})
const search = () => {
  // console.log("searchvalue",searchvalue);
  console.log(searchvalue.value);
  let newarr: any = reactive<Array<any>>([])
  var reg = new RegExp(searchvalue.value, 'ig');
  console.log('reg ==> ', reg);
  UserData1.value.forEach(function (item, index) {
    console.log("item,index");
    console.log(item, index);
    console.log("账号username", item._id);
    console.log("账号username", item.username);
    console.log("账号user_nickname", item.user_nickname);
    let test1 = reg.test(item.username);
    let test2 = reg.test(item.user_nicknam);
    console.log("test1", test1);
    console.log("test2", test2);
    if (test1 || test2) {
      console.log("item,index");
      console.log(item, index);
      newarr.push(item);
    }
    UserData1.value = newarr;
    console.log(" UserData1.value", UserData1.value);

    // console.log("姓名user_nickname",item.user_nickname);


  });


}
const searchreset = () => {
  UserData1.value = UserData.value.data;
  searchvalue=""
}
const submitForm = async (formEl: FormInstance | undefined, ruleForm) => {
  console.log("formEl", formEl);

  console.log("ruleForm", ruleForm);
  console.log("ruleForm.user_nickname", ruleForm.user_nickname);
  // ruleForm.token=useLoginStore().get()
  console.log(" ruleForm", ruleForm);
 

   console.log("ruleFormRef",ruleFormRef);

  if (ruleFormRef.email) {
     ruleFormRef.email=true
  }
  if (ruleFormRef.nickName) {
     ruleFormRef.nickName=true
  }
  

  if (
    ruleFormRef.userName==true&&
    ruleFormRef.nickName==true&&
    ruleFormRef.password==true&&
    ruleFormRef.email==true&&
    ruleFormRef.isEnable==true

  ) {
     ruleForm.token = useLoginStore().get()
      addUserFn(ruleForm)
       ElMessage({
        message: '添加成功',
        type: 'success',
      })

      
      resetdraw()
      CloseMask();
    } else {
      ElMessage({
        message: '添加失败',
        type: 'error',
      })
      return false
    }

  // alert("添加成功")
  
}
const resetForm = (formEl: FormInstance | undefined) => {
  if (!formEl) return
  formEl.resetFields()
  CloseMask();
}

// const options = Array.from({ length: 10000 }).map((_, idx) => ({
//   value: `${idx + 1}`,
//   label: `${idx + 1}`,
// }))
let isOpenMask = ref(false)
const onSubmit = () => {
  
  console.log("ruleFormRef",ruleFormRef);

  if (ruleFormRef.email) {
     ruleFormRef.email=true
  }
  if (ruleFormRef.nickName) {
     ruleFormRef.nickName=true
  }
  

  if (
    ruleFormRef.userName==true&&
    ruleFormRef.nickName==true&&
    ruleFormRef.password==true&&
    ruleFormRef.email==true&&
    ruleFormRef.isEnable==true

  ) {
       ElMessage({
        message: '编辑成功',
        type: 'success',
      })
      resetdraw()
    } else {
      ElMessage({
        message: '编辑失败',
        type: 'error',
      })
      return false
    }
}
const CloseMask = () => {
  isOpenMask.value = false;
}
const OpenMask = () => {
  isOpenMask.value = true;
  ruleForm.token = useLoginStore().get()
}
const value1 = ref(true)
const value2 = ref(false)

import {api} from '../api/api'
import {onMounted, reactive, ref} from 'vue'
import {getUserTable} from '@/api/AuthorApi'
import {addUser} from '@/api/AuthorApi'
import {editUserAuthor} from '@/api/AuthorApi'
import {editUserInfo} from '@/api/AuthorApi'
import {activateUsers} from '@/api/AuthorApi'
import type {FormInstance, FormRules} from 'element-plus'
import {useLoginStore} from '@/stores/loginStore'

let UserData: any = reactive<Array<any>>([])
let UserData1: any = reactive<Array<any>>([])
let DataCount: any = ref<Number<any>>([])
const tableData = [
  {
    date: '2016-05-03',
    name: 'Tom',
    address: 'No. 189, Grove St, Los Angeles',
  },
  {
    date: '2016-05-02',
    name: 'Tom',
    address: 'No. 189, Grove St, Los Angeles',
  },
  {
    date: '2016-05-04',
    name: 'Tom',
    address: 'No. 189, Grove St, Los Angeles',
  },
  {
    date: '2016-05-01',
    name: 'Tom',
    address: 'No. 189, Grove St, Los Angeles',
  },
]

const formatDateTime = (time) => {
  console.log("time", time);

  let date = time;
  let timeStr = date.getFullYear() + '-';
  timeStr += date.getMonth() + 1 + '-';
  timeStr += date.getDate();
  console.log("timeStr", timeStr);

  return timeStr;
}

let AddUserData: any = reactive<Array<any>>([])
onMounted(() => {
  // addUserFn(ruleForm)
})
const addUserFn = (ruleForm) => {
  addUser(ruleForm).then(res => {
    console.log("ruleForm==>addUser", ruleForm);
    let username = ruleForm.username;
    let user_nickname = ruleForm.user_nickname;
    let email = ruleForm.email;
    let password = ruleForm.password;
    let resource = ruleForm.resource;
    console.log(res);
    getUserTable().then(res => {
      UserData.value = {...res};
      UserData1.value = [...UserData.value.data];

      UserData1.value.forEach(function (item, index) {
        console.log("item,index");
        console.log(item, index);
        console.log(item.create_at);
        // let time = parseInt(item.create_at);
        // let times = timestampToTime(time);

        // console.log("times",times);
        let time = Number(item.create_at * 1000);

        const dt = new Date(time);
        console.log("dt", dt);

        console.log(time);
        formatDate(dt, 'yyyy-MM-dd ')
        // formatDateTime(dt);
        item.create_at = formatDate(dt, 'yyyy-MM-dd hh:mm:ss')
        console.log("item.create_at", item.create_at);
        if (item.last_login) {
          // 记录登录时间
          let time1 = Number(item.last_login * 1000);

          const dt1 = new Date(time1);
          //当前登录时间
          // let date = new Date();
          formatDate(dt1, 'yyyy-MM-dd ')
          item.last_login = formatDate(dt1, 'yyyy-MM-dd hh:mm:ss');
        }
      });
      handleCurrentChange(1)
      handleSizeChange(1)
    }).catch(err => {
      console.log(err)
    })
    console.log("UserData1.value", UserData1.value);

  }).catch(err => {
    console.log(err);
  })

}
const editUserAuthorFn = (power) => {
  editUserAuthor(power).then(res => {
    console.log("ruleForm==>addUser", power);

    console.log(res);

  }).catch(err => {
    console.log(err);
  })

}
const editUserInfoFn = (edit) => {
  editUserInfo(edit).then(res => {
    console.log("编辑成功123123123");

    console.log("edit", edit);

    console.log(res);

  }).catch(err => {
    console.log(err);
  })

}
const activateUsersFn = (active) => {
  activateUsers(active).then(res => {
    console.log("active", active);
    getUserTable().then(res => {
      UserData.value = {...res};
      UserData1.value = [...UserData.value.data];

      UserData1.value.forEach(function (item, index) {
        console.log("item,index");
        console.log(item, index);
        console.log(item.create_at);
        // let time = parseInt(item.create_at);
        // let times = timestampToTime(time);

        // console.log("times",times);
        let time = Number(item.create_at * 1000);

        const dt = new Date(time);
        console.log("dt", dt);

        console.log(time);
        formatDate(dt, 'yyyy-MM-dd ')
        // formatDateTime(dt);
        item.create_at = formatDate(dt, 'yyyy-MM-dd hh:mm:ss')
        console.log("item.create_at", item.create_at);
        if (item.last_login) {
          // 记录登录时间
          let time1 = Number(item.last_login * 1000);

          const dt1 = new Date(time1);
          //当前登录时间
          // let date = new Date();
          formatDate(dt1, 'yyyy-MM-dd ')
          item.last_login = formatDate(dt1, 'yyyy-MM-dd hh:mm:ss');
        }
      });
      handleCurrentChange(1)
      handleSizeChange(1)
    }).catch(err => {
      console.log(err)
    })
    console.log(res);

  }).catch(err => {
    console.log(err);
  })

}
const resetdraw = () => {
  getUserTable().then(res => {
    console.log(res);
    UserData.value = {...res};
    UserData1.value = [...UserData.value.data];
    //  UserData2.value=UserData.value.data;
    DataCount = UserData1.value.length;
    console.log("UserData", UserData.value.data);
    console.log(" UserData1.value", UserData1.value);

    UserData1.value.forEach(function (item, index) {
      console.log("item,index");
      console.log(item, index);
      console.log(item.create_at);
      // let time = parseInt(item.create_at);
      // let times = timestampToTime(time);

      // console.log("times",times);
      let time = Number(item.create_at * 1000);

      const dt = new Date(time);
      console.log("dt", dt);

      console.log(time);
      formatDate(dt, 'yyyy-MM-dd ')
      // formatDateTime(dt);
      item.create_at = formatDate(dt, 'yyyy-MM-dd hh:mm:ss')
      console.log("item.create_at", item.create_at);
      if (item.last_login) {
        // 记录登录时间
        let time1 = Number(item.last_login * 1000);

        const dt1 = new Date(time1);
        //当前登录时间
        // let date = new Date();
        formatDate(dt1, 'yyyy-MM-dd ')
        item.last_login = formatDate(dt1, 'yyyy-MM-dd hh:mm:ss');
      }
    });
    handleCurrentChange(1)
    handleSizeChange(1)
    isShow.value = true
    console.log("UserData1.value", UserData1.value);

  }).catch(err => {
    console.log(err);
  })
}
onMounted(() => {
  getUserTable().then(res => {
    console.log(res);
    UserData.value = res;
    UserData1.value = UserData.value.data;
    //  UserData2.value=UserData.value.data;
    DataCount = UserData1.value.length;
    console.log("UserData", UserData.value.data);
    console.log(" UserData1.value", UserData1.value);

    UserData1.value.forEach(function (item, index) {
      console.log("item,index");
      console.log(item, index);
      console.log(item.create_at);
      // let time = parseInt(item.create_at);
      // let times = timestampToTime(time);

      // console.log("times",times);
      let time = Number(item.create_at * 1000);

      const dt = new Date(time);
      console.log("dt", dt);

      console.log(time);
      formatDate(dt, 'yyyy-MM-dd ')
      // formatDateTime(dt);
      item.create_at = formatDate(dt, 'yyyy-MM-dd hh:mm:ss')
      console.log("item.create_at", item.create_at);
      if (item.last_login) {
        // 记录登录时间
        let time1 = Number(item.last_login * 1000);

        const dt1 = new Date(time1);
        //当前登录时间
        // let date = new Date();
        formatDate(dt1, 'yyyy-MM-dd ')
        item.last_login = formatDate(dt1, 'yyyy-MM-dd hh:mm:ss');
      }
    });
    handleCurrentChange(1)
    handleSizeChange(1)
    isShow.value = true
    console.log("UserData1.value", UserData1.value);

  }).catch(err => {
    console.log(err);
  })
})

</script>

<style lang="scss" scoped>

body {
  margin: 0;
}
.example-showcase .el-loading-mask {
  z-index: 9;
}

.table2 {
  margin-left: 20px
}

.spantitle {
  margin-left: 40px
}

.demo-pagination-block {
  // height:400px;
  margin-top: 22px;
  display: flex;
  flex-direction: row-reverse;
  margin-right: 20px;
}

.demo-pagination-block .demonstration {
  margin-bottom: 16px;
}

#user {
  width: 100%;
  // height:800px;
  position: relative;
  // background-color:red;
}

.pageselect {
  font-size: 16px;
  color: #a9abb1;
}

.pageselect span {
  color: #000;
  font-weight: bold;
}

.seacrcbigbox {

  height: 150px;
  /* border: 1px solid black; */
  box-shadow: 0 0 5px 1px rgba(158, 179, 193, .5);
  margin-top: 10px;
  overflow: hidden;
  border-radius: 2px
}

.wrappper {
  // width: 1064px;
  width: 100%;

}

.searchtop {
  width: 96%;
  height: 30px;
  // background-color: antiquewhite;
  margin: auto;
  margin-top: 30px;
  display: flex;
  justify-content: space-between;
}

.reset {
  background-color: #ffffff;
  border: 1px solid #e6e7ec;
  padding: 6px 15px;
  border-radius: 4px;
  box-shadow: 0 0 0px 0.5px #e6e7ec;
}

.searchbottom {
  width: 96%;
  height: 30px;
  // background-color: antiquewhite;
  margin: auto;
  margin-top: 30px;
  display: flex;
  justify-content: space-between;
}

.searchbottom .searchbox input {
  width: 200px;
  border: 1px solid #dddfe5;
  border-radius: 4px;
  padding: 5px 5px;
  outline: none;
}

button {
  cursor: pointer;
}

.btn-search {
  background-color: #5a9cf8;
  color: white;
  border: 1px solid #5a9cf8;
  border-radius: 4px;
  padding: 6px 15px;
}

.addusertable {
  height: 70px;
  background-color: white;
  box-shadow: 0 0 5px 1px rgba(158, 179, 193, .5);
  margin-top: 25px;
  overflow: hidden;
  border-radius: 2px;
  margin-bottom: 25px;


}

.btn-add {
  background-color: #5a9cf8;
  color: white;
  border: 1px solid #5a9cf8;
  border-radius: 4px;
  padding: 6px 15px;
  margin-top: 19px;
  float: right;
  margin-right: 22px
}

.mask {
  width: 100%;
  height: 100%;
  position: fixed;
  top: 60px;
  right: 0px;
  font-size: 14px;
  background: rgba(0, 0, 0, .5);
  z-index: 1000;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
}

.maskbox {
  width: 800px;
  height: 500px;
  background-color: white;
  margin: auto;
  margin-top: 30px;
  overflow: hidden;
  //  z-index: 10011;
}

.maskbox .masktop {
  width: 90%;
  //  background-color:red;
  display: flex;
  margin: auto;
  justify-content: space-between;
  margin-top: 30px;
  margin-bottom: 30px;
}

.maskbox .masktop h1 {
  margin: 0px;
  display: block;
  line-height: 40px;
}

.maskbox .masktop .del {
  font-size: 30px;
  color: #a3a5aa;
  cursor: pointer;
}

.maskbox .masktop .del:hover {
  color: #e4393c;

}

.maskbox .maskbody {
  width: 90%;
  margin: auto;
  margin-top: 30px;
  height: 200px;
  background-color: gray;
}

.maskbox .maskbottom {
  width: 94%;
  margin: auto;
  height: 32px;
  //  background-color:orange;
  display: flex;
  justify-content: flex-end;
}

.maskbottom div {
  width: 42px;
  height: 15px;
  padding: 8px 15px;
  border: 1px solid #409eff;
  background-color: #409eff;
  color: white;
  text-align: center;
  line-height: 15px;
  margin-left: 20px;
  border-radius: 4px;
  cursor: pointer;
}

.maskbottom div:nth-child(1) {
  background-color: white;
  color: black;
  border: 1px solid #f0f1f4;
}

.maskbottom div:nth-child(1):hover {
  box-shadow: 0 0 5px 1px rgba(158, 179, 193, .5);
}

.maskbottom div:nth-child(2):hover {
  box-shadow: 0 0 5px 1px rgba(8, 123, 238, 0.5);
}
</style>