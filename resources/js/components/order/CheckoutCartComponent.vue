<template>

  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Checkout</h5>
            <button type="button" @click.prevent = "closeComponent" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>


          <div class="card card-primary card-outline">
                <form role="form" ref="form" @keydown="form.onKeydown($event)" @submit.prevent='add' >
                    <div class="card-body">
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="row"  v-if = "customerStatus == false">
                                    <div class="col-md-12" >
                                        <fieldset class="border border-warning p-2">
                                            <legend  class="w-auto small font-weight-bold border bg-warning">Customer Info</legend>
                                            <div class="form-group">
                                                <label for="name ">Input Customer id:</label>
                                                    <input id= "customer_id" ref="customer_id" name = "customer_id" list = "customers" class="form-control" type="text" v-model = "customer_id" required="" >
                                                    <datalist id="customers">
                                                        <option  v-for = "customer in customers" :value="customer.id"></option>
                                                    </datalist>
                                            </div>
                                            <div v-if= "customer_details != ''" >
                                                <h2 class="small font-weight-bold">Details</h2>
                                                <div class="form-group">
                                                    <label for="number">Name</label>
                                                    <input  type=text v-model="customer_details[0].name" disabled =""  class="form-control" ref="name" placeholder="name">
                                                </div>
                                                <div class="form-group">
                                                    <label for="number">Number </label>
                                                    <input  type="text" v-model="customer_details[0].number" disabled ="" class="form-control" ref="number" placeholder="number">
                                                </div>
                                                <div class="text-center">
                                                    <button @click.prevent = "nextStep()" type="button" ref = "nextStep" class="btn btn-warning font-weight-bold">next</button>
                                                </div>
                                            </div>
                                            <div v-else> 
                                            	<li class="p-4 m-3 border border-info">
				                                    <h4 class="text-center small text-secondary">No Customer found</h4>
												</li>

                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                                <div v-if = "loading == true">
	                                <li class="p-4 m-3 border border-info">
	                                    <h4 class="text-center small text-secondary">payment module loading</h4>
	                                </li>
                                 </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>





            <div class="modal-footer border  border-top-0 border-primary">
              <button v-if = "loading == false"  type="button"  @click = "closeComponent" ref = "closeButton" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>

</template>
<script>
  export default {
    mounted(){
            if(this.$root.checkoutCart != undefined || this.$root.checkoutCart != ''){
            	console.log(this.$root.checkoutCart);
                this.cart = this.$root.checkoutCart;
                this.$root.checkoutCart = '';
            }
            this.loadCustomers();
        },
        data(){
          return{
            form : new Form({
            		order_id : '',
            		orderDetails:[],
            		customer_id : '',
            	}),
            cart: [],
            builtCart:[],
            orders: '',
            customer_id:'',
            customers: [],
            customerStatus: false,
            customer_details:'',
            orderdetails : [],
            orderData:'',
            orderID:'',
            invoice_id: '',
            transaction_id: '',
            transaction: '',
            loading : false,
          }
        },
        watch: {
            customer_id(){
            	var data = [];
              if(this.customer_id){
              data =  this.customers.filter((item)=>{


                var keys = Object.values(item)
                var boolean = false
                if(item == undefined){
                    return false
                }
                 var bool = keys.forEach((key) => {
                  
                  if(key != null && key == this.customer_id) {
                    boolean = true
                  }
                }) 
                 return boolean
              })
              }else{
                data = [];
              }
              this.customer_details = data;
                // this.form.get('./api/customers/'+ this.customer_id)
                // .then(response => {
                //     this.customer_details = response.data.data
                // })
            }
        },
        methods:{
        	nextStep(){
                this.buildCart(this.cart)
                this.form.customer_id = this.customer_id
        		this.getOrders()
        		this.customerStatus = true
        		this.loading = true
        	},
        	loadCustomers(){
        		this.form.get('./api/customers/')
                .then(response => {
                    this.customers =  response.data.data.item
                })

        	},
        	getOrders(){
        		this.form.post('./api/orders/')
                .then(response => {
                    this.orders =  response.data.data
                    this.orderID = this.orders.id
                    this.sendOrder()
                    this.$Progress.start()
                })
        	},
            closeComponent(){
            	this.cart = [];
            	this.builtCart = [];
            	this.customers ="";
            	this.customerStatus = false;
            	this.orderData = "";
            	this.customer_details = "";
            	this.orderID = ""
            	this.transaction_id = "";
            	this.invoice_id = "";
            	this.orders = "";
            	this.loading = false
            	this.form.reset();
            	this.$emit('closeViewCheckoutCart')
            	$('modal').hide();

            },
            beforeDestroy(){
            	this.cart = [];
            	this.builtCart = [];
            	this.customers ="";
            	this.customerStatus = false;
            	this.orderData = "";
            	this.customer_details = "";
            	this.orderID = ""
            	this.transaction_id = "";
            	this.invoice_id = "";
            	this.orders = "";
            	this.transaction = "";
            	this.form.reset();
            	this.loading = false;
            	$('modal').hide();

            },
            buildCart(cart){

            	cart.forEach((item)=>{
            		if(item != undefined){
            			this.builtCart.push(item)
            		}
            	})

            	this.builtCart.forEach((item) => {
            		this.orderdetails.push({[item.id] : item.quantity})
            	})
            	
            },
            sendOrder(){
	        	this.form.orderDetails = this.orderdetails;
	        	this.form.order_id = this.orderID;

	        	this.form.post('./api/orderdetails/')
	            .then(response => {
	                this.orderData =  response.data.data
	                this.$emit('order_created',this.orderData)
	                this.$root.alert('success', 'success','Order placed')
	                this.loadPayment();
	            })
	            .catch(error=> {
	                this.$Progress.fail()
	                console.log(error.response)
	            }); 
	        },
	        loadPayment(){
	        	this.form.get('./api/orders/'+this.orderID)
	            .then(response => {
	                this.invoice_id =  response.data.data.invoice_id
	                this.loadTransactionId()
	            })
	            // this.form.get('./api/invoices/'+this.invoice_id)
	            // .then(response => {
	            //     this.transaction_id =  response.data.data.transaction_id
	            //     console.log(response.data.data);
	            // })
	            // this.form.get('./api/transactions/'+this.transaction_id)
	            // .then(response => {
	            //    var  transaction =  response.data.data
	            //    console.log('transctiont',transaction);
	            //     //this.$root.addTransactionComponent(transaction);
	            // })
	        },
	        loadTransactionId(){
	        	this.form.get('./api/invoices/'+this.invoice_id)
	            .then(response => {
	                this.transaction_id =  response.data.data.transaction_id
	                this.getTransaction();
	        	})
	        },

	        getTransaction(){
	            this.loading = false
	        	this.form.get('./api/transactions/'+this.transaction_id)
	            .then(response => {
	            	this.$Progress.finish()
	                this.transaction = response.data.data;
	               	this.$refs.closeButton.click();
	                this.closeComponent()
	                this.$root.addTransactionComponent(this.transaction)
	        	})
	        }
    	}
        
  	}
</script>