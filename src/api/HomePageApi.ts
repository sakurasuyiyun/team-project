import http from "@/utils/HomePageRequest"
import {get} from "@/utils/normalRequest"

// 获取城市销量数据
export const getData = (): RSP<IHomeData> => http.get('/admin/stat')

//  获取销售饼图数据
export const getSalePie = (): RQ<Api,Login> => get('/api/getSalePie',{})
