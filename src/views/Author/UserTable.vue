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
                        <Search />
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

       
       
      <div class="table">
      <el-table :data="UserData1.value" border style="width: 100%">
       <el-table-column prop="_id" label="编号" />
       <el-table-column prop="username" label="账号"  />
       <el-table-column prop="user_nickname" label="姓名" />
       <el-table-column prop="email" label="邮箱" width="170"/>
       <el-table-column prop="create_at" label="添加时间" width="170" />
       <el-table-column prop="last_login" label="最后登录" width="170" />
        <el-table-column label="是否启用"  >
         <template #default="scope">
          <el-switch  :value="scope.row.user_enable==1?true:false" @click="isupdate(scope.$index, scope.row)"/>
          <!-- <el-switch v-model="value2" v-else="user_enable==0" /> -->
           <!-- <span>{{UserData.value.data[0].user_enable}}</span> -->
         </template>
       </el-table-column>
        <el-table-column label="操作"  width="180">
         <template #default="scope">
           <el-button size="small" @click="OpenMask1(scope.$index, scope.row)"  v-for="button in buttons"
         :key="button.text"
         :type="button.type"
         text
             >分配角色</el-button
           >
           <!-- handleDelete(scope.$index, scope.row) -->
           <el-button size="small"   v-for="button in buttons"
         :key="button.text"
         :type="button.type"
         text
           @click="OpenMask2(scope.$index, scope.row)">编辑</el-button>
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
          <el-form  ref="ruleFormRef"
                :model="ruleForm"
                :rules="rules"
                label-width="120px"
                class="demo-ruleForm"
                :size="formSize"
                status-icon>
            <el-form-item label="账号:" prop="username">
              <el-input v-model="ruleForm.username"  placeholder="请输入账号" style="width: 94%"/>
            </el-form-item>
            <el-form-item label="姓名:" prop="user_nickname">
              <el-input v-model="ruleForm.user_nickname"  placeholder="请输入姓名" style="width: 94%"/>
            </el-form-item>
            <el-form-item label="邮箱:" prop="email">
              <el-input v-model="ruleForm.email"  type="email" placeholder="请输入邮箱" style="width: 94%"/>
            </el-form-item>
            <el-form-item label="密码:"  prop="password">
              <el-input v-model="ruleForm.password" type="password"  placeholder="请输入密码" style="width: 94%"/>
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
              <div class="cancel" @click="resetForm(ruleFormRef)">取消</div>
              <div class="create" @click="onSubmit1()">确定</div>
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
          <el-form  ref="ruleFormRef"
                :model="edit"
                :rules="rules1"
                label-width="120px"
                class="demo-ruleForm"
                :size="formSize"
                
                status-icon>
            <el-form-item label="旧用户名:" prop="oldUserName">
              <el-input v-model="edit.oldUserName"  placeholder="请输入账号" style="width: 94%"/>
            </el-form-item>
            <el-form-item label="新用户名:" prop="userName">
              <el-input v-model="edit.userName"  placeholder="请输入账号" style="width: 94%"/>
            </el-form-item>
            <el-form-item label="用户昵称:" prop="nickname">
              <el-input v-model="edit.nickName"  placeholder="请输入姓名" style="width: 94%"/>
            </el-form-item>
            <el-form-item label="用户邮箱:" prop="email">
              <el-input v-model="edit.email"  type="email" placeholder="请输入邮箱" style="width: 94%"/>
            </el-form-item>
            <el-form-item label="用户密码:" prop="password">
              <el-input v-model="edit.password"  type="email" placeholder="请输入邮箱" style="width: 94%"/>
            </el-form-item>
            
            
            <el-form-item label="是否启用:" prop="isEnable">
             <el-radio-group v-model="a">
               <el-radio label="1"/>
               <el-radio label="0"/>
              </el-radio-group>
            </el-form-item>
            </el-form>
          <div class="maskbottom">
              <div class="cancel" @click="resetForm(ruleFormRef)">取消</div>
              <div class="create" @click="onSubmit2(edit)">确定</div>
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
            :page-sizes="[10, 20, 30, 40]"
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
  
  // constisupdate(scope.$index, scope.row){

  // }


  import { ref } from 'vue'
  const value = ref('') 
  const a = ref('')

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

    function onSubmit1() {
      
      console.log("value",value);
      console.log("value",value._value);
      options.forEach(function (item,index) {
        console.log(item,index);
        console.log(item.label);
        console.log(item.label==value._value);
        if (item.label==value._value||item.value==value._value) {
           power.author=item.value
            power.token = useLoginStore().get()
        }
        
        
      });
      console.log("power",power);
      
      
      editUserAuthorFn(power) 
      console.log('submit!')
      isOpenMask1.value=false;
      alert("分配角色成功成功")
    }

    // 编辑提交
    function onSubmit2() {
      console.log('submit!')
      console.log("edit",edit);
      edit.token = useLoginStore().get()
      editUserInfoFn();
      isOpenMask2.value=false;
      alert("编辑成功")
    }


     let isOpenMask1 = ref(false) 
    
    const CloseMask1=()=>{
      isOpenMask1.value=false;
    }
    const OpenMask1=(index, row)=>{
      console.log("index, row");
      console.log(index, row);
      isOpenMask1.value=true;
      power.author=row.character;
      power.username=row.username;
      console.log("power",power);
      value.value=options[power.author-1].label
      console.log("value.value",value.value);
      
      
    }
  let power: any = reactive<Array<any>>([])

    // mask2
     let isOpenMask2 = ref(false) 
    
    const CloseMask2=()=>{
      isOpenMask2.value=false;
    }
    const OpenMask2=(index, row)=>{
      console.log("index, row");
      console.log(index, row);
      
      edit.oldUserName=row.username//旧用户名
      edit.userName="",//新用户名
      edit.nickName=row.user_nickname
      
      edit.email=row.email
      edit.password=""
      edit.isEnable=row.user_enable
      a.value = edit.isEnable.toString()
      console.log('aaaaa',a.value);
      



      isOpenMask2.value=true;
      console.log("edit",edit);
      
    }
  //  let edit: any = reactive<Array<any>>([])

      const edit = reactive<RuleForm>({
      oldUserName: '',

      userName: '',
      userName: '',
      email: '',
      password: '',

      isEnable: '',
      
    })



    let date = new Date();
    // let date = new Date().getTime();
    onMounted(() => {
      console.log("date时间戳",date)
      formatDate(date, 'yyyy-MM-dd hh:mm')
    })
    const formatDate=(date, fmt) =>{
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
      console.log("fmt",fmt);
      
    	return fmt
    }
    const padLeftZero=(str)=> {
	   return ('00' + str).substr(str.length)
    }
    const currentPage1 = ref(5)
    const currentPage2 = ref(5)
    const currentPage3 = ref(5)
    const currentPage4 = ref(4)
    const pageSize2 = ref(10)
    const pageSize3 = ref(10)
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
    const buttons = [
      { type: 'primary', text: 'primary' },//#5a9cf8

    ] as const

    
   
    const formSize = ref('default')
    const ruleFormRef = ref<FormInstance>()
    const ruleForm = reactive<RuleForm>({
      name: '',

      username: '',
      user_nickname: '',
      email: '',
      password: '',

      isEnable: '',
      desc: '',
    })

   

    const rules1 = reactive<FormRules<RuleForm>>({
      
      oldUserName: [
        { required: true, message: 'Please input Activity name', trigger: 'blur' },
        { min: 3, max: 5, message: 'Length should be 3 to 5', trigger: 'blur' },
      ],
     userName: [
        { required: true, message: 'Please input Activity name', trigger: 'blur' },
        { min: 3, max: 5, message: 'Length should be 3 to 5', trigger: 'blur' },
      ],
     nickName: [
        { required: true, message: 'Please input Activity name', trigger: 'blur' },
        { min: 3, max: 5, message: 'Length should be 3 to 5', trigger: 'blur' },
      ],
      email: [
        { required: true, message: 'Please input Activity name', trigger: 'blur' },
        { min: 3, max: 5, message: 'Length should be 3 to 5', trigger: 'blur' },
      ],
      password: [
        { required: true, message: 'Please input Activity name', trigger: 'blur' },
        { min: 3, max: 5, message: 'Length should be 3 to 5', trigger: 'blur' },
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
        { required: true, message: 'Please input Activity name', trigger: 'blur' },
        { min: 3, max: 5, message: 'Length should be 3 to 5', trigger: 'blur' },
      ],
      username: [
        { required: true, message: 'Please input Activity name', trigger: 'blur' },
        { min: 3, max: 5, message: 'Length should be 3 to 5', trigger: 'blur' },
      ],
      user_nickname: [
        { required: true, message: 'Please input Activity name', trigger: 'blur' },
        { min: 3, max: 5, message: 'Length should be 3 to 5', trigger: 'blur' },
      ],
      email: [
        { required: true, message: 'Please input Activity name', trigger: 'blur' },
        { min: 3, max: 5, message: 'Length should be 3 to 5', trigger: 'blur' },
      ],
      password: [
        { required: true, message: 'Please input Activity name', trigger: 'blur' },
        { min: 3, max: 5, message: 'Length should be 3 to 5', trigger: 'blur' },
      ],
        isEnable: [
        {
          required: true,
          message: 'Please select activity resource',
          trigger: 'change',
        },
      ],
        desc: [
        { required: true, message: 'Please input activity form', trigger: 'blur' },
      ],

  })
   let searchvalue = ref('')
   onMounted(() => {
     console.log("searchvalue",searchvalue)
   })
  const search = ()=>{
      // console.log("searchvalue",searchvalue);
      console.log(searchvalue.value);
      let newarr: any = reactive<Array<any>>([])
      var reg = new RegExp(searchvalue.value, 'ig');
            console.log('reg ==> ', reg);
          UserData1.value.forEach(function (item,index) {
            console.log("item,index");
            console.log(item,index);
            console.log("账号username",item._id);
            console.log("账号username",item.username);
            console.log("账号user_nickname",item.user_nickname);
            let test1=reg.test(item.username);
            let test2=reg.test(item.user_nicknam);
            console.log("test1",test1);
            console.log("test2",test2);
            if (test1||test2) {
              console.log("item,index");
              console.log(item,index);
              newarr.push(item);
            }
            UserData1.value=newarr;
            console.log(" UserData1.value", UserData1.value);
            
            // console.log("姓名user_nickname",item.user_nickname);
            
            
          });
            
      
      
  }
  const searchreset=()=>{
    UserData1.value=UserData.value.data;
  }
  const submitForm = async (formEl: FormInstance | undefined,ruleForm) => {
    console.log("ruleForm",ruleForm);
    console.log("ruleForm.user_nickname",ruleForm.user_nickname);
    // ruleForm.token=useLoginStore().get()
    console.log(" ruleForm", ruleForm);
    ruleForm.token = useLoginStore().get()
    addUserFn(ruleForm) 

    if (!formEl) return
    await formEl.validate((valid, fields) => {
      console.log("valid, fields");
       console.log(valid, fields);
    
      if (valid) {
       console.log('submit!')
        console.log("ruleForm",ruleForm);
      } else {
       console.log('error submit!', fields)
      }
   })
  }
  const resetForm = (formEl: FormInstance | undefined) => {
    if (!formEl) return
    formEl.resetFields()
  }

    // const options = Array.from({ length: 10000 }).map((_, idx) => ({
    //   value: `${idx + 1}`,
    //   label: `${idx + 1}`,
    // }))
    let isOpenMask = ref(false) 
    const onSubmit = () => {
      console.log('submit!')
    }
    const CloseMask=()=>{
      isOpenMask.value=false;
    }
    const OpenMask=()=>{
      isOpenMask.value=true;
    }
    const value1 = ref(true)
    const value2 = ref(false)

    import {api} from '../api/api'
    import {onMounted,reactive,ref } from 'vue'
    import {getUserTable} from '@/api/AuthorApi'
    import {addUser} from '@/api/AuthorApi'
    import {editUserAuthor} from '@/api/AuthorApi'
    import {editUserInfo} from '@/api/AuthorApi'
    import type { FormInstance, FormRules } from 'element-plus'
    import {useLoginStore} from '@/stores/loginStore'
    let UserData: any = reactive<Array<any>>([])
    let UserData1: any = reactive<Array<any>>([])
     let DataCount:any=  ref<Number<any>>([]) 
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
      
     
    onMounted(() => {
        console.log(tableData);
    })

 const formatDateTime = (time) => {
  console.log("time",time);
  
    let date = time;
    let timeStr = date.getFullYear() + '-';
    timeStr += date.getMonth() + 1 + '-';
    timeStr += date.getDate();
    console.log("timeStr",timeStr);
    
    return timeStr;
}

  let AddUserData: any = reactive<Array<any>>([])
    onMounted(() => {
      addUserFn(ruleForm)
    })
    const addUserFn = (ruleForm) => {
      addUser(ruleForm).then(res => {
        console.log("ruleForm==>addUser",ruleForm);
        let username=ruleForm.username;
        let user_nickname=ruleForm.user_nickname;
        let email=ruleForm.email;
        let password=ruleForm.password;
        let resource=ruleForm.resource;
        console.log(res);
          AddUserData.value=res;
         UserData1.value=UserData.value.data;
         DataCount= UserData1.value.length;
         console.log("AddUserData.value",AddUserData.value);
        console.log(" UserData1.value", UserData1.value);

        UserData1.value.forEach(function (item,index) {
          console.log("item,index");
          console.log(item,index);
          console.log(item.create_at);
          // let time = parseInt(item.create_at);
          // let times = timestampToTime(time);
        
          // console.log("times",times);
          let time=Number(item.create_at*1000);
          
          const dt = new Date(time);
          console.log("dt",dt);
          
          console.log(time);
            formatDate(dt, 'yyyy-MM-dd ')
          // formatDateTime(dt);
          item.create_at= formatDate(dt, 'yyyy-MM-dd hh:mm:ss')
          console.log("item.create_at",item.create_at);
          if (item.last_login) {
            // 记录登录时间
             let time1=Number(item.last_login*1000);
          
          const dt1 = new Date(time1);
          //当前登录时间
          // let date = new Date();
          formatDate(dt1, 'yyyy-MM-dd ')
          item.last_login= formatDate(dt1, 'yyyy-MM-dd hh:mm:ss');
          }
        });
        console.log("UserData1.value",UserData1.value);
        
      }).catch(err => {
        console.log(err);
      })
    
   } 
    const editUserAuthorFn = (power) => {
      editUserAuthor(power).then(res => {
        console.log("ruleForm==>addUser",power);
       
        console.log(res);
       
      }).catch(err => {
        console.log(err);
      })
    
   } 
    const editUserInfoFn = (edit) => {
      editUserInfo(edit).then(res => {
        console.log("ruleForm==>addUser",power);
       
        console.log(res);
       
      }).catch(err => {
        console.log(err);
      })
    
   } 
    onMounted(() => {
      getUserTable().then(res => {
        console.log(res);
        UserData.value=res;
       UserData1.value=UserData.value.data;
      //  UserData2.value=UserData.value.data;
       DataCount= UserData1.value.length;
       console.log("UserData",UserData.value.data);
        console.log(" UserData1.value", UserData1.value);

        UserData1.value.forEach(function (item,index) {
          console.log("item,index");
          console.log(item,index);
          console.log(item.create_at);
          // let time = parseInt(item.create_at);
          // let times = timestampToTime(time);
        
          // console.log("times",times);
          let time=Number(item.create_at*1000);
          
          const dt = new Date(time);
          console.log("dt",dt);
          
          console.log(time);
            formatDate(dt, 'yyyy-MM-dd ')
          // formatDateTime(dt);
          item.create_at= formatDate(dt, 'yyyy-MM-dd hh:mm:ss')
          console.log("item.create_at",item.create_at);
          if (item.last_login) {
            // 记录登录时间
             let time1=Number(item.last_login*1000);
          
          const dt1 = new Date(time1);
          //当前登录时间
          // let date = new Date();
          formatDate(dt1, 'yyyy-MM-dd ')
          item.last_login= formatDate(dt1, 'yyyy-MM-dd hh:mm:ss');
          }
        });
        console.log("UserData1.value",UserData1.value);
        
      }).catch(err => {
        console.log(err);
      })
    })
   onMounted(() => {
        console.log("UserData.value",UserData.value)
      })


