<template>
    
    <div class="modal-dialog  modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Edit user</h4>
            <button type="button" @click = "closeAddComponent" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="card card-primary card-outline">
                <form role="form" ref="form" @keydown="form.onKeydown($event)" @submit.prevent='add' >
                    <div class="card-body">
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="user_name">First Name</label>
                                            <input type="text" v-model="form.first_name"  class="form-control" ref="first_name" placeholder="Enter first_name">
                                            <has-error :form="form" field="first_name"></has-error>
                                        </div>
                                        <div class="form-group">
                                            <label for="user_name">Last Name</label>
                                            <input type="text" v-model="form.last_name"  class="form-control" ref="last_name" placeholder="Enter last_name">
                                            <has-error :form="form" field="last_name"></has-error>
                                        </div>
                                    </div>
                                    <div class="col-md-6 ml-auto">
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <input type="text" v-model="form.address"  class="form-control" ref="address" placeholder="Enter address">
                                            <has-error :form="form" field="address"></has-error>
                                        </div>
                                        <div class="form-group">
                                            <label for="user_level_id">User Group</label>
                                            <select v-model="form.user_level_id"  ref="user_level" class="custom-select" id="inputGroupSelect02">
                                                <option selected value="1">Standard</option>
                                                <option value="2">Administrator</option>
                                                <option value="3">Super-Administrator</option>
                                            </select>
                                            <has-error :form="form" field="user_level_id"></has-error>
                                        </div>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button @click = "closeAddComponent" type="button" ref = "closeButton" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" :disabled="form.busy" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
      
</template>

<script>
    import { 
      HasError,
      AlertError,
      AlertErrors, 
      AlertSuccess
    } from 'vform'
     
    import Vue from 'vue'
    Vue.component(HasError.name, HasError)
    Vue.component(AlertError.name, AlertError)
    Vue.component(AlertErrors.name, AlertErrors)
    Vue.component(AlertSuccess.name, AlertSuccess)
    
    export default {
        mounted(){
            $('.btn-group-toggle').button('toggle')
        },
        data() { 
            return {
                form : new Form({
                    last_name: '',
                    first_name: '',
                    address:'',
                    user_level_id : 1,
                    }),
                user:'',
                user_levels: [],
            }
        },
        created(){
            Fire.$on('edit_user', (data)=> {this.form.fill(data);
             this.user=data
             var names = data.names.split(" ")
             this.form.last_name = names[1];
             this.form.first_name = names[0];
             this.form.user_level_id = 1
         })
        },
        beforeDestroy(){
            this.$refs.closeButton.click();
            this.form.reset();
        }, 
        methods: {
            add(){
                this.$Progress.start();
                axios.patch('/users/'+this.user.id)
                .then(response => {
                    this.$refs.closeButton.click()
                    window.dispatchEvent(new Event('close_sidebar_min'));
                    if(response.data.data.status == true){
                        Fire.$emit('user_edited', response.data.data.data)
                        this.form.reset()
                        this.$Progress.finish()
                        this.$root.alert('success','success','user edited')
                    }
                    else{
                        this.$Progress.fail()
                        this.$root.alert('error','error','An unexpected error occured, Try again Later')
                    }
                })
                .catch( error => {
                    this.$Progress.fail()
                    this.$root.alert('error','error',error.response.data.message)
                    var error = error.response.data.data.error;
                    console.log(error);
                    if(error.last_name){
                        this.$refs.last_name.classList.add('is-invalid');
                    }
                    if(error.first_name){
                        this.$refs.first_name.classList.add('is-invalid');
                    }
                    if(error.username){
                        this.$refs.username.classList.add('is-invalid');
                    }
                    if(error.password){
                        this.$refs.password.classList.add('is-invalid');
                    }
                    if(error.user_level_id){
                        this.$refs.user_level_id.classList.add('is-invalid');
                    }
                    if(error.number){
                        this.$refs.number.classList.add('is-invalid');
                    }
                    if(error.address){
                        this.$refs.address.classList.add('is-invalid');
                    }
                    if(error.email){
                        this.$refs.email.classList.add('is-invalid');
                    }
                    if(error.activated){
                        this.$refs.activated.classList.add('is-invalid');
                    }
                }); 
            },
            closeAddComponent(){
                window.dispatchEvent(new Event('close_sidebar_min'));
                return true;
            }

        }

    }

</script>