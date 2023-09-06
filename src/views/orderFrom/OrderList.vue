<script lang="ts" setup>
import {computed, onMounted, reactive, ref} from 'vue'
// @ts-ignore
import {orderList} from "@/api/orderApi";

const formInline = reactive({
	orderNumber: '',
	consignee: '',
	date: '',
	orderState: '',
	orderClassify: '',
	orderSource: ''
})

// 表格数据
// @ts-ignore
let tableData: orderList = reactive([])
const isShow = ref(false)


onMounted(() => {
	// console.log(12312)
	orderList().then(res => {
		// @ts-ignore
		tableData = res.data
		// console.log(tableData)
		// @ts-ignore
		tableData.forEach(item => {
			// @ts-ignore
			item.time = TimestampToDate(item.create_at)
		})
		isShow.value = true
		console.log(tableData)
	}).catch(err => {
		console.log(err)
	})
})
// 搜索过滤数据
const show = reactive({
	allShow :false,
	orderNumberShow:false,
	consigneeShow: false,
	dateShow: false,
	orderStateShow: false,
	orderClassifyShow: false,
	orderSourceShow: false
})
const onSubmit = () => {
	console.log(formInline)
	// for (const key in formInline) {
	// 	if(formInline[key].trim() != '' ){
	// 		show.allShow = true
	// 		return
	// 	}
	// }
	tableData.forEach(item =>{
		// @ts-ignore
		if (formInline.orderNumber == item.order_num) {
			show.orderNumberShow = true
		}
		// @ts-ignore
		if (formInline.consignee == item.username){
			show.consigneeShow = true
		}
		// // @ts-ignore
		// if (formInline.orderState == item.order_status){
		// 	show.orderStateShow = true
		// }
	})
}

const newTableData = computed(() => {
	let data = [...tableData]
	if (show.orderNumberShow){
		// @ts-ignore
		data = data.filter(item => item.order_num == formInline.orderNumber)
		show.orderNumberShow = false
		return data
	}
	if (show.consigneeShow){
		// @ts-ignore
		data = data.filter(item => item.username == formInline.consignee)
		show.consigneeShow = false
		return data
	}
	// if (show.orderStateShow){
	// 	// @ts-ignore
	// 	data = data.filter(item => item.order_status == formInline.orderState)
	// 	show.orderStateShow = false
	// 	return data
	// }


	if (!show.orderNumberShow){
		data = [...tableData]
	}
		return data
})

// 时间戳转换为日期格式
// @ts-ignore
function TimestampToDate(Timestamp) {
	let date = new Date(Timestamp * 1000);
	let y = date.getFullYear();
	let m = date.getMonth() + 1;
	let d = date.getDate();
	return y + "-" + (m < 10 ? "0" + m : m) + "-" + (d < 10 ? "0" + d : d) + " " + date.toTimeString().substr(0, 8);
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
				<el-button>重置</el-button>
				<el-button type="primary" @click="onSubmit">查询搜索</el-button>
			</div>
		</div>
		<div class="search-box">
			<el-form :inline="true" :model="formInline" class="demo-form-inline">
				<el-form-item label="输入搜索">
					<el-input v-model="formInline.orderNumber" clearable placeholder="订单编号"/>
				</el-form-item>
				<el-form-item label="收货人">
					<el-input v-model="formInline.consignee" clearable placeholder="收货人姓名/手机号码"/>
				</el-form-item>
				<el-form-item label="提交时间">
					<el-date-picker v-model="formInline.date" clearable placeholder="请选择时间" type="date"/>
				</el-form-item>
				<el-form-item label="订单状态">
					<el-select v-model="formInline.orderState" clearable placeholder="全部">
						<el-option label="待发货" value="待发货"/>
						<el-option label="已发货" value="已发货"/>
						<el-option label="已收货" value="已收货"/>
						<el-option label="已关闭" value="已关闭"/>
					</el-select>
				</el-form-item>
				<el-form-item label="订单分类">
					<el-select v-model="formInline.orderClassify" clearable placeholder="全部">
						<el-option label="微信" value="微信"/>
						<el-option label="支付宝" value="支付宝"/>
						<el-option label="未支付" value="未支付"/>
					</el-select>
				</el-form-item>
				<el-form-item label="订单来源">
					<el-select v-model="formInline.orderSource" clearable placeholder="全部">
						<el-option label="APP" value="APP"/>
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
		<el-table :data="newTableData" border fit max-height="450" style="width: 100%; text-align: center;">
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
				<el-button>查看订单</el-button>
				<el-button type="danger">删除订单</el-button>
			</el-table-column>
		</el-table>
	</div>
	</div>
</template>

<style lang="scss" scoped>
.box{
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
</style>