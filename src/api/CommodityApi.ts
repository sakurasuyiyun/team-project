import {get,post} from "@/utils/normalRequest";

//  获取所有商品分类
export const getCategory = () => get('/api/getCategories')
//获取所有商品
export const getGoods = () => get('/api/getProducts')
//获取所有店铺
export const getShops = () => get('/api/getShops')

//增加分类
export const addProductCategory = (obj:object) => post('/api/addProductCategory',obj)
//删除分类
export const delProductCategory = (obj:object) => post('/api/delProductCategory',obj)

//删除商品
export const delProduct = (obj:object) => post('/api/delProduct',obj)
//添加商品
export const addProduct = (obj:object) => post('/api/addProducts',obj)

//添加品牌
export const addShop = (obj:object) => post('/api/addShop',obj)
//删除品牌
export const delShop = (obj:object) => post('/api/delShop',obj)

//上传图片
export const uploadFile = (formData:any) => post('/api/uploadFile',formData,"multipart/form-data")

//编辑商品
//编辑图片
export const editProductImg = (formData:any,productId?: string) => post(`/api/editProductImg?productId=${productId}`,formData,"multipart/form-data")

export const editProduct = (obj:object) => post('/api/editProduct',obj)
