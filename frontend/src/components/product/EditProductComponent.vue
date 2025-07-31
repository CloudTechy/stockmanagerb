<template>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h3 class="modal-title display-4 font-weight-bold small ">Edit Product Price</h3>
                <button type="button" @click="closeAddComponent" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="card card-primary card-outline">
                <form role="form" ref="form" @keydown="form.onKeydown($event)" @submit.prevent='add'>
                    <div class="card-body">
                        <div class="modal-body">
                            <div class="container-fluid">

                                <div class="row">
                                    <div class="col-md-6">
                                        <fieldset class="border border-warning p-2">

                                            <legend class="w-auto small font-weight-bold border bg-warning">Product Info
                                            </legend>
                                            <div class="form-group">
                                                <label for="product">Product Name</label>
                                                <input disabled="" type=text v-model="product.name" required=""
                                                    class="form-control" ref="product" id="product"
                                                    placeholder="Enter product name">
                                            </div>
                                            <div class="form-group">
                                                <label for="quantity">Quantity</label>
                                                <input disabled="" type=number v-model="product.stock" required=""
                                                    class="form-control" ref="quantity" id="quantity"
                                                    placeholder="Enter quantity">
                                            </div>
                                            <div class="form-group">
                                                <label for="price">Price</label>
                                                <input step="0.01" type=number v-model="form.purchase_price" required=""
                                                    class="form-control" ref="price" id="price"
                                                    placeholder="Enter purchase price">
                                            </div>
                                            <div class="form-group">
                                                <label for="price">Sale Price</label>
                                                <input step="0.01" type=number v-model="form.sale_price" required=""
                                                    class="form-control" ref="sale_price" id="sale_price"
                                                    placeholder="Enter Selling price">
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="col-md-6 ml-auto">
                                        <fieldset class="border border-warning p-2">
                                            <legend class="w-auto small font-weight-bold border bg-warning">Others
                                            </legend>
                                            <div class="form-group">
                                                <label for="brand">Brand </label>
                                                <select disabled="" v-model="product.brand" ref="brand" required
                                                    class="custom-select mr-sm-2" id="brand">
                                                    <option v-if="brands != ''" v-for="brand in brands"
                                                        v-bind:value="brand.type"> {{ brand.type }} </option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="category">Category </label>
                                                <select disabled="" v-model="product.category" ref="category" required
                                                    class="custom-select mr-sm-2" id="category">
                                                    <option v-if="categories != ''" v-for="category in categories"
                                                        v-bind:value="category.name"> {{ category.name }} </option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="size">Size </label>
                                                <select disabled="" v-model="product.size" ref="size" required
                                                    class="custom-select mr-sm-2" id="size">
                                                    <option v-if="sizes != ''" v-for="size in sizes"
                                                        v-bind:value="size.name"> {{ size.name }} </option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="unit">Unit </label>
                                                <select disabled="" v-model="product.unit" ref="pku" required
                                                    class="custom-select mr-sm-2" id="unit">
                                                    <option v-if="units != ''" v-for="unit in units"
                                                        v-bind:value="unit.name"> {{ unit.name }} </option>
                                                </select>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border bordr border-top-0 border-primary">
                        <button @click="closeAddComponent" type="button" ref="closeButton" class="btn btn-danger"
                            data-dismiss="modal">Close</button>
                        <button type="submit" :disabled="form.busy" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</template>
<script>

export default {
    mounted() {
        this.loadBrands();
        this.loadUnits();
        this.loadSizes();
        this.loadCategories();
    },
    data() {
        return {
            form: new Form({
                sale_price: '',
                purchase_price: '',
                percent_sale: 0,
            }),
            error: '',
            product: '',
            brands: '',
            categories: '',
            units: '',
            sizes: ''
        }
    },

    created() {
        Fire.$on('edit_product', (data) => {
            this.product = data
            this.form.sale_price = data.price
            this.form.purchase_price = data.purchase_price
        })
    },
    beforeDestroy() {
        this.$refs.closeButton.click();
        this.form.reset();
    },
    methods: {
        add() {
            this.$Progress.start();
            this.form.patch('/attributeproducts/' + this.product.id)
                .then(response => {
                    this.$refs.closeButton.click()
                    window.dispatchEvent(new Event('close_sidebar_min'));
                    if (response.data.status == true) {
                        Fire.$emit('product_edited', response.data.data)
                        this.form.reset()
                        this.$Progress.finish()
                        this.$root.alert('success', 'success', 'product edited')
                    }
                    else {
                        this.$Progress.fail()
                        this.$root.alert('error', 'error', 'An unexpected error occured, Try again Later')
                    }
                })
                .catch(error => {
                    this.$Progress.fail()
                    this.$root.alert('error', 'error', error.response.data.message)
                    var error = error.response.data.data.error;
                    console.log(error);
                });
        },
        closeAddComponent() {
            window.dispatchEvent(new Event('close_sidebar_min'));
            return true;
        },
        loadBrands() {
            this.form.get('/attributes')
                .then(response => {
                    this.brands = response.data.data.item
                })
        },
        loadCategories() {
            this.form.get('/categories')
                .then(response => {
                    this.categories = response.data.data.item
                })
        },
        loadSizes() {
            this.form.get('/sizes')
                .then(response => {
                    this.sizes = response.data.data.item
                })
        },
        loadUnits() {
            this.form.get('/units')
                .then(response => {
                    this.units = response.data.data.item
                })
        },
    }
}

</script>