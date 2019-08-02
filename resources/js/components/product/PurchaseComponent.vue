<template>
  <div>
    <nav-component></nav-component>
    <sidebar-component></sidebar-component>
    <div class="modal fade" id="addProductComponent"><add-product-component></add-product-component></div>
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
        <div  class="container-fluid">
            <div class="card card-success card-outline m-auto ">
        <div class="card-header">
            <h5 class="card-title"> Summary</h5>
        </div>
  
        <div class="card-body no-print box-profile">
          <div class="row container text-center  mb-2 mt-4">

            <div class="col mb-3 text-center">
              <div class="circle border-success mb-lg-4"><div class="inner-circle border-secondary"><p  class=" circle-text">{{ productStat.count }}</p></div></div>
              <h4 class="profile-username ">Products</h4>
            </div>
            <div class="col mb-3 text-center">
              <div class="circle border-success mb-lg-4"><div class="inner-circle border-secondary"><p class="circle-text">{{ productStat.stock }}</p></div></div>
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
                      <div class="input-group input-group-sm float-right" >
                        <input  v-model="search" type="text" ref = 'search' name="table_search" class="p-2 form-control" placeholder="Search products">
                     </div>
                    </div> 
                    <div class="clearfix"> </div>
                    <ul class="products-list product-list-in-card pl-2 pr-2">

                      <li ref = 'product' v-if = "loading == false" v-for = "product in  $root.myFilter(products,search).slice(0,5)" class="item">
                          <div class="product-img">
                            <img v-bind:src=" 'img/' + product.image" alt="img" class="img-size-50">
                          </div>
                          <div class="product-info">
                            <a href="javascript:void(0)" class="product-title"> <span class="small">{{product.name }}</span>
                              <span class="badge badge-warning float-right"> <span style="text-decoration: line-through">N</span> {{$root.numeral(product.price) }}</span></a>
                            <span class="product-description small">
                              {{ product.category +  ", " + product.brand + ", " + product.size + ", " + product.unit}}.
                            </span>
                            <span v-bind:class = "{badge: true,small : true,'badge-success' :product.stock > 0, 'badge-danger': product.stock == 0, 'float-right' : true}"> {{ product.stock }}</span>
                            <span  class="text-primary product-description small">Quantity
                            </span>
                            <h3 href="javascript:void(0)" class="users-list-name font-weight-bold"> {{'Product ID: ' + product.id }} </h3>
                          </div>
                        </li>
                      <li class="p-4 m-3 border border-info" v-if="loading == false && $root.myFilter(products,search).length == 0">
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
      <button type="button"  class="btn btn-success  rounded-circle" title="Add new product" id = "add" data-toggle="modal" data-target="#addProductComponent" ><i class="fa fa-plus"></i> </button>
    </div>
  </div>
  
</template>

<script>
  
    export default {
        mounted() {
<<<<<<< HEAD
          if(localStorage.products){
            this.products = JSON.parse(localStorage.products)
            this.loading = false
          }
          if(localStorage.productStat){
            this.loading = false;
            this.productStat = localStorage.productStat
          }
        },
        data() { 
          var d = new Date();
          return {
            month : d.getMonth() + 1,
            year : d.getFullYear(),
            products : [],
            productStat: {},
            loading : true,
            error : '',
            search : '',
            form: new Form(),
            title : 'RECENTLY ADDED',
          }
=======
          
            },
         data() { 
            var d = new Date();
            return {
              month : d.getMonth() + 1,
              year : d.getFullYear(),
              products : [],
              productStat: {},
              pending: [],
              not_paid: [],
              paid: [],
              order_type : [],
              purchase_type:[],
              loading : true,
              error : '',
              search : '',
              form: new Form(),
              owed : '',
              title : 'RECENTLY ADDED',
            }
>>>>>>> a90f05ca68e2264c685a9477281ef51e4d16983b
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
                this.$refs.search.focus()
            })
            this.loadProducts();
            this.loadProductStat();
<<<<<<< HEAD
            Echo.channel('product')
            .listen('UpdateProduct', (e) => {
                this.loadProducts();
                this.loadProductStat();
            });
            Echo.channel('purchase')
            .listen('UpdatePurchase', (e) => {
                this.loadProducts();
                this.loadProductStat();
            });
=======
>>>>>>> a90f05ca68e2264c685a9477281ef51e4d16983b
        },
        methods: {
            loadProducts(){
              window.dispatchEvent(new Event('sidebar_min'))
                this.form.get('./api/attributeproducts')
                .then (response =>{
                  if(response.data.status == true){
                    this.loading = false;
                      this.products = response.data.data.item;
<<<<<<< HEAD
                      localStorage.products = JSON.stringify(this.products)
=======
>>>>>>> a90f05ca68e2264c685a9477281ef51e4d16983b
                  }
                  else{
                    console.log("load supplier did not return positive response");
                  }
                    
                })
                .catch (error => {
                    this.error = error.response.data.error;
                    console.log(error);
                }); 
            },
            loadProductStat(){
              this.form.get('./api/statistics/products?count')
              .then (response =>{
                  if(response.data.status == true){
                    this.loading = false;
                    this.productStat = response.data.data.item[0];
<<<<<<< HEAD
                    localStorage.productStat = this.productStat
=======
>>>>>>> a90f05ca68e2264c685a9477281ef51e4d16983b
                  }
                  else{
                    console.log(response.data);
                  }
                })
              .catch (error => {
                  this.error = error.response.data.error;
                  console.log(error);
              });
            },

            collapse(id){
              $('#' + id).toggle('collapse')
            },
        }

    }

</script>

<style  type="text/css">
  
  
   
</style>