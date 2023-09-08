<script lang="ts" setup>
import {computed, onMounted, reactive, ref} from 'vue'
// @ts-ignore
import {orderList, orderRemove, orderSearch} from "@/api/orderApi";
import {useLoginStore} from "@/stores/loginStore"
import {ElMessage} from "element-plus";

// input输入框的双向数据绑定值
const formInline = reactive({
	token: useLoginStore().get(),
	order_num: '',
	username: '',
	create_at: '',
	order_status: '',
	payment: '',
	order_from: ''
})


// 表格数据
let data = reactive<Array<object>>([{}])
let tableData = reactive<Array<object>>([{}])

let pageSize = ref(5)
let total = ref(0)
let currentPage = ref(1)

// 获取到数据在渲染
const isShow = ref(false)
const list = () => {
	orderList().then(res => {
		// @ts-ignore
		data = res.data
		// @ts-ignore
		data.forEach(item => {
			// @ts-ignore
			item.time = TimestampToDate(item.create_at)
		})
		total.value = data.length
		handleCurrentChange(1)
		isShow.value = true
	}).catch(err => {
		console.log(err)
	})
}

// 渲染初始数据
onMounted(() => {
	list()
})
// 搜索过滤数据
const onSubmit = () => {
	// 转换时间戳
	let date = new Date(formInline.create_at).getTime()
	orderSearch({...formInline, create_at: date}).then(res => {
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
		})
		total.value = data.length
		tableData = data.slice((currentPage.value - 1) * pageSize.value, currentPage.value * pageSize.value)
	}).catch(err => {
		console.log(err)
	})
}

// 时间戳转换为日期格式
// @ts-ignore
function TimestampToDate(Timestamp) {
	let date = new Date(Timestamp * 1000);
	let y = date.getFullYear();
	let m = date.getMonth() + 1;
	let d = date.getDate();
	return y + "-" + (m < 10 ? "0" + m : m) + "-" + (d < 10 ? "0" + d : d) + " " + date.toTimeString().substr(0, 8);
}

// 删除数据
const removeList = (id: string) => {
	let removeObj = {
		token: useLoginStore().get(),
		orderId: id
	}
	orderRemove(removeObj).then( res => {
		console.log(res)
		ElMessage({
			// @ts-ignore
			message: res.msg,
			type: 'success',
		})
		list()
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
			<el-breadcrumb-item>订单列表</el-breadcrumb-item>
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
						<el-input v-model="formInline.order_num" clearable placeholder="订单编号"/>
					</el-form-item>
					<el-form-item label="收货人">
						<el-input v-model="formInline.username" clearable placeholder="收货人姓名/手机号码"/>
					</el-form-item>
					<el-form-item label="提交时间">
						<el-date-picker v-model="formInline.create_at" clearable placeholder="请选择时间" type="date"/>
					</el-form-item>
					<el-form-item label="订单状态">
						<el-select v-model="formInline.order_status" clearable placeholder="全部">
							<el-option label="待发货" value="待发货"/>
							<el-option label="已发货" value="已发货"/>
							<el-option label="已收货" value="已收货"/>
							<el-option label="已关闭" value="已关闭"/>
						</el-select>
					</el-form-item>
					<el-form-item label="订单分类">
						<el-select v-model="formInline.payment" clearable placeholder="全部">
							<el-option label="微信" value="微信"/>
							<el-option label="支付宝" value="支付宝"/>
							<el-option label="未支付" value="未支付"/>
						</el-select>
					</el-form-item>
					<el-form-item label="订单来源">
						<el-select v-model="formInline.order_from" clearable placeholder="全部">
							<el-option label="APP订单" value="APP订单"/>
							<el-option label="小程序" value="小程序"/>
						</el-select>
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
				<el-table-column align="center" label="编号" prop="_id" width="60"/>
				<el-table-column align="center" label="订单编号" prop="order_num" width="200"/>
				<el-table-column align="center" label="提交时间" prop="time" width="170"/>
				<el-table-column align="center" label="用户账号" prop="username"/>
				<el-table-column align="center" label="订单金额" prop="order_price"/>
				<el-table-column align="center" label="支付方式" prop="payment"/>
				<el-table-column align="center" label="订单来源" prop="order_from"/>
				<el-table-column align="center" label="订单状态" prop="order_status"/>
				<el-table-column align="center" label="操作" width="220">
					<template #default="scope">
						<el-button>查看订单</el-button>
						<el-button type="danger" @click="removeList(scope.row.order_num)">删除订单</el-button>
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