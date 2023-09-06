<template>
    <div name="recommend" class="recommend">
        <div class="nav">
            <div><span>首页</span>  /  <span>营销</span>  /  <span>品牌推荐</span></div>
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
                    <span>品牌名称:</span>
                    <div>
                        <input type="text" placeholder="品牌名称">
                    </div>
                </div>

                <div>
                    <span>推荐状态:</span>
                    <div class="cascader-a">
                        <el-cascader placeholder="全部" />
                    </div>
                </div>
            </div>
        </div>

        <div class="list">
            <div>数据列表</div>
            <div>
                选择品牌
            </div>
        </div>

        <div class="listData">
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

                <tr  v-for="(item, index) in recommendData">
                    <th class="column-width-a"><input type="checkbox"></th>
                    <th class="column-width-b">{{item._id}}</th>
                    <th class="column-width-c">{{item.product_name}}</th>
                    <th class="column-width-d"><el-switch v-model="item.Recommend"/></th>
                    <th class="column-width-e">{{item.sort}}</th>
                    <th class="column-width-f">{{item.isRecommend ? '推荐中' :'未推荐'}}</th>
                    <th class="column-width-g">
                          <span>设置排序</span>
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
                共112条
            </div>
            <div class="cascader-c">
                <el-cascader placeholder="5条/页" :options="options" @change="change" />
            </div>

            <div>
                <el-pagination background layout="prev, pager, next" :total=" Math.ceil(recommendData.length/5)" />
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
import { productRecommend} from "../../api/Marketing";
// import { number } from "echarts";
// const value1 = ref(false)
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
// 控制开关变量
// const valueData = ref([])
// 拿到数据
const  recommendData = ref<any>(null)
    productRecommend().then((res) => {
    // console.log(res.data, '品牌推荐');
    
    recommendData.value = res.data
    console.log(recommendData.value , '新品推荐');
    
})

const datarepeat = ref(['重置', '搜索'])
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

.recommend{
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
.nav{
    font-size: 12px;
    margin-bottom: 5px;
}
tr:hover{
    background-color: #f6f6f6;
    font-size: 12px;
}
// .a{
//     width: 30px !important;
// }
.cascader-c ::v-deep .el-input__wrapper {
    width: 75px;
    height: 25px;
}

.cascader-b ::v-deep .el-input__wrapper {
    width: 190px;
    height: 25px;
}

.cascader-a ::v-deep .el-input__wrapper {
    width: 55px;
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
    >span{
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
    >span{
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
    >span{
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
    >span{
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
    >span{
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
    >span{
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
    >span{
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
    >:nth-child(1){
        font-size: 12px;
        color: #757373;
        margin-right: 12px;
    }
    >:nth-child(2){
        // font-size: 12px;
        color: #757373;
        margin-right: 12px;
    }
    >:nth-child(4){
        margin-left: 12px;
        font-size: 12px;
        color: #757373;
        >input{
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