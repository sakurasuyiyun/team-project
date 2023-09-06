<template>
    <div id="resource">
        <div class="pageselect">
            <span>首页</span> / 
            <span>权限</span> / 
            资源列表
        </div>
        
        <!-- 资源列表主体 -->
        <el-table :data="ResourceData1.value" style="width: 100%" >
            <el-table-column prop="_id" label="编号" header-align="center" align="center" width="130"/>
            <el-table-column prop="assets_name" label="资源名称" header-align="center" align="center"  />
            <el-table-column prop="assets_url" label="资源路径" header-align="center" align="center"  />
            <el-table-column prop="assets_desc" label="描述" header-align="center" align="center"  />
            <el-table-column prop="assets_create_at" label="添加数据" header-align="center" align="center"  />
        </el-table>

        <!-- 底部 -->
        <div class="demo-pagination-block">
         <!-- <div class="demonstration">All combined</div> -->
         <el-pagination
            v-model:current-page="currentPage4"
            v-model:page-size="pageSize4"
            :page-sizes="[10, 20, 30, 40]"
            :small="small"
            :disabled="disabled"
            :background="background"
            layout="total, sizes, prev, pager, next, jumper"
            :total="DataCount"
            @size-change="handleSizeChange"
            @current-change="handleCurrentChange"
         />
       </div>
    </div>
</template>

<script setup lang="ts">
    import { ref } from 'vue'

    const currentPage1 = ref(5)
    const currentPage2 = ref(5)
    const currentPage3 = ref(5)
    const currentPage4 = ref(4)
    const pageSize2 = ref(10)
    const pageSize3 = ref(10)
    const pageSize4 = ref(10)
    const small = ref(false)
    const background = ref(true)
    const disabled = ref(false)

    const handleSizeChange = (val: number) => {
      console.log(`${val} items per page`)
    }
    const handleCurrentChange = (val: number) => {
      console.log(`current page: ${val}`)
    }

    const tableData = [
        {
          _id: '1',
          resource_name: 'Tom',
          resource_path: 'No. 189, Grove St, Los Angeles',
          desc: '',
          create_time: '2020-02-04 09:04:55',
        },
        {
          _id: '2',
          resource_name: 'Tom',
          resource_path: 'No. 189, Grove St, Los Angeles',
          desc: '',
          create_time: '2020-02-04 09:04:55',
        },
        {
          _id: '3',
          resource_name: 'Tom',
          resource_path: 'No. 189, Grove St, Los Angeles',
          desc: '',
          create_time: '2020-02-04 09:04:55',
        },
        {
          _id: '4',
          resource_name: 'Tom',
          resource_path: 'No. 189, Grove St, Los Angeles',
          desc: '',
          create_time: '2020-02-04 09:04:55',
        },
        {
          _id: '5',
          resource_name: 'Tom',
          resource_path: 'No. 189, Grove St, Los Angeles',
          desc: '',
          create_time: '2020-02-04 09:04:55',
        },
        {
          _id: '6',
          resource_name: 'Tom',
          resource_path: 'No. 189, Grove St, Los Angeles',
          desc: '',
          create_time: '2020-02-04 09:04:55',
        },
        {
          _id: '7',
          resource_name: 'Tom',
          resource_path: 'No. 189, Grove St, Los Angeles',
          desc: '',
          create_time: '2020-02-04 09:04:55',
        },
        {
          _id: '8',
          resource_name: 'Tom',
          resource_path: 'No. 189, Grove St, Los Angeles',
          desc: '',
          create_time: '2020-02-04 09:04:55',
        },
        {
          _id: '9',
          resource_name: 'Tom',
          resource_path: 'No. 189, Grove St, Los Angeles',
          desc: '',
          create_time: '2020-02-04 09:04:55',
        },
        {
          _id: '10',
          resource_name: 'Tom',
          resource_path: 'No. 189, Grove St, Los Angeles',
          desc: '',
          create_time: '2020-02-04 09:04:55',
        },
        {
          _id: '11',
          resource_name: 'Tom',
          resource_path: 'No. 189, Grove St, Los Angeles',
          desc: '',
          create_time: '2020-02-04 09:04:55',
        },
        {
          _id: '12',
          resource_name: 'Tom',
          resource_path: 'No. 189, Grove St, Los Angeles',
          desc: '',
         create_time: '2020-02-04 09:04:55',
        },
  
    ]


    const formatDate=(date, fmt) =>{
    	if (/(y+)/.test(fmt)) {
    		fmt = fmt.replace(RegExp.$1, (date.getFullYear() + '').substr(4 - RegExp.$1.length))
      }
    	let o = {	
    		'M+': date.getMonth() + 1,
    		'd+': date.getDate(),
    		'h+': date.getHours(),
    		'm+': date.getMinutes(),
    		's+': date.getSeconds()
    	}
    	for (let k in o) {
    		if (new RegExp(`(${k})`).test(fmt)) {
    			let str = o[k] + ''
    			fmt = fmt.replace(RegExp.$1, (RegExp.$1.length === 1) ? str : padLeftZero(str))
    		}
    	}
      console.log("fmt",fmt);
      
    	return fmt
    }
    const padLeftZero=(str)=> {
	   return ('00' + str).substr(str.length)
    }

     import {api} from '../api/api'
    import {onMounted,reactive,ref } from 'vue'
    import {getAssetsList} from '@/api/AuthorApi'
    import type { FormInstance, FormRules } from 'element-plus'
    let ResourceData: any = reactive<Array<any>>([])
    let ResourceData1: any = reactive<Array<any>>([])
    let DataCount:any=  reactive<Number<any>>([]) 
    
     onMounted(() => {
      getAssetsList().then(res => {
        console.log(res);
       ResourceData.value=res;
      ResourceData1.value=ResourceData.value.data;
      DataCount=ResourceData1.value.length;
        console.log("ResourceData",ResourceData.value.data);
        console.log("ResourceData1.value",ResourceData1.value);
        ResourceData1.value.forEach(function (item,index) {
          console.log("item,index");
          console.log(item,index);
          console.log(item.assets_create_at);
          // let time = parseInt(item.assets_create_at);
          // let times = timestampToTime(time);
        
          // console.log("times",times);
          let time=Number(item.assets_create_at*1000);
          
          const dt = new Date(time);
          console.log("dt",dt);
          
          console.log(time);
            formatDate(dt, 'yyyy-MM-dd ')
          // formatDateTime(dt);
          item.assets_create_at= formatDate(dt, 'yyyy-MM-dd hh:mm:ss')
          console.log("item.assets_create_at",item.assets_create_at);
        
        });
        console.log("ResourceData1.value",ResourceData1.value);
        
      }).catch(err => {
        console.log(err);
      })
    })


</script>

<style lang="scss"  scoped>
        #recourse{
          width: 1182px;
            // height:800px;
          position:relative;
          // background-color:red;
        }
      .pageselect{
            font-size: 16px;
            color: #616367;
            margin-bottom:20px;
           
        }
        .pageselect span{
            color: #000;
            font-weight: bold;
        }
         .demo-pagination-block {
          margin-top: 22px;
              margin-left: 724px;
        }
        .demo-pagination-block .demonstration {
          margin-bottom: 16px;
        }
</style>