<template>
    <div class="info-box mb-3 bg-success">
      <span class="info-box-icon"><i class="fas fa-cart-arrow-down"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Today Sales</span>
        <span class="info-box-number">{{ orders[0].order }}</span>
      </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            if(localStorage.ordersStat){
                this.orders =  JSON.parse(localStorage.ordersStat)
                this.loading = false;
              }
            this.loadOrders();
            },
         data() { 
            var d = new Date();
            return {
            day: d.getDate(),
            month : d.getMonth() + 1,
            year : d.getFullYear(),
            orders : [{order:0}],
            loading : true,
            error : '',
            form : new Form(),
            }
        },
        created(){
          Fire.$on('orders_created', data => {
            this.loadOrders();
          })
        },
        methods: {
            loadOrders(){
                axios.get('/statistics/transactions?type=order&day='+this.day+'&year='+this.year)
                .then( response => {
                    
                    if(response.data.status == true){
                        this.loading = false;
                        this.orders = response.data.data.item.length !=0 ? response.data.data.item : [{order:0}];
                        localStorage.ordersStat = JSON.stringify(this.orders)
                    } 
                })
                .catch( error => {
                    if (error.response && error.response.data.status == 401) {
                   //this.$router.push("/login")

                }
                    if(error.response){
                        this.error = error.response.data.data.error;
                    } else {
                        this.error = 'An error occurred while loading information.';
                        console.log(error);
                    }
                }); 
            },
        }
    }

</script>