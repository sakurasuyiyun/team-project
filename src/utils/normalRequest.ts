import Axios from 'axios'

const instance = Axios.create({
    baseURL: 'http://111.230.242.195',
    timeout: 10000
})

export const get = (url: string, params = {}) => {
    return new Promise((resolve, reject) => {
        instance.get(url, {params}).then((response) => {
            resolve(response.data)
        }, err => {
            reject(err)
        })
    })
}

export const post = (url: string, data = {}, requestHeader?: string) => {
    const header = requestHeader ? requestHeader : 'application/json';
    return new Promise((resolve, reject) => {
        instance.post(url, data, {
            headers: {
                'Content-Type': header
            }
        }).then((response) => {
            resolve(response.data)
        }, err => {
            reject(err)
        })
    })
}

export default instance