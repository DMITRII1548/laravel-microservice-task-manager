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
    async getTasks({ commit, getters }, page = 1) {
        return API.get(`/users/tasks?page=${page}`)
            .then(res => {
                const existingTasks = getters.tasks

                let tasks = Array.from(new Set([...existingTasks, ...res.data]))
                tasks = tasks.filter((task, index, self) =>
                    index === self.findIndex(t => t.id === task.id)
                );

                commit('setTasks', tasks)

                return res
            })
            .catch(e => {
                throw e
            })
    },

    async storeTask({ getters, commit }, data) {
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

                const tasks = getters.tasks
                tasks.unshift(res.data)

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

    destroyTask({ dispatch, commit }, id) {
        API.delete(`/users/tasks/${id}`)
            .then(res => {
                commit('setTasks', [])
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
