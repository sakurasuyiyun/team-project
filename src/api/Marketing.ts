import http from "@/utils/HomePageRequest"
import {get} from "@/utils/normalRequest"

// 品牌推荐
export const recommend = ():RQ<Api,Register> => get('/api/getShopRecommend')
// 商品推荐
export const productRecommend = ():RQ<Api,Register> => get('/api/getProductRecommend')
// 广告列表
export const advertisement = ():RQ<Api,Register> => get('/api/getAdvertisement')
