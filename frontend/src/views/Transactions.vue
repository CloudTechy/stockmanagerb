<template>
    <div>
        <nav-component></nav-component>
        <sidebar-component></sidebar-component>
        <div class="content-wrapper" style="min-height: 606px;">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <h1 class="m-0 text-dark">Transactions</h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="container-fluid">
                    <div class="card card-success card-outline m-auto ">
                        <div class="card-header">
                            <h5 class="card-title"> Summary</h5>
                        </div>
                        <div class="card-body box-profile">
                            <div class="row container text-center  mb-2 mt-4">
                                <div class="col-sm-6 col-md-3 mb-3 text-center">
                                    <div class="circle border-success mb-lg-4">
                                        <div class="inner-circle border-secondary">
                                            <p class=" circle-text ">{{ transactions.length }}</p>
                                        </div>
                                    </div>
                                    <h4 class="profile-username ">Transactions</h4>
                                </div>
                                <div class="col-sm-6 col-md-3 mb-3 text-center">
                                    <div class="circle border-success mb-lg-4">
                                        <div class="inner-circle border-secondary">
                                            <p class="circle-text ">{{ orderCount }}</p>
                                        </div>
                                    </div>
                                    <h4 class="profile-username ">Total Orders</h4>
                                </div>
                                <div class="col-sm-6 col-md-3 mb-3 text-center">
                                    <div class="circle border-success mb-lg-4">
                                        <div class="inner-circle border-secondary">
                                            <p class="circle-text ">{{ purchaseCount }}</p>
                                        </div>
                                    </div>
                                    <h4 class="profile-username ">Total Purchase</h4>
                                </div>
                                <div class="col-sm-6 col-md-3 mb-3 text-center">
                                    <div class="circle border-success mb-lg-4">
                                        <div class="inner-circle border-secondary">
                                            <p class="circle-text bg-success font-weight-bold"><span class=""
                                                    style="text-decoration: line-through">N</span>{{ $root.numeral(transactions.sum('payment')) }}
                                            </p>
                                        </div>
                                    </div>
                                    <h4 class="profile-username bg-success  font-weight-bold">Revenue</h4>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div>
                            <div class="card-header mb-2">
                                <h5 class="card-title">View Transactions</h5>
                            </div>
                            <show-transactions-component></show-transactions-component>
                        </div>
                        <hr>
                        <div style="position: fixed; bottom: 25px; right: 25px">
                            <button type="button" @click.prevent="$root.addTransactionComponent()"
                                class="btn btn-success  rounded-circle" title="Add new transaction" id="add"
                                data-toggle="modal" data-target="#addTransactionComponent"><i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import NavComponent from '@/components/NavComponent.vue';
import SidebarComponent from '@/components/SidebarComponent.vue';
import ShowTransactionsComponent from '@/components/transaction/ShowTransactionsComponent.vue';


export default {
    components: {
        NavComponent,
        SidebarComponent,
        ShowTransactionsComponent,
    },
    mounted() {
        if (localStorage.transactions) {
            this.transactions = JSON.parse(localStorage.transactions)
            this.transactions.forEach(this.count);
            this.loading = false;
        }
    },
    data() {
        var d = new Date();
        return {
            month: d.getMonth() + 1,
            year: d.getFullYear(),
            transactions: [],
            purchase_type: [],
            order_type: [],
            orderCount: 0,
            revenue: 0,
            loading: true,
            error: '',
            search: '',
            form: new Form(),
        }
    },
    computed: {
        purchaseCount() {
            return this.transactions.length - this.orderCount
        }
    },
    beforeDestroy() {
        window.dispatchEvent(new Event('close_sidebar_min'))
    },
    created() {
        Fire.$on('transaction_created', (data) => {
            this.loadTransactions();
        })
        Fire.$on('transaction_deleted', (data) => {
            this.loadTransactions();
        })
        Fire.$on('transaction_edited', (data) => {
            this.loadTransactions();
        })
        Fire.$on('view', (data) => {
            this.search = data.id;
            this.title = "TRANSACTIONS DETAILS"
            this.$refs.search.focus()
        })
        this.loadTransactions();
        this.loadStatOrder()
        // Echo.channel('transaction')
        // .listen('UpdateTransaction', (e) => {
        //     this.loadData();
        // });
    },
    methods: {
        loadTransactions() {
            this.form.get('/transactions')
                .then(response => {
                    if (response.data.status == true) {
                        this.loading = false;
                        this.transactions = response.data.data.item;
                        this.order_type = []
                        this.purchase_type = []
                        response.data.data.item.forEach(this.count);
                        localStorage.transactions = JSON.stringify(this.transactions)
                    }
                    else {
                        console.log("load transaction did not return positive response");
                    }

                })
                .catch(error => {
                    this.error = error.response.data.error;
                    console.log(error);
                });
        },
        count(transaction) {
            if (transaction.type == 'purchase') {
                this.purchase_type.push(transaction);
            }
            else if (transaction.type == 'order') {
                this.order_type.push(transaction);
            }
        },

        loadStatOrder() {
            this.form.get('/statistics/invoices?type=order')
                .then(response => {
                    this.orderCount = response.data.data.total
                })
                .catch(err => {
                    console.log(err)
                })
        },

    }
}

</script>
<style type="text/css">
.inner-circle {
    position: relative;
    top: 4%;
    left: 0%;
    width: 120px;
    height: 120px;
    border: 3px solid green;
    display: inline-block;
    border-radius: 50%;
}

.circle {
    display: inline-block;
    width: 140px;
    height: 140px;
    border: 5px dashed gray;
    position: relative;
    text-align: center;
    box-sizing: border-box;
    border-radius: 50%;
}

.circle-text {
    position: relative;
    top: 43%;
    margin: 0px;
}
</style>