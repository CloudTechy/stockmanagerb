<template>
    <div>
        <nav-component></nav-component>
        <sidebar-component></sidebar-component>
        <div class="content-wrapper" style="min-height: 606px;">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <h1 class="m-0 text-dark">Invoice Payment Module</h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="container-fluid">
                    <div class="card card-success card-outline m-auto ">
                        <!-- <div class="card-header">
                            <h5 class="card-title"> Summary</h5>
                        </div> -->
                        <!-- <div class="card-body no-print box-profile">
          <div class="row container text-center  mb-2 mt-4">

            <div class="col mb-3 text-center">
              <div class="circle border-success mb-lg-4"><div class="inner-circle border-secondary"><p  class=" circle-text">{{ invoices.length }}</p></div></div>
              <h4 class="profile-username ">Invoices</h4>
            </div>
            <div class="col mb-3 text-center">
              <div class="circle border-danger mb-lg-4"><div class="inner-circle border-secondary"><p class="circle-text">{{ not_paid.length }}</p></div></div>
              <h4 class="profile-username ">Not Paid</h4>
            </div>
            <div class="col mb-3 text-center">
              <div class="circle border-warning mb-lg-4"><div class="inner-circle border-secondary"><p class="circle-text">{{ pending.length }}</p></div></div>
              <h4 class="profile-username ">Pending</h4>
            </div>
            <div class="col mb-3 text-center">
              <div class="circle border-success mb-lg-4"><div class="inner-circle border-secondary"><p class="circle-text">{{ paid.length }}</p></div></div>
              <h4 class="profile-username ">Paid</h4>
            </div>
            <div class="col mb-3 text-center">
              <div class="circle border-success mb-lg-4"><div class="inner-circle border-secondary"><p class="circle-text">{{ order_type.length}}</p></div></div>
              <h4 class="profile-username ">Orders</h4>
            </div>
            <div class="col mb-3 text-center">
              <div class="circle border-success mb-lg-4"><div class="inner-circle border-secondary"><p class="circle-text">{{ purchase_type.length}}</p></div></div>
              <h4 class="profile-username ">Purchases</h4>
            </div>
          </div>
        </div> -->
                        <!-- <hr> -->
                        <div class="no-print">
                            <div class="card-header  mb-2">
                                <h5 class="card-title font-weight-bold text-secondary"> Invoice Manager </h5>
                            </div>
                            <show-invoices-component></show-invoices-component>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="card-body box-profile">
                                <div class="row container  mb-3 mt-3">
                                    <div class="col-lg-12 col-sm-12 mb-3 ">
                                        <div class="card-header">
                                            <h5 class="card-title">{{ title }}</h5>
                                        </div>
                                        <h2 class="card-title small"> &nbsp;</h2>
                                        <div class="">
                                            <div class="input-group input-group-sm float-right">
                                                <input v-model="search" type="text" ref='search' name="table_search" class="p-2 form-control" placeholder="Ex: D4d1ef50-Fb62-11ea-9326-Dda7ec05dd16">
                                            </div>
                                        </div>
                                        <div v-if = "invoice"  class="clearfix"> </div>
                                        <ul class="products-list product-list-in-card pl-2 pr-2">
                                            <li ref='invoice' v-if="loading == false  && invoice != '' " class="item">
                                                <div class="product-info">
                                                    <span class="users-list-date">{{ invoice.date }}</span>
                                                    <span v-bind:class="{badge:true,' float-right':true, 'badge-danger':invoice.status == 'not-paid',  'badge-success' : invoice.status == 'paid', 'badge-warning':invoice.status == 'pending' }">
                                                        {{ invoice.status }}
                                                    </span>
                                                    <h3 href="javascript:void(0)" class="users-list-name"> <strong>{{ invoice.type=='order' ? 'Order ID: ':'Purchase ID: ' }}</strong>{{ invoice.type=='order' ? invoice.order_id : invoice.purchase_id }} </h3>
                                                    <span class="badge float-right badge-warning small strong"> <span class="" style="text-decoration: line-through">N</span>{{ $root.numeral(invoice.total) }}
                                                    </span>
                                                    <strong class="users-list-date">Amount</strong>
                                                    <span class="users-list-date small text-info font-weight-bold"><strong>Name: </strong>{{ invoice.name }}</span>
                                                    <span style="cursor: pointer" v-if="invoice.status == 'pending' || invoice.status == 'not-paid'" class="  small   float-right  strong">
                                                        <div class="" @click.prevent="makeTransaction(invoice)">
                                                            <button title="make transaction" class=" btn badge badge-success m-1 p-2" data-toggle="" data-target="">
                                                                {{'Pay Now'}}
                                                            </button>
                                                        </div>
                                                    </span>
                                                    <!-- <span style="cursor: pointer" v-if="invoice.status == 'pending' || invoice.status == 'not-paid'" class="badge float-right badge-secondary small strong">
    <div class=" text-center" @click.prevent="makeTransaction(invoice)">
        <button title="make transaction" class=" btn-link badge badge-secondary btn-secondary small m-1">
            {{'pay?'}}
        </button>
    </div>
