import Axios from 'axios'

const instance = Axios.create({
    baseURL: 'http://111.230.242.195/',
    timeout: 10000
})

export default instance