<template>
    <div class="modal-dialog  modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Add Size</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="card card-primary card-outline">
                
                 <form role="form" ref="form" @keydown="form.onKeydown($event)" @submit.prevent='addSize' >
                    <div class="card-body">
                        <div class="form-group">
                            <label for="size_name"  class="text-capitalize">size Name</label>
                            <input type="text" v-model="form.name" required="" class="form-control" ref="size_name" placeholder="Enter size name">
                            <p v-if="errors.name" v-for="error in errors.name" class="text-danger m-0 p-2">{{error}}</p>
                        </div>
                        <div class="form-group">
                            <label for="size_description"  class="text-capitalize">size Description</label>
                            <input v-model = "form.description" type="text" class="form-control" ref="size_description" placeholder="Enter size description">
                            <p v-if="errors.description" v-for="error in errors.description" class="text-danger m-0 p-2">{{error}}</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" ref = "closeButton" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit":disabled="form.busy" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
          </div>
        </div>
    </div>   	
</template>
<script>
    
    export default {
        data() { 
            return {
               	form : new Form({
                    name: '',
                    description:'No Description Yet',
                }),
                errors : {}
            }
        },
        beforeDestroy(){
            this.$refs.closeButton.click();
            this.form.reset();
        },
        methods: {
            addSize(){
                this.$Progress.start();
                this.form.post('/sizes')
                .then(response => {
                    this.$refs.closeButton.click()
                    if(response.data.status == true){
                        Fire.$emit('size_created', response.data.data)
                        this.form.reset()
                        this.$refs.size_name.classList.remove('is-invalid')
                        this.$refs.size_description.classList.remove('is-invalid');
                        this.$Progress.finish()
                        this.$root.alert('success','success','size created')
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
                    this.errors = error
                    console.log(error);
                    if(error.name){
                        this.$refs.size_name.classList.add('is-invalid');
                    }
                    if (error.description) {
                        this.$refs.size_description.classList.add('is-invalid');
                    }
                }); 
            },
        }
    }

</script>