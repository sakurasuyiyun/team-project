<script lang="ts" setup>
import {computed, onMounted, reactive, ref, watch} from 'vue'
// @ts-ignore
import {orderSales, SalesReturnSearch} from "@/api/orderApi";
import {useLoginStore} from "@/stores/loginStore"
import {ElMessage} from "element-plus";

// input输入框的双向数据绑定值
const formInline = reactive({
	token: useLoginStore().get(),
	_id: '',
	order_id: '',
	create_at: '',
	username: '',
	isFinished: ''
})

// 表格数据
let data = reactive<Array<any>>([{}])

let tableData = reactive<Array<any>>([{}])

let pageSize = ref(5)
let total = ref(0)
let currentPage = ref(1)

// 获取到数据在渲染
const isShow = ref(false)

const list = () => {
	orderSales().then((res: any) => {
		console.log(res.data)
		data = res.data
		data.forEach(item => {
			// @ts-ignore
			item.time = TimestampToDate(item.create_at)
			item.status = item.isFinished === 0 ? '退货中' : item.isFinished === 1 ? '已完成' : '已拒绝'
		})
		isShow.value = true
		total.value = data.length
		handleCurrentChange(1)
	}).catch(err => {
		console.log(err)
	})
}

// 渲染初始数据
onMounted(() => {
	list()
})

// 时间戳转换为日期格式
function TimestampToDate(Timestamp: any) {
	let date = new Date(Timestamp * 1000);
	let y = date.getFullYear();
	let m = date.getMonth() + 1;
	let d = date.getDate();
	return y + "-" + (m < 10 ? "0" + m : m) + "-" + (d < 10 ? "0" + d : d) + " " + date.toTimeString().substr(0, 8);
}

// 搜索过滤数据
const onSubmit = () => {
	isShow.value = false
	// 转换时间戳
	let date = new Date(formInline.create_at).getTime()
	SalesReturnSearch({...formInline, create_at: date}).then(res => {
		// let type = res.msg != '删除订单成功' ? 'error' : 'success'
		ElMessage({
			// @ts-ignore
			message: res.msg,
			type: 'success',
		})

		// @ts-ignore
		if (res.errno === 1) {
			// @ts-ignore
			console.log(res.msg)
			tableData = []
			total.value = 0
			return
		}
		// @ts-ignore
		data = res.data
		// @ts-ignore
		data.forEach(item => {
			// @ts-ignore
			item.time = TimestampToDate(item.create_at)
			item.status = item.isFinished === 0 ? '退货中' : item.isFinished === 1 ? '已完成' : '已拒绝'
		})
		total.value = data.length
		tableData = data.slice((currentPage.value - 1) * pageSize.value, currentPage.value * pageSize.value)
		isShow.value = true
	}).catch(err => {
		console.log(err)
	})
}


// 重置
const resetFrom = () => {
	for (const formInlineKey in formInline) {
		// @ts-ignore
		formInline[formInlineKey] = ''
	}
}

// 换页功能
const handleCurrentChange = (val: Number) => {
	tableData = [...data.slice((currentPage.value - 1) * pageSize.value, currentPage.value * pageSize.value)]
}

const handleSizeChange = (val: Number) => {
	tableData = [...data.slice((currentPage.value - 1) * pageSize.value, currentPage.value * pageSize.value)]
}

</script>

<template>
	<div class="box">
		<!-- 面包屑导航栏	-->
		<el-breadcrumb class="breadcrrumb" separator="/">
			<el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
			<el-breadcrumb-item :to="{path:'/orderlist'}">订单</el-breadcrumb-item>
			<el-breadcrumb-item>退货申请处理</el-breadcrumb-item>
		</el-breadcrumb>
		<el-divider/>
		<!-- 筛选搜索	-->
		<div class="top-radius">
			<div class="top-box">
				<div class="icon-box">
					<el-icon>
						<Search/>
					</el-icon>
					<span>&nbsp;筛选搜索</span>
				</div>
				<div>
					<el-button @click="resetFrom">重置</el-button>
					<el-button type="primary" @click="onSubmit">查询搜索</el-button>
				</div>
			</div>
			<div class="search-box">
				<el-form :inline="true" :model="formInline" class="demo-form-inline">
					<el-form-item label="输入搜索">
						<el-input v-model="formInline._id" clearable placeholder="服务单号"/>
					</el-form-item>
					<el-form-item label="输入搜索">
						<el-input v-model="formInline.order_id" clearable placeholder="订单编号"/>
					</el-form-item>
					<el-form-item label="处理状态">
						<el-select v-model="formInline.isFinished" clearable placeholder="全部">
							<el-option label="退货中" value="0"/>
							<el-option label="已完成" value="1"/>
							<el-option label="已拒绝" value="2"/>
						</el-select>
					</el-form-item>
					<el-form-item label="申请时间">
						<el-date-picker v-model="formInline.create_at" clearable placeholder="请选择时间" type="date"/>
					</el-form-item>
					<el-form-item label="输入搜索">
						<el-input v-model="formInline.username" clearable placeholder="退货人"/>
					</el-form-item>
				</el-form>

			</div>
		</div>
		<!-- 数据列表	-->
		<div class="dataList">
			<el-icon>
				<List/>
			</el-icon>
			<span>&nbsp;数据列表</span>
		</div>
		<!-- 数据	-->
		<div v-if="isShow" class="data-box">
			<el-table :data="tableData" border max-height="400" style="width: 100%; text-align: center;">
				<el-table-column align="center" fixed prop="date" type="selection" width="50"/>
				<el-table-column align="center" label="服务单号" prop="_id"/>
				<el-table-column align="center" label="订单编号" prop="order_id" width="200"/>
				<el-table-column align="center" label="申请时间" prop="time" width="170"/>
				<el-table-column align="center" label="用户账号" prop="username"/>
				<el-table-column align="center" label="退款金额" prop="return_price"/>
				<el-table-column align="center" label="申请状态" prop="status"/>
				<el-table-column align="center" label="操作" width="220">
					<template #default="scope">
						<el-button>查看详情</el-button>
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
.box {
	user-select: text;
}

.breadcrrumb {
	margin-bottom: 20px;
}

.top-radius {
	width: calc(100% - 20px);
	//height: 80px;
	border: 1px solid #eee;
	padding: 10px;

	.top-box {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 15px;
	}

	.search-box {
		width: 90%;
		margin: 0 auto;
	}

	::v-deep .el-input__wrapper {
		width: 200px;
	}

	::v-deep .el-form-item__label {
		width: 100px;
	}

	.icon-box {
		display: flex;
		align-items: center;
	}
}

.dataList {
	width: calc(100% - 20px);
	padding: 20px 10px;
	display: flex;
	align-items: center;
	border: 1px solid #eee;
	margin: 20px 0;
}

.pagination {
	position: absolute;
	right: 20px;
	bottom: 20px;
	margin-top: 10px;
}
</style>