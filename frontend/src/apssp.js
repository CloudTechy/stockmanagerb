/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
// import './bootstrap'
import Vue from 'vue'
import Index from './index'

window.Vue = Vue;

import VueAuth from '@websanova/vue-auth'
import VueAxios from 'vue-axios'
import VueRouter from 'vue-router'
import auth from './auth'
import router from './router'
import axios from 'axios'
import jquery from 'jquery'
import toastr from 'vue-toastr'
import 'admin-lte'; 
import bootstrap from 'bootstrap'
import * as Popper from '@popperjs/core'
// AdminLTE + Bootstrap + FontAwesome (Order matters!)
// import 'bootstrap/dist/css/bootstrap.min.css';
// import 'admin-lte/dist/css/adminlte.min.css';
// import '@fortawesome/fontawesome-free/css/all.min.css';

// // Import JS if needed (e.g., dropdowns)
// import 'bootstrap/dist/js/bootstrap.bundle.min.js';
// import 'admin-lte/dist/js/adminlte.min.js';

Vue.router = router
Vue.use(VueRouter)
$(document).ready(() => {
    $('[data-widget="treeview"]').Treeview?.('init'); // Safe initialization
  });

axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.baseURL = process.env.VUE_APP_API_URL || 'http://localhost:8000/api';
console.log(axios.defaults.baseURL)

const token = localStorage.getItem('stockmanager');

if (token) {
  axios.defaults.headers.common['Authorization'] = token;
}
window.axios = axios
Vue.use(VueAxios, axios)
Vue.use(VueAuth, auth)

import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
Vue.use(Loading)
let loader = null;

router.beforeEach((to, from, next) => {
  if (to.meta.progress !== undefined) {
    Vue.prototype.$Progress.parseMeta(to.meta.progress)
  }

  Vue.prototype.$Progress.start()
  loader = Vue.prototype.$loading.show({
    // Optional custom config
    canCancel: false,
    color: "orange", //28a745
    backgroundColor: "#fff",
    loader: "dots" 
  })
  next()
})

router.afterEach(() => {
  Vue.prototype.$Progress.finish()
  if (loader) {
    loader.hide()
    loader = null
  }
})



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
        termination: 500
    },
    autoRevert: false,
    location: 'top',
    inverse: false
}
window.Fire = new Vue()
Vue.use(VueProgressBar, options)

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

const sweetOptions = {
    confirmButtonColor: '#41b882',
    cancelButtonColor: '#ff7674'
}

Vue.use(VueSweetalert2, sweetOptions)

import VueCookies from 'vue-cookies'
Vue.use(VueCookies)


Vue.use(toastr, {
    defaultTimeout: 3000,
    defaultProgressBar: false,
    defaultProgressBarValue: 0,
    // defaultType: "error",
    defaultPosition: "toast-top-right",
    defaultCloseOnHover: false,
    // defaultStyle: { "background-color": "red" },
    defaultClassNames: ["animated", "zoomInUp"]
})
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
Date.prototype.addDays = function(date, days) {
    var result = new Date(date);
    result.setDate(result.getDate() + days)
    return result;
};

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
        loader: undefined,
        units : '',
        categories: '',
        brands : '',
        sizes:'',
    },
    
    mounted() {
        // this.$nextTick(function () {
        // console.log('test');
        // })
    },
    created() {

        this.$Progress.start()
        // this.loader = this.$loading.show({});
        // this.$router.beforeEach((to, from, next) => {
        //     if (to.meta.progress !== undefined) {
        //         let meta = to.meta.progress
        //         this.$Progress.parseMeta(meta)
        //     }
        //     this.$Progress.start()
        //     this.loader = this.$loading.show({});
        //     next()
        // })
        // this.$router.afterEach((to, from) => {
        //     this.$Progress.finish()
        //     if (this.loader) {
        //         this.loader.hide();
        //     }
        // })
        // })
        Fire.$on('user_login', (data) => {
            // this.loadUser(data);
        })
    },
    methods: {
        alert(type, title, message) {
            // this.$toastr.defaultType = "error"; // default type : success
            this.$toastr.Add({
                title: title,
                msg: message,
                position: "toast-top-right",
                type,
                timeout: 5000,
                progressbar: true,
                preventDuplicates: true,
                //progressBarValue:"", // if you want set progressbar value
                style: {},
                classNames: ["animated", "zoomInUp"],
                closeOnHover: true,
                clickClose: false,
                onCreated: () => {},
                onClicked: () => {},
                onClosed: () => {},
                onMouseOver: () => {},
                onMouseOut: () => {},
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
