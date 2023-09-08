<template>
  <div id="user">
    <div class="pageselect">
      <span>首页</span> /
      <span>权限</span> /
      角色列表
    </div>

    <div class="table">
      <el-table :data="RoleData1.value" border style="width: 100%">
        <el-table-column label="编号" prop="_id"/>
        <el-table-column label="角色名称" prop="name"/>
        <el-table-column label="描述" prop="desc"/>


        <el-table-column label="操作">
          <template #default="scope">
            <el-button size="small" @click="OpenMask2(scope.$index, scope.row)" v-for="button in buttons"
                       :key="button.text"
                       :type="button.type"
                       text
            >编辑
            </el-button>

          </template>
        </el-table-column>
      </el-table>
    </div>
    <!-- 遮罩 -->
    <div v-if="isOpenMask2==1" class="mask">
      <div class="maskbox">
        <div class="masktop">
          <h1 class="masktitle">角色列表编辑</h1>
          <div class="del" @click="CloseMask2">×</div>
        </div>
        <!-- 表单主体 -->
        <el-form ref="ruleFormRef"
                 :model="edit"
                 :rules="rules"
                 :size="formSize"
                 class="demo-ruleForm"
                 label-width="120px"
                 status-icon>
          <el-form-item label="编号:" prop="id">
            <el-input v-model="edit.id" placeholder="请输入账号" style="width: 94%"/>
          </el-form-item>
          <el-form-item label="角色名称 :" prop="name">
            <el-input v-model="edit.name" placeholder="请输入邮箱" style="width: 94%" type="email"/>
          </el-form-item>
          <el-form-item label="描述 :" prop="desc">
            <el-input v-model="edit.desc" placeholder="请输入邮箱" style="width: 94%" type="email"/>
          </el-form-item>
          <!-- <el-select v-model="value" class="m-2" placeholder="Select">
            <el-option
              v-for="item in options"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select> -->


          <el-form-item label="是否启用:" prop="isEnable">
            <el-radio-group v-model="a">
              <el-radio label="是"/>
              <el-radio label="否"/>
            </el-radio-group>
          </el-form-item>
        </el-form>
        <div class="maskbottom">
          <div class="cancel" @click="CloseMask2">取消</div>
          <div class="create" @click="submitForm(ruleFormRef)">确定</div>
        </div>
      </div>
    </div>

    <div class="demo-pagination-block">
      <!-- <div class="demonstration">All combined</div> -->
      <el-pagination
          v-model:current-page="currentPage4"
          v-model:page-size="pageSize4"
          :page-sizes="[5, 10, 15, 20]"
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

<script lang="ts" setup>
const isShow = ref(false)
import {ElMessage} from 'element-plus'
import {toRaw} from 'vue'

let date = new Date();
// let date = new Date().getTime();
onMounted(() => {
  console.log("date时间戳", date)
  formatDate(date, 'yyyy-MM-dd hh:mm')
})
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
  console.log("RoleData.value", RoleData.value);

  const databackUp = toRaw(RoleData.value)
  const res = databackUp.data
  let newData = [...res]
  RoleData1.value = [...newData.slice((currentPage4.value - 1) * pageSize4.value, currentPage4.value * pageSize4.value)];
}
const handleCurrentChange = (val: number) => {
  console.log("RoleData.value", RoleData.value);
  console.log(currentPage4.value, pageSize4.value)
  const databackUp = toRaw(RoleData.value)
  const res = databackUp.data
  console.log("res", res);

  let newData = [...res]
  RoleData1.value = [...newData.slice((currentPage4.value - 1) * pageSize4.value, currentPage4.value * pageSize4.value)];
}
const buttons = [
  {type: 'primary', text: 'primary'},//#5a9cf8

] as const

interface RuleForm {
  name: string
  username: string,
  user_nickname: string,
  email: string,
  password: string,
  resource: string,
  desc: string
}

const formSize = ref('default')
const ruleFormRef = ref<FormInstance>()
const ruleForm = reactive<RuleForm>({
  name: '',

  username: '',
  user_nickname: '',
  email: '',
  password: '',

  resource: '',
  desc: '',
})


