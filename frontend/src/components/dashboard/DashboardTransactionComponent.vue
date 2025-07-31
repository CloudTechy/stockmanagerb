<template>
    <div class="card card-warning card-outline">
        <div class="card-header">
          <h3 class="card-title">Recent Transactions</h3>
           <div class="card-tools">
                <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-widget="remove"><i class="fa fa-times"></i></button>
         </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper  container-fluid dt-bootstrap4">
                <div class="row">
                    <div class="col-6 col-md-6">
                        <div class="dataTables_length" id="example1_length">
                            <label>Entries:
                                <select v-model = "rowsPerPage" @change = "loadTransactions" aria-controls="dataTables-example" class="form-control input-sm"> 
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
                            <label>Search:<input v-model="search" type="search" class="form-control form-control-sm" placeholder="search " aria-controls="example1">
                            </label>
                        </div>
                    </div> 
                </div>
                <div class="row">
                    <div class="col-sm-12" >
                        <div  class="table-responsive-sm table-responsive ">
                            <table  class="table table-bordered table-small table-hover table-striped dataTable" >
                                <thead class="text-center ">
                                    <tr role="row" class="text-center">
                                        <th>Cashier</th>
                                            <th>Amount <br> (<span style="text-decoration: line-through">N</span>)</th>
                                            <th>Payment <br> (<span style="text-decoration: line-through">N</span>)</th>
                                           <th>Payment mode</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            
                                        </tr>
                                </thead>
                                <tbody id="body">
                                    <tr v-for = " transaction in pageLoader(current_page) ">
                                        <td>{{ transaction.user }}</td>
                                        <td>{{numeral(transaction.amount) }}</td>
                                        <td>{{numeral(transaction.payment) }}</td>
                                        <td>{{ transaction.payment_mode }}</td>
                                        <td class="p-sm-1 text-center" v-if = "transaction.amount == transaction.payment ">
                                           <span class="badge badge-success">paid</span>
                                        </td>
                                        <td class="p-sm-1 text-center" v-else-if = "transaction.amount > transaction.payment && transaction.payment > 0">
                                           <span class="badge badge-warning">pending</span><br>
                                           <div  class="small"  @click.prevent = "$root.addTransactionComponent(transaction)" >
                                                <button  title="make transaction" class=" btn-link badge badge-secondary btn-secondary small m-1" data-toggle="" data-target=""  >
                                                      {{'pay' }}
                                                </button>
                                            </div>
                                        </td>
                                        <td class="p-sm-1 text-center" v-else = "transaction.payment == 0">
                                            <span class="badge badge-danger">not-paid
                                            </span><br>
                                           <div class="small"  @click.prevent = "$root.addTransactionComponent(transaction)" >
                                                <button  title="make transaction" class=" btn-link badge badge-secondary btn-secondary small m-1" data-toggle="" data-target=""  >
                                                      {{'pay' }}
                                                </button>
                                            </div>
                                        </td>
                                        <td>{{ transaction.updated_at }}</td>
                                        
                                        
                                    </tr>
                                    <tr v-if = "loading == false && pageLoader(current_page).length == 0">
                                        <td colspan="7">
                                            <h4  class="text-center m-1 p-2 border border-info small text-success">Invoice Not Found</h4>
                                          </td>
                                    </tr>
                                    <tr v-if = "pageLoader(current_page).length > 0 ">
                                        <td colspan="1">
                                           <span class="small font-weight-bold text-success"> {{ "Summary" }}</span>
                                        </td>
                                    <td colspan="1">
                                       <span class="small font-weight-bold text-success">
                                        <span style="text-decoration: line-through">N</span>{{ $root.numeral(pageLoader(current_page).sum('amount')) }}</span>
                                    </td>
                                    <td colspan="5">    
                                         <span class="font-weight-bold badge badge-success">
                                            <span style="text-decoration: line-through">N</span>
                                           {{ $root.numeral(pageLoader(current_page).sum('payment')) }}
                                        </span>
                                    </td>
                                </tr>
                                 
                                </tbody>
                                <tfoot class="text-center">
                                <tr>
                                    <th>operator</th><th>Amount</th><th>Payment</th><th>Status</th><th>Date</th>

                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 col-md-6">
                        <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">Showing {{ start + 1 }} to {{ end > length ? length : end }} of {{ length }} entries
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

            </div>
        </div>  <!-- /.card-body -->
        <div class="modal fade" id="addTransactionComponentR">
            <add-transaction-component></add-transaction-component>
        </div>
        <div class="card-footer clearfix">
            <a  @click.prevent = "$root.addTransactionComponent()" href="javascript:void(0)" class="btn btn-sm btn-primary float-left">Make Payment</a>
           <router-link to="/transactions" class="btn btn-sm btn-secondary float-right">More Info</router-link>
        </div>
        <div v-if = "loading" class=" overlay">
            <div style = "position:absolute; top:50%; left:50%; "><i class="fas fa-sync-alt fa-spin"></i></div>
        </div>
    </div>

</template>

<script>
    import AddTransactionComponent from '@/components/transaction/AddTransactionComponent.vue';

    export default {
        components: {
            AddTransactionComponent,
        },
        mounted() {
            if(localStorage.transactions){
                this.transactions =  JSON.parse(localStorage.transactions)
                this.loading = false;
            }
            this.loadTransactions();
            },
        data() { 
            var d = new Date();
            return {
                d: d,
                month : d.getMonth() + 1,
                year : d.getFullYear(),
                transactions : [],
                loading : true,
                error : '',
                search : '',
                rowsPerPage: 5,
                current_page: 1,
                length: 0,
                pages : 0,
                form : new Form(),
            }

        },
        created(){
          Fire.$on('transaction_created', data => {
            this.loadTransactions();
          })
          // Echo.channel('transaction')
          //   .listen('UpdateTransaction', (e) => {
          //       this.loadTransactions();
          //   });
        },
        watch : {
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
            loadTransactions(){
               
                this.form.get('/transactions/?pageSize=1000000')
                .then( response => {
                    if(response.data.status == true){
                        this.transactions = response.data.data.item.length !=0 ? response.data.data.item : [];
                        localStorage.transactions = JSON.stringify(this.transactions)
                    }
                })
                .catch( error =>  {
                    if(error.response){
                        this.error = error.response.data.data.error;
                    } else {
                        this.error = 'An error occurred while loading information.';
                        console.log(error);
                    }
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
                var data = this.$root.myFilter(this.transactions,this.search)
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
            numeral(value){
                return numeral(value).format('0,0');
            },
        }
    }

</script>