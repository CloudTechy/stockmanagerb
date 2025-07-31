<template>
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Items in Cart</h5>
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

                          <li ref = 'product' v-for="product,index in cartLoader" class="item">
                            
                            <div class="product-info ml-0">
                              <a href="javascript:void(0)" class="product-title"> 
                                <span class="badge badge-warning float-right"> <span style="text-decoration: line-through">N</span> {{$root.numeral(product.price) }}</span>
                                <span class="product-description clearfix">{{product.product }}</span></a>
                              <span  v-bind:class = "{badge: true,small : true,'badge-success' :true, 'float-right' : true}"> {{ product.quantity }}</span>
                              <span  class="text-primary product-description small clearfix">Quantity</span>
                              <span class="badge badge-warning float-right">Amount: <span style="text-decoration: line-through">N</span> {{$root.numeral(product.price * product.quantity) }}</span>
                              <h3 href="javascript:void(0)" class="users-list-name font-weight-bold"> {{'Product ID: ' + product.id }} </h3>
                              
                              <div class="text-center p-1 mt-3 m-2"><button @click = "removeProduct(product,index,{index:product.index,quantity:product.quantity})" class="btn btn-outline-danger">Remove</button></div>
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
      this.cartLoader = this.cart;
    },
    props:['cart']
    ,
    data(){
      return{
        cartLoader: '',
        search: '',
        removedIndexes: [],
      }
    },
    beforeDestroy(){
        this.$refs.closeButton.click()
    },
    methods:{
      removeProduct(data, index, productIndex){
        if(this.cart != ''){
          this.cartLoader.splice(index, 1);
          this.removedIndexes.push(index)
          this.$emit('updateProduct',productIndex)
          this.$root.alert('success','success','product removed from cart')

        }
         else { console.log('cart not loaded');}
      },
      closeComponent(){
        this.$emit('closeCart')
        this.$emit('updateCart',this.cartLoader, this.removedIndexes)
      }
    },
  }
</script>