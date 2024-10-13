import { createStore } from 'vuex';
import auth from './modules/auth'
import task from './modules/task'
import profile from './modules/profile'

export default createStore({
    modules: {
        auth,
        task,
        profile
    }
})
