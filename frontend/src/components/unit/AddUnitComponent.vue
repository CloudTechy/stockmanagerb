<template>

    <div class="modal-dialog  modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Add Unit</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="card card-primary card-outline">
                
                 <form role="form" ref="form" @keydown="form.onKeydown($event)" @submit.prevent='addUnit' >
                    <div class="card-body">
                        <div class="form-group">
                            <label for="unit_name" class="text-capitalize">unit Name</label>
                            <input type="text" v-model="form.name" required="" class="form-control" ref="unit_name" placeholder="Enter unit name">
                            <p v-if="errors.name" v-for="error in errors.name" class="text-danger m-0 p-2">{{error}}</p>
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
                }),
                errors : {}
            }
        },
        beforeDestroy(){
            this.$refs.closeButton.click();
            this.form.reset();
        }, 
        methods: {
            addUnit(){
                this.$Progress.start();
                this.form.post('/units')
                .then(response => {
                    this.$refs.closeButton.click()
                    if(response.data.status == true){
                        Fire.$emit('unit_created', response.data.data)
                        this.form.reset()
                        this.$refs.unit_name.classList.remove('is-invalid')
                        this.$Progress.finish()
                        this.$root.alert('success','success','unit created')
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
                        this.$refs.unit_name.classList.add('is-invalid');
                    }
                }); 
            },
        }
    }

</script>