/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import './bootstrap'
import Vue from 'vue'
import Index from './index'

window.Vue = Vue;

import VueAuth from '@websanova/vue-auth'
import VueAxios from 'vue-axios'
import VueRouter from 'vue-router'
import auth from './auth'
import router from './router'
import axios from 'axios'
import jQuery from 'jquery'
import bootstrap from 'bootstrap'
import popper from 'popper.js'
Vue.router = router
Vue.use(VueRouter)

Vue.use(VueAxios, axios)
axios.defaults.baseURL = `${process.env.MIX_APP_URL}/api/`
// axios.defaults.baseURL = "http://spacehubtech-stockmanager.herokuapp.com/api"
Vue.use(VueAuth, auth)


import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
Vue.use(Loading, {
  canCancel: false,
  color: "orange", //28a745
  backgroundColor: "#fff",
  loader: "dots" // spinner, dots, bars
});

// Vue.component('index', Index)

window.numeral = require('numeral');
import Raphael from 'raphael/raphael'
global.Raphael = Raphael

import VueProgressBar from 'vue-progressbar'

const options = {
    color: '#ffc107',
    failedColor: 'red',
    thickness: '5px',
    transition: {
        speed: '0.1s',
        opacity: '0.6s',
        termination: 300
    },
    autoRevert: false,
    location: 'top',
    inverse: false
}
window.Fire = new Vue()
Vue.use(VueProgressBar, options)

import VueSweetalert2 from 'vue-sweetalert2';

const sweetOptions = {
    confirmButtonColor: '#41b882',
    cancelButtonColor: '#ff7674'
}

Vue.use(VueSweetalert2, sweetOptions)
import VueCookies from 'vue-cookies'
Vue.use(VueCookies)
VueCookies.config('7d')
import { Form, HasError, AlertError } from 'vform'

window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

import VueSession from 'vue-session'
Vue.use(VueSession, { persist: true })
const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))



Array.prototype.sum = function(prop) {
    var total = 0
    for (var i = 0, _len = this.length; i < _len; i++) {
        total = total + parseInt(this[i][prop])
    }
    return total
}

const app = new Vue({
    el: '#myDiv',
    router,
    data: {
        respond: '',
        VueProgressBar,
        VueSweetalert2,
        VueSession,
        VueCookies,
        donutOrders: {},
        error: '',
        token: '',
        user: {},
        form: new Form(),
        transactions: '',
        loader : undefined
    },
//     beforeCreate: function() {
//         this.$Progress.start()
// Vue.prototype.$session.start()
//         window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + Vue.prototype.$session.get('token')
//         if (!Vue.prototype.$session.exists() || window.axios.defaults.headers.common['Authorization'] == undefined) {
//             console.log('app routing to login');
//             this.$router.push('/login')
//         } else {
//             window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + Vue.prototype.$session.get('token')
//         }
//         var form = new Form()
//         form.get('./users/?id=1')
//             .then(response => {
//                 this.$Progress.finish()
//             })
//             .catch((error) => {
//                 if (error.response.status == 401) {
//                     this.$Progress.finish()
//                     this.$router.push("/login")

//                 }
//             })

//     },
    mounted() {
        // this.$nextTick(function () {
        // console.log('test');
        // })
    },
    created() {

        this.$Progress.start()
        // this.loader = this.$loading.show({});
        this.$router.beforeEach((to, from, next) => {
            if (to.meta.progress !== undefined) {
                let meta = to.meta.progress
                this.$Progress.parseMeta(meta)
            }
            this.$Progress.start()
            this.loader = this.$loading.show({});
            next()
        })
        this.$router.afterEach((to, from) => {
            this.$Progress.finish()
            if(this.loader){
                this.loader.hide();
            }
        })
        // })
        Fire.$on('user_login', (data) => {
            // this.loadUser(data);
        })
    },
    methods: {
        alert(type, title, message) {
            this.$swal({
                toast: true,
                position: 'top-end',
                type,
                title,
                text: message,
                showConfirmButton: false,
                timer: 1500
            })
        },
        numeral(value) {
            return numeral(value).format('0,0.00');
        },
        created_atFilter(list, search) {

            var data = [];
            if (search) {
                data = list.filter((item) => {

                    return item.created_at.toString().toLowerCase().includes(search.toLowerCase())
                })

            } else {
                data = list;
            }
            return data;
        },
        myFilter(list, search) {
            if (list.length == 0) {
                return []
            }
            var data = [];
            if (search) {
                data = list.filter((item) => {
                    var keys = Object.values(item)
                    var boolean = false
                    if (item == undefined) {
                        return false
                    }
                    var bool = keys.forEach((key) => {
                        if (key != null && key.toString().toLowerCase().includes(search.toLowerCase())) {
                            boolean = true
                        }
                    })
                    return boolean
                })
            } else {
                data = list;
            }
            return data;
        },
        loadUser() {

        },
        addTransactionComponent(transaction, type) {
            this.$Progress.start()
            if (transaction == undefined) {
                this.transaction = undefined;
                this.orderIDs = undefined
                this.purchaseIDs = undefined
                this.invoice = undefined;
                console.log('payment default');
            } else if (type == undefined) {
                this.transaction = transaction;
                this.orderIDs = undefined
                this.purchaseIDs = undefined
                this.invoice = undefined;
            } else if (type == 'order') {
                this.orderIDs = transaction
                this.transaction = undefined;
                this.purchaseIDs = undefined
                this.invoice = undefined;
            } else if (type == 'purchase') {
                this.purchaseIDs = transaction
                this.transaction = undefined;
                this.orderIDs = undefined
                this.invoice = undefined;
            } else if (type == 'invoice') {
                this.invoice = transaction;
                this.purchaseIDs = undefined
                this.transaction = undefined;
                this.orderIDs = undefined;
            }
            window.dispatchEvent(new Event('sidebar_min'));
            this.$Progress.finish()
            this.$router.push('/payment')
        },
        back() {
            this.$router.go(-1)
        },
    }
});
