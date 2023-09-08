
import { get, post } from "@/utils/normalRequest";

// 获取秒杀活动列表
export const getFKList = (): RQ<Api, Login> => get('/api/getSeckillList')


// {
//   token: String
//   title: String
//   activeState: Number
//   activeStartTime: String
//   activeEndTime: String
//   isActive: Number
// }
// 添加秒杀活动列表
export const addFKItem = (obj: object): RQ<Api, Login> => post('/api/addSeckillList', obj)

// {
//   token: String
//   id: Number
// }
// 删除秒杀活动列表
export const deleteFKItem = (obj: object): RQ<Api, Login> => post('/api/delSeckillList', obj)