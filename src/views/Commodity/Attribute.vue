<template>
    <div>
        
        <el-breadcrumb>
          <el-breadcrumb-item>首页</el-breadcrumb-item>
          <el-breadcrumb-item>商品</el-breadcrumb-item>
          <el-breadcrumb-item>属性管理</el-breadcrumb-item>
        </el-breadcrumb>
        <div class="list-box">
            <div class="title"><el-icon class="icon"><Tickets /></el-icon>数据列表</div>
            <div class="btn">添加</div>
        </div>
        <el-table  :data="tableData" height="500" border  style="width: 100%">
          <el-table-column align="center" prop="date" label="编号" />
          <el-table-column align="center" prop="name" label="类型名称" />
          <el-table-column align="center" prop="number" label="属性数量" />
          <el-table-column align="center" prop="parameterNum" label="参数数量" />
          <el-table-column align="center" prop="setUp" label="设置" >
            <el-button size="small">属性列表</el-button>
            <el-button size="small">参数列表</el-button>
          </el-table-column>
          <el-table-column align="center" prop="operate" label="操作" >
             <el-button size="small" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
             <el-button
               size="small"
               type="danger"
               @click="handleDelete(scope.$index, scope.row)"
               >删除</el-button>
          </el-table-column>
        </el-table>
        <div class="demo-pagination-block">
          <el-pagination
            v-model:current-page="currentPage4"
            v-model:page-size="pageSize4"
            :page-sizes="[10, 20, 30, 40]"
            :small="small"
            :disabled="disabled"
            :background="background"
            layout="total, sizes, prev, pager, next, jumper"
            :total="40"
            @size-change="handleSizeChange"
            @current-change="handleCurrentChange"
          />
        </div>
    </div>
</template>

<script setup lang="ts">
import { Timer } from '@element-plus/icons-vue'
import { ref } from 'vue'

interface User {
  date: string
  name: string
  number:number
  parameterNum:number
  sort:number
}

const tableData = [
  {
    date: '01',
    name: '服饰',
    number:10,
    parameterNum:0,
    sort:1,
  },
]

</script>

<style lang="scss" scoped>
.list-box{
    margin-top: 10px;
    height: 50px;
    box-shadow: 0 0 5px rgba(170,170,170, .9);
    display: flex;
    line-height: 50px;
    justify-content: space-between;
    .title{
        margin-left: 10px;
        display: flex;
        .icon{
            display: block;
            margin-top: 18px;
            margin-right: 5px;
        }
    }
    .btn{
        height: 30px;
        margin-right: 10px;
        line-height: 30px;
        width: 50px;
        text-align: center;
        margin-top: 10px;
        box-shadow: 0 0 2px rgba(170,170,170, .9);
    }
}
.demo-pagination-block{
    margin-top: 10px;
    display: flex;
    justify-content: flex-end;
}
</style>