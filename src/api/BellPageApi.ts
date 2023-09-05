
import {get,post} from "@/utils/normalRequest";

// 获取秒杀活动列表
export const getFKList = (): RQ<Api,Login> => get('/api/getSeckillList')