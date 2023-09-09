<template>
	<div class="recommend" name="recommend">
		<el-breadcrumb class="breadcrrumb" separator="/">
			<el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
			<el-breadcrumb-item :to="{ path: '/Recommendedgoods' }">营销</el-breadcrumb-item>
			<el-breadcrumb-item>新品推荐</el-breadcrumb-item>
		</el-breadcrumb>
		<div class="seach-box">
			<div class="top">
				<div>
					<!-- <el-icon><Search /></el-icon> -->
					<span><el-icon>
                            <Search/>
                        </el-icon></span>
					<span>筛选搜索</span>
				</div>

				<div class="top-a">
					<div v-for="(item, index) in datarepeat" :class="`${state == index ? 'con' : ''}`"
					     @click="repeat(index)">
						{{ item }}
					</div>
				</div>
			</div>
			<div class="bottom">
				<div>
					<span>品牌名称:</span>
					<div>
						<input v-model="inputvalue" placeholder="品牌名称" type="text">
					</div>
				</div>

				<div>
					<span>推荐状态:</span>
					<div class="cascader-a">
						<el-select v-model="value" placeholder="全部">
							<el-option v-for="(item, index) in options_a" :key="index" :label="item.label"
							           :value="item.label"/>
						</el-select>
					</div>
				</div>
			</div>
		</div>

		<div class="list">
			<div>数据列表</div>
		</div>

		<div class="listData" style=" height: 350px;  overflow-y: auto;">
			<table style=" width: 100%;  border-spacing: 0;
             border-left:1px solid #e6e6e6 ;
             border-top: 1px solid #e6e6e6;
             color: #626262 ;
             ">

				<tr>
					<th class="column-width-a"><input type="checkbox"></th>
					<th class="column-width-b">编号</th>
					<th class="column-width-c">商品名称</th>
					<th class="column-width-d">是否推荐</th>
					<th class="column-width-e">排序</th>
					<th class="column-width-f">状态</th>
					<th class="column-width-g">操作</th>
				</tr>

				<tr v-for="(item, index) in recommendData_show_new">
					<th class="column-width-a"><input type="checkbox"></th>
					<th class="column-width-b">{{ item._id }}</th>
					<th class="column-width-c">{{ item.shop_name }}</th>
					<th class="column-width-d">
						<el-switch v-if="item.isRecommend" v-model="val" disabled/>
						<el-switch v-if="!item.isRecommend" v-model="val_a" disabled/>
					</th>
					<th class="column-width-e">{{ item.sort }}</th>
					<th class="column-width-f">{{ item.isRecommend ? '推荐中' : '未推荐' }}</th>
					<th class="column-width-g">
						<span @click="dalete(index)">删除</span>
					</th>
				</tr>
			</table>
		</div>

		<!-- <div>
				<div class="cascader-b">
						<el-cascader placeholder="批量操作" />
						<span class="confirm">确定</span>
				</div>
		</div> -->

		<div v-if="isShow" class="sorter">
			<div>
				共{{ recommendData_show.length }}条
			</div>
			<div class="cascader-c">
				<el-select v-model="value_a" placeholder="5条/页">
					<el-option v-for="(item, index) in options" :key="index" :label="item.label" :value="item.label"/>
				</el-select>
			</div>

			<div>
				<el-pagination :current-page="currentPage" :page-size="pageCount" :total="recommendData_show.length"
				               background layout="prev, pager, next" @current-change="handlePageChange"/>
			</div>

			<div>
				<span>前往</span>
				<input v-model="input_page_cont" class="last-inp" type="text" @keydown="keydown">
				页
			</div>
		</div>

	</div>
</template>

<script lang="ts" setup>
import {reactive, ref, watch, onMounted} from "vue";
import {useRouter} from "vue-router";
import {recommend} from "../../api/Marketing";
import {ElMessage} from "element-plus";


let val = ref(true)
let val_a = ref(false)
const isShow = ref(false)
// 控制开关变量
// 拿到数据 (备份)
let recommendData = ref<any>(null)
// 显示数据（显示）
let recommendData_show = ref<any>(null)
// 最终展示数据
let recommendData_show_new = ref<any>(null)
//每页展示几条数据
let pageCount = ref<any>(5)
// 组件展示第几页(高亮)
let currentPage = ref<any>(1)
//点击第几页
let clickCount = ref<any>(null)
//输入框value
let inputvalue = ref<any>(null)
// 选择状态
let value = ref('')
// 多少条每页
let value_a = ref('')
let input_page_cont = ref<any>(1)
const modules = () => {
	recommend().then((res) => {
		console.log(res, 'xxx');
		recommendData.value = res.data
		recommendData_show.value = res.data
		recommendData_show_new.value = res.data.slice(0, pageCount.value)
		isShow.value = true
	})
}

