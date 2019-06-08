<template>

    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h3 class="modal-title display-4 font-weight-bold small ">Make Payment</h3>
                <button type="button" @click = "closeAddModalComponent" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="card card-primary card-outline">
                <form role="form" ref="form" @keydown="form.onKeydown($event)" @submit.prevent='add' >
                    <div class="card-body">
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="row">

                                    <div v-if = "transaction != undefined && transactionView == true"  class="col-md-12">
                                        <fieldset class="border border-warning p-2">
                                            <legend  class="w-auto small font-weight-bold border bg-warning">Make Quick Payment</legend>
                                            <table class="table table-valign-middle">
                                              <tbody>
                                                <tr>
                                                    <td class="font-weight-bold">Invoice ID: </td>
                                                    <td>
                                                       {{  transaction.invoice_id }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">Total: </td>
                                                    <td>
                                                        <span class="" style="text-decoration: line-through">N</span>
                                                       {{  $root.numeral(transaction.amount) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">Paid: </td>
                                                    <td>
                                                        <span class="" style="text-decoration: line-through">N</span>
                                                        {{ $root.numeral(transaction.payment) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">Amount: </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="number" v-model="form.payment" required="" class="form-control" ref="payment" placeholder="Enter the amount you wish to pay">
                                                            <has-error :form="form" field="payment"></has-error>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bold">Due Date: </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="date" v-model="form.due_date" class="form-control" ref="due_date" placeholder="Enter the amount you wish to pay">
                                                            <has-error :form="form" field="due_date"></has-error>
                                                        </div>
                                                    </td>
                                                </tr>

                                              </tbody>
                                            </table>
                                            
                                        </fieldset>

                                    </div>
                                    <div v-if = 'normalView == true && transaction == ""' class="col-md-12 ml-auto">
                                        <fieldset class="border border-warning p-2">
                                            <legend  class="w-auto small font-weight-bold border bg-warning">Process Transaction</legend>
                                            <table class="table table-valign-middle">
                                              <tbody>
                                                <tr v-if = "transaction_type == ''">
                                                    <td class="font-weight-bold">Transaction Type: </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <select v-model="form_transaction_type" ref="transaction_type" class="custom-select mr-sm-2" id="inlineFormCustomSelect">
                                                                <option value="orders" selected>Order</option>
                                                                <option value="purchases">Purchase</option>
                                                            </select>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr v-if="transaction_type != ''  && idTypes != '' ">
                                                    <td class="font-weight-bold">{{ 'Input ' + transaction_type + ' ID' }}</td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input id= "idType" ref="idType" name = "idType" list = "idTypes" class="form-control" type="text" v-model = "form_typeid" required="" >
                                                            <datalist id="idTypes">
                                                                <option  v-for = "type in idTypes" :value="type.id"></option>
                                                            </datalist>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="text-center">
                                                    <td class="font-weight-bold" colspan="2">
                                                        <button @click.prevent = "nextStep()" type="button" ref = "nextStep" class="btn btn-warning">next</button>
                                                    </td>
                                                </tr>
                                              </tbody>
                                            </table>
                                        </fieldset>
                                    </div>
                                     <div v-if = "invoice != undefined && invoiceView == true"  class="col-md-12">
                                        <fieldset class="border border-warning p-2">
                                            <legend  class="w-auto small font-weight-bold border bg-warning">Invoice preview</legend>
                                            <invoice-detail-component :title = '"Details"' :invoice = 'invoice'></invoice-detail-component>
                                            <print-bar-component :invoice = 'invoice'></print-bar-component>
                                        </fieldset>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border bordr border-top-0 border-primary">
                        <button @click = "closeAddModalComponent" type="button" ref = "closeButton" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button v-if = '!invoiceView' type="submit" :disabled="form.busy" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>      	
</template>
<script>
    
    export default {
        created(){
            // Fire.$on('create_transaction',(data) => {
            //     console.log(data);
            //     if(data.transaction != undefined || data.transaction != ''){
            //         this.transaction = data.transaction
            //         this.form.invoice_id = this.transaction.invoice_id
            //         this.transactionView = true
            //         this.normalView = false
            //     }
                
            // })

            if(this.$root.transaction != undefined){
                    this.transaction = this.$root.transaction
                    this.form.invoice_id = this.transaction.invoice_id
                    this.transactionView = true
                    this.normalView = false
            }
             if(this.$root.orderIDs != undefined){
                    this.idTypes = this.$root.orderIDs
                    // this.form.invoice_id = this.idTypes.invoiceId
                    this.transaction_type = 'orders'
                    this.transactionView = false
                    this.normalView = true
            }
            else if(this.$root.purchaseIDs != undefined){
                    this.idTypes = this.$root.purchaseIDs
                    // this.form.invoice_id = this.idTypes.invoiceId
                    this.transaction_type = 'purchases'
                    this.transactionView = false
                    this.normalView = true
            }
            else if(this.$root.invoice != undefined){
                    this.invoice = this.$root.invoice
                    this.loadInvoiceView();
            }
            else{
                this.normalView = true
            }
            Fire.$on('printing',(data) => {
                $('Modal').modal('hide')
                this.transaction = ''
                this.idTypes = ''
                this.transactionView = false;
                this.normalView = false;
                this.invoice = '';
                this.form.reset()
                this.$root.transaction = undefined
                this.$root.orderIDs = undefined
                this.$root.purchaseIDs = undefined
                this.form.invoice_id = ''
                this.transaction_type = ''
                this.form.invoice_id = ''
            })
        },
        data() { 
           
            return {
               	form : new Form({
                    invoice_id:  '',
                    payment:  "",
                    due_date: "",
                    }),
                error: '',
                transaction : '',
                invoice:'',
                transactionView: false,
                invoiceView : false,

                normalView : true,
                transaction_type: '',
                form_transaction_type: '',
                form_typeid: '',
                idTypes : '',
            }

        },
            
        methods: {
            add(){
                this.$Progress.start();
                this.form.patch('./api/transactions/'+this.transaction.id)
                .then(response => {
                    console.log(response.data.status);
                    if(response.data.status == true){
                        this.$Progress.finish()
                        this.$root.alert('success','success','payment received ')
                        this.loadInvoice(this.transaction.invoice_id);
                    }
                    else{
                        this.$root.alert('danger','success','transaction created but error occured')
                        console.log(response.data);
                    }
                })
                .catch( error => {
                    this.$Progress.fail()
                    this.$root.alert('error','error',error.response.data.error)
                    var error = error.response.data.error;
                    console.log(error);
                    if(error.payment){
                        this.$refs.payment.classList.add('is-invalid');
                    }
                    if(error.due_date){
                        this.$refs.due_date.classList.add('is-invalid');
                    }
                }); 
            },
            closeAddModalComponent(){
                Fire.$emit('transaction_created', this.transaction)
                window.dispatchEvent(new Event('close_sidebar_min'));
                this.transaction = ''
                this.idTypes = ''
                this.transactionView = false;
                this.invoice = '';
                this.form.reset()
                if(this.$route.path == "/payment"){

                    this.$router.go(-1)
                }
                return true;
            },
            loadInvoice(invoice_id){
                this.form.get('./api/invoices/'+this.transaction.invoice_id)
                .then(response => {
                    if(response.data.status == true){
                        this.invoice = response.data.data
                        this.loadInvoiceView();
                    } 
                })
                .catch( error => {
                    var error = error.response.message;
                    console.log(error);
                })

            },
            beforeDestroy(){
                this.transaction = ''
                this.idTypes = ''
                this.transactionView = false;
                this.normalView = true;
                this.invoice = '';
                this.form.reset()
                this.$root.transaction = undefined
                this.$root.orderIDs = undefined
                this.$root.purchaseIDs = undefined
                this.form.invoice_id = ''
                this.transaction_type = ''
                this.form.invoice_id = ''
            },
            loadInvoiceView(){
                this.transaction = ''
                this.transactionView = false
                this.invoiceView = true
                this.normalView = false
            },
            nextStep(){
                if(this.form_transaction_type != '' && this.transaction_type == ''){
                    
                    this.transaction_type = this.form_transaction_type
                    this.load(this.form_transaction_type)
                }
                else if(this.transaction_type != '' || this.form_typeid != ''){
                    
                    this.idTypes = '';
                    this.form.get('./api/'+this.transaction_type+'/'+this.form_typeid)
                    .then(response => {
                        if(response.data.status == true){
                            var transaction_id = response.data.data.transaction_id
                            this.loadTransaction(transaction_id)
                        } 
                    })
                    .catch( error => {
                        var error = error.response;
                        console.log(response);
                    })
                }
            },
            load(type){
                this.form.get('./api/'+type)
                 .then(response => {
                    if(response.data.status == true){
                        this.idTypes = response.data.data.item
                    } 
                })
                .catch( error => {
                    var error = error.response.error;
                    console.log(error);
                })
            },
            loadTransaction(id){
                this.form.get('./api/transactions/'+id)
                 .then(response => {
                    if(response.data.status == true){
                        this.normalView = false
                        this.transactionView = true
                        this.transaction = response.data.data
                    } 
                })
                .catch( error => {
                    var error = error.response.error;
                    console.log(error);
                })
            }
        }
    }

</script>