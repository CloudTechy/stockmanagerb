<template>
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">New Products </h3>
        <div class="card-tools ">
          <button type="button" class="btn btn-tool" data-widget="collapse">
            <i class="fa fa-minus"></i>
          </button>
          <button type="button" class="btn btn-tool" data-widget="remove">
            <i class="fa fa-times"></i>
          </button>
        </div>
      </div>

      <div class="card-body p-0">
        <div class="card">
            <div class="">
                <div class="input-group input-group-sm float-right" >
            <input  v-model="search" type="text" name="table_search" class="p-lg-3 p-sm-2 p-2 form-control" placeholder="Search product by name here...">
          </div>
            </div> 
        </div>
        <ul class="products-list product-list-in-card pl-2 pr-2">
          <li v-if = "loading == false" v-for = "product in  filteredProducts.slice(0,5)" class="item">
            <div class="product-img">
              <img v-bind:src=" 'img/' + product.image" alt="Product Image" class="img-size-50">
            </div>
            <div class="product-info">
              <a href="javascript:void(0)" class="product-title"> <span class="small">{{product.name }}</span>
                <span class="badge badge-warning float-right"> <span style="text-decoration: line-through">N</span> {{numeral(product.price) }}</span></a>
              <span class="product-description small">
                {{ product.category +  ", " + product.brand + ", " + product.size + ", " + product.unit}}.
              </span>
              <span v-bind:class = "{badge: true,small : true,'badge-success' :product.stock > 0, 'badge-danger': product.stock == 0, 'float-right' : true}"> {{ product.stock }}</span>
              <span  class="text-primary product-description small">Quantity
              </span>
            </div>
          </li>
            <li v-if = "loading == false && filteredProducts.length == 0">
              <h4  class="text-center m-1 p-2 border border-info small text-success">Product Not Found</h4>
            </li>
        </ul>
      </div>

        <div v-if = "loading" class=" overlay">
            <div style = "position:absolute; top:50%; left:50%; ">
              <i class="fas fa-sync-alt fa-spin"></i>
            </div>
        </div>
       
        <div class="card-footer clearfix">
           <router-link to="/products" class="btn btn-sm btn-secondary float-right">More Info</router-link>
        </div>

  </div>
</template>

<script>
    export default {
        mounted() {
          this.loadProducts();
        },
        props: ['token'],
         data() { 
            var d = new Date();
            return {
              month : d.getMonth() + 1,
              year : d.getFullYear(),
              products : [],
              loading : true,
              error : '',
              search : '',
              form : new Form(),
              }
        },
        created(){
          Fire.$on('product_created', data => {
            this.loadProducts();
          })
        },
         computed: {
            filteredProducts (){
                var data = [];
              if(this.search){
              data =  this.products.filter((item)=>{
                return item.name.toLowerCase().includes(this.search.toLowerCase());
              })
              }else{
                data = this.products;
              }
              return data;
            },

        },
        methods: {
            loadProducts(){
                this.form.get('./api/attributeproducts?year='+this.year)
                .then(response => {
                    this.loading = false;
                    this.products = response.data.data.item;
                })
                .catch(error => {
                    this.error = error.response.data.error;
                }); 
            },
            numeral(value){
                return numeral(value).format('0,0');
            },
        }
    }

</script>