import API from '../../api'

const state = {
    profile: null,
    errors: {
        image: '',
        name: '',
        surname: '',
        patronymic: '',
        age: ''
    },
    isLoadingProfile: false
}

const actions = {
    getProfile({commit}) {
        API.get('/users/profile')
            .then(res => {
                commit('setProfile', res.data)
            })
    },

    async storeProfile({commit}, data) {
        commit('setIsLoadingProfile', true)
        const headers = {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
        }

        return API.post('/users/profile', data, headers)
            .then(res => {
                commit('setProfile', res.data.data)
                commit('setIsLoadingProfile', false)
                return res
            })
            .catch(e => {
                commit('setIsLoadingProfile', false)

                if (e.response.data.error.errors?.image) commit('setImageProfileError', e.response.data.error.errors.image[0])
                if (e.response.data.error.errors?.name) commit('setNameProfileError', e.response.data.error.errors.name[0])
                if (e.response.data.error.errors?.surname) commit('setSurnameProfileError', e.response.data.error.errors.surname[0])
                if (e.response.data.error.errors?.patronymic) commit('setPatronymicProfileError', e.response.data.error.errors.patronymic[0])
                if (e.response.data.error.errors?.age) commit('setAgeProfileError', e.response.data.error.errors.age[0])

                throw e
            })
    },

    async destroyProfile({commit}) {
        commit('setIsLoadingProfile', true)

        return API.delete('/users/profile')
            .then(res => {
                commit('setIsLoadingProfile', false)
                commit('setProfile', null)
                return res
            })
            .catch(e => {
                commit('setIsLoadingProfile', false)
                throw e
            })
    }
}

const getters = {
    profile: state => state.profile,
    imageProfileError: state => state.errors.image,
    nameProfileError: state => state.errors.name,
    surnameProfileError: state => state.errors.surname,
    patronymicProfileError: state => state.errors.patronymic,
    ageProfileError: state => state.errors.age,
    isLoadingProfile: state => state.isLoadingProfile
}

const mutations = {
    setProfile(state, profile) {
        state.profile = profile
    },
    setImageProfileError(state, error) {
        state.errors.image = error
    },
    setNameProfileError(state, error) {
        state.errors.name = error
    },
    setSurnameProfileError(state, error) {
        state.errors.surname = error
    },
    setPatronymicProfileError(state, error) {
        state.errors.patronymic = error
    },
    setAgeProfileError(state, error) {
        state.errors.age = error
    },
    setIsLoadingProfile(state, isLoading) {
        state.isLoadingProfile = isLoading
    }
}

export default {
    state,
    actions,
    getters,
    mutations
}
