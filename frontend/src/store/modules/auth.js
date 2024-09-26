import router from "../../router/index"
import API from '../../api'

const state = {
    authErrors: {
        name: '',
        email: '',
        password: '',
    },

    isLoading: false
}

const actions = {
    login({ commit }, data) {
        commit('setEmailError', '')
        commit('setPasswordError', '')
        commit('setIsLoading', true)
        
        API.post('/login', {
            email: data.email,
            password: data.password
        })
            .then(res => {
                localStorage.setItem('token', res.data.token)
                router.push({ name: 'dashboard' })
            })
            .catch(e => {
                if(e.status === 401) commit('setEmailError', 'Your email or password are wrong')
                if (e.response.data.errors?.email) commit('setEmailError', e.response.data.errors.email[0])
                if (e.response.data.errors?.password) commit('setPasswordError', e.response.data.errors.password[0])
            })
            .finally(() => {
                commit('setIsLoading', false)
            })
    },

    register({ commit, dispatch }, data) {
        commit('setNameError', '')
        commit('setEmailError', '')
        commit('setPasswordError', '')
        commit('setIsLoading', true)
        
        API.post('/register', {
            name: data.name,
            email: data.email,
            password: data.password
        })
            .then(res => {
                dispatch('login', {
                    email: data.email,
                    password: data.password
                })
            })
            .catch(e => {
                if(e.status === 401) commit('setEmailError', 'Your email or password are wrong')
                
                if (e.response.data.errors?.name) commit('setNameError', e.response.data.errors.name[0])
                if (e.response.data.errors?.email) commit('setEmailError', e.response.data.errors.email[0])
                if (e.response.data.errors?.password) commit('setPasswordError', e.response.data.errors.password[0])
            
                commit('setIsLoading', false)
            })
    } 
}

const getters = {
    nameError: state => state.authErrors.name,
    emailError: state => state.authErrors.email,
    passwordError: state => state.authErrors.password,
    isLoading: state => state.isLoading
}

const mutations = {
    setNameError(state, error) {
        state.authErrors.name = error
    },

    setEmailError(state, error) {
        state.authErrors.email = error
    },

    setPasswordError(state, error) {
        state.authErrors.password = error
    },

    setIsLoading(state, isLoading) {
        state.isLoading = isLoading
    }
}

export default {
    state,
    actions,
    getters,
    mutations
}
