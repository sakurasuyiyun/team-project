// @ts-ignore
import {get, post} from "@/utils/normalRequest";


export const orderList = () => get('/api/getOrderList', {})

export const orderSearch = (obj: search) => post('/api/orderSearch', obj)

export const orderRemove = (obj: object) => post('/api/delOrderList', obj)

export const orderSales = () => get('/api/getReturnList', {})

export const orderSalesReturn = () => get('/api/getReturnReason',{})

export const addOrderSalesReturn = (obj:object) => post('/api/addReturnReason',obj)

