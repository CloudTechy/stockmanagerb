import Vue from "vue";
import App from "./App.vue";
import router from "./router/index";
import axios from "axios";
import VueAxios from "vue-axios";
import VueRouter from "vue-router";
import VueAuth from "@websanova/vue-auth";
import auth from "./auth";
// import { Form } from './CustomForm'; 
import {Form, HasError, AlertError, AlertSuccess, AlertErrors } from 'vform'
// import './bootstrap'


// CSS + Assets
import "admin-lte";
import "admin-lte/dist/css/adminlte.min.css";
import "bootstrap/dist/css/bootstrap.min.css";
import "@fortawesome/fontawesome-free/css/all.min.css";

// Bootstrap JS + jQuery
import "bootstrap";
import "jquery";
// import jquery from 'jquery';


// Import global libraries


// Other Plugins
import VueCookies from "vue-cookies";
import VueSession from "vue-session";
import VueSweetalert2 from "vue-sweetalert2";
import VueProgressBar from "vue-progressbar";
import toastr from "vue-toastr";
import Loading from "vue-loading-overlay";
import numeral from "numeral";
window.numeral = numeral;

// Plugin CSS
import "vue-loading-overlay/dist/vue-loading.css";
import "sweetalert2/dist/sweetalert2.min.css";

// Vue Plugin Init
Vue.use(VueAxios, axios);
Vue.router = router;
Vue.use(VueAuth, auth);
Vue.use(VueRouter, router);


Vue.use(VueCookies);
Vue.use(VueSession, { persist: true });
Vue.use(VueSweetalert2, {
    confirmButtonColor: "#41b882",
    cancelButtonColor: "#ff7674",
});
Vue.use(toastr,  {
  defaultTimeout: 3000,
  defaultProgressBar: false,
  defaultProgressBarValue: 0,
  // defaultType: "error",
  defaultPosition: "toast-top-right",
  defaultCloseOnHover: false,
  // defaultStyle: { "background-color": "red" },
  defaultClassNames: ["animated", "zoomInUp"]
});
Vue.use(Loading); 
// let loader = null;

// router.beforeEach((to, from, next) => {
//   if (to.meta.progress !== undefined) {
//     Vue.prototype.$Progress.parseMeta(to.meta.progress)
//   }

//   Vue.prototype.$Progress.start()
//   loader = Vue.prototype.$loading.show({
//     // Optional custom config
//     canCancel: false,
//     color: "orange", //28a745
//     backgroundColor: "#fff",
//     loader: "dots" 
//   })
//   next()
// })

// router.afterEach(() => {
//   Vue.prototype.$Progress.finish()
//   if (loader) {
//     loader.hide()
//     loader = null
//   }
// })

Vue.use(VueProgressBar, {
    color: "#ffc107",
    failedColor: "red",
    thickness: "5px",
});

// Global Components
window.Fire = new Vue()
// Axios Defaults

axios.defaults.baseURL =
    import.meta.env.VITE_API_URL || "http://localhost:8000/api";
axios.defaults.headers.common["Accept"] = "application/json";

console.log(axios.defaults.baseURL);
// const token = localStorage.getItem("stockmanager");
// if (token) {
//     axios.defaults.headers.common["Authorization"] = token;
// }
window.axios = axios;
window.axios.defaults.baseURL = axios.defaults.baseURL || "http://localhost:8000/api";
    
Form.axios = axios
window.Form = Form;
// window.Form.baseUrl = axios.defaults.baseURL;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
Vue.component(AlertErrors.name, AlertErrors)
Vue.component(AlertSuccess.name, AlertSuccess)

$(document).ready(() => {
  $('[data-widget="treeview"]').Treeview?.('init'); // Safe initialization
});
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

// Vue Instance
new Vue({
    el: "#app",
    router,
    data: {
        respond: "",
        VueProgressBar,
        VueSweetalert2,
        VueSession,
        VueCookies,
        donutOrders: {},
        error: "",
        token: "",
        user: {},
        form: new Form(),
        transactions: "",
        loader: undefined,
        units: "",
        categories: "",
        brands: "",
        sizes: "",
    },
    mounted() {
      this.form.baseURL = axios.defaults.baseURL;
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
            });
        },
        numeral(value) {
            return numeral(value).format("0,0.00");
        },
        created_atFilter(list, search) {
            var data = [];
            if (search) {
                data = list.filter((item) => {
                    return item.created_at
                        .toString()
                        .toLowerCase()
                        .includes(search.toLowerCase());
                });
            } else {
                data = list;
            }
            return data;
        },
        myFilter(list, search) {
            if (list.length == 0) {
                return [];
            }
            var data = [];
            if (search) {
                data = list.filter((item) => {
                    var keys = Object.values(item);
                    var boolean = false;
                    if (item == undefined) {
                        return false;
                    }
                    var bool = keys.forEach((key) => {
                        if (
                            key != null &&
                            key
                                .toString()
                                .toLowerCase()
                                .includes(search.toLowerCase())
                        ) {
                            boolean = true;
                        }
                    });
                    return boolean;
                });
            } else {
                data = list;
            }
            return data;
        },
        loadUser() {},
        addTransactionComponent(transaction, type) {
            this.$Progress.start();
            if (transaction == undefined) {
                this.transaction = undefined;
                this.orderIDs = undefined;
                this.purchaseIDs = undefined;
                this.invoice = undefined;
                console.log("payment default");
            } else if (type == undefined) {
                this.transaction = transaction;
                this.orderIDs = undefined;
                this.purchaseIDs = undefined;
                this.invoice = undefined;
            } else if (type == "order") {
                this.orderIDs = transaction;
                this.transaction = undefined;
                this.purchaseIDs = undefined;
                this.invoice = undefined;
            } else if (type == "purchase") {
                this.purchaseIDs = transaction;
                this.transaction = undefined;
                this.orderIDs = undefined;
                this.invoice = undefined;
            } else if (type == "invoice") {
                this.invoice = transaction;
                this.purchaseIDs = undefined;
                this.transaction = undefined;
                this.orderIDs = undefined;
            }
            window.dispatchEvent(new Event("sidebar_min"));
            this.$Progress.finish();
            this.$router.push("/payment");
        },
        back() {
            this.$router.go(-1);
        },
    },
    render: (h) => h(App),
});