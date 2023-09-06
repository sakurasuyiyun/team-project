<script lang="ts" setup>
import {computed, onMounted, reactive, ref} from 'vue'
import {orderSales} from "@/api/orderApi";
import {useLoginStore} from "@/stores/loginStore";
import {ElMessage} from "element-plus";

let tableData: orderSales = reactive([{
	order_num: '1',
	username: 'test',
	create_at: '123231123123',
	order_status: '已发货',
	payment: '支付宝',
	order_from: 'APP订单'
}])
const isShow = ref(false)
const formInline = reactive({
	token: useLoginStore().get(),
	order_num: '',
	username: '',
	create_at: '',
	order_status: '',
	payment: '',
	order_from: ''
})

onMounted(() => {
	orderSales().then(res => {
		console.log(res)
	}).catch(err => {
		console.log(err)
	})
})

const newTableData = computed(()=>{
	return tableData
})

</script>

<template>
	<div class="box">
		<!-- 面包屑导航栏	-->
		<el-breadcrumb class="breadcrrumb" separator="/">
			<el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
			<el-breadcrumb-item :to="{path:'/orderlist'}">订单</el-breadcrumb-item>
			<el-breadcrumb-item>退货处理申请</el-breadcrumb-item>
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
					<el-button type="primary">查询搜索</el-button>
				</div>
			</div>
			<div class="search-box">
				<el-form :inline="true" :model="formInline" class="demo-form-inline">
					<el-form-item label="输入搜索">
						<el-input v-model="formInline.order_num" clearable placeholder="服务单号"/>
					</el-form-item>
					<el-form-item label="处理状态">
						<el-select v-model="formInline.order_status" clearable placeholder="全部">
							<el-option label="已完成" value="已完成"/>
							<el-option label="已拒绝" value="已拒绝"/>
							<el-option label="退货中" value="退货中"/>
						</el-select>
					</el-form-item>
					<el-form-item label="申请时间">
						<el-date-picker v-model="formInline.create_at" clearable placeholder="请选择时间" type="date"/>
					</el-form-item>
					<el-form-item label="操作人员">
						<el-select v-model="formInline.order_status" clearable placeholder="全部">
							<el-option label="待发货" value="待发货"/>
							<el-option label="已发货" value="已发货"/>
							<el-option label="已收货" value="已收货"/>
							<el-option label="已关闭" value="已关闭"/>
						</el-select>
					</el-form-item>
					<el-form-item label="处理时间">
						<el-date-picker v-model="formInline.create_at" clearable placeholder="请选择时间" type="date"/>
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
				<el-table-column align="center" label="服务单号" prop="_id" width="60"/>
				<el-table-column align="center" label="申请时间" prop="order_num" width="200"/>
				<el-table-column align="center" label="用户账号" prop="username"/>
				<el-table-column align="center" label="退款金额" prop="order_price"/>
				<el-table-column align="center" label="申请状态" prop="payment"/>
				<el-table-column align="center" label="处理时间" prop="order_from"/>
				<el-table-column align="center" label="操作" width="220">
					<template #default="scope">
						<el-button>查看订单</el-button>
						<el-button type="danger">删除订单</el-button>
					</template>
				</el-table-column>
			</el-table>
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
</style>