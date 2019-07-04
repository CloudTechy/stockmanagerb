<template>
    <div id="example1_wrapper" class="dataTables_wrapper  container-fluid dt-bootstrap4">
        <div class="row">
            <div class="col-6 col-md-6">
                <div class="dataTables_length" id="example1_length">
                    <label>Entries:
                        <select v-model = "rowsPerPage" @change = "loadInvoices" aria-controls="dataTables-example" class="form-control input-sm"> 
                            <option value="5">5</option>
                            <option value="10">10</option> 
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select> 
                    </label>
                </div>
            </div>
            <div class="col-6 col-md-6">
                <div id="example1_filter" class="dataTables_filter float-right">
                    <label>Search:<input v-model="search" type="search" class="form-control form-control-sm" placeholder="search invoice" aria-controls="example1">
                    </label>
                </div>
            </div> 
        </div>
        <div class="row">
            <div class="col-sm-12" >
                <div class="table-responsive-sm table-responsive">
                <table  class="table table-small table-hover dataTable" >
                    <thead>
                        <tr role="row " >
                            <th>ID</th>
                            <th>Type</th>
                            <th>Order ID</th>
                            <th>Done By</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th class="text-center">Action</th>

                        </tr>
                    </thead>
                    <tbody id="body">
                        <tr v-for = "invoice,index in pageLoader(current_page)">
                            <td class="text-capitalize">{{  invoice.id }}</td>
                            <td class="text-capitalize">{{  invoice.type }}</td>
                            <td class="text-capitalize">{{  invoice.order_id }}</td>
                            <td class="text-capitalize">{{  invoice.user }}</td>
                            <td class="text-center">
                                <span v-bind:class="{badge:true, 'badge-danger':invoice.status == 'not-paid',  'badge-success' : invoice.status == 'paid', 'badge-warning':invoice.status == 'pending' }">
                                    {{ invoice.status }}
                                </span><br>
                                <span style="cursor: pointer" v-if = "invoice.status == 'not-paid' || invoice.status == 'pending'" class="badge text-center badge-secondary small strong">
                                    <div class=" text-center"  @click.prevent = "makeTransaction(invoice)">
                                        <button  title="make transaction" class=" btn-link badge badge-secondary btn-secondary small m-1">
                                              {{'pay?'}}
                                        </button>
                                    </div>
                                </span>
                            </td>
                            <td class="text-capitalize small">{{  invoice.date }}</td>
                            <td>
                                <div style="width: 110px;">
                                    <button @click="loadView(invoice,index+start)" type="button" title="view more" class="  m-1 btn btn-outline-info"><i class="fas fa-street-view"></i></button>
                                    <button @click="deleteData(invoice.id,index+start)" type="button" title="delete this invoice" class="btn btn-outline-danger"><i class="fas fa-trash-alt"></i></button>
                                </div>
                            </td>
                        </tr>
                         <tr v-if = "loading == false && pageLoader(current_page).length == 0">
                            <td colspan="7">
                                <li class="p-4 m-3 border border-info" v-if="loading == false && $root.myFilter(invoices,search).length == 0">
                                    <h4 class="text-center small text-secondary">Invoices Not Found</h4>
                                </li>
                            </td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6 col-md-6">
                <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">Showed {{ start + 1 }} to {{ end > length ? length : end }} of {{ length }} entries
                </div>
            </div>
            <div class="col-6 col-md-6">
                <div class="dataTables_paginate paging_simple_numbers float-right" id="example1_paginate">
                    <ul class="pagination">
                        <li class="paginate_button page-item previous" ref = "prev" id="example1_previous">
                            <a href="#" @click.prevent ="pageLoaderB(-1)" aria-controls="example1" data-dt-idx="0" tabindex="0" class="page-link">Previous
                            </a>
                        </li>
                        <li class="paginate_button bg-primary p-2 text-center page-item" style="display: inline; min-width: 70px"  v-if = "Math.floor(pages) > 6">
                            {{current_page + ' of ' + Math.floor(pages)}}
                        </li>
                        <li v-else v-for = "value in Math.floor(pages)" v-bind:class="classObject(value)" class="paginate_button page-item" >
                            <a  aria-controls="example1" data-dt-idx="1" tabindex="0" class="page-link" @click.prevent ="pageLoader(value)" href="#">{{ value }}</a>
                        </li>
                       
                        <li class="paginate_button page-item next" ref = "next" id="example1_next">
                            <a @click.prevent ="pageLoaderB(1)" href="#" aria-controls="example1" data-dt-idx="7" tabindex="0" class="page-link">Next
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- <div class="modal fade" id="editModal"><edit-invoice-component></edit-invoice-component></div> -->

    </div>
