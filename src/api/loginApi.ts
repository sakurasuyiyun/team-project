import {get,post} from "@/utils/normalRequest";

export const login = (username: string,password: string):RQ<Api,Login> => get('/api/login',{
    username: username,
    password: password
})

export const register = ():RQ<Api,Register> => post('/api/register',{
    username: 'admin',
    password: '12345678'
})