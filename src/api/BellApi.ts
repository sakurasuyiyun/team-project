
import { get, post } from "@/utils/normalRequest";

// 获取秒杀活动列表
export const getFKList = (): RQ<Api, Login> => get('/api/getSeckillList')

// 添加秒杀活动
// {
//   token: String            用户token
//   title: String            秒杀标题
//   activeState: Number      是否推荐/是否开始
//   activeStartTime: String  活动开始时间
//   activeEndTime: String    活动结束时间
//   isActive: Number         状态
// }
export const addFKItem = (obj: object): RQ<Api, Login> => post('/api/addSeckillList', obj)

// 删除秒杀活动
// {
//   token: String            用户token
//   id: Number               秒杀活动ID
// }
export const deleteFKItem = (obj: object): RQ<Api, Login> => post('/api/delSeckillList', obj)