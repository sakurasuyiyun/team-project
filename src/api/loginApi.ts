import normalRequest from "@/utils/normalRequest";

export const login = ():RQ<Login> => normalRequest.get('/api/login',{
    params: {
        // 测试
        username: 'admin',
        password: '12345678'
    }
})