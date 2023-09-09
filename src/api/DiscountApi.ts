
import { get, post } from "@/utils/normalRequest";

// 获取优惠券列表
export const getCouponList = (): RQ<Api, Login> => get('/api/getCoupons')

// 添加优惠券
// {
//   token: String                 用户token
//   couponName: String            优惠券内容
//   couponType: String            优惠券来源
//   couponProduct: String         适用商品
//   couponSill: String            使用门槛
//   couponPrice: String ｜ Number 优惠券金额
//   couponScope: String           优惠券适用范围（客户端）
//   couponStart: String           优惠券开始时间
//   couponEnd: String             优惠券结束时间
//   couponStatus: Number          优惠券是否启用
// }
export const addCouponList = (obj: object): RQ<Api, Login> => post('/api/addCoupons', obj)

// 删除优惠券
// {
//   token: String                 用户token
//   id: Number                    优惠券ID
// }
export const deleteCouponList = (obj: object): RQ<Api, Login> => post('/api/delCoupons', obj)