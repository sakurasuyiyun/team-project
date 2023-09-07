<script lang="ts" setup>
import {computed, onMounted, reactive, ref, watch} from 'vue'
import {orderSalesReturn, addOrderSalesReturn} from "@/api/orderApi";
import {ElMessage, ElMessageBox} from 'element-plus'
import {useLoginStore} from "@/stores/loginStore"

let pageSize = ref(5)
let total = ref(10)
let currentPage = ref(1)
const isShow = ref(false)
const visible = ref(false)
let data = reactive<object>({
	number: '1',
	cause: '123',
	sort: '0',
	value: true,
	time: '2023-09-06 10:42:35'
})


let tableData = reactive<Array<object>>([])

onMounted(() => {
	orderSalesReturn().then(res => {
		console.log(res)
		tableData = res.data
		tableData.forEach(item => {
			item.time = TimestampToDate(item.create_at)
			item.value = item.isAvailable === 1
		})
		total = tableData.length
		isShow.value = true
	}).catch(err => {
		console.log(err)
	})
})

// 添加退货原因


// 时间戳转换函数
function TimestampToDate(Timestamp) {
	let date = new Date(Timestamp * 1000);
	let y = date.getFullYear();
	let m = date.getMonth() + 1;
	let d = date.getDate();
	return y + "-" + (m < 10 ? "0" + m : m) + "-" + (d < 10 ? "0" + d : d) + " " + date.toTimeString().substr(0, 8);
}

const handleCurrentChange = (val: Number) => {

	tableData = [...tableData.slice((currentPage.value - 1) * pageSize.value, currentPage.value * pageSize.value)]
}

const handleSizeChange = (val: Number) => {
	console.log(currentPage.value, pageSize.value)
	tableData = [...tableData.slice((currentPage.value - 1) * pageSize.value, currentPage.value * pageSize.value)]
	handleCurrentChange(1)
}
const isOpenMask = ref(false)
const open = () => {
	isOpenMask.value = true
}


</script>

<template>
	<div class="box">
		<!-- 面包屑导航栏	-->
		<el-breadcrumb class="breadcrrumb" separator="/">
			<el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
			<el-breadcrumb-item :to="{path:'/orderlist'}">订单</el-breadcrumb-item>
			<el-breadcrumb-item>退货原因</el-breadcrumb-item>
		</el-breadcrumb>
		<el-divider/>

		<!-- 数据列表	-->
		<div class="dataList">
			<div>
				<el-icon>
					<List/>
				</el-icon>
				<span>&nbsp;数据列表</span>
			</div>
			<el-button style="width: 80px" @click="open">添加</el-button>
			<!-- 遮罩 -->
			<div v-if="isOpenMask" class="mask">
				<div class="maskbox">
					<!-- 表单主体 -->
			</div>
			</div>
		</div>
		<!-- 数据	-->
		<div v-if="isShow" class="data-box">
			<el-table :data="tableData" border max-height="600" style="width: 100%; text-align: center;">
				<el-table-column align="center" fixed prop="date" type="selection" width="50"/>
				<el-table-column align="center" label="编号" prop="_id" width="60"/>
				<el-table-column align="center" label="原因类型" prop="name" width="200"/>
				<el-table-column align="center" label="排序" prop="isAvailable" width="170"/>
				<el-table-column align="center" label="是否可用" prop="value">
					<template #default="scope">
						<el-switch v-model="scope.row.value"/>
					</template>
				</el-table-column>
				<el-table-column align="center" label="添加时间" prop="time"/>
				<el-table-column align="center" label="操作" width="220">
					<template #default="scope">
						<el-button>编辑</el-button>
						<el-button>删除</el-button>
					</template>
				</el-table-column>
			</el-table>
		</div>
		<div class="pagination">
			<el-pagination
				v-model:current-page="currentPage"
				v-model:page-size="pageSize"
				:page-sizes="[5, 10, 20, 100]"
				:total="total"
				background
				class="el-pagination" layout="total, sizes, prev, pager, next"
				@current-change="handleCurrentChange"
				@size-change="handleSizeChange"
			/>
		</div>
	</div>
</template>

<style lang="scss" scoped>
.box{
	position: relative;
}
.data-box {
	margin-top: 20px;
}

.pagination {
	position: absolute;
	right: 20px;
	bottom: 20px;
	margin-top: 10px;
}

.dataList {
	width: calc(100% - 20px);
	padding: 20px 10px;
	display: flex;
	justify-content: space-between;
	align-items: center;
	border: 1px solid #eee;
	margin: 20px 0;
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

	.maskbox {
		width: 800px;
		height: 500px;
		background-color: white;
		margin: auto;
		margin-top: 30px;
		overflow: hidden;
	}
}
</style>