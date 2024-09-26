import API from '../../api'

const state = {
    tasks: [],
    taskErrors: {
        title: '',
        content: '',
        tags: ''
    },
    isLoadingTask: false
}

const actions = {
    getTasks({ commit }) {
        API.get('/users/tasks')
            .then(res => {
                commit('setTasks', res.data)
            })
    },

    async storeTask({ dispatch, commit }, data) {
        commit('setIsloadingTask', true)

        commit('setTitleTaskError', '')
        commit('setContentTaskError', '')
        commit('setTagsTaskError', '')

        return API.post('/users/tasks', {
            title: data.title,
            content: data.content,
            tags: data.tags
        })
            .then(res => {
                commit('setIsloadingTask', false)
                dispatch('getTasks')
                return res
            })
            .catch(e => {
                commit('setIsloadingTask', false)

                if (e.response.data.error.errors?.title) commit('setTitleTaskError', e.response.data.error.errors.title[0])
                if (e.response.data.error.errors?.content) commit('setContentTaskError', e.response.data.error.errors.content[0])
                if (e.response.data.error.errors?.tags) commit('setTagsTaskError', e.response.data.error.errors.tags[0])
                
                throw e
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
    tasks: state => state.tasks,

    titleTaskError: state => state.taskErrors.title,
    contentTaskError: state => state.taskErrors.content,
    tagsTaskError: state => state.taskErrors.tags,
    isLoadingTask: state => state.isLoadingTask
}

const mutations = {
    setTasks(state, tasks) {
        state.tasks = tasks
    },

    setTitleTaskError(state, error) {
        state.taskErrors.title = error
    },

    setContentTaskError(state, error) {
        state.taskErrors.content = error
    },

    setTagsTaskError(state, error) {
        state.taskErrors.tags = error
    },

    setIsloadingTask(state, value) {
        state.isLoadingTask = value
    }
}

export default {
    state,
    actions,
    getters,
    mutations
}