onMounted(() => {
	modules()
})


// 删除数据
const dalete = (index: number) => {
	 ElMessage({
		 type:'success',
		 message:'删除成功'
	 })
	recommendData_show_new.value.splice(index, 1)
}

// 查询，重置
const state = ref(1)
const datarepeat = ref(['重置', '搜索'])
const repeat = (index: number) => {
	// 重置
	if (index === 0) {
		recommendData_show.value = recommendData.value
		recommendData_show_new.value = recommendData_show.value.slice(0, pageCount.value)
		inputvalue.value = ''
		value.value = '全部'
	}

	// 查询
	if (index === 1 && isShow.value === true) {
		let state: any = null
		if (value.value === '推荐中') {
			state = true
		} else if (value.value === '未推荐') {
			state = false
		} else {
			state = -1
		}
		// console.log(recommendData.value);

		recommendData_show.value = recommendData.value.filter(function (item: any) {
			return item.shop_name.includes(inputvalue.value) && (state == -1 ? true : state == item.isRecommend) || (inputvalue.value == null && (state == -1 ? true : state == item.isRecommend))
		})
		recommendData_show_new.value = recommendData_show.value.slice(0, pageCount.value)
	}
}

// 点击显示相应页数内容
const handlePageChange = (page: number) => {
	currentPage.value = page
	clickCount.value = page
	input_page_cont.value = page
	recommendData_show_new.value = recommendData_show.value.slice((page - 1) * pageCount.value, (page - 1) * pageCount.value + pageCount.value)
}

// input输入回车前往几页（输入的数字）

const keydown = (event: any) => {
	if (event.keyCode === 13) {
		// 阻止默认的回车提交行为
		event.preventDefault();
		// 在此处执行回车后的操作
		currentPage.value = parseInt(input_page_cont.value)

	}
}

// 多少条每页
watch(value_a, (newValue, oldValue) => {
	pageCount.value = parseInt(value_a.value.slice(0, 1))
	recommendData_show_new.value = recommendData_show.value.slice(0, pageCount.value)
});

const options_a: any = [
	{

		label: '未推荐',

	},
	{

		label: '推荐中',

	},
	{
		label: '全部',
	},

]

// 下拉数据
const options: any = [
	{

		label: '9条/每页',

	},
	{

		label: '7条/每页',

	},
	{

		label: '5条/每页',

	},
	{

		label: '3条/每页',

	},
]

</script>

<style lang="scss" scoped>
* {
	margin: 0;
	padding: 0;

}

.top-a {
	:nth-child(2) {
		border: 1px solid rgb(255, 255, 255);
	}

	:nth-child(1) {
		border: 1px solid rgb(255, 255, 255);
	}

	:nth-child(2):hover {
		background-color: #51b8f8;
		cursor: pointer;
	}

	:nth-child(1):hover {
		//   background-color: #51b8f8;
		border: 1px solid #51b8f8;
		cursor: pointer;

	}
}

input:focus {
	border: 1px solid rgb(64, 158, 255) !important;
	/* 在获取焦点时将边框颜色设置为红色 */
}

.breadcrrumb {
	margin-bottom: 15px;
}

.recommend {
	position: relative;
}

.seach-box input {
	outline: none;
	box-shadow: 0 0 1px 1px #e6e6e6;
	border: 1px solid rgb(255, 255, 255);
	height: 20px;
	width: 150px;
	color: #b1b1b1;
	text-indent: 10px;
}

.seach-box input:hover {
	border: 1px solid rgb(230, 230, 230);
}

.nav {
	font-size: 12px;
	margin-bottom: 5px;
}

tr:hover {
	background-color: #f6f6f6;
	font-size: 12px;
}

.cascader-c ::v-deep .el-input__wrapper {
	width: 75px;
	height: 25px;
}

.cascader-b ::v-deep .el-input__wrapper {
	width: 190px;
	height: 25px;
}

.cascader-a ::v-deep .el-input__wrapper {
	width: 70px;
	height: 25px;
	text-indent: 20px;
}

