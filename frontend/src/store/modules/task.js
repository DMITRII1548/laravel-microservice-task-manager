import API from '../../api'

const state = {
    tasks: [],
    task: {},
    taskErrors: {
        title: '',
        content: '',
        tags: '',
        status: '',
        startedAt: '',
        finishedAt: ''
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

    async updateTask({ dispatch, commit, getters }, data) {
        commit('setIsUpdatingTask', true)
        data.startedAt = await dispatch('formatDate', data.startedAt)
        data.finishedAt = await dispatch('formatDate', data.finishedAt)

        

        return API.patch(`/users/tasks/${data.id}`, {
            title: data.title,
            content: data.content,
            tags: data.tags,
            status: data.status,
            created_at: data.startedAt,
            finished_at: data.finishedAt
        })
            .then(async res => {
                await dispatch('getTask', data.id)

                const updatedTask = getters.task
                const tasks = getters.tasks.map(task => 
                    task.id === updatedTask.id ? updatedTask : task
                )

                commit('setTasks', tasks)
                commit('setIsUpdatingTask', false)

                return res
            })
            .catch(e => {
                commit('setIsUpdatingTask', false)

                if (e.response.data.error.errors?.title) commit('setTitleTaskError', e.response.data.error.errors.title[0])
                if (e.response.data.error.errors?.content) commit('setContentTaskError', e.response.data.error.errors.content[0])
                if (e.response.data.error.errors?.tags) commit('setTagsTaskError', e.response.data.error.errors.tags[0])
                if (e.response.data.error.errors?.status) commit('setStatusTaskError', e.response.data.error.errors.status[0])
                if (e.response.data.error.errors?.started_at) commit('setStartedAtTaskError', e.response.data.error.errors.started_at[0])
                if (e.response.data.error.errors?.finished_at) commit('setFinishedAtTaskError', e.response.data.error.errors.finished_at[0])

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
    },

    formatDate({}, value) {
        if (!value) return null

        const date = new Date(value)
        
        if (isNaN(date.getTime())) {
            console.error('Invalid date:', value)
            return null
        }
    
        return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')} ${String(date.getHours()).padStart(2, '0')}:${String(date.getMinutes()).padStart(2, '0')}:00`
    }
}

const getters = {
    tasks: state => state.tasks,
    task: state => state.task,

    titleTaskError: state => state.taskErrors.title,
    contentTaskError: state => state.taskErrors.content,
    tagsTaskError: state => state.taskErrors.tags,
    statusTaskError: state => state.taskErrors.status,
    startedAtTaskError: state => state.taskErrors.startedAt,
    finishedAtTaskError: state => state.taskErrors.finishedAt,

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

    setStatusTaskError(state, error) {
        state.taskErrors.status = error
    },

    setstartedAtTaskError(state, error) {
        state.taskErrors.startedAt = error
    },

    setFinishedAtTaskError(state, error) {
        state.taskErrors.finishedAt = error
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
