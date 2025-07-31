<template>
    <div class="card card-warning">
        <div class="card-header">
            <h3 class="card-title">Today Sales</h3>
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
                    <div class="input-group input-group-sm float-right">
                        <input v-model="dateSearch" type="text" name="table_search" class="p-lg-3 p-sm-2 p-2 form-control" placeholder="Search Ex: 2021-01-21">
                        <span>
                            <button style="font-size: smaller" :title="dateSearch ? 'download products' : 'input date'" :disabled="dateSearch == ''" @click="loadProducts(dateSearch)" type="button" class="btn btn-outline-warning"><i class="fas fa-download"></i></button>
                        </span>
                    </div>
                </div>
            </div>
            <ul class="products-list product-list-in-card pl-2 pr-2" style="max-height: 500px; overflow: auto">
                <li v-if="loading == false" v-for="product in  filteredProducts" class="item">
                    <div class="product-img">
                        <img v-bind:src=" 'img/' + product.image" alt="Product Image" class="img-size-50">
                    </div>
                    <div class="product-info">
                        <span class="product-description font-weight-bold small">{{ product.product }}</span>
                        <a href="javascript:void(0)" class="product-title"> <span class="small">{{'Price'}}</span>
                            <span class="badge badge-warning float-right"> <span style="text-decoration: line-through">N</span> {{numeral(product.price) }}</span></a>
                        <div class="clearfix"></div>
                        <span v-bind:class="{badge: true, 'badge-success' :product.quantity > 0, 'badge-danger': product.quantity == 0, 'float-right' : true}"> {{ product.quantity }}</span>
                        <span class="text-primary product-description small">Quantity
                        </span>
                    </div>
                    <div class="product-img">
                        <span class="product-description small">Amount:&nbsp;<span class="badge badge-success"><span style="text-decoration: line-through">N</span>{{$root.numeral(product.amount) }}</span></span>
                    </div>
                    <div class="product-img float-right">
                        <span class="product-description  small">{{'Sold by ' + product.user}}</span>
                    </div>
                </li>
                <li v-if="loading == false && filteredProducts.length == 0">
                    <h4 class="text-center m-1 p-2 border border-info small text-success">No sales yet</h4>
                </li>
            </ul>
        </div>
        <div v-if="loading" class=" overlay">
            <div style="position:absolute; top:50%; left:50%; ">
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
        // if(localStorage.purchasedetails){
        //       this.products = JSON.parse(localStorage.purchasedetails)
        //       this.loading = false
        //   }
        this.loadProducts(new Date());
    },
    props: ['token'],
    data() {
        return {
            products: [],
            loading: true,
            error: '',
            dateSearch: '',
            search: '',
            form: new Form(),
        }
    },
    created() {
        Fire.$on('product_created', data => {
            this.loadProducts(new Date());
        })
        // Echo.channel('purchase')
        //   .listen('UpdatePurchase', (e) => {
        //     this.loadProducts();
        //   }); 
    },
    computed: {
        filteredProducts() {
            var data = [];
            if (this.search) {
                data = this.products.filter((item) => {
                    return item.name.toLowerCase().includes(this.search.toLowerCase());
                })
            } else {
                data = this.products;
            }
            return data;
        },

    },
    methods: {
        loadProducts(date) {
            this.loading = true
            date = this.dateString(date)
            var d = new Date(date);
            d.setDate(parseInt(d.getDate())  + 1)
            var x = this.dateString(d)
              
            this.form.get('/orderdetails?dateAfter=' + date + '&dateBefore=' + x)
                .then(response => {
                    this.loading = false;
                    this.products = response.data.data.item;
                })
                .catch(err => {
                    this.loading = false
                    if (err.response) {
                        // this.$root.alert('error',err.response.data.data.error, err.response.data.data.message )
                    }
                    else this.$root.alert('error','error', err )
                    console.log(err)
                    console.log(err.response)

                });
        },
        dateString(date){
              var d = new Date(date);
              var year = d.getFullYear();
              var month = d.getMonth()+1;
              var dt = d.getDate();

              if (dt < 10) {
                dt = '0' + dt;
              }
              if (month < 10) {
                month = '0' + month;
              }

             return year + '-' + month + '-' + dt
            },
        numeral(value) {
            return numeral(value).format('0,0');
        },
    }
}

</script>
