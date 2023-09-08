// @ts-ignore
import {get, post} from "@/utils/normalRequest";


export const orderList = () => get('/api/getOrderList', {})

export const orderSearch = (obj: search) => post('/api/orderSearch', obj)

export const orderRemove = (obj: object) => post('/api/delOrderList', obj)

export const orderSales = () => get('/api/getReturnList', {})

export const orderSalesReturn = () => get('/api/getReturnReason', {})

export const addOrderSalesReturn = (obj: object) => post('/api/addReturnReason', obj)

export const SalesReturnSearch = (obj: object) => post('/api/orderReturnSearch', obj)

export const removeOrderSalesReturn = (obj: object) => post('/api/delReturnReason', obj)

export const changeSalesReturn = (obj: object) => post('/api/changeReturnReasonStatus', obj)

export const editSalesReturn = (obj: object) => post('/api/editReturnReason', obj)