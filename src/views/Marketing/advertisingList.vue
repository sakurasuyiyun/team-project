<template>
    <div name="recommend" class="recommend">
        <div class="mask" v-show='maskShow'>
            <!-- <input type="text" class="textinp" v-model="advStart"> -->
            <div> 广告名称：<input type="text" class="textinp" v-model="advName"></div>
            <div> 广告位置：<input type="text" class="textinp" v-model="advPosition"></div>
            <div> 开始时间：<el-date-picker v-model="advStart" clearable placeholder="请选择时间" /></div>
            <div> 结束时间：<el-date-picker v-model="advEnd" clearable placeholder="请选择时间" @blur="inpblur_b"/></div>
            <div> 上传图片：<input type="file" class="fileinp" @change="file"></div>
            <div></div>
            <p class="p">
                <span @click="out">取消</span>
                <span @click="up">确定</span>

                <!-- <el-button :plain="true" @click="open4">error</el-button> -->
            </p>

        </div>
        <el-breadcrumb class="breadcrrumb" separator="/">
            <el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
            <el-breadcrumb-item :to="{ path: '/advertisingList' }">营销</el-breadcrumb-item>
            <el-breadcrumb-item>广告列表</el-breadcrumb-item>
        </el-breadcrumb>
        <div class="seach-box">
            <div class="top">
                <div>
                    <span><el-icon>
                            <Search />
                        </el-icon></span>
                    <span>筛选搜索</span>
                </div>

                <div class="top-a">
                    <div v-for="(item, index) in datarepeat" @click="repeat(index)"
                        :class="`${state == index ? 'con' : ''}`">
                        {{ item }}</div>
                </div>
            </div>
            <div class="bottom">
                <div>
                    <span>广告名称:</span>
                    <div>
                        <input type="text" placeholder="广告名称" v-model="inputvalue">
                    </div>
                </div>

                <!-- <div>
                    <span>广告位置:</span>
                    <div class="cascader-a">
                        <el-cascader placeholder="pc轮播图" />
                    </div>
                </div> -->
                <div>
                    <span>到期时间:</span>
                    <div class="cascader-b">
                        <!-- <el-cascader placeholder="请选择时间" /> -->
                        <el-date-picker v-model="value1" clearable placeholder="请选择时间" />
                    </div>
                </div>
            </div>
        </div>

        <div class="list">
            <div>数据列表</div>
            <div @click="mask">
                添加广告
            </div>
        </div>

        <div class="listData">
            <table style=" width: 100%;  border-spacing: 0; 
             border-left:1px solid #e6e6e6 ;
             border-top: 1px solid #e6e6e6;
             color: #626262
             ">
                <tr>
                    <th class="column-width-a"><input type="checkbox"></th>
                    <th class="column-width-b">编号</th>
                    <th class="column-width-c">广告名称</th>
                    <th class="column-width-d">广告位置</th>
                    <th class="column-width-e">广告图片</th>
                    <th class="column-width-f">时间</th>
                    <th class="column-width-g">上线/下线</th>
                    <th class="column-width-h">点击次数</th>
                    <th class="column-width-i">生成订单</th>
                    <th class="column-width-j">
                        操作
                    </th>
                </tr>
                <tr v-for="(item, index) in recommendData_show_new">
                    <th class="column-width-a"><input type="checkbox"></th>
                    <th class="column-width-b">{{ item._id }}</th>
                    <th class="column-width-c">{{ item.adv_name }}</th>
                    <th class="column-width-d">
                        <!-- <img :src="item.adv_imgUrl" alt=""> -->
                        {{ item.adv_position }}
                    </th>
                    <th class="column-width-e">
                        <img :src="item.adv_imgUrl" alt="">

                    </th>
                    <th class="column-width-f">
                        <div><span>开始时间：</span><span>{{ time(item.adv_start_at) }}</span></div>
                        <div><span>到期时间：</span><span>{{ time(item.adv_end_at) }}</span></div>
                    </th>
                    <th class="column-width-g"><el-switch v-model="item.adv_isShow" /></th>
                    <th class="column-width-h">{{ item.adv_click_time }}</th>
                    <th class="column-width-i">0</th>
                    <th class="column-width-j">
                        <!-- <span>编辑</span> -->
                        <span @click="del(item._id)">删除</span>
                    </th>
                </tr>

            </table>
        </div>

        <div class="sorter" v-if="isShow">
            <div>
                共{{ recommendData_show.length }}条
            </div>
            <div class="cascader-c">
                <el-select v-model="value_a" placeholder="5条/页">
                    <el-option v-for="(item, index) in options" :key="index" :label="item.label" :value="item.label" />
                </el-select>
            </div>

            <div>
                <el-pagination background layout="prev, pager, next" :total="recommendData_show.length"
                    :page-size="pageCount" @current-change="handlePageChange" :current-page="currentPage" />
            </div>

            <div>

                <span>前往</span>
                <input type="text" v-model="input_page_cont" @keydown="keydown">
                页
            </div>
        </div>
        <el-empty description="无" v-if="!isShow"/>

    </div>
