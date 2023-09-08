<template>
  <div id="user">
    <div class="pageselect">
      <span>首页</span> /
      <span>权限</span> /
      菜单列表
    </div>

    <div class="addusertable wrappper">
      <div class="menutable">
        <el-icon :size="22">
          <Tickets/>
        </el-icon>
        <span>数据列表</span>
      </div>
      <button class="btn-add" @click="OpenMask">添加</button>
    </div>


    <el-table :data="tableData1" border style="width: 100%">
      <el-table-column prop="_id" label="编号" width="130" header-align="center" align="center"/>
      <el-table-column prop="menu_name" label="菜单名称" width="130" header-align="center" align="center"/>
      <el-table-column prop="menu_series" label="菜单级数" width="130" header-align="center" align="center"/>
      <el-table-column prop="icon" label="前端图标" header-align="center" align="center">
						
							<template #default="scope">
                	<el-icon>
									<component :is="scope.row.icon"/>
								</el-icon>
              </template>
								
			
							
      </el-table-column>
      <el-table-column label="是否显示" width="140" header-align="center" align="center">
        <template #default="scope">
          <el-switch :value="scope.row.isshow==1?true:false"/>
          <!-- <el-switch v-model="value2" v-else="user_enable==0" /> -->
          <!-- <span>{{UserData.value.data[0].user_enable}}</span> -->
        </template>
      </el-table-column>
      <el-table-column prop="sort" label="排序" width="130" header-align="center" align="center"/>
      <el-table-column label="设置" width="180" header-align="center" align="center">
        <template #default="scope">
          <el-button size="small" @click="OpenMask1(scope.$index, scope.row)" v-for="button in buttons"
                     :key="button.text"
                     :type="button.type"
                     text
          >查看下级
          </el-button
          >

        </template>
      </el-table-column>
      <el-table-column label="操作"  header-align="center" align="center">
        <template #default="scope">
          <el-button size="small" @click="handleEdit(scope.$index, scope.row)" v-for="button in buttons"
                     :key="button.text"
                     :type="button.type"
                     text
          >编辑
          </el-button>

        </template>
      </el-table-column>
    </el-table>

    <!-- 遮罩 v-if="isOpenMask1==1"-->
    <div class="mask" v-if="isOpenMask1==1">
      <div class="maskbox">
        <div class="masktop">
          <h1 class="masktitle">分配角色</h1>
          <div class="del" @click="CloseMask1">×</div>
        </div>
        <!-- 表单主体 -->
        <div></div>
        <!-- <div class="list">
          <div class="item" v-for="(item, index) in asideIcon" :key="index" :index="item.index">
            <span>{{navindex}}</span>
          </div>
        </div> -->
        <el-table :data="asideIcon" border style="width: 100%">
          <el-table-column prop="asideIcon" label="前端图标" header-align="center" align="center">
						
							<template #default="scope">
                <div>{{scope.row.title}}</div>
              </template>
								
			
							
      </el-table-column>
        </el-table>
        <div class="maskbottom">
          <div class="cancel" @click="resetForm(ruleFormRef)">取消</div>
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
          :total="tableData.length"
          @size-change="handleSizeChange"
          @current-change="handleCurrentChange"
      />
    </div>
  </div>

 <!-- <el-button text @click="dialogVisible = true">
    click to open the Dialog
  </el-button>

  <el-dialog
    v-model="dialogVisible"
    title="Tips"
    width="30%"
    :before-close="handleClose"
  >
    <span>This is a message</span>
    <template #footer>
      <span class="dialog-footer">
        <el-button @click="dialogVisible = false">Cancel</el-button>
        <el-button type="primary" @click="dialogVisible = false">
          Confirm
        </el-button>
      </span>
    </template>
  </el-dialog> -->

</template>

<script setup lang="ts">
// import { ElMessageBox } from 'element-plus'

// const dialogVisible = ref(false)

// const handleClose = (done: () => void) => {
//   ElMessageBox.confirm('Are you sure to close this dialog?')
//     .then(() => {
//       done()
//     })
//     .catch(() => {
//       // catch error
//     })
// }


let navindex=ref("");
let isOpenMask1 = ref(false)
const OpenMask1=(index, row)=>{
  console.log("index,row");
  console.log(index,row);
  navindex=index;
  console.log("navindex",navindex);
  
  
 isOpenMask1.value =true
}
const CloseMask1=()=>{
 isOpenMask1.value = false;
}








import { asideIcon } from "@/data/asideData";
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
  // const databackUp = toRaw(tableData)
  // console.log("databackUp",databackUp);
  // const res = databackUp.data
  // let newData = [...res]
  
  
  tableData1 = [...tableData.slice((currentPage4.value - 1) * pageSize4.value, currentPage4.value * pageSize4.value)];
  console.log("tableData1",tableData1);
}
const handleCurrentChange = (val: number) => {
  console.log(currentPage4.value, pageSize4.value)
  // const databackUp = toRaw(tableData)
  // console.log("databackUp",databackUp);
  
  // const res = databackUp.data
  // let newData = [...res]
  tableData1 = [...tableData.slice((currentPage4.value - 1) * pageSize4.value, currentPage4.value * pageSize4.value)];
  console.log("tableData1",tableData1);
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

const submitForm = async (formEl: FormInstance | undefined) => {
  if (!formEl) return
  await formEl.validate((valid, fields) => {
    if (valid) {
      console.log('submit!')
    } else {
      console.log('error submit!', fields)
    }
  })
}

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
import {getUserTable} from '@/api/AuthorApi'
import type {FormInstance, FormRules} from 'element-plus'

let UserData: any = reactive<Array<any>>([])
let UserData1: any = reactive<Array<any>>([])
const tableData = [
  {
    _id: 97,
    menu_name: '权限',
    menu_series: '一级',
    icon: "Key",
    isshow: 1,
    sort: 0,
  },
  {
    _id: 99,
    menu_name: '商品',
    menu_series: '一级',
    icon: "Goods",
    isshow: 1,
    sort: 0,
  },
  {
    _id: 117,
    menu_name: '订单',
    menu_series: '一级',
    icon: "List",
    isshow: 1,
    sort: 0,
  },
  {
    _id: 118,
    menu_name: '营销',
    menu_series: '一级',
    icon: "TrendCharts",
    isshow: 1,
    sort: 0,
  },
  // {
  //   _id: 131,
  //   menu_name: '添加菜单',
  //   menu_series: '一级',
  //   icon: "",
  //   isshow: 1,
  //   sort: 0,
  // },

]
let tableData1=tableData

onMounted(() => {
  console.log(tableData);
  console.log("tableData1",tableData1);
  
})

const formatDateTime = (time) => {
  console.log("time", time);

  let date = time;
  let timeStr = date.getFullYear() + '-';
  timeStr += date.getMonth() + 1 + '-';
  timeStr += date.getDate();
  console.log("timeStr", timeStr);

  return timeStr;
}
onMounted(() => {
  getUserTable().then(res => {
    console.log(res);
    UserData.value = res;
    UserData1.value = UserData.value.data;
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
    console.log("UserData1.value", UserData1.value);

  }).catch(err => {
    console.log(err);
  })
})
onMounted(() => {
  console.log("UserData.value", UserData.value)
})


</script>

<style lang="scss" scoped>
.dialog-footer button:first-child {
  margin-right: 10px;
}
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
  z-index: 10010;
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