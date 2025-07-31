
import router from '../router';
import {fetchLogin, fetchSignUp} from "../data/api";
import {getToken} from "../data/localStorage";
        
const state = {
	isLoggedIn: !!getToken(),
	pending: false,
	showPassword: false,
	password: '',
	username: '',
	mode:'login',
	error : '',
	path : '/orders'
};

// getters
const getters = {
   isLoggedIn: state => {
       return state.isLoggedIn
   }
};

// actions
const actions = {

    loginOrSignUp({dispatch,state}) {
       if (state.mode === 'login') {
            dispatch('login')
        } else {
            dispatch('signUp')
        }
    },
    login({commit}) {
        commit('login');
        fetchLogin().then(jsonResponse => {
            saveToken(jsonResponse.headers.authorization);
            commit('loginSuccess');
            router.push('/')
        });
    },
    logout({commit}) {
        commit('logout');
        removeToken();
        router.push('/login')
    },
    signUp({commit}) {
        commit('login')
        fetchSignUp().then(jsonResponse => {
            commit('loginSuccess');
            commit('loginMode');
        })
    }
};

// mutations
const mutations = {
   ['login'](state) {
        state.pending = true;
    },
    ['loginSuccess'](state) {
        state.isLoggedIn = true;
        state.pending = false;
    },
    ['logout'](state) {
        state.isLoggedIn = false;
    },
    ['setUsername'](state, username) {
        state.username = username;
    },
    ['setPassword'](state, password) {
        state.password = password;
    },
    ['togglePassword'](state) {
        state.showPassword = !state.showPassword;
    },
    ['loginMode'](state) {
        state.mode = 'login';
    },
    ['signUpMode'](state) {
        state.mode = 'signUp';
    },
    ['toggleMode'](state) {
    state.mode = state.mode === 'signUp'? 'login': 'signUp';
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