</script>

<style lang="scss" scoped>  
        .table2{
          margin-left:20px
        }
        .spantitle{
          margin-left:40px
        }
         
        .demo-pagination-block {
          // height:400px;
          margin-top: 22px;
             margin-left: 724px;
        }
        .demo-pagination-block .demonstration {
          margin-bottom: 16px;
        }
        #user{
          width: 1182px;
            // height:800px;
          position:relative;
          // background-color:red;
        }
        .pageselect{
            font-size: 16px;
            color: #a9abb1;
        }
        .pageselect span{
            color: #000;
            font-weight: bold;
        }
        .seacrcbigbox{
           
            height: 150px;
            /* border: 1px solid black; */
            box-shadow: 0 0 5px 1px rgba(158,179,193,.5);
            margin-top:10px;
            overflow:hidden;
             border-radius:2px
        }
        .wrappper{
            // width: 1064px;
            width: 1182px;
            
        }
        .searchtop{
            width: 96%;
            height: 30px;
            // background-color: antiquewhite;
            margin: auto;
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
        }
        .reset{
            background-color:#ffffff;
            border:1px solid #e6e7ec;
            padding:6px 15px;
            border-radius:4px;
            box-shadow: 0 0 0px 0.5px #e6e7ec;
        }
        .searchbottom{
            width: 96%;
            height: 30px;
            // background-color: antiquewhite;
            margin: auto;
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
        }
        .searchbottom .searchbox input{
            width:200px;
            border:1px solid #dddfe5;
            border-radius:4px;
            padding:5px 5px;
            outline:none;
        }
        button{
            cursor:pointer;
        }
        .btn-search{
            background-color:#5a9cf8;
            color:white;
            border:1px solid #5a9cf8;
            border-radius:4px;
            padding:6px 15px;
        }
        .addusertable{
            height:70px;
            background-color:white;
             box-shadow: 0 0 5px 1px rgba(158,179,193,.5);
            margin-top:25px;
            overflow:hidden;
            border-radius:2px;
            margin-bottom:25px;


        }
        .btn-add{
            background-color:#5a9cf8;
            color:white;
            border:1px solid #5a9cf8;
            border-radius:4px;
            padding:6px 15px;
            margin-top:19px;
            float:right;
            margin-right:22px
        }
       .mask{
              width: 1223px;
          height: 100%;
          position: fixed;
          top: 60px;
          right: 0px;
          font-size: 14px;
          background: rgba(0,0,0,.5);
          z-index: 1000;
          -webkit-box-align: center;
          -ms-flex-align: center;
          align-items: center;
       }
       .maskbox{
         width:800px;
         height:500px;
         background-color:white;
         margin:auto;
         margin-top:30px;
         overflow:hidden;
        //  z-index: 10011;
       }
       .maskbox .masktop{
         width:90%;
        //  background-color:red;
         display:flex;
         margin:auto;
         justify-content:space-between;
         margin-top:30px;
         margin-bottom:30px;
       }
       .maskbox .masktop h1{
         margin:0px;
         display:block;
         line-height:40px;
       }
       .maskbox .masktop .del{
         font-size:30px;
         color:#a3a5aa;
         cursor:pointer;
       }
       .maskbox .masktop .del:hover{
         color:#e4393c;
            
       }
       .maskbox .maskbody{
         width:90%;
         margin:auto;
         margin-top:30px;
         height:200px;
         background-color:gray;
       }
       .maskbox .maskbottom{
         width:94%;
         margin:auto;
         height:32px;
        //  background-color:orange;  
        display:flex;
         justify-content: flex-end;
       }
       .maskbottom div{
         width:42px;
         height:15px;
         padding:8px 15px;
         border:1px solid #409eff;
         background-color:#409eff;
         color:white;
         text-align: center;
         line-height:15px;
         margin-left:20px;
         border-radius:4px;
         cursor: pointer;
       }
       .maskbottom div:nth-child(1){
         background-color:white;
         color:black;
         border:1px solid #f0f1f4;
       }
       .maskbottom div:nth-child(1):hover{
         box-shadow: 0 0 5px 1px rgba(158,179,193,.5);
       }
       .maskbottom div:nth-child(2):hover{
         box-shadow: 0 0 5px 1px rgba(8, 123, 238, 0.5);
       }
</style>