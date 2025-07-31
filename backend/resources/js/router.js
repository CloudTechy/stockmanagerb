import VueRouter from 'vue-router'

// Pages
import Users from './components/user/UserSummaryComponent'
import Sizes from './components/size/SizeComponent'
import Units from './components/unit/UnitComponent'
import Login from './components/login/LoginComponent'
import Dashboard from './components/dashboard/DashboardComponent'
// import Home from './components/size/SizeComponent'
import Home from './components/dashboard/DashboardComponent'
import Unknown from './components/dashboard/DashboardComponent'
import Brands from './components/brand/BrandComponent'
import Banks from './components/bank/BankComponent'
import Categories from './components/category/CategoryComponent'
import Customers from './components/customer/CustomerComponent'
import Suppliers from './components/supplier/SupplierComponent'
import Invoices from './components/invoice/InvoiceComponent'
import Print_invoice from './components/InvoicePrintComponent'
import Transactions from './components/transaction/TransactionComponent'
import Payment from './components/transaction/AddTransactionComponent'
import Products from './components/product/PurchaseComponent'
import BillingPurchase from './components/product/AddProductBilling'
import Orders from './components/order/OrderComponent'
import Statistics from './components/statistic/StatisticComponent'

const routes = [{
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            auth: false
        }
    },
    // USER ROUTES
    {
        path: '/',
        name: '/',
        component: Home,
        meta: {
            auth: true
        }
    },
    //  {
    //     path: '/404',
    //     name: 'Unknown',
    //     component: Unknown,
    //     meta: {
    //         auth: undefined
    //     }
    // },
    {
        path: '/units',
        name: 'units',
        component: Units,
        meta: {
            auth: true
        }
    },
    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard,
        meta: {
            auth: true
        }
    }, {
        path: '/statistics',
        name: 'statistics',
        component: Statistics,
        meta: {
            auth: true
        }
    }, {

        path: '/users',
        name: 'users',
        component: Users,
        meta: {
            auth: true
        }
    }, {

        path: '/sizes',
        name: 'sizes',
        component: Sizes,
        meta: {
            auth: true
        }
    }, {

        path: '/brands',
        name: 'brands',
        component: Brands,
        meta: {
            auth: true
        }
    }, {

        path: '/banks',
        name: 'banks',
        component: Banks,
        meta: {
            auth: true
        }
    }, {

        path: '/categories',
        name: 'categories',
        component: Categories,
        meta: {
            auth: true
        }
    }, {

        path: '/customers',
        name: 'customers',
        component: Customers,
        meta: {
            auth: true
        }
    }, {

        path: '/suppliers',
        name: 'suppliers',
        component: Suppliers,
        meta: {
            auth: true
        }
    }, {

        path: '/invoices',
        name: 'invoices',
        component: Invoices,
        meta: {
            auth: true
        }
    }, {

        path: '/print_invoice',
        name: 'print_invoice',
        component: Print_invoice,
        meta: {
            auth: true
        }
    }, {

        path: '/transactions',
        name: 'transactions',
        component: Transactions,
        meta: {
            auth: true
        }
    }, {

        path: '/payment',
        name: 'payment',
        component: Payment,
        meta: {
            auth: true
        }
    }, {

        path: '/BillingPurchase',
        name: 'BillingPurchase',
        component: BillingPurchase,
        meta: {
            auth: true
        }
    }, {

        path: '/orders',
        name: 'orders',
        component: Orders,
        meta: {
            auth: true
        }
    }, {
        path: '/products',
        name: 'products',
        component: Products,
        meta: {
            auth: true
        }
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
                selector: to.hash
            }
        } else {
            return { x: 0, y: 100 }
        }
    }
})
export default router