.seach-box {
	width: 100%;
	height: 120px;
	color: #494949;
	// background-color: aqua;
	// border: 1px solid red;
	box-shadow: 0 0 3px 2px #e6e6e6;
	padding-top: 20px;
	padding-left: 18px;
	font-size: 14px;
	box-sizing: border-box;

	.top {
		width: 98%;

		display: flex;
		justify-content: space-between;

		:nth-child(1) {
			display: flex;

			span {
				margin-right: 8px;
			}
		}

		:nth-child(2) {
			display: flex;

			div {
				width: 45px;
				height: 22px;
				line-height: 22px;
				font-size: 13px;
				text-align: center;
				box-shadow: 0 0 1px 1px #e6e6e6;
				margin-left: 5px;
				display: flex;
				justify-content: center;
				border-radius: 5px;
			}
		}
	}

	.bottom {
		margin-top: 20px;
		display: flex;
		align-items: center;
		color: #494949;

		> :nth-child(1) {
			display: flex;
			align-items: center;

			// margin-left: 8px;
			> span {
				font-size: 12px;
				margin-right: 8px;
			}
		}

		> :nth-child(2) {
			// margin-left: 8px;
			display: flex;
			align-items: center;

			> span {
				font-size: 12px;
				margin-right: 8px;
			}
		}
	}
}

.list {
	width: 100%;
	height: 70px;
	box-shadow: 0 0 3px 2px #e6e6e6;
	margin-top: 20px;
	display: flex;
	align-items: center;
	justify-content: space-between;
	padding: 0 20px 0 20px;
	box-sizing: border-box;
	margin-bottom: 15px;

	:nth-child(1) {
		font-size: 13px;

	}

	:nth-child(2) {
		font-size: 13px;
		width: 70px;
		text-align: center;
		height: 26px;
		line-height: 26px;
		box-shadow: 0 0 1px 1px #e6e6e6;
		border-radius: 4px;
	}
}

// 表格
.column-width-a {
	width: 35px;
	font-size: 14px !important;
	font-weight: normal !important;
	height: 45px;
	border-right: 1px solid #e6e6e6;
	border-bottom: 1px solid #e6e6e6;

	> span {
		color: #1c9eef;
		margin-left: 30px;
	}

}

.column-width-b {
	width: 120px;
	height: 35px;
	font-size: 14px !important;
	font-weight: normal !important;
	border-right: 1px solid #e6e6e6;
	border-bottom: 1px solid #e6e6e6;

	> span {
		color: #1c9eef;
		margin-left: 30px;
	}

}

.column-width-c {
	width: 160px;
	height: 35px;

	font-size: 14px !important;
	font-weight: normal !important;
	border-right: 1px solid #e6e6e6;
	border-bottom: 1px solid #e6e6e6;

	> span {
		color: #1c9eef;
		margin-left: 30px;
	}

}

.column-width-d {
	width: 160px;
	height: 35px;
	font-size: 14px !important;
	font-weight: normal !important;
	border-right: 1px solid #e6e6e6;
	border-bottom: 1px solid #e6e6e6;

	> span {
		color: #1c9eef;
		margin-left: 30px;
	}

}

.column-width-e {
	width: 120px;
	height: 35px;
	font-size: 14px !important;
	font-weight: normal !important;
	border-right: 1px solid #e6e6e6;
	border-bottom: 1px solid #e6e6e6;

	> span {
		color: #1c9eef;
		margin-left: 30px;
	}

}

.column-width-f {
	width: 140px;
	height: 30px;
	font-size: 14px !important;
	font-weight: normal !important;
	border-right: 1px solid #e6e6e6;
	border-bottom: 1px solid #e6e6e6;

	> span {
		color: #1c9eef;
		margin-left: 30px;
	}

}

.column-width-g {
	width: 300px;
	height: 30px;
	font-size: 14px !important;
	font-weight: normal !important;
	border-right: 1px solid #e6e6e6;
	border-bottom: 1px solid #e6e6e6;

	> span {
		color: #1c9eef;

	}

}

.confirm {
	display: inline-block;
	width: 60px;
	height: 25px;
	font-size: 13px;
	text-align: center;
	line-height: 25px;
	color: #ffffff;
	background-color: #1c9eef;
	border-radius: 4px;
	margin-left: 5px;
}

.sorter {
	margin-top: 15px;
	display: flex;
	align-items: center;
	// padding-left: 45%;
	// margin-right: 15px;
	position: absolute;
	right: 0;
	box-sizing: border-box;

	> :nth-child(1) {
		font-size: 12px;
		color: #757373;
		margin-right: 12px;
	}

	> :nth-child(2) {
		// font-size: 12px;
		color: #757373;
		margin-right: 12px;
	}

	> :nth-child(4) {
		margin-left: 12px;
		font-size: 12px;
		color: #757373;

		> input {
			text-align: center;
			height: 25px;
			width: 55px;
			border: none;
			outline: none;
			margin: 0 8px;
			box-shadow: 0 0 1px 1px #d8d8d8;
		}
	}
}

.con {
	background-color: #1c9eef;
	color: rgb(255, 255, 255);
}
</style>