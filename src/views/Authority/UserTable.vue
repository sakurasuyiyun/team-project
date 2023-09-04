<template>
     <div class="pageselect">
            <span>首页</span> / 
            <span>权限</span> / 
            用户列表
        </div>
        <div class="seacrcbigbox wrappper">
            <div class="searchtop">
                <div class="searchtitle">
                    <el-icon style="vertical-align: middle">
                        <Search />
                    </el-icon>
                    <span>搜索</span>
                </div>
                <button class="reset">重置</button>
            </div>
             <div class="searchbottom">
                <div class="searchbox">
                    <span>账号/姓名:</span>
                    <input type="text" class="search">
                </div>
                <button class="btn-search">搜索</button>
            </div>
        </div>
        <div class="addusertable wrappper">
            <button class="btn-add">添加</button>
        </div>

        <!-- <div class="tablebox wrappper">
            <table>
                <thead>
                  <tr>
                    <th>编号</th>
                    <th>账号</th>
                    <th>姓名</th>
                    <th>邮箱</th>
                    <th>添加时间</th>
                    <th>最后登录</th>
                    <th>是否启用</th>
                    <th>操作</th>

                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>行1，列1</td>
                    <td>行1，列2</td>
                    <td>行1，列3</td>
                    <td>行1，列4</td>
                    <td>行1，列5</td>
                    <td>行1，列6</td>
                    <td>行1，列7</td>
                    <td>行1，列8</td>
                  </tr>
                  <tr>
                    <td>行2，列1</td>
                    <td>行2，列2</td>
                    <td>行2，列3</td>
                    <td>行2，列4</td>
                    <td>行2，列5</td>
                    <td>行2，列6</td>
                    <td>行2，列7</td>
                    <td>行2，列8</td>
                  </tr>
                </tbody>
            </table>
        </div> -->
        <!-- 表格 -->
       <template>
  <el-table :data="tableData" style="width: 800px">
    <el-table-column prop="date" label="Date" width="180" height="50" />
    <el-table-column prop="name" label="Name" width="180" height="50"/>
    <el-table-column prop="address" label="Address" />
  </el-table>
</template>
        <!-- <pre>{{tableData}}</pre> -->
</template>

<script setup lang="ts">
import {onMounted,reactive} from 'vue'
    const tableData = [
  {
    date: '2016-05-03',
    name: 'Tom',
    address: 'No. 189, Grove St, Los Angeles',
  },
  {
    date: '2016-05-02',
    name: 'Tom',
    address: 'No. 189, Grove St, Los Angeles',
  },
  {
    date: '2016-05-04',
    name: 'Tom',
    address: 'No. 189, Grove St, Los Angeles',
  },
  {
    date: '2016-05-01',
    name: 'Tom',
    address: 'No. 189, Grove St, Los Angeles',
  },
]
onMounted(() => {
    console.log(tableData);
})
</script>

<style lang="scss" scoped>
        .pageselect{
            font-size: 12px;
            color: #a9abb1;
        }
        .pageselect span{
            color: #000;
            font-weight: bold;
        }
        .seacrcbigbox{
           
            height: 150px;
            /* border: 1px solid black; */
            box-shadow: 0 0 5px 1px rgba(158,179,193,.5);
            margin-top:10px;
            overflow:hidden;
             border-radius:2px
        }
        .wrappper{
            // width: 1064px;
            width: 1182px;
            
        }
        .searchtop{
            width: 96%;
            height: 30px;
            // background-color: antiquewhite;
            margin: auto;
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
        }
        .reset{
            background-color:#ffffff;
            border:1px solid #e6e7ec;
            padding:6px 15px;
            border-radius:4px;
            box-shadow: 0 0 0px 0.5px #e6e7ec;
        }
        .searchbottom{
            width: 96%;
            height: 30px;
            // background-color: antiquewhite;
            margin: auto;
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
        }
        .searchbottom .searchbox input{
            width:200px;
            border:1px solid #dddfe5;
            border-radius:4px;
            padding:5px 5px;
            outline:none;
        }
        button{
            cursor:pointer;
        }
        .btn-search{
            background-color:#5a9cf8;
            color:white;
            border:1px solid #5a9cf8;
            border-radius:4px;
            padding:6px 15px;
        }
        .addusertable{
            height:70px;
            background-color:white;
             box-shadow: 0 0 5px 1px rgba(158,179,193,.5);
            margin-top:25px;
            overflow:hidden;
            border-radius:2px

        }
        .btn-add{
            background-color:#5a9cf8;
            color:white;
            border:1px solid #5a9cf8;
            border-radius:4px;
            padding:6px 15px;
            margin-top:19px;
            float:right;
            margin-right:22px
        }
        // .tablebox{
        //     height:600px;
        //     background-color:pink;
        //     margin-top:25px;
        //     //  box-sizing: border-box;
        // }
        // .tablebox table{
        //     width:100%;
        // }
        // .tablebox table thead tr th{
        //     padding:0px;
        //     border:1px solid black;
        //     // border-left:0px;
        // }
</style>