</template>

<script setup lang="ts">
import { reactive, ref, onMounted, watch } from "vue";
import { advertisement, delAddvertisment } from "../../api/Marketing";
import { useLoginStore } from '@/stores/loginStore'
import { post, get } from "@/utils/normalRequest";

const value1 = ref('')
const isShow = ref(false)
// 控制开关变量
// 拿到数据 (备份)
let recommendData = ref<any>([])
// 显示数据（显示）
let recommendData_show = ref<any>([])
// 最终展示数据
let recommendData_show_new = ref<any>([])
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

const inpblur_b = () => {
  if  (!(Math.floor(new Date(advStart.value).getTime() / 1000) < Math.floor(new Date(advEnd.value).getTime() / 1000))) {
      alert('开始时间不可以大于结束时间')
  }
}
// 拿到数据
const modules = () => {
    advertisement().then((res) => {
        recommendData.value = res.data
        recommendData_show.value = res.data
        // console.log(res.errno);
        
        if (res?.errno === 0) {
            isShow.value = true
            recommendData_show_new.value = res.data.slice(0, pageCount.value)
        }else{
            isShow.value = false
            recommendData_show_new.value = null
        }
    })
}

onMounted(() => {
    modules()
})

// 遮罩层
let maskShow = ref(false)
const mask = () => {
    maskShow.value = true
}

// 删除数据
let token = useLoginStore().get()
const del = (id: any) => {
    delAddvertisment({ token, id }).then((res) => {
        console.log('删除成功');
        modules()
    })
}

// 添加广告图片
let img = ref<any>(null) 
let advName = ref<any>(null)
let advPosition = ref<any>(null)
let advStart = ref<any>(null)
let advEnd = ref<any>(null)
const file = async (e: Event) => {
    const formData = new FormData()
    
    formData.append('file', e.target.files[0])
    const res = await post('/api/uploadAdvertisment', formData, "multipart/form-data")
    if (res?.errno === 0) {
        console.log("上传图片成功")
    }
    
}
// 取消
const out = () => {
    // console.log( advStart.value);
    // const dateString = advStart_r.value
    Math.floor(new Date(advStart.value).getTime() / 1000);

    console.log( Math.floor(new Date(advStart.value).getTime() / 1000));
    maskShow.value = false
}

// 添加广告内容
const up = async () => {

    const addProducts = await get('/api/addAdvertisement', {
        advName: advName.value,
        advPosition: advPosition.value,
        advStart: Math.floor(new Date(advStart.value).getTime() / 1000),
        advEnd:  Math.floor(new Date(advEnd.value).getTime() / 1000),
        token: useLoginStore().get()
    })

    if (addProducts?.errno === 0) {
        out()
        modules()
        console.log("添加商品成功")
    } else {
        out()
        alert('添加失败')
    }
}

// 时间格式化
const time = (data: any) => {
    const date = new Date(data * 1000); // 将时间戳转换为毫秒
    let year = date.getFullYear();
    let month: any = date.getMonth() + 1;
    month = month < 10 ? '0' + month : month
    let day: any = date.getDate();
    day = day < 10 ? '0' + day : day
    let hour: any = date.getHours();
    hour = hour < 10 ? '0' + hour : hour
    let minute: any = date.getMinutes();
    minute = minute < 10 ? '0' + minute : minute
    let second: any = date.getSeconds();
    second = second < 10 ? '0' + second : second
    return year + '-' + month + '-' + day + ' ' + hour + ':' + minute + ':' + second
}



