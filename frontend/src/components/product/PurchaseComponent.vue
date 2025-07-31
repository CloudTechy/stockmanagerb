<template>
    <div>
        <nav-component></nav-component>
        <sidebar-component></sidebar-component>
        <div class="modal fade" v-if="addProductShow" @closingAddProductCart="closeAddProduct" id="addProductComponent">
            <add-product-component v-if="addProductShow"></add-product-component>
        </div>
        <div class="modal fade" v-if="showAddPurchase" @closingAddPurchaseCart="closeAddPurchase" id="addPurchaseComponent">
            <add-purchase-component @checkout="checkOutPurchase" :products="products" v-if="showAddPurchase"></add-purchase-component>
        </div>
        <div class="content-wrapper" style="min-height: 606px;">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <h1 class="m-0 text-dark">Products</h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="container-fluid">
                    <div class="card card-success card-outline m-auto ">
                        <div class="card-header">
                            <h5 class="card-title"> Summary</h5>
                        </div>
                        <div class="card-body no-print box-profile">
                            <div class="row container text-center  mb-2 mt-4">
                                <div class="col mb-3 text-center">
                                    <div class="circle border-success mb-lg-4">
                                        <div class="inner-circle border-secondary">
                                            <p class=" circle-text">{{ productStat.count }}</p>
                                        </div>
                                    </div>
                                    <h4 class="profile-username ">Products</h4>
                                </div>
                                <div class="col mb-3 text-center">
                                    <div class="circle border-success mb-lg-4">
                                        <div class="inner-circle border-secondary">
                                            <p class="circle-text">{{ productStat.stock }}</p>
                                        </div>
                                    </div>
                                    <h4 class="profile-username ">Stock</h4>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="no-print">
                            <div class="card-header  mb-2">
                                <h5 class="card-title font-weight-bold text-secondary">Manage products</h5>
                            </div>
                            <show-products-component></show-products-component>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="card-body box-profile">
                                <div class="row container  mb-3 mt-3">
                                    <div class="col-lg-12 col-sm-12 mb-3 ">
                                        <div class="card-header">
                                            <h5 class="card-title">{{ title }}</h5>
                                        </div>
                                        <h2 class="card-title small"> &nbsp;</h2>
                                        <div class="">
                                            <div class="input-group input-group-sm float-right">
                                                <input v-model="search" type="text" ref='search' name="table_search" class="p-2 form-control" placeholder="Search products">
                                            </div>
                                        </div>
                                        <div class="clearfix"> </div>
                                        <ul class="products-list product-list-in-card pl-2 pr-2">
                                            <li ref='product' v-if="loading == false && products" v-for="product in  $root.myFilter(products,search).slice(0, products.length > 5?5:products.length)" class="item">
                                                <div class="product-img">
                                                    <img v-bind:src=" 'img/' + product.image" alt="img" class="img-size-50">
                                                </div>
                                                <div class="product-info">
                                                    <a href="javascript:void(0)" class="product-title"> <span class="small">{{product.name }}</span>
                                                        <span class="badge badge-warning float-right"> <span style="text-decoration: line-through">N</span> {{$root.numeral(product.price) }}</span></a>
                                                    <span class="product-description small">
                                                        {{ product.category + ", " + product.brand + ", " + product.size + ", " + product.unit}}.
                                                    </span>
                                                    <span v-bind:class="{badge:true, 'badge-warning':product.stock < 100, 'badge-danger':product.stock <= 50,  'badge-success' : product.stock >= 100 }">{{ product.stock }}
                                                    </span>
                                                    <span class="text-primary product-description small">Quantity
                                                    </span>
                                                    <h3 href="javascript:void(0)" class="users-list-name font-weight-bold"> {{'Product ID: ' + product.id }} </h3>
                                                </div>
                                            </li>
                                            <li class="p-4 m-3 border border-info" v-if="loading == false && products.length > 0 && $root.myFilter(products,search).length == 0">
                                                <h4 class="text-center small text-secondary">products Not Found</h4>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="position: fixed; bottom: 25px; right: 25px">
            <button type="button" @click="addProductComponent" class="btn btn-success  rounded-circle" title="Add new product" id="add" data-toggle="modal" data-target="#addProductComponent"><i class="fa fa-plus"></i> </button>
            <button type="button" @click="addPurchaseComponent" class="btn btn-success " title="supplier billing" id="add" data-toggle="modal" data-target="#addPurchaseComponent">Top Up </button>
        </div>
    </div>
