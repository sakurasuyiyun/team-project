<script setup lang="ts">
import {ref} from 'vue'
import {login} from "@/api/loginApi";

const username = ref('')
const password = ref('')

const Login = () => {
	login(username,password).then(res => {
		
		console.log(res)
	}).catch(err => {
		console.log(err)
	})
}


</script>

<template>
	<div class="login-box">
		<div class="login">
			<div class="title">后台管理系统</div>
			<el-input
				v-model="username"
				class="w-50 m-2"
				placeholder="请输入账号"
				prefix-icon="User"
				size="large"
			/>
			<el-input
				v-model="password"
				class="w-50 m-2"
				placeholder="请输入密码"
				prefix-icon="Lock"
				type="password"
				size="large"
			/>

			<el-button type="success" size="large" color="#506278" class="btn" @click="Login">登录</el-button>
		</div>


	</div>
</template>

<style lang="scss" scoped>
.login-box {
	display: flex;
	justify-content: center;
	align-items: center;
	height: 100vh;
	background-color: #304156;
}

.login {
	width: 300px;
	height: 220px;
	display: flex;
	flex-wrap: wrap;
	align-content: space-between;
	text-align: center;
	justify-content: center;

	.btn {
		width: 300px;
	}

	.title {
		text-align: center;
		color: #fff;
		font-size: 28px;
		font-weight: bold;

	}
}
</style>