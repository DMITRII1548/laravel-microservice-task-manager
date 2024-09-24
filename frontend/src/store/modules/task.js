import API from '../../api'

const state = {
    tasks: []
}

const actions = {
    getTasks({ commit }) {
        API.get('/users/tasks')
            .then(res => {
                commit('setTasks', res.data)
            })
    },

    destroyTask({ dispatch }, id) {
        API.delete(`/users/tasks/${id}`)
            .then(res => {
                dispatch('getTasks')
            })
    }
}

const getters = {
    tasks: state => state.tasks
}

const mutations = {
    setTasks(state, tasks) {
        state.tasks = tasks
    },
}

export default {
    state,
    actions,
    getters,
    mutations
}
