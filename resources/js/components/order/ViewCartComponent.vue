<template>
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Add Product to Cart</h5>
            <button type="button" @click.prevent = "closeComponent" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="card card-primary card-outline">
              <div class="modal-body">
                  <div class="container">
    
                    <div class="row">
                      <div class=" col-12 card-body ">
                        <ul class="products-list product-list-in-card pl-2 pr-2">

                          <li ref = 'product' v-if ="product != undefined" v-for = "product,index in  $root.myFilter(cart,search)" class="item">
                            
                            <div class="product-info">
                              <a href="javascript:void(0)" class="product-title"> <span class="small">{{product.name }}</span>
                                <span class="badge badge-warning float-right"> <span style="text-decoration: line-through">N</span> {{$root.numeral(product.price) }}</span></a>
                              <span class="product-description small">
                                {{ product.category +  ", " + product.brand + ", " + product.size + ", " + product.unit}}.
                              </span>
                              <span v-bind:class = "{badge: true,small : true,'badge-success' :true, 'float-right' : true}"> {{ product.quantity }}</span>
                              <span  class="text-primary product-description small">Quantity
                              </span>
                              <h3 href="javascript:void(0)" class="users-list-name font-weight-bold"> {{'Product ID: ' + product.id }} </h3>
                              <span class="badge badge-warning float-right">Subtotal: <span style="text-decoration: line-through">N</span> {{$root.numeral(product.price * product.quantity) }}</span></a>
                              <div class="text-center p-2 mt-3 m-2"><button @click = "removeProduct(product,index)" class="btn btn-outline-danger">Remove</button></div>
                            </div>
                          </li>
                          <li class="p-4 mt-3 border border-info" v-if="$root.myFilter(cart,search).length == 0">
                            <h4 class="text-center small text-secondary">Cart is Empty</h4>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
              </div>

            <div class="modal-footer border m-lg-0  border-top-0 border-primary">
              <button  type="button"  @click = "closeComponent" ref = "closeButton" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
    </div>

</template>
<script>
  export default {
    mounted(){
            if(this.$root.cart != undefined || this.$root.cart != ''){
                this.cart = this.$root.cart;
                this.$root.cart = '';
            }
        },
        data(){
          return{
            cart: '',
            search: '',
            removedIndexes: [],
          }
        },
        methods:{
            removeProduct(data, index){
              if(this.cart != ''){
                console.log('viewcart index', index);
                this.cart.splice(index, 1);
                this.removedIndexes.push(index)
                this.$root.alert('success','success','product removed from cart')

              }
               else { console.log('cart not loaded');}
            },
            closeComponent(){
                this.$emit('closeCart')
                this.$emit('updateCart',this.cart, this.removedIndexes)
            }
        },
  }
</script>