<template>
    <div>
        <nav-component></nav-component>
        <sidebar-component></sidebar-component>
        <div class="modal fade" id="addSupplierComponent">
            <add-supplier-component></add-supplier-component>
        </div>
        <div class="content-wrapper" style="min-height: 606px;">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <h1 class="m-0 text-dark">Vendor</h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="container-fluid">
                    <div class="card card-success card-outline m-auto ">
                        <div class="card-header">
                            <h5 class="card-title mt-1 m-2">Account Summary</h5>
                        </div>
                        <div class="card-body box-profile">
                            <div class="row container text-center  mb-2 mt-4">
                                <div class="col-lg-3 col-sm-6 mb-3 text-center">
                                    <div class="circle  mb-lg-4">
                                        <div class="inner-circle border-success">
                                            <p class=" circle-text">{{ suppliers.length }}</p>
                                        </div>
                                    </div>
                                    <h4 class="profile-username ">Suppliers</h4>
                                </div>
                                <div class="col-lg-3 col-sm-6 mb-3 text-center">
                                    <div class="circle mb-lg-4">
                                        <div class="inner-circle border-danger">
                                            <p class="circle-text">{{ creditors.length }}</p>
                                        </div>
                                    </div>
                                    <h4 class="profile-username ">Creditors</h4>
                                </div>
                                <div class="col-lg-3 col-sm-6 mb-3 text-center">
                                    <div class="circle mb-lg-4">
                                        <div class="inner-circle border-success">
                                            <p class="circle-text">{{ suppliers.length - creditors.length }}</p>
                                        </div>
                                    </div>
                                    <h4 class="profile-username ">Non Creditors</h4>
                                </div>
                                <div class="col-lg-3 col-sm-6 mb-3 text-center">
                                    <div class="circle mb-lg-4">
                                        <div class="inner-circle border-danger">
                                            <p class="circle-text"><span class=""
                                                    style="text-decoration: line-through">N</span>{{ owed }}</p>
                                        </div>
                                    </div>
                                    <h4 class="profile-username ">Total in Debt</h4>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div>
                            <div class="card-header mb-2">
                                <h5 class="card-title mt-1 m-2">Manage suppliers</h5>
                            </div>
                            <show-suppliers-component></show-suppliers-component>
                        </div>
                        <hr>
                        <!-- <div class="row">
            <div class="card-body box-profile">
              <div class="row container  mb-3 mt-3">
                <div class="col-lg-12 col-sm-12 mb-3 ">
                  <div class="card-header">
                      <h5 class="card-title mt-1 m-2">RECENTLY ADDED</h5>
                  </div>
                  <h2 class="card-title mt-1 m-2 small"> &nbsp;</h2>
                  <div class="">
                          <div class="input-group input-group-sm float-right" >
                            <input  v-model="search" type="text" ref = 'search' name="table_search" class="p-2 form-control" placeholder="Search suppliers">
                         </div>
                     </div> 
                     <div class="clearfix"> </div>
                        
                        <ul class="products-list product-list-in-card pl-2 pr-2">
                          <li ref = 'supplier' v-if = "loading == false" v-for = "supplier in  $root.myFilter(suppliers,search).slice(0,5)" class="item">
                            <div class="product-img">
                              <img v-bind:src=" 'img/user.png'" alt="supplier Image" class="rounded-circle">
                            </div>
                            <div class="product-info">
                              <span class="users-list-date">
                                {{ supplier.date }}
                              </span>
                              <span class="users-list-date font-weight-bold">
                                {{ supplier.id }}
                              </span>
                              <span v-if = "supplier.owed > 0" v-bind:class="{'users-list-date' : true, 'float-right':true, badge:true, small:true, 'badge-danger':supplier.owed > 0, 'badge-success' : supplier.owed ==0}">owed <span class="" style="text-decoration: line-through">N</span>
                                {{ $root.numeral(supplier.owed) }}
                              </span>
                              <h3 href="javascript:void(0)" class="users-list-name">{{supplier.name }} </h3>
                              <span style="cursor: pointer" v-if = "supplier.owed > 0 && supplier.purchases.length > 0" class="badge float-right badge-secondary small strong">
                                <div class="small"  @click.prevent = "$root.addTransactionComponent(supplier.purchases,'purchase')" >
                                    <button  title="make transaction" class=" btn-link badge badge-secondary btn-secondary small m-1" data-toggle="" data-target=""  >
                                          {{'pay ' + supplier.purchases.length + ' purchase(s)'}}
                                    </button>
                                </div>
                              </span>
                              <span class="users-list-date">
                                {{ supplier.phone }}</span>
                              </span>
                              <span class="users-list-date">
                                {{ supplier.address }}</span>
                              </span>
                              <span class="users-list-date">
                                {{ supplier.email }}
                              </span>
                              <span v-if = "supplier.owed > 0" v-bind:class="{'users-list-date' : true, 'float-right':false, badge:true, small:true,  'badge-warning' : supplier.due_date != null}">
                                {{'Due Date: ' + supplier.due_date }}
                              </span>
                              <span v-bind:class="{'users-list-date' : true, 'float-right':true, badge:true, small:true, 'badge-danger':supplier.is_stock_available == 0, 'badge-success' : supplier.is_stock_available ==1}">
                                {{ supplier.is_stock_available ? 'stock available' : 'out of stock' }}
                              </span>
                              <span v-if = "supplier.bank_details.length != 0" class="users-list-date small text-info font-weight-bold m-1">
                                {{ supplier.bank_details[0].bank + '-' + supplier.bank_details[0].account_name + '-' + supplier.bank_details[0].account_number  }}
                              </span>
                            </div>
                         </li>
                            <li class="p-4 m-3 border border-info" v-if = "loading == false && filteredSuppliers.length == 0">
                                <h4 class="text-center small text-secondary">Suppliers Not Found</h4>
                            </li>
                        </ul>
                      
                </div>
              </div>
            </div>
          </div>-->
                    </div>
                </div>
            </div>
        </div>
        <div style="position: fixed; bottom: 25px; right: 25px">
            <button type="button" @click="addSupplierComponent" class="btn btn-success  rounded-circle"
                title="Add new supplier" id="add" data-toggle="modal" data-target="#addSupplierComponent"><i
                    class="fa fa-plus"></i> </button>
        </div>
    </div>
