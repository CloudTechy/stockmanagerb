<template>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Add Product to Cart</h5>
            <button type="button" @click.prevent = "closeComponent" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="card card-primary card-outline">
                <form role="form" ref="form" @keydown="form.onKeydown($event)" @submit.prevent='add' >
                        <div class="modal-body">
                            <div class="container-fluid">
                                
                                <fieldset class="border border-warning p-2">

                                    <legend  class="w-auto small font-weight-bold border bg-warning">Product Details</legend>
                                    <table class="table table-valign-middle">
                                        <tbody>
                                            <tr>
                                                <td class="font-weight-bold">Product Name: </td>
                                                <td>
                                                   <div class="form-group">
                                                        <input  type=text v-model="form.name" disabled =""  class="form-control" ref="name" placeholder="name">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">Brand: </td>
                                                <td>
                                                   <div class="form-group">
                                                        <input  type=text v-model="form.brand" disabled =""  class="form-control" ref="brand" placeholder="brand">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">Size: </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input  type="text" v-model="form.size" disabled ="" class="form-control" ref="size" placeholder="size">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">Quantity: </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input  type="number" v-model="form.quantity" required="" min="1" class="form-control" ref="quantity" placeholder="quantity">
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </fieldset>
                                
                            </div>
                        </div>

                    <div class="modal-footer border  border-top-0 border-primary">
                        <button  type="button"  @click = "closeComponent" ref = "closeButton" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" :disabled="form.busy" class="btn btn-success">Add to Cart</button>
                    </div>
                </form>
          </div>
        </div>
      </div>
    </div>
</template>


<script>
    export default {
        mounted(){
            if(this.$root.product != undefined || this.$root.product != ''){
                this.product = this.$root.product;
                this.index = this.$root.index;
                this.product.quantity = 0;
                this.form.name = this.product.name;
                this.form.category = this.product.category;
                this.form.brand = this.product.brand;
                this.form.size = this.product.size
                this.$root.product = '';
            }
        },
        data() {
            return{
                form : new Form({
                    name: '',
                    category: '',
                    brand : '',
                    size : '',
                    quantity: '',
                }),
                product : {},
            }
        },
        methods:{
            add(){

                if(this.product.stock < this.form.quantity){
                    this.$root.alert('error','error','not enough stock for this product')
                }else{
                    var product = this.product.id;
                    var quantity = this.form.quantity;
                    this.product.quantity = this.form.quantity
                    this.$emit('cart_created',this.product)
                    //this.$emit('cart_created',{[product]:quantity})
                    this.$refs.closeButton.click();
                    this.closeComponent()
                }
                
            },
            beforeDestroy(){
                this.product = {};
                this.product.quantity = 0
                this.form.reset();
            },
            closeComponent(){
                this.product = {};
                this.product.quantity = 0
                this.form.reset();
                this.$emit('closeCart')
            }
        },
    }
</script>