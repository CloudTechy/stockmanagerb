<template>
    <div id="example1_wrapper" class="dataTables_wrapper  container-fluid dt-bootstrap4">
        <div class="row">
            <div class="col-6 col-md-6">
                <div class="dataTables_length" id="example1_length">
                    <label>Entries:
                        <select v-model = "rowsPerPage" @change = "loadUsers" aria-controls="dataTables-example" class="form-control input-sm"> 
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
                    <label>Search:<input v-model="search" type="search" class="form-control form-control-sm" placeholder="search user" aria-controls="example1">
                    </label>
                </div>
            </div> 
        </div>
        <div class="row">
            <div class="col-sm-12" >
                <div class="table-responsive-sm table-responsive">
                <table  class="table table-small  table-hover dataTable" >
                    <thead>
                        <tr class="border border-warning" role="row " >
                            <th>Names</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>User Level</th>
                            <th>Created</th>
                            <th class="text-center">Action</th>

                        </tr>
                    </thead>
                    <tbody id="body">        
                        
                        <tr v-for = "user,index in pageLoader(current_page)">
                            <td class="text-capitalize">{{  user.names }}</td>
                            <td class="text-capitalize">{{  user.number }}</td>
                            <td class="text-capitalize">{{  user.email }}</td>
                            <td class="text-capitalize">{{  user.address }}</td>
                            <td class="text-capitalize">{{  user.user_level }}</td>
                            <td class="text-capitalize small">{{  user.date }}</td>
                            <td>
                                <div style="width: 100px;">
                                    <button  @click="loadEditUser(user,index)" type="button" title="edit this user" class="btn btn-outline-primary m-1"  data-toggle="modal" data-target="#editUserModal" ><i class="fas fa-pen"></i></button>
                                    <button @click="deleteUser(user.id,index+start)" type="button" title="deactivate this user" class="btn btn-outline-danger"><i class="fas fa-trash-alt"></i></button>
                                </div>
                            </td>
                            
                        </tr>
                         <tr v-if = "loading == false && pageLoader(current_page).length == 0">
                            <td colspan="4">
                                <h4 class="text-center">user Not Found</h4>
                            </td>
                        </tr>
                     
                    </tbody>
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

        <div class="modal fade" id="editUserModal"><edit-user-component></edit-user-component></div>

    </div>
</template>

<script>
    import EditUserComponent from '@/components/user/EditUserComponent.vue';
    export default {
        components: {
            EditUserComponent
        },
        mounted() {
            if(localStorage.users){
                this.users = JSON.parse(localStorage.users)
            }
        },
        data() { 
            var d = new Date();
            return {
                month : d.getMonth() + 1,
                year : d.getFullYear(),
                users : [],
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
        created(){
            this.$Progress.start()
            if(!localStorage.users){
                this.loadUsers();
            }
            Fire.$on('user_created', (data)=> {
                this.loadUsers();
            })
            Fire.$on('user_deleted', (data)=> {
                this.loadUsers();
            })
            Fire.$on('user_edited', (data)=> {
                this.loadUsers();
            })
            this.loadUsers();
            // Echo.channel('user')
            // .listen('UpdateUser', (e) => {
            //     this.loadUsers();
            // });
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
            loadUsers(){
                this.$Progress.start();
                
                this.form.get('/users?pageSize=1000000&activated=1')
                .then( response => {
                    if(response.data.status == true){
                        this.$Progress.finish()
                        Fire.$emit('Users_loaded', response.data.data)
                        this.users = response.data.data.item.length !=0 ? response.data.data.item : [];
                        localStorage.users = JSON.stringify(this.users)
                    }
                    else{
                        this.$Progress.fail()
                        this.$root.alert('error','error','An unexpected error occured, Try again Later')
                    }
                })
                .catch(error=> {
                    this.$Progress.fail()
                    var message = error.response.data.data.error.includes("No connection could be made") ? "No server connection" : error.response.data.message
                    this.$root.alert('error','error',message)
                    console.log(error.response.data.data.error)
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
                var data = this.$root.myFilter(this.users,this.search)
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
            loadEditUser(User,index){
                Fire.$emit('edit_user',User);
                window.dispatchEvent(new Event('sidebar_min'))
                return true;
            },
            deleteUser(id,index){
            this.$swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, deactivate account!'
                })
                .then((result) => {
                    if (result.value) {
                        this.$Progress.start();
                        this.form.delete('/users/'+id)
                        .then(response => {
                            if(response.data.status == true){
                                Fire.$emit('user_deleted', response.data.data)
                                this.$Progress.finish()
                                this.$root.alert('success','success','Account deactivated')
                            }
                            else{
                                this.$Progress.fail()
                                this.$root.alert('error','error','An unexpected error occured, Try again Later')
                            }
                        })
                        .catch( error => {
                            this.$Progress.fail()
                            var message = error.response.data.data.error.includes("No connection could be made") ? "No server connection" : error.response.data.message
                            this.$root.alert('error','error',message)
    
                            console.log(error.response.data.data.error)
                        })
                    }
                }) 
            },
        }
    }

</script>