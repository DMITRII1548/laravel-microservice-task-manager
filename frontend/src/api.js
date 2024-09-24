import axios from "axios"

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

export default api