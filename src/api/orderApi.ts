// @ts-ignore
import {get, post} from "@/utils/normalRequest";

export const orderList = () => get('/api/getOrderList', {})

export const orderSearch = (obj:search) => post('/api/orderSearch\n', obj)