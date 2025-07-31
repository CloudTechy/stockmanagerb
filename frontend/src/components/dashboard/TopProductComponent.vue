<template>
    <div class="card card-success">
      <div class="card-header no-border">
        <h3 class="card-title">Top Selling Products</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-widget="remove"><i class="fa fa-times"></i></button>
     </div>
      </div>
      <div class="card-body table-responsive p-0">
         <div class="card">
            <div class="">
                <div class="input-group input-group-sm float-right" >
                    <input  v-model="search" type="text" name="table_search" class="form-control p-lg-2 p-sm-2 p-1" placeholder="Search product by name here...">
                 </div>
            </div> 
        </div>
        <table class="table table-small table-striped table-valign-middle">
          <thead>
          <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Sales</th>
          </tr>
          </thead>
          <tbody>
            <tr v-for = "product in filteredProducts.slice(0,5)">
              <td class="small">
                {{ product.product }}
              </td>
              <td class="small"><span><span style="text-decoration: line-through">N</span> {{numeral(product.price) }}</span></td>
              <td class="small">
                <span class="text-success mr-1">
                  <i class="fa fa-arrow-up"></i>
                  {{ getPercent(product.quantity) }}%
                </span>
                {{product.quantity}} Sold
              </td>
            </tr>
            <tr v-if = "loading == false && filteredProducts == 0">
              <td colspan="3">
                <h4  class="text-center m-1 p-2 border border-info small text-success">Product Not Found</h4>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
        <div class="card-footer clearfix">
           <router-link to="/products" class="btn btn-sm btn-secondary float-right">More Info</router-link>
        </div>
        <div v-if = "loading" class=" overlay">
            <div style = "position:absolute; top:50%; left:50%; "><i class="fas fa-sync-alt fa-spin"></i></div>
        </div>
    </div>

</template>

<script>
    
    export default {
        mounted() {
          if(localStorage.products){
                this.products = JSON.parse(localStorage.products)
                this.loading = false
            }
          this.loadProductDetails();
            },
         data() { 
            var d = new Date();
            return {
              month : d.getMonth() + 1,
              year : d.getFullYear(),
              products : [],
              search: '',
              totalProducts : 0,
              loading : true,
              error : '',
              form : new Form(),
            }
        },
        created(){
          Fire.$on('product_created', data => {
            this.loadProductDetails();
          })
          // Echo.channel('purchase')
          //   .listen('UpdatePurchase', (e) => {
          //     this.loadProductDetails();
          //   }); 
        },
         computed: {

            filteredProducts (){
                var data = [];
              if(this.search){
              data =  this.products.filter((item)=>{
                return item.product.toLowerCase().includes(this.search.toLowerCase());
              })
              }else{
                data = this.products;
              }
              return data;
            },

        },
        methods: {
            loadProductDetails(){
                axios.get('/statistics/orders?products&year='+this.year+'&pageSize=5')
                .then( response => {
                     if(response.data.status == true){
                        this.products = response.data.data.item.length !=0 ? response.data.data.item : [];
                        this.totalProducts = response.data.data.item.length !=0 ? this.products.sum("quantity") : 0;
                        this.loading = false;
                    }
                })
                .catch( error => {
                    if(error.response){
                        this.error = error.response.data.data.error;
                    } else {
                        this.error = 'An error occurred while loading information.';
                        console.log(error);
                    }
                }); 
            },
            getPercent(quantity){
                var percent = this.totalProducts != 0 ? parseInt(quantity / this.totalProducts * 100)  : 0;
               
                return percent;
            },
            numeral(value){
                return numeral(value).format('0,0');
            },

        }
    }

</script>