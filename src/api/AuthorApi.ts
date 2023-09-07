import {get} from "@/utils/normalRequest"
import {post} from "@/utils/normalRequest"

//  获取获取用户列表  GET
export const getUserTable = async (): RQ<Api,Author> => await get('/api/getUserLists',{})
//  获取获取资源列表  GET
export const getAssetsList = (): RQ<Api,Author> => get('/api/getAssetsList',{})
//  获取获取用户权限列表  GET
export const getUserAuthor = (): RQ<Api,Author> => get('/api/getUserAuthor',{})
//  获取获取用户权限列表  GET
export const addUser = (data:object): RQ<Api,Author> => post('/api/addUser',data)
//  获取获取用户权限列表  GET
export const editUserAuthor = (data:object): RQ<Api,Author> => post('/api/editUserAuthor',data)
//  获取获取用户权限列表  GET
export const editUserInfo = (data:object): RQ<Api,Author> => post('/api/editUserInfo',data)
//  获取获取用户权限列表  GET
export const activateUsers = (data:object): RQ<Api,Author> => post('/api/activateUsers',data)

// {
//     token: "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImtpZCI6ImxvZ2luIn0.eyJpc3MiOiJzYWt1cmFfc3V5aXl1biIsInVzZXJuYW1lIjoiYWRtaW4iLCJuYmYiOjE2OTM5MTM5MTIsImV4cCI6MTY5NDAwMDMxMiwiYXV0aG9yaXR5IjoxfQ.i2tBMqZDpeZxvp7QbmmZQF3rKaHULw-RbpGBJw3gbLY",
//     userName:ruleForm.username,
//     password:ruleForm.password,
//     isEnable:ruleForm.resource
// }