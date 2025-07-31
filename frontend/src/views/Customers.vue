<template>
    <div>
        <nav-component></nav-component>
        <sidebar-component></sidebar-component>
        <div class="modal fade" id="addComponent">
            <add-customer-component></add-customer-component>
        </div>
        <div class="content-wrapper" style="min-height: 606px;">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <h1 class="m-0 text-dark">Customer</h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="container-fluid">
                    <div class="card card-success card-outline m-auto ">
                        <div class="card-header">
                            <h5 class="card-title">Account Summary</h5>
                        </div>
                        <div class="card-body box-profile">
                            <div class="row container text-center  mb-2 mt-4">
                                <div class="col-lg-3 col-sm-6 mb-3 text-center">
                                    <div class="circle  mb-lg-4">
                                        <div class="inner-circle border-success">
                                            <p class=" circle-text">{{ customers.length }}</p>
                                        </div>
                                    </div>
                                    <h4 class="profile-username ">Customers</h4>
                                </div>
                                <div class="col-lg-3 col-sm-6 mb-3 text-center">
                                    <div class="circle mb-lg-4">
                                        <div class="inner-circle border-danger">
                                            <p class="circle-text">{{ debtors.length }}</p>
                                        </div>
                                    </div>
                                    <h4 class="profile-username ">Debtors</h4>
                                </div>
                                <div class="col-lg-3 col-sm-6 mb-3 text-center">
                                    <div class="circle mb-lg-4">
                                        <div class="inner-circle border-success">
                                            <p class="circle-text">{{ customers.length - debtors.length }}</p>
                                        </div>
                                    </div>
                                    <h4 class="profile-username ">Non debtors</h4>
                                </div>
                                <div class="col-lg-3 col-sm-6 mb-3 text-center">
                                    <div class="circle mb-lg-4">
                                        <div class="inner-circle border-danger">
                                            <p class="circle-text"><span class="" style="text-decoration: line-through">N</span>{{ owing }}</p>
                                        </div>
                                    </div>
                                    <h4 class="profile-username ">Total owing</h4>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div>
                            <div class="card-header mb-2">
                                <h5 class="card-title">Manage Customers <i class="fas fa-user"></i></h5>
                            </div>
                            <show-customers-component></show-customers-component>
                        </div>
                        <hr>
                        <!-- <div class="row">
            <div class="card-body box-profile">
              <div class="row container  mb-3 mt-3">
                <div class="col-lg-12 col-sm-12 mb-3 ">
                  <div class="card-header">
                      <h5 class="card-title">RECENTLY ADDED</h5>
                  </div>
                  <h2 class="card-title small"> &nbsp;</h2>
                  <div class="">
                            <div class="input-group input-group-sm float-right" >
                            <input ref = "search" v-model="search" type="text" name="table_search" class="p-2 form-control" placeholder="Search customers">
                         </div>
                     </div> 
                     <div class="clearfix"> </div>
                        
                        <ul class="products-list product-list-in-card pl-2 pr-2">
                          <li v-if = "loading == false" v-for = "customer in $root.myFilter(customers,search).slice(0,5)" class="item">

                            <div class="product-img">
                              <img v-bind:src=" 'img/user.png'" alt="customer Image" class="rounded-circle">
                            </div>
                            <div class="product-info">
                              <span class="users-list-date">
                                {{ customer.date }}
                              </span>
                              <span v-if = "customer.owing > 0" v-bind:class="{'users-list-date' : true, 'float-right':true, badge:true, small:true, 'badge-danger':customer.owing > 0, 'badge-success' : customer.owing ==0}">Owing <span class="" style="text-decoration: line-through">N</span>
                                {{ $root.numeral(customer.owing) }}
                              </span>
                              <h3 href="javascript:void(0)" class="users-list-name">{{customer.name }} </h3>
                              <h3 href="javascript:void(0)" class="users-list-name">{{"customer id: " + customer.id }} </h3>
                              <span style="cursor: pointer" v-if = "customer.owing > 0 && customer.orders.length > 0" class="badge float-right badge-secondary small strong">
                                <div class="small"  @click.prevent = "$root.addTransactionComponent(customer.orders,'order')" >
                                    <button  title="make transaction" class=" btn-link badge badge-secondary btn-secondary small m-1" data-toggle="" data-target=""  >
                                          {{'pay ' + customer.orders.length + ' order(s)'}}
                                    </button>
                                </div>
                              </span>
                              <span v-if="customer.number != null" class="users-list-date">
                                {{'Number: ' + customer.number }}
                              </span>
                              <span v-if="customer.email != null" class="users-list-date">
                                {{'Email: ' + customer.email }}
                              </span>
                              <span v-bind:class="{'users-list-date' : true, 'float-right':true, badge:true, small:true,  'badge-warning' : customer.due_date != null}">
                                {{ customer.due_date }}
                              </span>
                              <span class="users-list-date ">Due Date </span>
                             
                            </div>
                         </li>
                            <li class="p-4 m-3 border border-info" v-if = "loading == false && filteredCustomers.length == 0">
                                <h4 class="text-center small text-secondary">Customers Not Found</h4>
                            </li>
                        </ul>
                      
                </div>
              </div>
            </div>
          </div> -->
                    </div>
                </div>
            </div>
        </div>
        <div style="position: fixed; bottom: 25px; right: 25px">
            <button type="button" @click="addComponent" class="btn btn-success  rounded-circle" title="Add new customer" id="add" data-toggle="modal" data-target="#addComponent"><i class="fa fa-plus"></i> </button>
        </div>
    </div>