</template>
<script>
    export default {
        mounted() {
        localStorage.removeItem('products')
        localStorage.removeItem('productStats')
          if(localStorage.products){
            this.products = JSON.parse(localStorage.products)
            this.loading = false
          }
          if(localStorage.productStats){
            this.loading = false;
            this.productStat = JSON.parse(localStorage.productStats)
          }
        },
        data() { 
          var d = new Date();
          return {
            month : d.getMonth() + 1,
            year : d.getFullYear(),
            products : [],
            productStat: {count:0,stock:0},
            loading : true,
            error : '',
            showAddPurchase:false,
            addProductShow : false,
            search : '',
            form: new Form(),
            title : 'RECENTLY ADDED',
        }
      },
        beforeDestroy() {
            window.dispatchEvent(new Event('close_sidebar_min'))
        },
        created(){
            Fire.$on('product_created', (data)=> {
                this.loadProducts();
                this.loadProductStat();

            })
            Fire.$on('product_deleted', (data)=> {
                this.loadProducts();
                this.loadProductStat();
            })
            Fire.$on('product_edited', (data)=> {
                this.loadProducts();
                this.loadProductStat();
            })
            Fire.$on('view', (data)=> {
                this.search = data.id;
                this.title = "PRODUCT DETAILS"
                setTimeout(()=>this.$refs.search.focus(),1000)
                // this.$refs.search.focus()
            })
            this.loadProducts();
            this.loadProductStat();
            // Echo.channel('product')
            // .listen('UpdateProduct', (e) => {
            //     this.loadProducts();
            //     this.loadProductStat();
            // });
            // Echo.channel('purchase')
            // .listen('UpdatePurchase', (e) => {
            //     this.loadProducts();
            //     this.loadProductStat();
            // });
        },
        methods: {
            loadProducts(){
              window.dispatchEvent(new Event('sidebar_min'))
                this.form.get('/attributeproducts')
                .then (response =>{
                  if(response.data.status == true){
                    this.loading = false;
                      // this.products = response.data.data.item;
                      // localStorage.products = JSON.stringify(this.products)
                      var prod = response.data.data.item
                    if (prod) {
                        this.products =  prod;
                        localStorage.products = JSON.stringify(this.products)
                    }
                    else {
                        this.products = []
                        localStorage.removeItem("products")
                    }
                  }
                  else{
                    console.log("load supplier did not return positive response");
                  }
                    
                })
                .catch (error => {
                    this.error = error.response ? error.response.data.data.error : error;
                    console.log(error);
                }); 
            },
            loadProductStat(){
              this.form.get('/statistics/products?count')
              .then (response =>{
                  if(response.data.status == true){
                    this.loading = false;
                    var stat = response.data.data.item[0]
                    if (stat) {
                        this.productStat =  stat;
                        localStorage.productStats = JSON.stringify(this.productStat)
                    }
                        
                  }
                  else{
                    console.log(response.data.data);
                  }
                })
              .catch (error => {
                  this.error = error.response.data.data.error;
                  console.log(error);
              });
            },
            addPurchaseComponent(){
              //load show add purchase component here
              this.showAddPurchase = true
            },
            addProductComponent(){
              //load show add product component here
              this.addProductShow = true
            },
            closeAddPurchase(){
              this.showAddPurchase = false
            },
            closeAddProduct(){
              this.addProductShow = false
            },
            checkOutPurchase(cart){
              console.log(cart)
            },

            collapse(id){
              $('#' + id).toggle('collapse')
            },
        }

    }

</script>
<style type="text/css">
</style>
