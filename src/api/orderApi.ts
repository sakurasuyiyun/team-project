// @ts-ignore
import {get, post} from "@/utils/normalRequest";



export const orderList = () => get('/api/getOrderList', {})

export const orderSearch = (obj:search) => post('/api/orderSearch\n', obj)

export const orderRemove = (obj:object) => post('/api/delOrderList\n',obj)