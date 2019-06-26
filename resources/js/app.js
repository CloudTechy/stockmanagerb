
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router'

Vue.use(VueRouter)

let routes = [
  { path: '/users', component: require('./components/user/UserSummaryComponent.vue').default },
  { path: '/sizes', component: require('./components/size/SizeComponent.vue').default },
  { path: '/dashboard', component: require('./components/dashboard/DashboardComponent.vue').default },
  { path: '/units', component:  require('./components/unit/UnitComponent.vue').default},
  { path: '/brands', component: require('./components/brand/BrandComponent.vue').default },
  { path: '/banks', component: require('./components/bank/BankComponent.vue').default },
  { path: '/categories', component: require('./components/category/CategoryComponent.vue').default },
  { path: '/', component: require('./components/dashboard/DashboardComponent.vue').default },
  { path: '/login', component: require('./components/login/LoginComponent.vue').default },
  { path: '/customers', component: require('./components/customer/CustomerComponent.vue').default },
  { path: '/suppliers', component: require('./components/supplier/SupplierComponent.vue').default },
  { path: '/invoices', component: require('./components/invoice/InvoiceComponent.vue').default },
  { path: '/print_invoice', component: require('./components/InvoicePrintComponent.vue').default },
  { path: '/transactions', component: require('./components/transaction/TransactionComponent.vue').default },
  { path: '/payment', component: require('./components/transaction/AddTransactionComponent.vue').default },
  { path: '/products', component: require('./components/product/PurchaseComponent.vue').default },
  { path: '/orders', component: require('./components/order/OrderComponent.vue').default },
  { path: '/statistics', component: require('./components/statistic/StatisticComponent.vue').default },

]
const router = new VueRouter({ mode:'history',
  routes
})
 
window.Fire = new Vue()



window.numeral = require('numeral');
import Raphael from 'raphael/raphael'
global.Raphael = Raphael

import VueProgressBar from 'vue-progressbar'

const options = {
  color: '#bffaf3',
  failedColor: '#874b4b',
  thickness: '5px',
  transition: {
    speed: '0.2s',
    opacity: '0.6s',
    termination: 300
  },
  autoRevert: true,
  location: 'top',
  inverse: false
}

Vue.use(VueProgressBar, options)

import VueSweetalert2 from 'vue-sweetalert2';
 
const sweetOptions = {
  confirmButtonColor: '#41b882',
  cancelButtonColor: '#ff7674'
}
 
Vue.use(VueSweetalert2, sweetOptions)

import { Form, HasError, AlertError } from 'vform'
 
 window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

import VueSession from 'vue-session'
Vue.use(VueSession,{persist : true})


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('nav-component', require('./components/NavComponent.vue').default);
// Vue.component('sidebar-component', require('./components/SidebarComponent.vue').default);
// Vue.component('footer-component', require('./components/FooterComponent.vue').default);
// Vue.component('dashoard-info-component', require('./components/DashboardInfoComponent.vue').default);
// Vue.component('top-product-component', require('./components/TopProductComponent.vue').default);
// Vue.component('top-debtors-component', require('./components/TopDebtorComponent.vue').default);
// Vue.component('top-debt-component', require('./components/TopDebtComponent.vue').default);
// Vue.component('new-product-component', require('./components/NewProductComponent.vue').default);
// Vue.component('daily-orders-component', require('./components/DailyOrdersComponent.vue').default);
// Vue.component('pending-orders-component', require('./components/PendingOrdersComponent.vue').default);
// Vue.component('orders-component', require('./components/OrderComponent.vue').default);
// Vue.component('transaction-component', require('./components/TransactionComponent.vue').default);
// Vue.component('invoice-donut-component', require('./components/InvoiceDonutComponent.vue').default);
// Vue.component('revenue-line-chart-component', require('./components/RevenueLineChartComponent.vue').default);
// Vue.component('dashoard-component', require('./components/DashboardComponent.vue').default);

// Vue.component('brand-component', require('./components/brand/BrandComponent.vue').default);
// Vue.component('add-brand-component', require('./components/brand/AddBrandComponent.vue').default);
// Vue.component('show-brands-component', require('./components/brand/ShowBrandsComponent.vue').default);
// Vue.component('edit-brand-component', require('./components/brand/EditBrandComponent.vue').default);

// Vue.component('unit-component', require('./components/unit/UnitComponent.vue').default);
// Vue.component('add-unit-component', require('./components/unit/AddUnitComponent.vue').default);
// Vue.component('show-units-component', require('./components/unit/ShowUnitsComponent.vue').default);
// Vue.component('edit-unit-component', require('./components/unit/EditUnitComponent.vue').default);

