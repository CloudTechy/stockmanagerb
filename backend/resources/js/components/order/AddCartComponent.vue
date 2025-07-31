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
                                                <td class="font-weight-bold">Item: </td>
                                                <td>
                                                   <div class="form-group">
                                                        <input  type=text v-model="form.name" disabled =""  class="form-control" ref="name" placeholder="name">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">Price: </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input title="you can change the price if you may"  type="text" v-model="form.price" class="form-control" ref="price" placeholder="price">
                                                        <label class="small">Current price   <span style="text-decoration: line-through">N</span> {{$root.numeral(product.price) }}</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">Quantity: </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input  type="number" :max = "product.stock" v-model="form.quantity" required="" min="1" class="form-control" ref="quantity" placeholder="Enter quantity">
                                                         <label class="small">Remaining {{product.stock}}pc(s)</label>
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
            if(this.$root.product){
                this.product = this.$root.product;
                this.index = this.$root.index;
                this.product.quantity = 0;
                this.form.name = this.product.product;
                this.form.price = this.product.price
                this.$refs.quantity.focus();
                this.$root.product = '';
            }
        },
        data() {
            return{
                form : new Form({
                    name: '',
                    price : '',
                    quantity: '',
                }),
                product : {},
            }
        },
        computed : {
            amount(){
                return this.form.price != "" && this.form.quantity != "" ? this.form.price * this.form.quantity : 0
            }
        },
        beforeDestroy(){
            this.product = {};
            this.product.quantity = 0
            this.form.reset();
            this.$refs.closeButton.click();

        },
        methods:{
            add(){
                if(this.product.stock < this.form.quantity){
                    this.$root.alert('error','error','not enough stock for this product')
                }
                else{
                    var product = this.product.id;
                    var quantity = this.form.quantity;
                    this.product.quantity = this.form.quantity
                    this.product.price = this.form.price
                    this.product.amount = this.amount 
                    this.$emit('cart_created',this.product)
                    this.$refs.closeButton.click();
                    this.closeComponent()
                }
                
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