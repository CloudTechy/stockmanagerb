<template>
    <div id="example1_wrapper" class="dataTables_wrapper  container-fluid dt-bootstrap4">
        <div class="row">
            <div class="col-6 col-md-6">
                <div class="dataTables_length d-inline" id="example1_length">
                    <label>Entries:
                        <select v-model="rowsPerPage" @change="loadProducts" aria-controls="dataTables-example" class="form-control input-sm border border-success">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </label>
                    <button @click="loadProducts" type="button" title="Refresh data" class="btn m-1 mb-2 btn-outline-success"><i :class="{fas:true, 'fa-sync-alt' : true, 'fa-spin':refresh}"></i></button>
                </div>
            </div>
            <div class="col-6 col-md-6">
                <div id="example1_filter" class="dataTables_filter float-right">
                    <label>Search:<input v-model="search" type="search" class="form-control border border-success form-control-sm" placeholder="Ex: 300-18 sports komic" aria-controls="example1">
                    </label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive-sm table-responsive">
                    <table class="table table-small  table-hover dataTable">
                        <thead>
                            <tr role="row ">
                                <th>Name</th>
                                <th>category</th>
                                <th>Brand</th>
                                <th>Size</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="body">
                            <tr v-for="product,index in pageLoader(current_page) " v-if="product != undefined">
                                <td class="text-capitalize">{{ product.name }}</td>
                                <td class="text-capitalize">{{ product.category }}</td>
                                <td class="text-capitalize">{{ product.brand }}</td>
                                <td class="text-capitalize">{{ product.size }}</td>
                                <td class="text-capitalize">
                                    <span class="" style="text-decoration: line-through">N</span>
                                    {{ $root.numeral(product.price) }}
                                </td>
                                <td class="text-capitalize">
                                    <span v-bind:class="{badge:true, 'badge-warning':product.stock < 200, 'badge-danger':product.stock <= 70,  'badge-success' : product.stock >= 200 }">{{  product.stock }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="text-center">
                                        <button @click="loadAddCart(product,index+start)" data-toggle="modal" data-target="#addCart" type="button" title="add to cart" class="  m-1 btn btn-outline-success"><i class="fa fa-plus"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="loading == false && pageLoader(current_page).length == 0">
                                <td colspan="11">
                                    <li class="p-4 m-3 border border-info" v-if="loading == false && $root.myFilter(products,search).length == 0">
                                        <h4 class="text-center small text-secondary">Products Not Found</h4>
                                    </li>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6 col-md-6">
                <div class="p-2 m-3  small border border-success" id="" role="status" aria-live="polite">Showing {{ start + 1 }} to {{ end > length ? length : end }} of {{ length }} entries
                </div>
            </div>
            <div class="col-6 col-md-6">
                <div class="dataTables_paginate paging_simple_numbers pt-3 float-right" id="example1_paginate">
                    <ul class="pagination">
                        <li class="paginate_button page-item previous" ref="prev" id="example1_previous">
                            <a href="#" @click.prevent="pageLoaderB(-1)" aria-controls="example1" data-dt-idx="0" tabindex="0" class="page-link border-primary">Previous
                            </a>
                        </li>
                        <li class="paginate_button bg-primary p-2 text-center page-item" style="display: inline; min-width: 70px" v-if="Math.floor(pages) > 6">
                            {{current_page + ' of ' + Math.floor(pages)}}
                        </li>
                        <li v-else v-for="value in Math.floor(pages)" v-bind:class="classObject(value)" class="paginate_button page-item">
                            <a aria-controls="example1" data-dt-idx="1" tabindex="0" class="page-link border-primary" @click.prevent="pageLoader(value)" href="#">{{ value }}</a>
                        </li>
                        <li class="paginate_button page-item next" ref="next" id="example1_next">
                            <a @click.prevent="pageLoaderB(1)" href="#" aria-controls="example1" data-dt-idx="7" tabindex="0" class="page-link border-primary">Next
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="modal-footer m-0 p-0">
            <div v-if="cart.length != 0 " class=" m-0 p-0">
                <button @click="loadViewCart" data-toggle="modal" data-target="#viewCart" type="button" title="view products in your cart" class="btn btn-warning"><i class="fa fa-shopping-cart fa-fw"></i> View Cart</button>
                <button @click="loadCheckoutCart" ref="checkoutCart" data-toggle="modal" data-target="#checkoutCart" type="button" title="checkout and pay" class="btn btn-success"><i class="fas fa-money-check fas-fw"></i> Checkout</button>
            </div>
        </div>
        <!-- <component @changeComponent = "changeComponent" :products="products" class = "m-0 p-0" :is="componentName"></component> -->
        <!--  <div class="modal fade" id="editModal"><edit-product-component></edit-product-component></div> -->
        <div v-if="addCartShow == true" class="modal fade" id="addCart">
            <add-cart-component @cart_created="loadCart" @closeCart="closeCart" v-if="addCartShow == true"></add-cart-component>
        </div>
        <div v-if="viewCartShow == true" class="modal fade" id="viewCart">
            <view-cart-component @order_created="refreshCart" @updateProduct="indexProductUpdata" :cart="cart" @updateCart="updateCart" @closeViewCart="closeCart" v-if="viewCartShow == true"></view-cart-component>
        </div>
        <div v-if="checkoutCartShow == true" class="modal fade" id="checkoutCart">
            <checkout-cart-component @order_created="refreshCart" @closeViewCheckoutCart="closecheckOutCart" v-if="checkoutCartShow == true"></checkout-cart-component>
        </div>
        <div style="scrollbar-width:none" class="modal fade" id="addOrderComponent">
            <add-order-component @checkout="checkOut" @closingAddCart="closeCart" @updateProduct="indexProductUpdata" @updateCart="updateCart" @load_products="loadProducts" :carts="cart" :items="products" v-if="addOrderShow == true"></add-order-component>
        </div>
        <div style="position: fixed; bottom: 25px; right: 25px">
            <button type="button" @click="addOrderComponent" class="btn btn-success  rounded-circle" title="Take customer orders" id="add" data-toggle="modal" data-target="#addOrderComponent"><i class="fa fa-plus"></i> </button>
        </div>
    </div>
</template>
<script>
import AddCartComponent from  '@/components/order/AddCartComponent.vue'
import ViewCartComponent from '@/components/order/ViewCartComponent.vue'
import CheckoutCartComponent from '@/components/order/CheckoutCartComponent.vue'
import AddOrderComponent from '@/components/order/AddOrderComponent.vue'
export default {
    components:{
        AddCartComponent,
        ViewCartComponent,
        CheckoutCartComponent,
        AddOrderComponent
    },
    mounted() {
        window.dispatchEvent(new Event('sidebar_min'))
        window.scrollTo(0, 200)

        if (localStorage.cart) {
            this.cart = JSON.parse(localStorage.cart)
        }
        if (localStorage.productCart) {
            this.products = JSON.parse(localStorage.productCart)
        } 
        

    },
    data() {
        var d = new Date();
        return {
            month: d.getMonth() + 1,
            year: d.getFullYear(),
            products: [],
            cart: [],
            cartCopy: [],
            addCartShow: false,
            viewCartShow: false,
            checkoutCartShow: false,
            addOrderShow: false,
            index: '',
            loading: true,
            error: '',
            search: '',
            refresh: false,
            rowsPerPage: 5,
            current_page: 1,
            length: 0,
            pages: 0,
            componentName: '',
            form: new Form(),
            selectedProduct: {},
        }
    },
    watch: {},
    created() {
        this.$Progress.start()

        if (!localStorage.cart) {
            this.loadProducts();
        }
        this.search = this.$root.search != undefined ? this.$root.search : ""
        this.current_page = this.$root.productCurrentPage != undefined ? this.$root.productCurrentPage : 1
        // Echo.channel('product')
        //     .listen('UpdateProduct', (e) => {
        //         this.loadProducts();
        //     });
        // Echo.channel('purchase')
        //     .listen('UpdatePurchase', (e) => {
        //         this.loadProducts();
        //     });
    },
    computed: {
        start() {
            if (this.pages > 0 && this.current_page >= this.pages) {
                this.current_page = this.pages
            }
            return parseInt(this.rowsPerPage * (this.current_page - 1));
        },
        end() {
            return parseInt(this.rowsPerPage * (this.current_page - 1)) + parseInt(this.rowsPerPage);
        },
    },
    beforeDestroy() {
        this.$root.OrderCustomerID = '';
        if (this.cart.length > 0) {
            localStorage.cart = JSON.stringify(this.cart)
            localStorage.productCart = JSON.stringify(this.products)
        }
        else {
            localStorage.removeItem('cart')

        }
        // retain the current page number and search
        this.$root.productCurrentPage = this.current_page
        this.$root.search = this.search
    },
    methods: {
        loadProducts() {
            //do not load product when there is item on cart
            if (this.cart.length > 0) {
                this.$root.alert('warning', 'CAUTION', "You have some items in your cart")
                return
            }
            this.refresh = true
            this.$Progress.start();
            this.form.get('/attributeproducts')
                .then(response => {
                    this.refresh = false
                    this.$Progress.finish()
                    if (response.data.status == true) {
                        Fire.$emit('products_loaded', response.data.data)
                        window.dispatchEvent(new Event('sidebar_min'))
                        this.products = response.data.data.item.length != 0 ? response.data.data.item : [];
                        localStorage.productCart = JSON.stringify(this.products)
                        return true
                    } else {
                        this.$Progress.fail()
                        this.$root.alert('error', 'error', 'An unexpected error occured, Try again Later')
                        console.log(response.data);
                        return false
                    }
                })
                .catch(error => {
                    this.refresh = false
                    if (error.response && error.response.status == 401) {
                        this.$Progress.finish()
                        this.$router.push("/login")
                    }
                    this.$Progress.fail()
                    var message = error.response.data.error.includes("No connection could be made") ? "No server connection" : error.response.data.message
                    this.$root.alert('error', 'error', message)
                    console.log(error.response.data.error)
                    return false
                });


        },
        classObject(value) {
            if (value <= 1 && this.current_page == 1) {
                this.$refs.prev.classList.add('disabled')
            } else if (value <= 1 && this.current_page > 1) {
                this.$refs.prev.classList.remove('disabled')
            }
            if (this.current_page == this.pages) {
                this.$refs.next.classList.add('disabled')
            } else {
                this.$refs.next.classList.remove('disabled')
            }
            if (value == this.current_page) {
                return "active";
            }
        },
        pageLoader(pageNumber) {
            
            if (this.pages > 6) {
                this.$refs.prev.classList.remove('disabled')
                this.$refs.next.classList.remove('disabled')
            }
            this.current_page = pageNumber;
            this.loading = false;
            var data = this.$root.myFilter(this.products, this.search)
            this.length = data.length;
            this.pages = Math.ceil(data.length / this.rowsPerPage);
           return data.length > 0 ? data.slice(this.start,this.end) : [];
        },
        pageLoaderB(amount) {
            if (this.current_page <= 1 && amount == -1) {
                this.current_page = 1;
            } else if (this.current_page >= this.page) {
                this.current_page = this.page;
            } else {
                this.current_page += amount;
            }
        },
        loadEdit(product, index) {
            Fire.$emit('edit_product', product);
            window.dispatchEvent(new Event('sidebar_min'))
            return true;
        },
        loadView(data, index) {
            Fire.$emit('view', data);
            return true;
        },
        loadAddCart(product, index) {
            window.dispatchEvent(new Event('sidebar_min'))
            this.$root.product = product;
            this.selectedProduct = product
            this.$root.index = index;
            this.addCartShow = true;
            this.index = index
            // Fire.$emit('add_cart', product)
        },
        loadViewCart() {
            window.dispatchEvent(new Event('sidebar_min'))
            this.viewCartShow = true;
        },
        loadCheckoutCart() {
            window.dispatchEvent(new Event('sidebar_min'))
            this.$root.checkoutCart = this.cart;
            this.checkoutCartShow = true;
        },
        closeCart() {
            $('modal').hide();
            this.addCartShow = false;
            this.viewCartShow = false;
            this.addOrderShow = false;

        },
        closecheckOutCart() {
            this.checkoutCartShow = false;
            console.log('checkout cart closed')
            this.refreshCart()

        },
        checkOut() {
            this.$refs.checkoutCart.click()
        },
        updateCart(cart, indexes) {
            this.cart = cart;
            if (this.cart.length == 0) { this.loadProducts() }
            this.$root.alert('success', 'success', 'cart updated')
        },
        loadCart(data) {
            var index = this.products.indexOf(this.selectedProduct)
            var cartItem = { id: data.id, product: data.product, quantity: data.quantity, price: data.price, amount: data.amount, index }
            this.cart.unshift(cartItem)
            this.products[index].stock = this.products[index].stock - data.quantity
            this.$root.alert('success', 'success', 'added to cart')
            this.selectedProduct = {}
        },
        refreshCart() {
            this.cart = []
            this.cartCopy = []
            this.loadProducts()
            this.$root.customer_details = ""
            localStorage.removeItem('cart')
            localStorage.removeItem('productCart')
        },
        indexProductUpdata(productIndex) {
            this.products[productIndex.index].stock = parseInt(this.products[productIndex.index].stock) + parseInt(productIndex.quantity)
        },
        addOrderComponent() {
            this.addOrderShow = true
        },
        deleteData(id, index) {
            this.$swal({
                    title: 'Are you sure?',
                    text: "Note! You cannot delete a valid product",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete product!'
                })
                .then((result) => {
                    if (result.value) {
                        this.$Progress.start();
                        this.form.delete('/products/' + id)
                            .then(response => {
                                if (response.data.status == true) {
                                    Fire.$emit('product_deleted', response.data.data)
                                    this.$Progress.finish()
                                    this.$root.alert('success', 'success', 'product deleted ')
                                } else {
                                    this.$Progress.fail()
                                    this.$root.alert('error', 'error', 'An unexpected error occured, Try again Later')
                                }
                            })
                            .catch(error => {
                                this.$Progress.fail()
                                var message = error.response.data.message
                                if (error.response.data.error.includes("No connection could be made")) {
                                    message = "No server connection"
                                } else if (error.response.data.error.includes("Integrity constraint")) {
                                    message = "product is valid"
                                }

                                this.$root.alert('error', 'error', message)

                                console.log(error.response.data.error)
                            })
                    }
                })
        },
    }
}

</script>
