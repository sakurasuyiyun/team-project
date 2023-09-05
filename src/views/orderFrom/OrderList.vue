<script lang="ts" setup>
import {reactive , ref} from 'vue'

const formInline = reactive({
	orderNumber: '',
	consignee: '',
	date: '',
	orderState: '',
	orderClassify: '',
	orderSource: ''
})

const onSubmit = () => {
	console.log(formInline)
}


</script>

<template>
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
						<el-option label="Zone one" value="shanghai"/>
						<el-option label="Zone two" value="beijing"/>
					</el-select>
				</el-form-item>
				<el-form-item label="订单分类">
					<el-select v-model="formInline.orderClassify" clearable placeholder="全部">
						<el-option label="Zone one" value="shanghai"/>
						<el-option label="Zone two" value="beijing"/>
					</el-select>
				</el-form-item>
				<el-form-item label="订单来源">
					<el-select v-model="formInline.orderSource" clearable placeholder="全部">
						<el-option label="Zone one" value="shanghai"/>
						<el-option label="Zone two" value="beijing"/>
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

</template>

<style lang="scss" scoped>
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