</template>
<script>
import NavComponent from '@/components/NavComponent.vue';
import SidebarComponent from '@/components/SidebarComponent.vue';
import ShowSuppliersComponent from '@/components/supplier/ShowSuppliersComponent.vue';
import AddSupplierComponent from '@/components/supplier/AddSupplierComponent.vue';


export default {
    components: {
        NavComponent,
        SidebarComponent,
        ShowSuppliersComponent,
        AddSupplierComponent
    },
    mounted() {
        if (localStorage.suppliers) {
            this.suppliers = JSON.parse(localStorage.suppliers)
            this.suppliers.forEach(this.countDebts);
            if (localStorage.owed) {
                this.owed = localStorage.owed
            }
            else {
                this.loadOwed()
            }

            this.loading = false;
        }
    },
    data() {
        var d = new Date();
        return {
            month: d.getMonth() + 1,
            year: d.getFullYear(),
            suppliers: [],
            creditors: [],
            loading: true,
            addView: false,
            error: '',
            search: '',
            form: new Form(),
            owed: '',
        }
    },
    created() {
        this.loadSuppliers();
        Fire.$on('supplier_created', (data) => {
            this.loadSuppliers();
            this.addView = false
        })
        Fire.$on('supplier_deleted', (data) => {
            this.loadSuppliers();
        })
        Fire.$on('transaction_created', (data) => {
            this.loadSuppliers();
        })
        Fire.$on('transaction_created', (data) => {
            this.loadSuppliers();
        })
        Fire.$on('product_created', (data) => {
            this.loadSuppliers();
        })
        Fire.$on('view', (data) => {
            this.search = data.name;
            this.$refs.search.focus()
        })
        // Echo.channel('supplier')
        // .listen('UpdateSupplier', (e) => {
        //     this.loadSuppliers();
        // });
        // Echo.channel('transaction')
        // .listen('UpdateTransaction', (e) => {
        //     this.loadSuppliers();
        // });
        // Echo.channel('product')
        // .listen('UpdateProduct', (e) => {
        //     this.loadSuppliers();
        // });
    },
    watch: {
    },
    computed: {
        filteredSuppliers() {
            var data = [];
            if (this.search) {
                data = this.suppliers.filter((item) => {
                    return item.name.toLowerCase().includes(this.search.toLowerCase());
                })
            } else {
                data = this.suppliers;
            }
            return data;
        },
    },
    methods: {
        loadSuppliers() {
            this.form.get('./suppliers')
                .then(response => {
                    if (response.data.status == true) {
                        this.loading = false;
                        this.suppliers = response.data.data.item;
                        localStorage.suppliers = JSON.stringify(this.suppliers)
                        this.creditors = [];
                        response.data.data.item.forEach(this.countDebts);
                        window.dispatchEvent(new Event('sidebar_min'))
                    }
                    else {
                        console.log("load supplier did not return positive response");
                    }

                })
                .catch(error => {
                    this.error = error.response.data.error;
                    console.log(error);
                });
            this.loadOwed()
        },

        countDebts(supplier) {
            if (supplier.owed > 0) {
                this.creditors.push(supplier);
            }
        },
        addSupplierComponent(event) {
            window.dispatchEvent(new Event('sidebar_min'))
            this.addView = true
            return true;
        },
        loadOwed() {
            this.form.get('./statistics/suppliers?owed')
                .then(response => {
                    if (response.data.data.item[0]) {
                        this.owed = numeral(response.data.data.item[0].owed).format('0,0');
                        localStorage.owed = this.owed
                    }
                    else {
                        this.owed = 0
                    }

                })
                .catch(error => {
                    this.error = error.response.data.error;
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
