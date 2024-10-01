import API from '../../api'

const state = {
    profile: null
}

const actions = {
    getProfile({commit}) {
        API.get('/users/profile')
            .then(res => {
                console.log(res.data)
                commit('setProfile', res.data)
            })
    }
}

const getters = {
    profile: state => state.profile
}

const mutations = {
    setProfile(state, profile) {
        state.profile = profile
    },
}

export default {
    state,
    actions,
    getters,
    mutations
}
