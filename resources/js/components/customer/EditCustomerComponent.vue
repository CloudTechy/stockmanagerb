<template>

    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h3 class="modal-title display-4 font-weight-bold small ">Edit Customer Details</h3>
                <button type="button" @click = "closeAddComponent" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="card card-primary card-outline">
                <form role="form" ref="form" @keydown="form.onKeydown($event)" @submit.prevent='add' >
                    <div class="card-body">
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-6">
                                        <fieldset class="border border-warning p-2">
                                            <legend  class="w-auto small font-weight-bold border bg-warning">Quick Edit</legend>
                                            <div class="form-group">
                                                <label for="name">Full Names</label>
                                                <input type="text" v-model="form.name" required="" class="form-control" ref="name" placeholder="Enter name">
                                                <has-error :form="form" field="name"></has-error>
                                            </div>
                                            <div class="form-group">
                                                <label for="number">Number</label>
                                                <input type=text v-model="form.number" required="" class="form-control" ref="number" placeholder="Enter number">
                                                <has-error :form="form" field="number"></has-error>
                                            </div>

                                        </fieldset>
                                        
                                    </div>
                                    <div class="col-md-6 ml-auto">
                                        <fieldset class="border border-warning p-2">
                                            <legend  class="w-auto small font-weight-bold border bg-warning">Optional</legend>
                                            <div class="form-group">
                                                <label for="email">Email </label>
                                                <input type="email" v-model="form.email"  class="form-control" ref="email" placeholder="Enter email">
                                                <has-error :form="form" field="email"></has-error>
                                            </div>
                                            <div class="form-group">
                                                <label for="note">Short Note </label>
                                                <input type="text" max="150" v-model="form.note"  class="form-control" ref="note" placeholder="Enter note">
                                                <has-error :form="form" field="note"></has-error>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border bordr border-top-0 border-primary">
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
                    name : '',
                    number:'',
                    email: undefined,
                    note: '',
                }),
                customer: '',
            }
        },
        created(){
            Fire.$on('edit_customer', (data)=> {this.form.fill(data);
             this.customer=data
         })
        },
        beforeDestroy(){
            this.$refs.closeButton.click()
            this.form.reset()
        },
        methods: {
            add(){
                this.$Progress.start();
                this.form.patch('./customers/'+this.customer.id)
                .then(response => {
                    this.$refs.closeButton.click()
                    window.dispatchEvent(new Event('close_sidebar_min'));
                    if(response.data.status == true){
                        Fire.$emit('customer_edited', response.data.data)
                        this.form.reset()
                        this.$Progress.finish()
                        this.$root.alert('success','success','customer edited')
                    }
                    else{
                        this.$Progress.fail()
                        this.$root.alert('error','error','An unexpected error occured, Try again Later')
                    }
                })
                .catch( error => {
                    this.$Progress.fail()
                    this.$root.alert('error','error',error.response.data.message)
                    var error = error.response.data.error;
                    console.log(error);
                    if(error.name){
                        this.$refs.name.classList.add('is-invalid');
                    }
                    if(error.note){
                        this.$refs.note.classList.add('is-invalid');
                    }
                    if(error.number){
                        this.$refs.number.classList.add('is-invalid');
                    }
                    if(error.email){
                        this.$refs.email.classList.add('is-invalid');
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