<template>
    <div class="modal-dialog modal-dialog-centered modal-lg" 
         role="dialog" 
         aria-labelledby="modalTitle" 
         :inert="!addProductShow" 
         ref="addPurchaseModal">
      <div class="modal-content">
        <div class="modal-header text-center">
          <h3 id="modalTitle" class="modal-title display-5 font-weight-bold">
            <i class="fa fa-arrow-down text-success"></i>  Inbound Product 
          </h3>
          <button type="button" 
                  @click.prevent="closeComponent" 
                  class="close" 
                  aria-label="Close">
            &times;
          </button>
        </div>
        <div class="card card-primary card-outline">
          <form role="form" 
                ref="form" 
                @keydown="form.onKeydown($event)" 
                @submit.prevent="add(product)">
            <div class="card-body">
              <div class="modal-body p-0">
                <div class="container-fluid">
                  <div class="row">
                    <div v-if="cart.length > 0" class="col-md-12 p-0 m-0">
                      <fieldset class="border border-info p-1">
                        <legend class="p-2 w-auto small font-weight-bold border text-info">
                          Add product to cart <i class="fa fa-shopping-cart"></i>
                        </legend>
                        <div class="form-row">
                          <div class="col-12 col-md-6">
                            <label class="small" for="itemName">Input name of item</label>
                            <input v-model="name" 
                                   list="products" 
                                   class="form-control" 
                                   required 
                                   type="search" 
                                   ref="name" 
                                   placeholder="Ex: 300-18 Sports Ordinary tyre">
                            <datalist ref="datalist" id="products"></datalist>
                          </div>
                          <div class="col-6 col-md-3">
                            <label class="small" for="costPrice">Cost Price</label>
                            <input class="form-control" 
                                   type="number" 
                                   title="You can change the price if you wish" 
                                   v-model="product.price" 
                                   ref="price" 
                                   required 
                                   placeholder="Enter Price">
                            <label v-if="product.price != undefined" class="small text-danger">
                              Selling at <span style="text-decoration: line-through">N</span>{{ $root.numeral(product.price) }}
                            </label>
                          </div>
                          <div class="col-6 col-md-3">
                            <label class="small" for="quantity">Quantity</label>
                            <input type="number" 
                                   v-model="cartItem.quantity" 
                                   required 
                                   min="1" 
                                   class="form-control" 
                                   ref="quantity" 
                                   placeholder="How many pc(s)">
                            <label v-if="product.stock != undefined" class="small text-danger">
                              Remaining {{ product.stock }} pc(s)
                            </label>
                          </div>
                        </div>
                        <div class="text-center mt-3 m-2">
                          <button type="submit" 
                                  ref="nextStep" 
                                  class="font-weight-bold btn btn-block btn-info small">
                            Add to Cart <i class="fa fa-shopping-cart"></i>
                          </button>
                        </div>
                      </fieldset>
                    </div>
                  </div>
                  <div style="max-height: 37vh; overflow: auto;" class="row mt-3">
                    <div class="col-sm-12 p-0 m-0">
                      <h3>
                        Bill Adjustment <i class="fas fa-cog fa-spin text-info"></i>
                       
                      </h3>
                      <div class="clearfix"></div>
                      <div class="table-responsive">
                        <table class="table border border-info table-bordered table-small table-striped table-hover dataTable">
                          <thead class="bg-info">
                            <tr role="row">
                              <th class="text-center p-1">Index</th>
                              <th class="text-center p-1">Name</th>
                              <th class="text-center p-1">Quantity</th>
                              <th class="text-center p-1">Price</th>
                              <th class="text-center p-1">Amount</th>
                            </tr>
                          </thead>
                          <tbody id="body">
                            <tr v-for="(item, index) in cart" 
                                :key="item.index" 
                                @dragend="deleteItemOnCart(item, index, {index: item.index, quantity: item.quantity})" 
                                @dblclick="deleteItemOnCart(item, index, {index: item.index, quantity: item.quantity})" 
                                title="Double click or swipe to remove this item from cart">
                              <td class="text-center p-1 small text-capitalize">{{ index + 1 }}</td>
                              <td class="text-center p-1 small text-capitalize">{{ item.name }}</td>
                              <td contenteditable class="text-center p-1 small text-capitalize" 
                                  @input="editItemOnCart($event, item, index, 'quantity', item.index)">
                                {{ item.quantity }}
                              </td>
                              <td contenteditable class="text-center p-1 small text-capitalize" 
                                  @input="editItemOnCart($event, item, index, 'price', item.index)">
                                {{ item.price }}
                              </td>
                              <td :ref="'amount' + item.index" class="text-center p-1 small text-capitalize">
                                {{ $root.numeral(item.amount) }}
                              </td>
                            </tr>
                            <tr v-if="cart.length == 0">
                              <td colspan="5">
                                <h4 class="text-center small text-secondary">No item(s) in cart yet, add items</h4>
                              </td>
                            </tr>
                          </tbody>
                          <tfoot><tr><td colspan="5">
                            <h4> <span class="float-right">Total amount: <span class="text-success" style="text-decoration: line-through">N</span> {{$root.numeral(CartTotalAmount)}}</span></h4>
                          </td></tr></tfoot>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
          <div class="modal-footer border pt-0 pb-1 border-top-0 border-primary">
            <button @click="closeComponent" 
                    type="button" 
                    ref="closeButtonPurchase" 
                    class="btn btn-danger" 
                    data-dismiss="modal">Close
            </button>
            <button type="submit" 
                    @click.prevent="processCheckout" 
                    data-toggle="modal" 
                    data-target="#addProductComponent" 
                    :disabled="cart.length == 0" 
                    class="btn btn-info">Checkout
            </button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    created() {
      window.dispatchEvent(new Event('sidebar_min'));
      if (localStorage.purchasesCart) {
        this.cart = JSON.parse(localStorage.purchasesCart);
      }
    },
    mounted() {
      this.addProductShow = false;
      this.modalTrigger = document.activeElement;  // Store the current active element
    },
    data() {
      return {
        form: new Form(),
        name: "",
        product: {},
        cart: [],
        editMode: false,
        cartCopy: [],
        cartItem: {},
        index: "",
        addProductShow: false,
        CartTotalAmount: 0,
        productHash: new Set(),
      };
    },
    beforeDestroy() {
      this.product = {};
      this.product.quantity = "";
      this.form.reset();
      if (this.cart.length > 0) {
        localStorage.purchasesCart = JSON.stringify(this.cart);
      }
      window.dispatchEvent(new Event('close_sidebar_min'));
      this.$refs.closeButtonPurchase.click();
    },
    computed: {
      cartLoader() {
        return this.cart;
      },
      amount() {
        return this.cartItem.sale_price != "" && this.cartItem.quantity != "" ? 
               this.cartItem.sale_price * this.cartItem.quantity : 0;
      },
      productsRevised() {
        return this.products.map(({ product, stock }) => ({ product, stock }));
      }
    },
    watch: {
      name() {
        this.search();
        this.product = {};
        this.cartItem = {};
        if (this.name === "") {
          this.product = {};
          this.cartItem = {};
          return;
        }
        this.products.some((item, index) => {
          if (item.product === this.name) {
            this.product = item;
            this.cartItem.index = index;
            this.cartItem.brand = item.brand;
            this.cartItem.size = item.size;
            this.cartItem.category = item.category;
            this.cartItem.pku = item.unit;
            this.cartItem.product = item.name;
            this.cartItem.name = item.product;
            this.cartItem.pku = item.unit;
            this.cartItem.sale_price = item.price;
            this.index = index;
            return true;
          }
        });
      },
      cart() {
        this.CartTotalAmount = this.cart.reduce((total, item) => total + item.amount, 0);
      }
    },
    methods: {
      add() {
        this.cart.push(this.cartItem);
        this.cartItem = {};
        this.name = "";
      },
      closeComponent() {
        this.addProductShow = false;
        this.modalTrigger.focus();  // Return focus to the triggering element
      },
      openModal() {
        this.addProductShow = true;
        const modal = this.$refs.addPurchaseModal;
        modal.removeAttribute('inert');  // Remove inert when opening
        this.$nextTick(() => {
          this.$refs.closeButtonPurchase.focus();  // Focus on the modal's close button
        });
      },
      processCheckout() {
        // Handle the checkout process logic
      },
      search() {
        // Logic for searching products
      },
      editItemOnCart(event, item, index, field, key) {
        item[field] = event.target.innerText;
        item.amount = item.quantity * item.price;
        localStorage.purchasesCart = JSON.stringify(this.cart);
      },
      deleteItemOnCart(item, index) {
        this.cart.splice(index, 1);
        localStorage.purchasesCart = JSON.stringify(this.cart);
      }
    }
  };
  </script>
  