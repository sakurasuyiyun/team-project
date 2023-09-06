
import { get, post } from "@/utils/normalRequest";

// 获取秒杀活动列表
export const getFKList = (): RQ<Api, Login> => get('/api/getSeckillList')

// 添加秒杀活动列表
export const addFKList = (token:String,title:String,activeState:Number,activeStartTime:String,activeEndTime:String,isActive:Number): RQ<Api, Login> => post('/api/addSeckillList', {
  token: token,
})