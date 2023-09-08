<script lang="ts" setup>
import {computed, onMounted, reactive, ref, watch} from 'vue'
import {ElMessage, ElMessageBox} from "element-plus";

const input = reactive<object>({
	input1: 4,
	input2: 4,
	input3: 4,
	input4: 4,
	input5: 4
})



const open = () => {
	ElMessageBox.alert('确定作此更改吗？', '确认提交', {
		confirmButtonText: '提交',
		callback: (action: Action) => {
			ElMessage({
				type: 'success',
				message: `提交成功`,
			})
			console.log(input)
		},
	})
}

</script>

<template>
	<div class="box">
		<!-- 面包屑导航栏	-->
		<el-breadcrumb class="breadcrrumb" separator="/">
			<el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
			<el-breadcrumb-item :to="{path:'/orderlist'}">订单</el-breadcrumb-item>
			<el-breadcrumb-item>订单设置</el-breadcrumb-item>
		</el-breadcrumb>
		<el-divider/>

		<!-- 页面主体 -->
		<div class="banner-box">
			<div class="mt-4">
				<div class="text">秒杀订单超过：</div>
				<el-input v-model="input.input1" class="input" placeholder="时间" size="large">
					<template #append>分</template>
				</el-input>
				<div>未付款，订单自动关闭</div>
			</div>
			<div class="mt-4">
				<div class="text">正常订单超过：</div>
				<el-input v-model="input.input2" class="input" placeholder="时间" size="large">
					<template #append>分</template>
				</el-input>
				<div>未付款，订单自动关闭</div>
			</div>
			<div class="mt-4">
				<div class="text">发货超过：</div>
				<el-input v-model="input.input3" class="input" placeholder="时间" size="large">
					<template #append>天</template>
				</el-input>
				<div>未收货，订单自动完成</div>
			</div>
			<div class="mt-4">
				<div class="text">订单完成超过：</div>
				<el-input v-model="input.input4" class="input" placeholder="时间" size="large">
					<template #append>天</template>
				</el-input>
				<div>自动结束交易,不能申请售后</div>
			</div>
			<div class="mt-4">
				<div class="text">订单完成超过：</div>
				<el-input v-model="input.input5" class="input" placeholder="时间" size="large">
					<template #append>天</template>
				</el-input>
				<div>自动五星好评</div>

			</div>
			<el-button class="btn" type="primary" @click="open">提交</el-button>
		</div>
	</div>
</template>

<style lang="scss" scoped>
.banner-box {
	padding: 30px 0;
	margin: 100px auto;
	width: 80%;
	height: 500px;
	border: 1px solid #dcdfe6;

	.btn {
		width: 200px;
		height: 50px;
		margin: 0 auto;
		display: flex;
		justify-content: center;
	}
}

.mt-4 {
	display: flex;
	align-items: center;
	margin: 40px auto;
	width: 60%;

	.input {
		width: 300px;
		margin: 0 10px;
	}

	.text {
		width: 120px;
		text-align: right;
	}
}
</style>