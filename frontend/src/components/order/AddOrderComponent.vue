<template>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h3 class="modal-title display-5 font-weight-bold  ">Customer Billing <i
                        class="fa fa-user text-info"></i></h3>
                <button type="button" @click="closeComponent" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="card card-primary card-outline">
                <form role="form" ref="form" @keydown="form.onKeydown($event)" @submit.prevent='add(product)'>
                    <div class="card-body">
                        <div class="modal-body p-0">
                            <div class="container-fluid">
                                <div class="row">
                                    <div v-if="true" class="col-md-12 p-0 m-0">
                                        <fieldset class="border border-info p-1">
                                            <legend class="p-2 w-auto small font-weight-bold border text-info">Take
                                                Customer Orders <i class="fa fa-shopping-cart "></i></legend>
                                            <div class="form-row">
                                                <div class="col-12 col-md-6">
                                                    <label class="small" for="">Input name of item</label>
                                                    <input v-model="name" list="products" class="form-control"
                                                        required="" type="search" ref="name"
                                                        placeholder="Ex: 300-18 Sports Ordinary tyre">
                                                    <datalist ref="datalist" id="products">
                                                    </datalist>
                                                </div>
                                                <div class="col-6 col-md-3">
                                                    <label class="small" for="">Price</label>
                                                    <input class="form-control" type="text"
                                                        title="you can change the price if you may"
                                                        v-model="cartItem.price" ref="price" required=""
                                                        placeholder="Enter Price">
                                                    <label v-if="product.price != undefined"
                                                        class="small text-danger">Cost Price: <span
                                                            style="text-decoration: line-through">
                                                            N</span>{{ $root.numeral(product.purchase_price) }}</label>
                                                </div>
                                                <div class="col-6 col-md-3">
                                                    <label class="small" for="">Quantity</label>
                                                    <input type="number" :max="product.stock"
                                                        v-model="cartItem.quantity" required="" min="1"
                                                        class="form-control" ref="quantity"
                                                        placeholder="How many pc(s)">
                                                    <label v-if="product.stock != undefined"
                                                        class="small text-danger">Remaining {{ product.stock }}
                                                        pc(s)</label>
                                                </div>
                                            </div>
                                            <div class="text-center mt-3 m-2">
                                                <button type="submit" @submit.prevent='add(product)' ref="nextStep"
                                                    class="font-weight-bold btn btn-block btn-info small">Add to Invoice
                                                    <i class="fa fa-shopping-cart "></i></button>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                                <div style="max-height: 37vh;  overflow: auto; " class="row mt-3">
                                    <div class="col-sm-12 p-0 m-0">
                                        <h3>Bill Adjustment
                                            <i class="fas fa-cog fa-spin text-info"></i>
                                            <!-- <span class="float-right">Total amount: <span style="text-decoration: line-through">N</span> {{$root.numeral(CartTotalAmount)}}</span> -->
                                        </h3>
                                        <div class="clearfix"></div>
                                        <div class="table-responsive">
                                            <table
                                                class="table border border-info table-bordered table-small table-striped table-hover dataTable">
                                                <thead class="bg-info">
                                                    <tr role="row ">
                                                        <th class="text-center p-1">Index</th>
                                                        <th class="text-center p-1">Name</th>
                                                        <th class="text-center p-1">Quantity</th>
                                                        <th class="text-center p-1">Price</th>
                                                        <th class="text-center p-1">Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="body">
                                                    <tr draggable
                                                        @dragend="deleteItemOnCart(item, index, { index: item.index, quantity: item.quantity })"
                                                        @dblclick="deleteItemOnCart(item, index, { index: item.index, quantity: item.quantity })"
                                                        title="double click or swipe to remove this item from cart"
                                                        v-for="item, index in cart">
                                                        <td class="text-center p-1 small text-capitalize">
                                                            {{ index + 1 }}
                                                        </td>
                                                        <td class="text-center p-1 small text-capitalize">
                                                            {{ item.product }}</td>
                                                        <td @input="editItemOnCart($event, item, index, 'quantity', item.index)"
                                                            @keypress.enter.prevent="" contenteditable=""
                                                            class="text-center p-1 small text-capitalize">
                                                            {{ item.quantity }}</td>
                                                        <td @input="editItemOnCart($event, item, index, 'price', item.index)"
                                                            @keypress.enter.prevent="" contenteditable=""
                                                            class="text-center p-1 small text-capitalize">
                                                            {{ item.price }}
                                                        </td>
                                                        <td :ref="'amount' + item.index" :key="item.index"
                                                            class="text-center p-1 small text-capitalize">
                                                            {{ $root.numeral(item.amount) }} </td>
                                                    </tr>
                                                    <tr v-if="cart.length == 0">
                                                        <td colspan="5">
                                                            <ul>
                                                                <li class="">
                                                                    <h4 class="text-center small text-secondary">No
                                                                        item(s)
                                                                        in cart yet, add items</h4>
                                                                </li>
                                                            </ul>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="5">
                                                            <h4 class="text-right"> <span class="">Total amount: <span
                                                                        style="text-decoration: line-through">N</span>
                                                                    {{ $root.numeral(CartTotalAmount) }}</span></h4>
                                                        </td>

                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer border pt-0 pb-1 border-top-0 border-primary">
                    <button @click="closeComponent" type="button" ref="closeButton" class="btn btn-danger"
                        data-dismiss="modal">Close</button>
                    <button type="submit" @click="closeComponent" :disabled="cart.length == 0" class="btn btn-info">Save
                        Changes</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    created() {
        window.dispatchEvent(new Event('sidebar_min'))
        this.cart = this.carts
        this.products = this.items
    },
    data() {
        return {
            form: new Form(),
            name: "",
            cartItem: {},
            products: [],
            cart: [],
            editMode: false,
            cartCopy: [],
            product: {},
            index: "",
            CartTotalAmount: 0,
            productHash: new Set(),
        }
    },
    props: ['items', 'carts'],
    beforeDestroy() {
        this.product = {};
        this.product.quantity = ""
        this.form.reset();
        this.$emit('closingAddCart')
        window.dispatchEvent(new Event('close_sidebar_min'))
        this.$refs.closeButton.click();

        //     if (this.cart.length > 0) {
        //     localStorage.cart = JSON.stringify(this.cart)
        //     localStorage.productCart = JSON.stringify(this.products)
        // }

    },
    computed: {
        cartLoader() {
            return this.cart
        },
        amount() {
            return this.cartItem.price != "" && this.cartItem.quantity != "" ? this.cartItem.price * this.cartItem.quantity : 0
        },
        productsRevised() {
            return this.products.map(({ product, stock }) => ({ product, stock }))
        }
    },
    watch: {
        name() {
            this.search()
            this.product = {}
            this.cartItem = {}
            if (this.name == "") {
                this.product = {}
                this.cartItem = {}
                return
            }

            this.products.some((item, index) => {
                if (item.product == this.name) {
                    this.product = item
                    this.cartItem.price = item.price
                    this.cartItem.product = item.product
                    this.cartItem.id = item.id
                    this.index = index
                    return true
                }
            })


        },

        cart() {
            this.CartTotalAmount = this.cart.sum('amount')
        }
    },
    methods: {
        add() {
            this.cartItem.amount = this.amount
            this.cartItem.index = this.index
            const n = this.cartItem
            this.products[this.index].stock = this.products[this.index].stock - this.cartItem.quantity
            this.cart.unshift(n)

            this.cartItem = {}
            this.name = ""
            this.product = {}
            this.$root.alert('success', 'success', 'item added to cart')
        },
        loadProducts() {
            this.$emit('load_products')
        },
        closeComponent() {
            this.name = ""
            this.product = {};
            this.form.reset();
            this.$refs.closeButton.click();
            this.$emit('closingAddCart')
        },
        editItemOnCart(e, item, index, type, productIndex) {
            this.$Progress.start();
            if (e.target.innerHTML == "") {
                this.$Progress.finish()
                return
            }
            this.editMode = true
            var value = parseInt(e.target.innerHTML)
            if (type == "price") {
                this.cart[index].price = value
            } else if (type == "quantity") {
                var shortFall = this.cart[index].quantity - value
                var oldStock = this.products[productIndex].stock
                var balance = oldStock - (-shortFall)
                if (balance < 0) {
                    this.$root.alert('error', 'error', 'Not enough stock')
                    e.target.innerHTML = this.cart[index].quantity
                    // e.target.innerHTML = balance < 0 ? oldStock : balance

                    this.$Progress.fail()
                    return
                }
                this.products[productIndex].stock = oldStock - (-shortFall)
                this.cart[index].quantity = value
            }
            this.$refs['amount' + productIndex][0].key = productIndex + 1
            this.cart[index].amount = this.cart[index].quantity * this.cart[index].price
            this.$refs['amount' + productIndex][0].innerHTML = this.cart[index].amount
            this.CartTotalAmount = this.cart.sum('amount')
            this.$Progress.finish()
        },
        deleteItemOnCart(data, index, productIndex) {
            this.$Progress.start();
            this.$swal({
                title: 'Remove Item from Cart',
                text: "You are about to delete an item from cart",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, go ahead!'
            })
                .then((result) => {
                    if (result.value) {
                        if (this.cart != '') {
                            this.cart.splice(index, 1);
                            this.$emit('updateProduct', productIndex)
                            // this.products[productIndex.index].stock = parseInt(this.products[productIndex.index].stock) + parseInt(productIndex.quantity)
                            this.$root.alert('success', 'success', 'product removed from cart')

                        } else { console.log('cart not loaded'); }
                    }
                    this.$Progress.finish()

                })


            this.$Progress.finish()
        },
        processCheckout() {
            this.$Progress.start();
            this.$swal({
                title: 'Checkout Bill',
                text: "Do you want to proceed to Checkout",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Checkout!'
            })
                .then((result) => {
                    if (result.value) {
                        this.$emit('checkout', this.cart)
                        this.closeComponent()
                        // this.products[productIndex.index].stock = parseInt(this.products[productIndex.index].stock) + parseInt(productIndex.quantity)
                        this.$root.alert('success', 'success', 'proceeding to checkout')


                    }
                    this.$Progress.finish()

                })


            this.$Progress.finish()
        },
        search() {
            var datalist = this.$refs.datalist
            var term = this.name.toLowerCase()
            var found = 0
            var frag = document.createDocumentFragment()
            for (var child of [].slice.apply(datalist.childNodes)) {
                datalist.removeChild(child)
            }
            for (var item of this.productsRevised) {

                if (item.product.toLowerCase().includes(term.trim().toLowerCase())) {
                    let option = document.createElement("option")
                    option.value = item.product
                    option.innerHTML = "Remaining " + item.stock
                    frag.appendChild(option)
                    found++
                }

                if (found == 7) break

            }
            datalist.appendChild(frag)

        }
    },
}

</script>
