import Axios from 'axios'

const instance = Axios.create({
    baseURL: 'http://tech.wolfcode.cn:8360',
    timeout: 10000
})

// instance.interceptors.request.use((config) => {
//         // 带上token
//         let token = localStorage.getItem('token')
//         if (token) {
//             config.headers = config.headers || {};
//             config.headers.Authorization = token;
//         }
//         return config
//     },
//     (error) => {
//         return Promise.reject(error)
//     }
// )

// instance.interceptors.response.use((res) => {
//     // 如果检测到token已经失效
//     if (res.data.code === 401) {
//         // 退出登录,强制用户重新登录
//     }
//     // 请求成功,但是业务失败
//     if (res.data.code !== 200) {
//         // ElMessage.error(res.data.message);
//     }
//     return res.data
// }, (error) => {
//     // console.log(error);
//     // 请求失败
//     error.code !== "ERR_CANCELED" && ElMessage.error(error.message);
//     return Promise.reject(error)
// })

export default instance;