// Vue.component('size-component', require('./components/size/SizeComponent.vue').default);
// Vue.component('add-size-component', require('./components/size/AddSizeComponent.vue').default);
// Vue.component('show-sizes-component', require('./components/size/ShowSizesComponent.vue').default);
// Vue.component('edit-size-component', require('./components/size/EditSizeComponent.vue').default);

// Vue.component('category-component', require('./components/category/CategoryComponent.vue').default);
// Vue.component('add-category-component', require('./components/category/AddCategoryComponent.vue').default);
// Vue.component('show-categories-component', require('./components/category/ShowCategoriesComponent.vue').default);
// Vue.component('edit-category-component', require('./components/category/EditCategoryComponent.vue').default);

// Vue.component('user-summary-component', require('./components/user/UserSummaryComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Array.prototype.sum = function(prop) {
  var total = 0
  for ( var i = 0, _len = this.length; i < _len; i++ ) {
      total = total + parseInt( this[i][prop])
  }
  return total
} 

const app = new Vue({
  el: '#dashboard',
  router,
  data : {
  	respond : '',
    VueProgressBar,
    VueSweetalert2,
    VueSession,
  	donutOrders:{},
  	error : '',
    token:'',
    user: {},
    form: new Form(),
    transactions: ''
  },
  beforeCreate: function () {
    window.axios.defaults.headers.common['Authorization'] = 'Bearer '+ this.$session.get('token')
    if (!this.$session.exists() || window.axios.defaults.headers.common['Authorization'] == undefined) {
      console.log('app routing to login');
      this.$router.push('/login')
    }
    else {
      window.axios.defaults.headers.common['Authorization'] = 'Bearer '+ this.$session.get('token')
    } 
  },
  mounted(){
  // this.$nextTick(function () {
	// console.log('test');
	// })
  },
  created () {
    this.$Progress.start()
    this.$router.beforeEach((to, from, next) => {
      if (to.meta.progress !== undefined) {
        let meta = to.meta.progress
        this.$Progress.parseMeta(meta)
      }
      this.$Progress.start()
      next()
    })
    this.$router.afterEach((to, from) => {
      this.$Progress.finish()
    })

    if(!this.$session.exists() || window.axios.defaults.headers.common['Authorization'] != 'Bearer '+ this.$session.get('token')) {
      this.$router.push('/login')
    }

    //window.axios.defaults.headers.common['Authorization'] = 'Bearer '+ this.$session.get('token')

    window.addEventListener('beforeunload', () => {
      this.$session.destroy()
      this.$root.alert('info','leaving?','see you soon')
    })
    Fire.$on('user_login', (data)=> {
      this.loadUser(data);
    })
  },
  methods:{
    alert(type,title,message){
      this.$swal({
        toast: true,
        position: 'top-end',type,title,text:message,
        showConfirmButton: false,
        timer: 1500
      })
    },
    numeral(value){
        return numeral(value).format('0,0.00');
    },
    myFilter (list,search){
      var data = [];
      if(search){
        data =  list.filter((item)=>{
        var keys = Object.values(item)
        var boolean = false
        if(item == undefined){
          return false
        }
        var bool = keys.forEach((key) => {
          if(key != null && key.toString().toLowerCase().includes(search.toLowerCase())) {
            boolean = true
          }
        }) 
         return boolean
      })
      }else{
        data = list;
      }
      return data;
    },
    loadUser(email){
      
    },
    addTransactionComponent(transaction,type){
      if (transaction == undefined) {
        this.transaction = undefined;
        this.orderIDs = undefined
        this.purchaseIDs = undefined
        this.invoice = undefined;
        console.log('payment default');
      }
      else if(type == undefined){
        this.transaction = transaction;
        this.orderIDs = undefined
        this.purchaseIDs = undefined
        this.invoice = undefined;
      }
      else if(type == 'order'){
        this.orderIDs = transaction
        this.transaction = undefined;
        this.purchaseIDs = undefined
        this.invoice = undefined;
      }
      else if(type == 'purchase'){
        this.purchaseIDs = transaction
        this.transaction = undefined;
        this.orderIDs = undefined
        this.invoice = undefined;
      }
      else if(type == 'invoice'){
        this.invoice = transaction;
        this.purchaseIDs = undefined
        this.transaction = undefined;
        this.orderIDs = undefined;
      }
      window.dispatchEvent(new Event('sidebar_min'));
      this.$router.push('/payment')
    },
    back(){
      this.$router.go(-1)
    },
  }
});


