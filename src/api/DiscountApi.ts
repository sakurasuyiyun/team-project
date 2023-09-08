
import { get, post } from "@/utils/normalRequest";

// 获取优惠券列表
export const getCouponList = () => get('/api/getCoupons')

// 添加优惠券
// export const addFKList = (token: String, title: String, activeState: Number, activeStartTime: String, activeEndTime: String, isActive: Number): RQ<Api, Login> => post('/api/addSeckillList', {
//   token: token,
//   title: title,
//   activeState: activeState,
//   activeStartTime: activeStartTime,
//   activeEndTime: activeEndTime,
//   isActive: isActive,
// })