
import router from '../router';
        
const state = {
//    isLoggedIn: !!getToken(),
//    pending: false,
//    showPassword: false,
//    password: '',
//    username: ''
};

// getters
const getters = {
//    isLoggedIn: state => {
//        return state.isLoggedIn
//    }
};

// actions
const actions = {

//    login({commit}) {
//        commit('login');
//        fetchLogin().then(jsonResponse => {
//            saveToken(jsonResponse.headers.authorization);
//            commit('loginSuccess');
//            router.push('/')
//        });
//    },
//    logout({commit}) {
//        return new Promise((resolve, reject) => {
//            commit('logout');
//            removeToken();
//            resolve()
//        })
//    }
};

// mutations
const mutations = {
//    ['login'](state) {
//        state.pending = true;
//    },
//    ['loginSuccess'](state) {
//        state.isLoggedIn = true;
//        state.pending = false;
//    },
//    ['logout'](state) {
//        state.isLoggedIn = false;
//    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
