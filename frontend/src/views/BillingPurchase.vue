<template>
    <div style="min-height: 90vh" class="container">
        <div class="modal fade show" style="padding-right: 17px; display: block;" aria-modal="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h3 class="modal-title display-4 font-weight-bold small ">Add and Purchase New Product</h3>
                        <button type="button" @click="$router.push('/products')" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="card card-primary card-outline">
                        <form role="form" ref="form" @keydown="form.onKeydown($event)" @submit.prevent='add'>
                            <div class="card-body">
                                <div class="modal-body">
                                    <div class="container-fluid">
                                        <div class="row" v-if="supplierStatus == false">
                                            <div class="col-md-12">
                                                <div class="text-center">
                                                    <h3 class=" badge badge-secondary font-weight-bold ">STEP 1</h3>
                                                </div>
                                                <fieldset class="border border-warning p-2">
                                                    <legend class="w-auto small font-weight-bold border bg-warning">Supplier Info</legend>
                                                    <div class="form-group row">
                                                        <label for="name" class="col-sm-6 col-form-label">Supplier ID</label>
                                                        <div class="col-sm-6">
                                                            <input id="supplier_id" ref="supplier_id" name="supplier_id" list="suppliers" class="form-control" type="text" v-model="supplierID" required="">
                                                            <datalist id="suppliers">
                                                                <option v-for="supplier in suppliers" :value="supplier.id">
                                                                    {{`${supplier.name} ${supplier.phone}`}}
                                                                </option>
                                                            </datalist>
                                                        </div>
                                                    </div>
                                                    <div v-if="supplier_details">
                                                        <h2 class="small p-2 text-center bg-warning font-weight-bold">Details</h2>
                                                        <table class="table table-valign-middle">
                                                            <tbody class="text-center">
                                                                <tr>
                                                                    <td class="text-left font-weight-bold">Names</td>
                                                                    <td>
                                                                        {{ supplier_details.contact_person }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-left font-weight-bold">Phone Number</td>
                                                                    <td>
                                                                        {{ supplier_details.phone }}
                                                                    </td>
                                                                </tr>
                                                                <tr v-if="supplier_details.address">
                                                                    <td class="text-left font-weight-bold">Address: </td>
                                                                    <td>
                                                                        {{ supplier_details.address }}
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <div class="text-center">
                                                            <button @click.prevent="nextStep()" type="button" ref="nextStep" class="btn btn-warning font-weight-bold">next</button>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                        <!-- <div class="row" v-if="supplierStatus == true">
                                        <div class="text-center mb-2 col-12">
                                            <h3 class=" badge badge-secondary font-weight-bold ">STEP 2</h3>
                                        </div>
                                        <div class="col-md-6">
                                            <fieldset class="border border-warning p-2">
                                                <legend class="w-auto small font-weight-bold border bg-warning">Product Info</legend>
                                                <div class="form-group">
                                                    <label for="product">Product Name</label>
                                                    <input type=text v-model="form.purchaseDetails[0].product" required="" class="form-control" ref="product" id="product" placeholder="Enter product name">
                                                </div>
                                                <div class="form-group">
                                                    <label for="quantity">Quantity</label>
                                                    <input type=number v-model="form.purchaseDetails[0].quantity" required="" class="form-control" ref="quantity" id="quantity" placeholder="Enter quantity">
                                                </div>
                                                <div class="form-group">
                                                    <label for="price">Price</label>
                                                    <input step="0.01" type=number v-model="form.purchaseDetails[0].price" required="" class="form-control" ref="price" id="price" placeholder="Enter purchase price">
                                                </div>
                                                <div class="form-group">
                                                    <label for="price">Sale Price</label>
                                                    <input step="0.01" type=number v-model="form.purchaseDetails[0].sale_price" required="" class="form-control" ref="sale_price" id="sale_price" placeholder="Enter Selling price">
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-6 ml-auto">
                                            <fieldset class="border border-warning p-2">
                                                <legend class="w-auto small font-weight-bold border bg-warning">Others</legend>
                                                <div class="form-group">
                                                    <label for="brand">Brand </label>
                                                    <select v-model="form.purchaseDetails[0].brand" ref="brand" required class="custom-select mr-sm-2" id="brand">
                                                        <option v-if="brands != ''" v-for="brand in brands" v-bind:value="brand.type"> {{ brand.type }} </option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="category">Category </label>
                                                    <select v-model="form.purchaseDetails[0].category" ref="category" required class="custom-select mr-sm-2" id="category">
                                                        <option v-if="categories != ''" v-for="category in categories" v-bind:value="category.name"> {{ category.name }} </option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="size">Size </label>
                                                    <select v-model="form.purchaseDetails[0].size" ref="size" required class="custom-select mr-sm-2" id="size">
                                                        <option v-if="sizes != ''" v-for="size in sizes" v-bind:value="size.name"> {{ size.name }} </option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="unit">Unit </label>
                                                    <select v-model="form.purchaseDetails[0].pku" ref="pku" required class="custom-select mr-sm-2" id="unit">
                                                        <option v-if="units != ''" v-for="unit in units" v-bind:value="unit.name"> {{ unit.name }} </option>
                                                    </select>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div> -->
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border bordr border-top-0 border-primary">
                                <button @click="$router.push('/products')" type="button" ref="closeButton" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button type="submit" :disabled="form.busy" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    mounted() {
        this.loadSuppliers();

        if (this.$root.purchaseSupplierID) {
            this.supplierID = this.$root.purchaseSupplierID;

        }

        // Echo.channel('brand')
        // .listen('UpdateBrand', (e) => {
        //     this.loadBrands();
        // });
        // Echo.channel('unit')
        // .listen('UpdateUnit', (e) => {
        //     this.loadUnits();
        // });
        // Echo.channel('size')
        // .listen('UpdateSize', (e) => {
        //     this.loadSizes();
        // });
        // Echo.channel('category')
        // .listen('UpdateCategory', (e) => {
        //     this.loadCategories();
        // });
        // Echo.channel('supplier')
        // .listen('UpdateSupplier', (e) => {
        //     this.loadSuppliers();
        // });
        this.cart = this.$root.PurchaseCart
        this.$root.PurchaseCart = []
    },

    beforeRouteEnter(to, from, next) {
        next(vm => {
            if (from.path == "/" || from.path != "/products") {
                vm.$router.push('/products')
            }
            if (!vm.$root.PurchaseCart) {
                vm.$root.alert('warning', 'caution', 'Your purchase Cart is empty, redirecting to billing')
                vm.$router.push('/products')
            }
        })


        // console.log(to)
    },
    created() { this.loadSuppliers(); },

    // props: ['cart'],
    data() {

        return {
            form: new Form({
                supplier_id: '',
                purchaseDetails: [{
                    product: '',
                    brand: '',
                    pku: '',
                    category: '',
                    size: '',
                    price: '',
                    quantity: '',
                    sale_price: '',
                    purchase_id: '',
                }],
            }),
            error: '',
            supplierStatus: false,
            productStatus: false,
            suppliers: '',
            supplier_details: '',
            supplierID: '',
            purchaseDetails: [],
            brands: '',
            categories: '',
            units: '',
            sizes: '',
            purchase: '',
            cart: [],
        }
    },
    watch: {
        // supplierID(){
        //     this.form.get('/suppliers/'+ this.supplierID)
        //     .then(response => {
        //         this.supplier_details = response.data.data
        //     })
        // },
        supplierID() {
            if (this.supplierID) {
                this.loadSupplierDetails();
            }

        },
        suppliers() {
            if (this.supplierID) {
                this.loadSupplierDetails();
            }
        }

    },
    beforeDestroy() {
        this.form.reset();
    },
    methods: {

        loadSuppliers() {
            this.$Progress.start();
            this.form.get('/suppliers')
                .then(response => {
                    this.$Progress.start();
                    this.suppliers = response.data.data.item
                })
                .catch(err => console.log('supliers', err))
        },
        nextStep() {
            if (this.cart) {
                this.$Progress.start();
                this.form.supplier_id = this.supplierID;
                this.getPurchase(this.supplierID);
                console.log("there is cart")
                return
            } else {
                console.log("there is no cart returning")
                this.$router.push('/products')
            }
        },
        getPurchase(id) {
            this.$Progress.start();
            this.form.post('/purchases')
                .then(response => {
                    this.$Progress.finish();
                    this.purchase = response.data.data
                        var cart = this.cart.map(obj => ({ ...obj, purchase_id: this.purchase.id }))
                        this.form.purchaseDetails = cart
                        this.add()
                })
                .catch(err => console.log(err))
        },
        add() {

            this.$Progress.start();
            let loader = this.$loading.show({});
            // this.form.purchaseDetails[0].purchase_id = this.purchase.id
            // this.form.purchaseDetails[0].product = this.form.purchaseDetails[0].product.toLowerCase();
            this.form.post('/purchasedetails')
                .then(response => {
                    this.$Progress.finish()
                    if (response.data.status == true) {
                        loader.hide()
                        localStorage.removeItem("purchasesCart")
                        this.form.reset()
                        this.$root.alert('success', 'success', 'product added, redirecting to payment')
                        this.$root.purchaseId = this.purchase.id
                        this.$router.push('/payment')
                        return
                        
                    } else {
                        this.$Progress.fail()
                        loader.hide()
                        this.$root.alert('error', 'error', 'An unexpected error occured, Try again Later')
                        console.log(response.data.data);
                    }
                })
                .catch(error => {
                    this.$Progress.fail()
                    loader.hide()
                    if(error.response){
                        this.$root.alert('error', 'error', error.response.data.message)
                    }
                    var error = error.response.data.data.error;
                    console.log(error);
                });
        },
        closeComponent() {
            window.dispatchEvent(new Event('close_sidebar_min'));
           
        },
        loadBanks() {
            this.form.get('/banks')
                .then(response => {
                    if (response.data.status == true) {
                        this.banks = response.data.data.item
                    }
                })
                .catch(error => {
                    var error = error.response.message
                    console.log(error)
                })

        },
        loadSupplierDetails() {
            var data = []
            if (this.supplierID) {
                data = this.suppliers.filter((item) => {
                    var keys = Object.values(item)
                    var boolean = false
                    if (item == undefined) {
                        return false
                    }
                    var bool = keys.forEach((key) => {
                        if (key != null && key == this.supplierID) {
                            boolean = true
                        }
                    })
                    return boolean
                })
            } else {
                data = ""
            }
            this.supplier_details = data[0]
        }
    }
}

</script>