</span>
 -->
                                                    <span class="users-list-date"><strong>Contact: </strong>{{ invoice.number }}</span>
                                                    <span v-if="invoice.status == 'paid'" class='users-list-date badge badge-success'><strong>Balance :</strong>
                                                        <span class="" style="text-decoration: line-through">N</span>
                                                       {{  $root.numeral(invoice.balance) }}
                                                    </span>
                                                    <h3 v-if="invoice.status == 'paid'"  href="javascript:void(0)" class="users-list-name">Payment is made by <span class="text-capitalize">{{invoice.payment_mode}}</span> </h3>
                                                    <p id="accordion" class="mt-2" ref='accordion'>
                                                        <button @click.prevent="collapse(invoice.id)" class="btn btn-outline-success" type="button">
                                                            view details
                                                        </button>
                                                    </p>
                                                </div>
                                                <div class=" collapse product-info" v-bind:id="invoice.id">
                                                    <invoice-detail-component :title='title' :invoice='invoice'></invoice-detail-component>
                                                    <print-bar-component :invoice='invoice' ></print-bar-component>
                                                </div>
                                            </li>
                                           <!--  <li class="p-4 m-3 border border-info" v-if="loading == false && $root.myFilter(invoices,search).length == 0">
                                                <h4 class="text-center small text-secondary">Invoices Not Found</h4>
                                            </li> -->
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        mounted() {
          if(localStorage.invoices){
            this.invoices = JSON.parse(localStorage.invoices)
            this.invoices.forEach(this.count); 
            this.loading = false;
          }
          window.scrollTo(0, 0)
        },
        data() { 
            var d = new Date();
            return {
              month : d.getMonth() + 1,
              year : d.getFullYear(),
              invoices : [],
              invoice : "",
              pending: [],
              not_paid: [],
              paid: [],
              order_type : [],
              purchase_type:[],
              loading : true,
              error : '',
              search : '',
              form: new Form(),
              title : 'VIEW INVOICE',
            }
        },
        beforeDestroy() {
            window.dispatchEvent(new Event('close_sidebar_min'))
        },
        created(){
            Fire.$on('invoice_created', (data)=> {
                this.loadinvoices();

            })
            Fire.$on('invoice_deleted', (data)=> {
                this.loadinvoices();
            })
            Fire.$on('invoice_edited', (data)=> {
                this.loadinvoices();
            })
            Fire.$on('view', (data)=> {
              this.loading = true
              this.invoice = ""
              this.search = data.id;
              this.loadInvoice(data.id)
              this.title = "INVOICE DETAILS"
              setTimeout(()=>this.$refs.search.focus(),1000)

            })

            // this.loadinvoices();
            // Echo.channel('invoice')
            // .listen('UpdateInvoice', (e) => {
            //     this.loadInvoices();
            // });
        },
        methods: {
            loadInvoice(id){
              this.$Progress.start()
              this.loadind = true
              this.search = id;
              this.form.get('/invoices/' + id)
                .then(response => {
                  window.dispatchEvent(new Event('sidebar_min'))
                  this.$Progress.finish()
                  this.invoice = response.data.data
                  this.loading = false
                })
                .catch(error=>{
                  this.$Progress.fail()
                  this.error = error.response.data.error;
                  console.log(error.response);
                  this.loading = false
                })
            },

            loadinvoices(){
              this.$Progress.start()
                this.loading = true
                this.form.get('/invoices')
                .then (response =>{
                  this.$Progress.finish()
                  if(response.data.status == true){
                    this.loading = false;
                      this.invoices = response.data.data.item;
                      this.paid = []
                      this.pending = []
                      this.not_paid = []
                      this.order_type = []
                      this.purchase_type = []
                      response.data.data.item.forEach(this.count);
                      localStorage.invoices = JSON.stringify(this.invoices)
                      this.loading = false
                      
                  }
                  else{
                    console.log("load supplier did not return positive response");
                    this.loading = false
                  }
                    
                })
                .catch (error => {
                    this.error = error.response.data.error;
                    console.log(error.response);
                    this.loading = false
                    this.$Progress.fail()
                }); 
            },
            collapse(id){
              $('#' + id).toggle('collapse')
            },

            count(invoice){
              if(invoice.status == 'paid'){
                this.paid.push(invoice);
              }
              else if (invoice.status == 'pending') {
                this.pending.push(invoice);
              }
              else if (invoice.status == 'not-paid') {
                this.not_paid.push(invoice);
              }
              if(invoice.type == 'order'){
                this.order_type.push(invoice);
              }
              else if(invoice.type == 'purchase'){
                this.purchase_type.push(invoice);
              }
            },
             makeTransaction(invoice){
              this.$Progress.start()
                this.form.get('/transactions/'+invoice.transaction_id)
                .then(response => {
                    this.transaction = response.data.data;
                    this.$root.addTransactionComponent(this.transaction)
                })
            }
            
        }

    }

</script>
<style type="text/css">
</style>