</template>

<script>
    
    export default {
        mounted() {
            if(localStorage.invoices){
                this.invoices = JSON.parse(localStorage.invoices)
            }
        },
        data() { 
            var d = new Date();
            return {
                month : d.getMonth() + 1,
                year : d.getFullYear(),
                invoices : [],
                loading : true,
                error : '',
                search : '',
                rowsPerPage: 5,
                current_page: 1,
                length: 0,
                pages : 0,
                form: new Form()
            }
        },
        watch : {
        },
        created(){
            this.$Progress.start()
            Fire.$on('invoice_created', (data)=> {
                this.loadInvoices();

            })
            Fire.$on('invoice_deleted', (data)=> {
                this.loadInvoices();
            })
            Fire.$on('invoice_edited', (data)=> {
                this.loadInvoices();
            })
            this.loadInvoices();
        },

        computed: {
            start(){
                if (this.pages > 0  && this.current_page  >=  this.pages ) {
                    this.current_page = this.pages
                }
                return parseInt(this.rowsPerPage * (this.current_page -1));
            },
            end(){
                return parseInt(this.rowsPerPage * (this.current_page - 1)) + parseInt(this.rowsPerPage);  
            }         
        },
        methods: {
            loadInvoices(){
                this.$Progress.start();
                var form = new Form()
                form.get('./api/invoices')
                .then( response => {
                    if(response.data.status == true){
                        this.$Progress.finish()
                        Fire.$emit('invoices_loaded', response.data.data)
                        this.invoices = response.data.data.item.length !=0 ? response.data.data.item : [];
                        localStorage.invoices = JSON.stringify(this.invoices)
                    }
                    else{
                        this.$Progress.fail()
                        this.$root.alert('error','error','An unexpected error occured, Try again Later')
                    }
                })
                .catch(error=> {
                    this.$Progress.fail()
                    var message = error.response.data.error.includes("No connection could be made") ? "No server connection" : error.response.data.message
                    this.$root.alert('error','error',message)
                    console.log(error.response.data.error)
                }); 
            },
            classObject (value) {
                if(value <= 1 && this.current_page == 1){
                    this.$refs.prev.classList.add('disabled')
                }
                else if(value <= 1 && this.current_page > 1){
                    this.$refs.prev.classList.remove('disabled')
                }
                if(this.current_page == this.pages){
                    this.$refs.next.classList.add('disabled')
                }
                else{
                  this.$refs.next.classList.remove('disabled')   
                }
                if (value == this.current_page) {
                    return "active";
                }
            },
            pageLoader(pageNumber){
                if(this.pages > 6){
                    this.$refs.prev.classList.remove('disabled')
                    this.$refs.next.classList.remove('disabled')
                }
                this.current_page = pageNumber;
                this.loading = false;
                var data = this.$root.myFilter(this.invoices,this.search)
                this.length = data.length;
                this.pages =  Math.ceil(data.length / this.rowsPerPage);
                return data.slice(this.start,this.end);
            },
            pageLoaderB(amount){
                if(this.current_page <= 1 && amount == -1){
                   this.current_page = 1; 
                }
                else if(this.current_page >= this.page){
                    this.current_page = this.page;
                }
                else{
                   this.current_page += amount;
                }
            },
            loadEdit(invoice,index){
                Fire.$emit('edit_invoice',invoice);
                window.dispatchEvent(new Event('sidebar_min'))
                return true;
            },
            loadView(data,index){
                Fire.$emit('view', data);
                return true;
            },
            deleteData(id,index){
            this.$swal({
                    title: 'Are you sure?',
                    text: "Note! You cannot delete a valid invoice",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete invoice!'
                })
                .then((result) => {
                    if (result.value) {
                        this.$Progress.start();
                        this.form.delete('./api/invoices/'+id)
                        .then(response => {
                            if(response.data.status == true){
                                Fire.$emit('invoice_deleted', response.data.data)
                                this.$Progress.finish()
                                this.$root.alert('success','success','invoice deleted ')
                            }
                            else{
                                this.$Progress.fail()
                                this.$root.alert('error','error','An unexpected error occured, Try again Later')
                            }
                        })
                        .catch( error => {
                            this.$Progress.fail()
                            var message = error.response.data.message
                            if(error.response.data.error.includes("No connection could be made")){
                                message = "No server connection"
                             }
                             else if(error.response.data.error.includes("Integrity constraint")){
                                message = "invoice is valid"
                             }

                            this.$root.alert('error','error',message)
    
                            console.log(error.response.data.error)
                        })
                    }
                }) 
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