const rules = reactive<FormRules<RuleForm>>({
  name: [
    {required: true, message: 'Please input Activity name', trigger: 'blur'},
    {min: 3, max: 5, message: 'Length should be 3 to 5', trigger: 'blur'},
  ],
  username: [
    {required: true, message: 'Please input Activity name', trigger: 'blur'},
    {min: 3, max: 5, message: 'Length should be 3 to 5', trigger: 'blur'},
  ],
  user_nickname: [
    {required: true, message: 'Please input Activity name', trigger: 'blur'},
    {min: 3, max: 5, message: 'Length should be 3 to 5', trigger: 'blur'},
  ],
  email: [
    {required: true, message: 'Please input Activity name', trigger: 'blur'},
    {min: 3, max: 5, message: 'Length should be 3 to 5', trigger: 'blur'},
  ],
  password: [
    {required: true, message: 'Please input Activity name', trigger: 'blur'},
    {min: 3, max: 5, message: 'Length should be 3 to 5', trigger: 'blur'},
  ],
  resource: [
    {
      required: true,
      message: 'Please select activity resource',
      trigger: 'change',
    },
  ],
  desc: [
    {required: true, message: 'Please input activity form', trigger: 'blur'},
  ],

})

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

const value = ref('')
const submitForm = async (formEl: FormInstance | undefined) => {

  ElMessage({
    message: '编辑成功',
    type: 'success',
  })
  //  isShow.value=true;
  editAccountFn(edit)
  CloseMask2();
  isShow.value = false;

}

let isOpenMask = ref(false)
const onSubmit = () => {
  console.log('submit!')
}
const CloseMask = () => {
  isOpenMask.value = false;
}
const OpenMask = () => {
  isOpenMask.value = true;
}
const value1 = ref(true)
const value2 = ref(false)

import {api} from '../api/api'
import {onMounted, reactive, ref} from 'vue'
import {getUserAuthor, getUserTable} from '@/api/AuthorApi'
import {editAccount} from '@/api/AuthorApi'
import type {FormInstance, FormRules} from 'element-plus'
import {useLoginStore} from '@/stores/loginStore'

let RoleData: any = reactive<Array<any>>([])
let RoleData1: any = reactive<Array<any>>([])
let DataCount: any = reactive<Number<any>>([])
const a = ref('')
const edit = reactive<RuleForm>({


  id: '',
  name: '',
  email: '',
  desc: '',

  isEnable: '1',

})

let isOpenMask2 = ref(false)
const CloseMask2 = () => {
  isOpenMask2.value = false;
  isShow.value = true
}
const OpenMask2 = (index, row) => {
  console.log("index, row");
  console.log(index, row);
  isShow.value = false
  edit.name = row.name

  edit.id = row._id
  edit.desc = row.desc
  edit.token = useLoginStore().get()

  edit.isEnable = 1
  // a.value= edit.isEnable.toString();

  a.value = "是";
  console.log("aaaaa", a.value);


  isOpenMask2.value = true;
  console.log("edit", edit);

}

const editAccountFn = (edit) => {
  editAccount(edit).then(res => {
    resetdraw()
  }).catch(err => {
    console.log(err);
  })
}


const formatDateTime = (time) => {
  console.log("time", time);

  let date = time;
  let timeStr = date.getFullYear() + '-';
  timeStr += date.getMonth() + 1 + '-';
  timeStr += date.getDate();
  console.log("timeStr", timeStr);

  return timeStr;
}

const resetdraw = () => {
  console.log("是否调用了");

  getUserAuthor().then(res => {
    console.log(res);
    RoleData.value = {...res};
    RoleData1.value = [...RoleData.value.data];
    DataCount = RoleData1.value.length;

    handleCurrentChange(1)
    handleSizeChange(1)
    isShow.value = true
  }).catch(err => {
    console.log(err);
  })
}
onMounted(() => {
  getUserAuthor().then(res => {
    console.log(res);
    RoleData.value = res;
    RoleData1.value = RoleData.value.data;
    DataCount = RoleData1.value.length;
    console.log("RoleData", RoleData.value.data);
    console.log("RoleData1.value", RoleData1.value);

    console.log("RoleData1.value", RoleData1.value.length);
    console.log("DataCount", DataCount);
    handleCurrentChange(1)
    handleSizeChange(1)
    isShow.value = true
  }).catch(err => {
    console.log(err);
  })
})
onMounted(() => {
  console.log("RoleData.value", RoleData.value)
})


</script>

<style lang="scss" scoped>
.demo-pagination-block {
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
  margin-bottom: 20px
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

.menutable {
  float: left;
  // background-color:red;
  margin-top: 25px;
  margin-left: 20px;
  display: flex;
}

.menutable span {
  height: 26px;
  line-height: 23px;
  margin-left: 10px;
  margin-top: 0px;
  display: block;
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
  // position: fixed;
  // top: 30px;
  // right: 240px;
  margin-left: 27%;
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