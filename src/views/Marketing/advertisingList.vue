<template>
    <div name="recommend" class="recommend">
        <div class="nav">
            <div><span>首页</span> / <span>营销</span> / <span>广告列表</span></div>
        </div>
        <div class="seach-box">
            <div class="top">
                <div>
                    <span><el-icon>
                            <Search />
                        </el-icon></span>
                    <span>筛选搜索</span>
                </div>

                <div>
                    <div v-for="(item, index) in datarepeat" @click="repeat(index)"
                        :class="`${state == index ? 'con' : ''}`">
                        {{ item }}</div>
                </div>
            </div>
            <div class="bottom">
                <div>
                    <span>广告名称:</span>
                    <div>
                        <input type="text" placeholder="广告名称">
                    </div>
                </div>

                <div>
                    <span>广告位置:</span>
                    <div class="cascader-a">
                        <el-cascader placeholder="pc首页轮播图" />
                    </div>
                </div>
                <div>
                    <span>到期时间:</span>
                    <div class="cascader-b">
                        <!-- <el-cascader placeholder="请选择时间" /> -->
                        <el-date-picker placeholder="请选择时间" />
                        <!-- <el-date-picker  type="datetime" placeholder="请选择时间" /> -->
                        <!-- <el-date-picker v-model="value1" type="datetime" placeholder="请选择时间" /> -->

                    </div>
                </div>
            </div>
        </div>

        <div class="list">
            <div>数据列表</div>
            <div>
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




                <tr v-for="(item, index) in recommendData">
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
                        <span>编辑</span>
                        <span>删除</span>
                    </th>
                </tr>

            </table>
        </div>

        <div>
            <div class="cascader-b">
                <el-cascader placeholder="批量操作" />
                <span class="confirm">确定</span>
            </div>
        </div>

        <div class="sorter">
            <div>
                共{{ recommendData.length }}条
            </div>
            <div class="cascader-c">
                <el-cascader placeholder="5条/页" :options="options" @change="change" />
            </div>

            <div>
                <el-pagination background layout="prev, pager, next" :total="Math.ceil(recommendData.length / 5)" />
            </div>

            <div>

                <span>前往</span>
                <input type="text">
                页
            </div>
        </div>

    </div>
</template>

<script setup lang="ts">
import { reactive, ref } from "vue";
import { advertisement } from "../../api/Marketing";
const value1 = ref(true)
// 下拉数据
const options: any = [
    {

        label: '列表一',

    },
    {

        label: '列表二',

    },
]
// 列表改变后触发
const change = () => {
    console.log('改变');

}
// 拿到数据
const recommendData = ref<any>(null)
advertisement().then((res) => {
    console.log(res);
    
    recommendData.value = res.data
    console.log(recommendData.value, '广告列表');
})

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



const datarepeat = ref(['重置', '查询搜索'])
const state = ref(1)
const repeat = (index: number) => {
    // state.value = index
    console.log(111);
}

</script>

<style lang="scss" scoped>
* {
    margin: 0;
    padding: 0;

}

.recommend {
    position: relative;
}

.seach-box input {
    outline: none;
    box-shadow: 0 0 1px 1px #e6e6e6;
    border: none;
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
        width: 100%;
        // height: ;
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
        margin-left: 30px;
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
}
</style>