import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const routes = [
  {
    path: '/login',
    name: 'login',
    component: () => import('@/views/Login.vue'),  // Lazy load Login component
    meta: {
      auth: false,
    },
  },
  // USER ROUTES
  {
    path: '/',
    name: 'home',
    component: () => import('@/views/Orders.vue'),  // Lazy load HomeView component
    meta: {
      // auth: false
    },
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: () => import('@/views/Dashboard.vue'),  // Lazy load Dashboard component
    meta: {
      auth: true,
    },
  },
  {
    path: '/statistics',
    name: 'statistics',
    component: () => import('@/views/Statistics.vue'),  // Lazy load Statistics component
    meta: {
      auth: true,
    },
  },
  {
    path: '/products',
    name: 'products',
    component: () => import('@/views/Products.vue'),  // Lazy load Products component
    meta: {
      auth: true,
    },
  },
  {
    path: '/users',
    name: 'users',
    component: () => import('@/views/Users.vue'),  // Lazy load Users component
    meta: {
      auth: true,
    },
  },
  {
    path: '/orders',
    name: 'orders',
    component: () => import('@/views/Orders.vue'),  // Lazy load Orders component
    meta: {
      auth: true,
    },
  },
  {
    path: '/invoices',
    name: 'invoices',
    component: () => import('@/views/Invoices.vue'),  // Lazy load Invoices component
    meta: {
      auth: true,
    },
  },
  {
    path: '/categories',
    name: 'categories',
    component: () => import('@/views/Categories.vue'),  // Lazy load Categories component
    meta: {
      auth: true,
    },
  },
  {
    path: '/brands',
    name: 'brands',
    component: () => import('@/views/Brands.vue'),  // Lazy load Brands component
    meta: {
      auth: true,
    },
  },
  {
    path: '/banks',
    name: 'banks',
    component: () => import('@/views/Banks.vue'),  // Lazy load Banks component
    meta: {
      auth: true,
    },
  },
  {
    path: '/sizes',
    name: 'sizes',
    component: () => import('@/views/Sizes.vue'),  // Lazy load Banks component
    meta: {
      auth: true,
    },
  },
  {
    path: '/units',
    name: 'units',
    component: () => import('@/views/Units.vue'),  // Lazy load Banks component
    meta: {
      auth: true,
    },
  },
  {
    path: '/suppliers',
    name: 'suppliers',
    component: () => import('@/views/Suppliers.vue'),  // Lazy load Suppliers component
    meta: {
      auth: true,
    },
  },
  {
    path: '/customers',
    name: 'customers',
    component: () => import('@/views/Customers.vue'),  // Lazy load Customers component
    meta: {
      auth: true,
    },
  },
  {
    path: '/payment',
    name: 'payment',
    component: () => import('@/views/Payment.vue'),  // Lazy load Payment component
    meta: {
      auth: true,
    },
  },
  {
    path: '/print_invoice',
    name: 'print_invoice',
    component: () => import('@/components/InvoicePrintComponent.vue'),  // Lazy load PrintInvoice component
    meta: {
      auth: true,
    },
  },
  {
    path: '/transactions',
    name: 'transactions',
    component: () => import('@/views/Transactions.vue'),  // Lazy load Transactions component
    meta: {
      auth: true,
    },
  },
  {
    path: '/billing_purchase',
    name: 'billing_purchase',
    component: () => import('@/views/BillingPurchase.vue'),  // Lazy load BillingPurchase component
    meta: {
      auth: true,
    },
  },
]

const router = new VueRouter({
  history: true,
  mode: 'history',
  routes,
  scrollBehavior: (to, from, savedPosition) => {
    if (savedPosition) {
      return savedPosition
    } else if (to.hash) {
      return {
        selector: to.hash,
      }
    } else {
      return { x: 0, y: 100 }
    }
  },
})



export default router
