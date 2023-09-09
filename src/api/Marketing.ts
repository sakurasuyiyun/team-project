import http from "@/utils/HomePageRequest"
import {get, post} from "@/utils/normalRequest"

// 品牌推荐
export const recommend = (): RQ<Api, Register> => get('/api/getShopRecommend')
// 商品推荐
export const productRecommend = (): RQ<Api, Register> => get('/api/getProductRecommend')
// 广告列表
export const advertisement = (): RQ<Api, Register> => get('/api/getAdvertisement')
// 添加广告
export const addAdvertisement = (data: object): RQ<Api, Register> => post('/api/addAdvertisement')
export const uploadAdvertisment = (): RQ<Api, Register> => post('/api/uploadAdvertisment')
// 删除广告
export const delAddvertisment = (obj: object) => post('/api/delAddvertisment', obj)
// export const delAddvertisment = (token,id):RQ<Api,Register> => post('/api/delAddvertisment')
//上传图片
export const uploadFile = (formData: any) => post('/api/uploadFile', formData, "multipart/form-data")

export const delCoupons = (obj: object) => post('/api/delCoupons', obj)

export const delShopRecomend = (obj: object) => post('/api/delShopRecomend', obj)