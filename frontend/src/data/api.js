import axios from "axios";
import {getToken} from "../data/localStorage";
import store from '../store/index';

export function fetchLogin() {
    let state = store.state.login;
    return post('/api/login', {
        username: state.username,
        password: state.password
    });
}

export function fetchSignUp() {
    let state = store.state.login;
    return post('/api/v1/sign-up', {
        username: state.username,
        password: state.password
    });
}


export function post(url,creds) {
    return axios.post(url, creds, {
            headers: {
                Authorization: getToken()
            }
        }
    );
}

export function get(url) {
    return axios.get(url, {
            headers: {
                Authorization: getToken()
            }
        }
    );
}
