<template>
	<div id="user">
		<div class="pageselect">
			<span>首页</span> /
			<span>权限</span> /
			角色列表
		</div>


		<el-table :data="RoleData1.value" border style="width: 100%">
			<el-table-column label="编号" prop="_id"/>
			<el-table-column label="角色名称" prop="name"/>
			<el-table-column label="描述" prop="desc"/>


			<el-table-column label="操作" prop="operate">
				<!-- <template #default="scope">
					<el-button size="small" @click="handleEdit(scope.$index, scope.row)"  v-for="button in buttons"
				:key="button.text"
				:type="button.type"
				text
						>编辑</el-button>

				</template> -->
			</el-table-column>
		</el-table>

		<!-- 遮罩 -->
		<div v-if="isOpenMask==1" class="mask">
			<div class="maskbox">
				<div class="masktop">
					<h1 class="masktitle">分配角色</h1>
					<div class="del" @click="CloseMask">×</div>
				</div>
				<!-- 表单主体 -->
				<el-form ref="ruleFormRef"
				         :model="ruleForm"
				         :rules="rules"
				         :size="formSize"
				         class="demo-ruleForm"
				         label-width="120px"
				         status-icon>
					<el-form-item label="账号:" prop="username">
						<el-input v-model="ruleForm.username" placeholder="请输入账号" style="width: 94%"/>
					</el-form-item>
					<el-form-item label="姓名:" prop="user_nickname">
						<el-input v-model="ruleForm.user_nickname" placeholder="请输入姓名" style="width: 94%"/>
					</el-form-item>
					<el-form-item label="邮箱:" prop="email">
						<el-input v-model="ruleForm.email" placeholder="请输入邮箱" style="width: 94%" type="email"/>
					</el-form-item>
					<el-form-item label="密码:" prop="password">
						<el-input v-model="ruleForm.password" placeholder="请输入密码" style="width: 94%" type="password"/>
					</el-form-item>

					<el-form-item label="是否启用:" prop="resource">
						<el-radio-group v-model="ruleForm.resource">
							<el-radio label="是"/>
							<el-radio label="否"/>
						</el-radio-group>
					</el-form-item>
				</el-form>
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
          :total="DataCount"
          @size-change="handleSizeChange"
          @current-change="handleCurrentChange"
      />
    </div>
  </div>


</template>

<script lang="ts" setup>
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
  const databackUp = toRaw(RoleData.value)
  const res = databackUp.data
  let newData = [...res]
  RoleData1.value = [...newData.slice((currentPage4.value - 1) * pageSize4.value, currentPage4.value * pageSize4.value)];
}
const handleCurrentChange = (val: number) => {
  console.log(currentPage4.value, pageSize4.value)
  const databackUp = toRaw(RoleData.value)
  const res = databackUp.data
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
import {getUserAuthor} from '@/api/AuthorApi'
import type {FormInstance, FormRules} from 'element-plus'

let RoleData: any = reactive<Array<any>>([])
let RoleData1: any = reactive<Array<any>>([])
let DataCount: any = reactive<Number<any>>([])
const tableData = [

	{
		_id: 5,
		role_name: '超级管理员',
		desc: '别乱改(改成功算我输)',
		operate: ""
	},
	{
		_id: 6,
		role_name: '超级CEO',
		desc: '我管超级管理员',
		operate: ""
	},
	{
		_id: 8,
		role_name: '超级打击cc',
		desc: '安抚撒啊实打',
		operate: ""
	},
	{
		_id: 10,
		role_name: '陈富专用a',
		desc: '',
		operate: ""
	},
	{
		_id: 15,
		role_name: '商品管理员',
		desc: '只能查看及操作商品',
		operate: ""
	},
	{
		_id: 24,
		role_name: '测试号11123',
		desc: '的观点123',
		operate: ""
	},
	{
		_id: 39,
		role_name: 'pyt',
		desc: 'pyt的',
		operate: ""
	},
	{
		_id: 40,
		role_name: 'zzh',
		desc: '得到广泛的大哥666',
		operate: ""
	},
	{
		_id: 41,
		role_name: 'hezixian',
		desc: '666',
		operate: ""
	},
	{
		_id: 42,
		role_name: 'LQW',
		desc: '打打',
		operate: ""
	},

]


onMounted(() => {
	console.log(tableData);
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
  getUserAuthor().then(res => {
    console.log(res);
    RoleData.value = res;
    RoleData1.value = RoleData.value.data;
    DataCount = RoleData1.value.length;
    console.log("RoleData", RoleData.value.data);
    console.log("RoleData1.value", RoleData1.value);
    
    console.log("RoleData1.value", RoleData1.value);
    handleCurrentChange(1)
      handleSizeChange(1)
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
	width: 1223px;
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