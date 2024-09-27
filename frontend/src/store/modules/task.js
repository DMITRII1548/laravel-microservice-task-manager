import API from '../../api'

const state = {
    tasks: [],
    task: {},
    taskErrors: {
        title: '',
        content: '',
        tags: ''
    },
    isLoadingTask: false,
    isUpdatingTask: false
}

const actions = {
    async getTasks({ commit, getters }, page = 1) {
        return API.get(`/users/tasks?page=${page}`)
            .then(res => {
                const existingTasks = getters.tasks

                let tasks = Array.from(new Set([...existingTasks, ...res.data]))
                tasks = tasks.filter((task, index, self) =>
                    index === self.findIndex(t => t.id === task.id)
                )

                commit('setTasks', tasks)

                return res
            })
            .catch(e => {
                throw e
            })
    },

    async getTask({ commit }, id) {
        return API.get(`/users/tasks/${id}`)
            .then(res => {
                commit('setTask', res.data)

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
    },

    async changeToNextStatusTask({ dispatch, getters, commit }, id) {
        commit('setIsUpdatingTask', true)
        return API.patch(`/users/tasks/${id}/status/next`)
            .then(async (res) => {
                await dispatch('getTask', id)

                const updatedTask = getters.task
                console.log(updatedTask)
                
                const tasks = getters.tasks.map(task => 
                    task.id === updatedTask.id ? updatedTask : task
                )

                commit('setTasks', tasks)
                commit('setIsUpdatingTask', false)

                return res
            })
            .catch(e => {
                commit('setIsUpdatingTask', false)
                throw e
            })
    },

    async changeToBackStatusTask({ dispatch, getters, commit }, id) {
        commit('setIsUpdatingTask', true)
        return API.patch(`/users/tasks/${id}/status/back`)
            .then(async (res) => {
                await dispatch('getTask', id)

                const updatedTask = getters.task
                console.log(updatedTask)
                
                const tasks = getters.tasks.map(task => 
                    task.id === updatedTask.id ? updatedTask : task
                )

                commit('setTasks', tasks)
                commit('setIsUpdatingTask', false)

                return res
            })
            .catch(e => {
                commit('setIsUpdatingTask', false)
                throw e
            })
    }
}

const getters = {
    tasks: state => state.tasks,
    task: state => state.task,

    titleTaskError: state => state.taskErrors.title,
    contentTaskError: state => state.taskErrors.content,
    tagsTaskError: state => state.taskErrors.tags,
    isLoadingTask: state => state.isLoadingTask,
    isUpdatingTask: state => state.isUpdatingTask
}

const mutations = {
    setTask(state, task) {
        state.task = task
    },

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
    },

    setIsUpdatingTask(state, value) {
        state.isUpdatingTask = value
    }
}

export default {
    state,
    actions,
    getters,
    mutations
}
