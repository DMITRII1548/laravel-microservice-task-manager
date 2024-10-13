import axios from "axios"
import router from './router/index.js'

const api = axios.create({
    baseURL: 'http://127.0.0.1:8000/api/', 
})

api.interceptors.request.use(config => {
        if (localStorage.getItem('token')) {
            config.headers.authorization = `Bearer ${localStorage.getItem('token')}`
        }

        return config
    }, error => {
})

// start response

api.interceptors.response.use({}, error => {
    if (error.response.status === 401) {
        localStorage.setItem('token', '')
        router.push({ name: 'sing_in' })
    }
})

// end response

export default api