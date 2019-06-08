<template>
  <div>
    <nav-component></nav-component>
    <sidebar-component></sidebar-component>
    <div class="content-wrapper" style="min-height: 606px;">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-12">
              <h1 class="m-0 text-dark">Invoice</h1>
            </div>
          </div>
        </div>
      </div>

      <div class="content">
        <div  class="container-fluid">
            <div class="card card-success card-outline m-auto ">
  			<div class="card-header">
  			    <h5 class="card-title"> Summary</h5>
  			</div>
	
				<div class="card-body no-print box-profile">
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
				</div>
        <hr>

        <div class="no-print">
				  <div class="card-header  mb-2">
		        <h5 class="card-title  text-primary">Manage Invoices</h5>
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
                      <div class="input-group input-group-sm float-right" >
                        <input  v-model="search" type="text" ref = 'search' name="table_search" class="p-2 form-control" placeholder="Search invoices">
                     </div>
                    </div> 
                    <div class="clearfix"> </div>
                    <ul class="products-list product-list-in-card pl-2 pr-2">

                      <li ref = 'invoice' v-if = "loading == false" v-for = "invoice in  $root.myFilter(invoices,search).slice(0,5)" class="item">
                        <div class="product-info">
                          <span class="users-list-date">{{ invoice.date }}</span>
                          <h3 href="javascript:void(0)" class="users-list-name"> <strong>{{ invoice.type=='order' ? 'Order ID: ':'Purchase ID: ' }}</strong>{{ invoice.type=='order' ? invoice.order_id : invoice.purchase_id }} </h3>
                          <span  class="badge float-right badge-warning small strong"> <span class="" style="text-decoration: line-through">N</span>{{ $root.numeral(invoice.total) }}
                          </span>
                          <strong  class="users-list-date">Amount</strong>
                          <span class="users-list-date small text-info font-weight-bold"><strong>Name: </strong>{{ invoice.name }}</span>
                          <span style="cursor: pointer" v-if = "invoice.status == 'pending' || invoice.status == 'not-paid'" class="badge float-right badge-secondary small strong">
                            <div class=" text-center"  @click.prevent = "makeTransaction(invoice)">
                                <button  title="make transaction" class=" btn-link badge badge-secondary btn-secondary small m-1">
                                      {{'pay?'}}
                                </button>
                            </div>
                          </span>
                          <span class="users-list-date"><strong>Contact: </strong>{{ invoice.number }}</span>
                          <span v-if="invoice.status == 'paid'" class='users-list-date badge badge-success'><strong>Balance :</strong>
                            <span class="" style="text-decoration: line-through">N</span>
                            {{ invoice.balance }}
                          </span>
                          <p v-if = 'invoice.details.length > 0' id = "accordion" class="mt-2" ref = 'accordion'> 
                            <button @click.prevent = "collapse(invoice.id)" class="btn btn-outline-success" type="button" >
                              view details
                            </button>
                          </p>
                        </div>

                        <div class=" collapse product-info" v-bind:id="invoice.id">
                          <invoice-detail-component :title = 'title' :invoice = 'invoice'></invoice-detail-component>
                          <print-bar-component :invoice = 'invoice'></print-bar-component>
                        </div>
                      </li>
                      <li class="p-4 m-3 border border-info" v-if="loading == false && $root.myFilter(invoices,search).length == 0">
                        <h4 class="text-center small text-secondary">Invoices Not Found</h4>
                      </li>
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
            },
         data() { 
            var d = new Date();
            return {
              month : d.getMonth() + 1,
              year : d.getFullYear(),
              invoices : [],
              pending: [],
              not_paid: [],
              paid: [],
              order_type : [],
              purchase_type:[],
              loading : true,
              error : '',
              search : '',
              form: new Form(),
              owed : '',
              title : 'RECENTLY ADDED',
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
                this.search = data.id;
                this.title = "INVOICE DETAILS"
                this.$refs.search.focus()
            })
            this.loadinvoices();
        },
        methods: {
            loadinvoices(){
                this.form.get('./api/invoices')
                .then (response =>{
                	if(response.data.status == true){
                		this.loading = false;
                    	this.invoices = response.data.data.item;
                    	response.data.data.item.forEach(this.count);
                	}
                	else{
                		console.log("load supplier did not return positive response");
                	}
                    
                })
                .catch (error => {
                    this.error = error.response.data.error;
                    console.log(error);
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
                this.form.get('./api/transactions/'+invoice.transaction_id)
                .then(response => {
                    this.transaction = response.data.data;
                    this.$root.addTransactionComponent(this.transaction)
                })
            }
            
        }

    }

</script>

<style  type="text/css">
	
	
   
</style>