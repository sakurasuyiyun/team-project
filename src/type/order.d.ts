type orderList = Array<object>

// 搜索严格模式
interface search {
  token: string,
  order_num?: string,
  username?: string,
  create_at?: string | Number,
  order_status?:string,
  payment?:string,
  order_from?:string
}
// 删除严格模式
interface remove {
  token:string,
  orderId:string
}