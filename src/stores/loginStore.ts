import {defineStore} from 'pinia'

const getLocalStorage = (key: string) => {
    const data = localStorage[key]
    if (data) {
        return JSON.parse(localStorage[key])
    } else {
        return;
    }

}

const setLocalStorage = (key: string, value: string) => {
    return localStorage.setItem(key, JSON.stringify(value))
}

export const useLoginStore = defineStore('login', () => {
    const get = () => {
        return getLocalStorage('token')
    }

    const set = (payload: string) => {
        return setLocalStorage('token', payload)
    }

    return {
        get,
        set
    }
})

export const useisShowStore = defineStore('isShow', () => {
    const get = () => {
        return getLocalStorage('isShow')
    }

    const set = (payload: string) => {
        return setLocalStorage('isShow', payload)
    }

    return {
        get,
        set
    }
})