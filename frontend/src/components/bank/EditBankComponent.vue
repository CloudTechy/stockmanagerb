<template>

    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Edit Bank</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
                
                 <form role="form" ref="form" @keydown="form.onKeydown($event)" @submit.prevent='editBankAxios' >
                    <div class="card-body">
                        <div class="form-group">
                            <label for="bank_name" class="text-capitalize">bank Name</label>
                            <input type="text" v-model="form.name"  class="form-control" ref="bank_name" placeholder="Enter bank name">
                            <has-error :form="form" field="name"></has-error>
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
      
</template>
<script>

    
    export default {
        created(){
            Fire.$on('edit_bank', (data)=> {this.form.fill(data); this.bank = data})
        },
        data() { 
            return {
               	form : new Form({
                    name: '',
                }),
                bank: ''
            }
        },
        beforeDestroy(){
            this.$refs.closeButton.click()
            this.form.reset()
        },
        methods: {
            
            editBankAxios(){
                this.$Progress.start();
                    this.form.patch('/banks/'+this.bank.name)
                    .then(response => {
                        this.$refs.closeButton.click()

                        if(response.data.status == true){
                            Fire.$emit('bank_edited')
                            this.form.reset()
                            this.$Progress.finish()
                            this.$root.alert('success','success','bank edited')
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
                            this.$refs.bank_name.classList.add('is-invalid');
                        }
                }); 
            },

        }

    }

</script>