</template>
<script>
import NavComponent from '@/components/NavComponent.vue';
import SidebarComponent from '@/components/SidebarComponent.vue';
import ShowCustomersComponent from '@/components/customer/ShowCustomersComponent.vue';
import AddCustomerComponent from '@/components/customer/AddCustomerComponent.vue';


export default {
    components: {
        NavComponent,
        SidebarComponent,
        ShowCustomersComponent,
        AddCustomerComponent
    },
    mounted() {
        if (localStorage.customers) {
            this.customers = JSON.parse(localStorage.customers)
            this.customers.forEach(this.countDebtors);
            if (localStorage.owing) {
                this.owing = localStorage.owing
            } else {
                this.loadOwing()
            }

            this.loading = false;
        }
    },
    data() {
        var d = new Date();
        return {
            month: d.getMonth() + 1,
            year: d.getFullYear(),
            customers: [],
            debtors: [],
            loading: true,
            error: '',
            search: '',
            form: new Form(),
            owing: 0,
        }
    },
    beforeDestroy() {
        window.dispatchEvent(new Event('close_sidebar_min'))
    },
    created() {
        this.loadCustomers();
        Fire.$on('customer_created', (data) => {
            this.loadCustomers();
        })
        Fire.$on('customer_deleted', (data) => {
            this.loadCustomers();
        })
        Fire.$on('customer_edited', (data) => {
            this.loadCustomers();
        })
        Fire.$on('view', (data) => {
            this.search = data.name;
            this.$refs.search.focus()
        })
        Fire.$on('transaction_created', (data) => {
            this.loadCustomers();
        })
        // Echo.channel('customer')
        //     .listen('UpdateCustomer', (e) => {
        //         this.loadCustomers();
        //     });
        // Echo.channel('transaction')
        //     .listen('UpdateTransaction', (e) => {
        //         this.loadCustomers();
        //     });
        // Echo.channel('order')
        //     .listen('UpdateOrder', (e) => {
        //         this.loadCustomers();
        //     });

    },
    watch: {},
    computed: {
        filteredCustomers() {
            var data = [];
            if (this.search) {
                data = this.customers.filter((item) => {
                    return item.name.toLowerCase().includes(this.search.toLowerCase());
                })
            } else {
                data = this.customers;
            }
            return data;
        },
    },
    methods: {
        loadCustomers() {
            this.form.get('/customers')
                .then(response => {
                    if (response.data.status == true) {
                        this.loading = false;
                        this.customers = response.data.data.item;
                        localStorage.customers = JSON.stringify(this.customers)
                        this.debtors = [];
                        response.data.data.item.forEach(this.countDebtors);
                    } else {
                        console.log("loadCustomer did not return positive response");
                    }
                })
                .catch(error => {
                    this.error = error.response.data.error;
                    console.log(error);
                });
            this.loadOwing()
        },
        countDebtors(customer) {
            if (customer.owing > 0) {
                this.debtors.push(customer);
            }
        },
        addComponent(event) {
            window.dispatchEvent(new Event('sidebar_min'))
            return true;
        },
        loadOwing() {
            this.form.get('/statistics/customers?owing')
                .then(response => {
                    this.owing = numeral(response.data.data.item[0].owing).format('0,0');
                    localStorage.owing = this.owing
                })
                .catch(error => {
                    if(error.respone){
                        this.error = error.response.data.error;
                    }
                    else{
                        this.error = 'An unexpected error occurred while loading owing amount';
                    }
                    
                });
        }
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