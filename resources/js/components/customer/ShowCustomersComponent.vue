<template>
    <div id="example1_wrapper" class="dataTables_wrapper  container-fluid dt-bootstrap4">
        <div class="row">
            <div class="col-6 col-md-6">
                <div class="dataTables_length" id="example1_length">
                    <label>Entries:
                        <select v-model = "rowsPerPage" @change = "loadCustomers" aria-controls="dataTables-example" class="border border-warning form-control input-sm"> 
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
                <div id="example1_filter" class="dataTables_filter  float-right">
                    <label>Search:<input v-model="search" type="search" class="form-control border border-warning form-control-sm" placeholder="search customer" aria-controls="example1">
                    </label>
                </div>
            </div> 
        </div>
        <div class="row">
            <div class="col-sm-12" >
                <div class="table-responsive-sm table-responsive">
                <table  class="table  table-hover table-small table-striped dataTable" >
                    <thead class="border  ">
                        <tr role="row " >
                            <th>ID</th>
                            <th>Names</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Debt</th>
                            <th>Added By</th>
                            <th>Created</th>
                            <th class="text-center">Action</th>

                        </tr>
                    </thead>
                    <tbody id="body">        
                        
                        <tr v-for = "customer,index in pageLoader(current_page)">
                            <td class="text-capitalize">{{  customer.id }}</td>
                            <td class="text-capitalize">{{  customer.name }}</td>
                            <td class="text-capitalize">{{  customer.number }}</td>
                            <td class="text-capitalize">{{  customer.email }}</td>

                            <td>
                                <span style="cursor: pointer" v-if = "customer.owing > 0 " class="  small   float-right  strong">
                                    <div class=""  @click.prevent = "getCustomerOrders(customer.id)" >
                                        <button  title="make transaction" class=" btn badge badge-success m-1 p-2" data-toggle="" data-target=""  >
                                              {{'Pay Now'}}
                                        </button>
                                    </div>
                                </span>
                                <span  v-if = "customer.owing > 0" v-bind:class="{badge:true, 'm-1' : true, 'p-2' : true, 'badge-danger':customer.owing > 0, 'badge-success' : customer.owing ==0}"><span class="users-list-date float-right text-white  text-capitalize "> <span class="" style="text-decoration: line-through">N</span>{{ $root.numeral(customer.owing)  }}</span></span><br>
                                
                            </td>

                            <td class="text-capitalize">{{  customer.added_by }}</td>
                            <td class="text-capitalize small">{{  customer.date }}</td>
                            <td>
                                <div style="width: 200px;">
                                    <button @click="createOrder(customer.id,index+start)" type="button" title="make order" class="btn btn-outline-success"><i class="fas fa-shopping-cart"></i></button>
                                    <button  @click="loadEditCustomer(customer,index)" type="button" title="edit this customer" class="btn btn-outline-primary m-1"  data-toggle="modal" data-target="#editCustomerModal" ><i class="fas fa-pen"></i></button>
                                    <!-- <button @click="loadView(customer,index+start)" type="button" title="view more" class="  m-1 btn btn-outline-info"><i class="fas fa-street-view"></i></button> -->
                                    <button @click="deleteCustomer(customer.id,index+start)" type="button" title="delete this customer" class="btn btn-outline-danger"><i class="fas fa-trash-alt"></i></button>
                                </div>
                            </td>
                            
                        </tr>
                         <tr v-if = "loading == false && $root.myFilter(customers,search).length == 0">
                            <td colspan="8">
                                <h4 class="text-center small text-secondary">Customer Not Found</h4>
                            </td>
                        </tr>
                     
                    </tbody>
                </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6 mt-sm-3 col-md-6">
                <div id="example1_info" role="status" aria-live="polite"> <span class="p-2 m-3  small border border-warning">Showing {{ start + 1 }} to {{ end > length ? length : end }} of {{ length }} entries</span>
                </div>
            </div>
            <div class=" mt-sm-3 col-6 col-md-6">
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

        <div class="modal fade" id="editCustomerModal"><edit-customer-component></edit-customer-component></div>

    </div>