const state = ref(1)
const datarepeat = ref(['重置', '搜索'])
const repeat = (index: number) => {
    
    
    // 重置
    if (index === 0) {
        recommendData_show.value = recommendData.value
        recommendData_show_new.value = recommendData_show.value.slice(0, pageCount.value)
        inputvalue.value = ''
    }

    // 查询
    if (index === 1 && isShow.value === true) {
        const dateString = value1.value;
        const date = new Date(dateString);
        const year = date.getFullYear();
        const month = date.getMonth() + 1; 
        const day = date.getDate();
        const formattedDate = `${year}-${month < 10 ? '0' + month : month}-${day < 10 ? '0' + day : day}`;
        
        console.log(inputvalue.value, formattedDate ,'bbbbbb');
        
        recommendData_show.value = recommendData.value.filter(function (item: any) {
            return  item.adv_name.includes(inputvalue.value) && time(item.adv_end_at).slice(0,10) == formattedDate || 
            (inputvalue.value == null && time(item.adv_end_at).slice(0,10) == formattedDate) 
            ||  item.adv_name.includes(inputvalue.value) &&  formattedDate == `NaN-NaN-NaN`
             
            
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
          recommendData_show_new.value = recommendData_show.value.slice((currentPage.value - 1) * pageCount.value, (currentPage.value - 1) * pageCount.value + pageCount.value)
    }
}

// 多少条每页
watch(value_a, (newValue, oldValue) => {
    pageCount.value = parseInt(value_a.value.slice(0, 1))
    recommendData_show_new.value = recommendData_show.value.slice(0, pageCount.value)
});

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
.top-a{
    :nth-child(2){
        border: 1px solid rgb(255, 255, 255);
    }
    :nth-child(1){
        border: 1px solid rgb(255, 255, 255);
    }
    :nth-child(2):hover{
      background-color: #51b8f8;
      cursor: pointer;
    }
    :nth-child(1):hover{
    //   background-color: #51b8f8;
    border: 1px solid #51b8f8;
      cursor: pointer;
      
    }
}

.p {
    display: flex;
    justify-content: space-around;

    >span {
        margin-left: 30px;
        margin-top: 50px;
        width: 55px;
        height: 30px;
        background-color: #1c9eef;
        color: #ffffff;
        border-radius: 4px;
        text-align: center;
        line-height: 30px;
        cursor: pointer;
    }

    // >:nth-child(2){
    //     width: 50px;
    //     height: 35px;
    // }
}



.mask {
    width: 500px;
    height: 400px;
    background-color: #ffffff;
    position: absolute;
    top: 100px;
    left: 50%;
    transform: translateX(-50%);
    box-shadow: 0px 0px 10px rgb(135, 134, 134);
    z-index: 2;

    >div {
        width: 100%;
        font-size: 14px;
        color: #757373;
        display: flex;
        justify-content: center;
        margin-top: 25px;

        >input {
            border: 1px solid rgb(216, 216, 216);
            outline: none;
            width: 220px;
            height: 30px;
            border-radius: 3px;
            overflow: hidden;
            background-color: #ffffff;
            text-indent: 20px;
        }
    }
    >:nth-child(5){
        >input{
            border: none;
        }
    }
}



//   <div class="el-input__wrapper" tabindex="-1"><!-- prefix slot --><!--v-if--><input class="el-input__inner" type="text" autocomplete="off" tabindex="0" placeholder="Please input" id="el-id-2061-6"><!-- suffix slot --><!--v-if--></div>
input:focus {
    border: 1px solid rgb(64, 158, 255) !important;
    /* 在获取焦点时将边框颜色设置为红色 */
}

.seach-box input:hover {
    border: 1px solid rgb(230, 230, 230);
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
    width: 125px;
    height: 25px;
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

            :nth-child(1) {
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

            :nth-child(2) {
                width: 65px;
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
        justify-content: space-around;

        >:nth-child(1) {
            display: flex;
            align-items: center;

            // margin-left: 8px;
            >span {
                font-size: 12px;
                margin-right: 8px;
            }
        }

        >:nth-child(2) {
            // margin-left: 8px;
            display: flex;
            align-items: center;

            >span {
                font-size: 12px;
                margin-right: 8px;
            }
        }

        >:nth-child(3) {
            // margin-left: 8px;
            display: flex;
            align-items: center;

            >span {
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
        cursor: pointer;
    }
}

// <li class="is-active number" aria-current="true" aria-label="第 1 页" tabindex="0"> 1 </li>
// ::v-deep li{
//     width: 26px;
//     height: 30px;
// }
// ::v-deep button {
//     width: 26px;
//     height: 30px;
// }
// 表格
.column-width-a {
    width: 35px;
    font-size: 14px !important;
    font-weight: normal !important;
    height: 45px;
    border-right: 1px solid #e6e6e6;
    border-bottom: 1px solid #e6e6e6;

    >span {
        color: #1c9eef;
        margin-left: 30px;
    }

}

.column-width-b {
    width: 90px;
    height: 35px;
    font-size: 14px !important;
    font-weight: normal !important;
    border-right: 1px solid #e6e6e6;
    border-bottom: 1px solid #e6e6e6;

    >span {
        color: #1c9eef;
        margin-left: 30px;
    }

}

.column-width-c {
    width: 90px;
    height: 35px;
    font-size: 14px !important;
    font-weight: normal !important;
    border-right: 1px solid #e6e6e6;
    border-bottom: 1px solid #e6e6e6;

    >span {
        color: #1c9eef;
        margin-left: 30px;
    }

}

.column-width-d {
    width: 100px;
    height: 35px;
    font-size: 14px !important;
    font-weight: normal !important;
    border-right: 1px solid #e6e6e6;
    border-bottom: 1px solid #e6e6e6;

    >span {
        color: #1c9eef;
        margin-left: 30px;
    }


}

.column-width-e {
    width: 200px;
    height: 35px;
    font-size: 14px !important;
    font-weight: normal !important;
    border-right: 1px solid #e6e6e6;
    border-bottom: 1px solid #e6e6e6;

    >span {
        color: #1c9eef;
        margin-left: 30px;
    }

    >img {
        width: 80%;

    }

}

.column-width-f {
    width: 250px;
    height: 30px;
    font-size: 14px !important;
    font-weight: normal !important;
    border-right: 1px solid #e6e6e6;
    border-bottom: 1px solid #e6e6e6;

    div {
        margin-bottom: 8px;
    }

}

.column-width-g {
    width: 80px;
    height: 30px;
    font-size: 14px !important;
    font-weight: normal !important;
    border-right: 1px solid #e6e6e6;
    border-bottom: 1px solid #e6e6e6;

    >span {
        color: #1c9eef;
        margin-left: 30px;
    }

}

.column-width-h {
    width: 80px;
    height: 30px;
    font-size: 14px !important;
    font-weight: normal !important;
    border-right: 1px solid #e6e6e6;
    border-bottom: 1px solid #e6e6e6;

    >span {
        color: #1c9eef;
        margin-left: 30px;
    }

}

.column-width-i {
    width: 80px;
    height: 30px;
    font-size: 14px !important;
    font-weight: normal !important;
    border-right: 1px solid #e6e6e6;
    border-bottom: 1px solid #e6e6e6;

    >span {
        color: #1c9eef;
        margin-left: 30px;
    }

}

.column-width-j {
    width: 120px;
    height: 30px;
    font-size: 14px !important;
    font-weight: normal !important;
    border-right: 1px solid #e6e6e6;
    border-bottom: 1px solid #e6e6e6;

    >span {
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

    >:nth-child(1) {
        font-size: 12px;
        color: #757373;
        margin-right: 12px;
    }

    >:nth-child(2) {
        // font-size: 12px;
        color: #757373;
        margin-right: 12px;
    }

    >:nth-child(4) {
        margin-left: 12px;
        font-size: 12px;
        color: #757373;

        >input {
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
}</style>