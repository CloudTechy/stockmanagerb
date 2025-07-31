<template>
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Checkout</h5>
                <button type="button" @click.prevent="closeComponent" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="card card-primary card-outline">
                <form role="form" ref="form" @keydown="form.onKeydown($event)" @submit.prevent='add'>
                    <div class="card-body">
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="row" v-if="customerStatus == false">
                                    <div class="col-md-12">
                                        <fieldset class="border border-warning p-2">
                                            <legend class="w-auto small font-weight-bold border bg-warning">Customer Details</legend>
                                            <div class="form-group row">
                                                <label for="name" class="col-sm-6 col-form-label">Customer details</label>
                                                <div class="col-sm-6">
                                                    <input id="customer_id" ref="customer_id" name="customer_id" list="customers" class="form-control" type="text" v-model="customer_id" required="">
                                                    <datalist size='5' id="customers">
                                                        <option v-for="customer in customers" :value="customer.id"> {{ `${customer.name} ${customer.number}` }}</option>
                                                    </datalist>
                                                </div>
                                            </div>
                                            <div v-if="customer_details">
                                                <h2 class="small p-2 text-center bg-warning font-weight-bold">Details</h2>
                                                <table class="table table-valign-middle">
                                                    <tbody class="text-center">
                                                        <tr>
                                                            <td class="font-weight-bold">Names</td>
                                                            <td>
                                                                {{ customer_details.name }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="font-weight-bold">Phone Number</td>
                                                            <td>
                                                                {{ customer_details.number }}
                                                            </td>
                                                        </tr>
                                                        <tr v-if="customer_details.address">
                                                            <td class="font-weight-bold">Address: </td>
                                                            <td>
                                                                {{ customer_details.address }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <div class="text-center small">
                                                    <button @click.prevent="nextStep()" type="button" ref="nextStep" class="btn btn-primary small">Checkout and Pay</button>
                                                </div>
                                                <h2 class="text-center m-1 p-1 text-info small">
                                                    Please note that your order would be placed at this point and your cart emptied
                                                </h2>
                                            </div>
                                            <div v-if="customer_details == '' && customer_id != ''">
                                                <li class="p-4 m-3 ">
                                                    <h4 class="text-center small text-secondary">Unknown Customer</h4>
                                                </li>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                                <div v-if="loading == true">
                                    <li class="p-4 m-3 border border-info">
                                        <h4 class="text-center small text-secondary">payment module loading...</h4>
                                    </li>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border  border-top-0 border-primary">
                <button v-if="loading == false" type="button" @click="closeComponent" ref="closeButton" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    mounted() {
        
        if (this.$root.checkoutCart) {
            this.cart = this.$root.checkoutCart;
            this.$root.checkoutCart = '';
        }
        if (this.$root.customer_details) {
            this.customer_id = this.$root.customer_details.id;
            this.customer_details = this.$root.customer_details
        }
        if (this.customer_details == "") {
            this.$root.alert('warning', 'CAUTION', 'Assign customer for checkout')
            console.log('No customer details found by mounted, redirecting to customers page')
            // Redirect to customers page if no customer details found with a 3 sec timeout
            setTimeout(() => {
                this.$router.push('/customers')
            }, 1000)
        }
        

    },
    created(){this.loadCustomers();},
    data() {
        return {
            form: new Form({
                order_id: '',
                orderDetails: [],
                customer_id: '',
            }),
            cart: [],
            builtCart: [],
            orders: '',
            customer_id: '',
            customers: [],
            customerStatus: false,
            customer_details: '',
            orderdetails: [],
            orderData: '',
            orderID: '',
            invoice_id: '',
            transaction_id: '',
            transaction: '',
            loading: false,
            SendOrderStatus: ""
        }
    },
    beforeDestroy() {
        this.cart = [];
        this.builtCart = [];
        this.customers = "";
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

        if (this.$refs.closeButton) {
            this.$refs.closeButton.click();
        }
        window.dispatchEvent(new Event('close_sidebar_min'))
    },
    watch: {
        customer_id() {
            this.loadCustomerDetails();
        },
        customers() {
            if (this.customer_id && this.customers) {
                this.loadCustomerDetails();
            }
        },
        customer_details() {
            if (!this.customer_details && this.customerStatus) {
                if (this.$root.customer_details && this.customer_id) {
                    this.customer_details = this.$root.customer_details
                    return
                }
                console.log('No customer details found by watch')
                this.$router.push('/customers') 
            }
        }
    },
    methods: {
        nextStep() {
            this.$Progress.start();
            this.buildCart(this.cart)
            this.form.customer_id = this.customer_id
            this.getOrders()
            this.customerStatus = true
            this.loading = true
        },
        loadCustomers() {
            this.form.get('/customers')
                .then(response => {
                    this.customers = response.data.data.item
                })
                .catch(error => {
                    if (error.response) {
                        this.$Progress.fail()
                        console.log(error.response)
                        this.$root.alert('error', 'error', error.response.data.message)
                    } else {
                        this.$root.alert('error', 'error', 'An unexpected error occured, Try again Later')
                        console.log(error);
                    }
                });
        },
        loadCustomerDetails() {
            var data = [];
            if (this.customer_id) {
                data = this.customers.filter((item) => {
                    var keys = Object.values(item)
                    var boolean = false
                    if (item == undefined) {
                        return false
                    }
                    var bool = keys.forEach((key) => {
                        if (key != null && key == this.customer_id) {
                            boolean = true
                        }
                    })
                    return boolean
                })
            } else {
                data = [];
            }
            this.customer_details = data[0];
        },
        getOrders() {
            this.$Progress.start();
            this.form.post('/orders')
                .then(response => {
                    this.$Progress.finish();
                    this.orders = response.data.data
                    this.orderID = this.orders.id
                    this.sendOrder()
                    this.$Progress.start()
                })
                .catch(error => {
                    this.$Progress.fail();
                    if (error.response) {
                        this.$Progress.fail()
                        console.log(error.response)
                        this.$root.alert('error', 'error', error.response.data.message)
                    } else {
                        this.$root.alert('error', 'error', 'An unexpected error occured, Try again Later')
                        console.log(error);
                    }
                });
        },
        closeComponent() {
            this.cart = [];
            this.builtCart = [];
            this.customers = "";
            this.customerStatus = false;
            this.orderData = "";
            this.customer_details = "";
            this.orderID = ""
            this.transaction_id = "";
            this.invoice_id = "";
            this.orders = "";
            this.loading = false
            this.form.reset();
            if (this.SendOrderStatus == "unknown") {
                this.$root.alert('warning', 'CAUTION', 'Check your stock for changes before next checkout')

                this.$emit('closeViewCheckoutCart')
            }
            if (this.transaction) {
                this.$emit('closeViewCheckoutCart')
            }

        },
        buildCart(cart) {

            cart.forEach((item) => {
                if (item) {
                    this.builtCart.push(item)
                }
            })

            this.builtCart.forEach((item) => {
                this.orderdetails.push({
                    [item.id]: `${item.quantity} ${item.price}` })
            })

        },
        sendOrder() {
            this.$Progress.start();
            this.form.orderDetails = this.orderdetails;
            this.form.order_id = this.orderID;

            this.form.post('/orderdetails')
                .then(response => {
                    this.$Progress.finish();
                    if (response.data.status) {
                        this.orderData = response.data.data
                        this.$emit('order_created', this.orderData)
                        this.$root.alert('success', 'success', 'Order has been placed')
                        this.loadPayment();
                    } else {
                        this.$Progress.fail()
                        this.$root.alert('error', 'error', 'An unexpected error occured, Try again Later')
                        console.log(response.data);

                    }
                })
                .catch(error => {
                    this.$Progress.fail();
                    if (error.response) {
                        this.$Progress.fail()
                        console.log(error.response)
                        this.$root.alert('error', 'error', error.response.data.message)
                    } else {
                        this.$root.alert('error', 'error', 'An unexpected error occured, Try again Later')
                        console.log(error);
                    }
                    this.SendOrderStatus = "unknown"
                });
        },
        loadPayment() {
            this.$Progress.start();
            let loader = this.$loading.show({});
            this.form.get('/orders/' + this.orderID)
                .then(response => {
                    this.$Progress.finish();
                    this.invoice_id = response.data.data.invoice_id
                    this.loadTransactionId()
                    loader.hide()
                })
                .catch(error => {
                    this.$Progress.fail();
                    loader.hide()
                    if (error.response) {
                        this.$Progress.fail()
                        console.log(error.response)
                        this.$root.alert('error', 'error', error.response.data.message)
                    } else {
                        this.$root.alert('error', 'error', 'An unexpected error occured, Try again Later')
                        console.log(error);
                    }
                });
        },
        loadTransactionId() {
            this.$Progress.start();
            let loader = this.$loading.show({});
            this.form.get('/invoices/' + this.invoice_id)
                .then(response => {
                    this.$Progress.finish();
                    loader.hide()
                    this.transaction_id = response.data.data.transaction_id
                    this.getTransaction();
                })
                .catch(error => {
                    this.$Progress.fail();
                    if (error.response) {
                        this.$Progress.fail()
                        loader.hide()
                        console.log(error.response)
                        this.$root.alert('error', 'error', error.response.data.message)
                    } else {
                        this.$root.alert('error', 'error', 'An unexpected error occured, Try again Later')
                        console.log(error.response);
                    }
                });
        },

        getTransaction() {
            this.$Progress.start();
            this.loading = false
            this.form.get('/transactions/' + this.transaction_id)
                .then(response => {
                    this.$Progress.finish()
                    this.transaction = response.data.data;
                    this.closeComponent()
                    this.$root.addTransactionComponent(this.transaction)
                })
                .catch(error => {
                    if (error.response) {
                        this.$Progress.fail()
                        console.log(error.response)
                        this.$root.alert('error', 'error', error.response.data.message)
                    } else {
                        this.$root.alert('error', 'error', 'An unexpected error occured, Try again Later')
                        console.log(error);
                    }
                });
        },
    }
}

</script>