</template>

<script>
    
    export default {
        mounted() {
            if(localStorage.customers){
                this.customers = JSON.parse(localStorage.customers)
            }
        },
        data() { 
            var d = new Date();
            return {
                month : d.getMonth() + 1,
                year : d.getFullYear(),
                customers : [],
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
            this.loadCustomers();
            Fire.$on('customer_created', (data)=> {
                this.loadCustomers();
            })
            Fire.$on('customer_deleted', (data)=> {
                this.loadCustomers();
            })
            Fire.$on('customer_edited', (data)=> {
                this.loadCustomers();
            })
            Fire.$on('transaction_created', (data)=> {
                this.loadCustomers();
            })
            Echo.channel('customer')
            .listen('UpdateCustomer', (e) => {
                this.loadCustomers();
            });
            Echo.channel('transaction')
            .listen('UpdateTransaction', (e) => {
                this.loadCustomers();
            });
            Echo.channel('order')
            .listen('UpdateOrder', (e) => {
                this.loadCustomers();
            });
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
            getCustomerOrders(customer_id){
                this.form.get('./api/orders?pageSize=10000000&customer_id='+customer_id)
                .then( response => {
                    if(response.data.status == true){
                        this.$root.addTransactionComponent(response.data.data.item,'order')
                    }
                    else{
                        this.$root.alert('error','error','could not fetch orders, refresh this page and try again')
                        return []
                    }
                })
                .catch(error=> {
                    this.$Progress.fail()
                    if(error.response){
                        var message = error.response.data.error.includes("No connection could be made") ? "No server connection" : error.response.data.message
                        this.$root.alert('error','error',message)
                         console.log(error.response.data.error)
                    }
                    else{
                        console.log(error.response);
                    }
                }); 
            },
            loadCustomers(){
                this.$Progress.start();
                var form = new Form()
                form.get('./api/customers?pageSize=10000000')
                .then( response => {
                    if(response.data.status == true){
                        this.$Progress.finish()
                        Fire.$emit('customer_loaded', response.data.data)
                        this.customers = response.data.data.item.length !=0 ? response.data.data.item : [];
                        localStorage.customers = JSON.stringify(this.customers)
                    }
                    else{
                        this.$Progress.fail()
                        this.$root.alert('error','error','An unexpected error occured, Try again Later')
                    }
                })
                .catch(error=> {
                    this.$Progress.fail()
                    if(error.response){
                        var message = error.response.data.error.includes("No connection could be made") ? "No server connection" : error.response.data.message
                        this.$root.alert('error','error',message)
                         console.log(error.response.data.error)
                    }
                    else{
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
                var data = this.$root.myFilter(this.customers,this.search)
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
            loadEditCustomer(customer,index){
                Fire.$emit('edit_customer',customer);
                window.dispatchEvent(new Event('sidebar_min'))
                return true;
            },
            loadView(data,index){
                Fire.$emit('view', data);
                return true;
            },
            createOrder(id,index){
                this.$root.OrderCustomerID = id;
                this.$router.push('/orders')

            },
            deleteCustomer(id,index){
            this.$swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Delete Customer!'
                })
                .then((result) => {
                    if (result.value) {
                        this.$Progress.start();
                        this.form.delete('./api/customers/'+id)
                        .then(response => {
                            if(response.data.status == true){
                                Fire.$emit('customer_deleted', response.data.data)
                                this.$Progress.finish()
                                this.$root.alert('success','success','Customer deleted')
                            }
                            else{
                                this.$Progress.fail()
                                this.$root.alert('error','error','An unexpected error occured, Try again Later')
                            }
                        })
                        .catch( error => {
                            this.$Progress.fail()
                            var message = error.response.data.error.includes("No connection could be made") ? "No server connection" : error.response.data.message
                            this.$root.alert('error','error',message)
    
                            console.log(error.response.data.error)
                        })
                    }
                }) 
            },
        }
    }

</script>