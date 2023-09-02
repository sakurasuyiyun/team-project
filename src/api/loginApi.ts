import {get,post} from "@/utils/normalRequest";

export const login = ():RQ<Api,Login> => get('/api/login',{
    username: 'admin',
    password: '12345678'
})