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
<<<<<<< HEAD
            if(localStorage.ordersStat){
                this.orders =  JSON.parse(localStorage.ordersStat)
                this.loading = false;
              }
=======
>>>>>>> a90f05ca68e2264c685a9477281ef51e4d16983b
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
                axios.get('./api/statistics/transactions?type=order&day='+this.day+'&year='+this.year)
                .then( response => {
                    
                    if(response.data.status == true){
                        this.loading = false;
                        this.orders = response.data.data.item.length !=0 ? response.data.data.item : [{order:0}];
<<<<<<< HEAD
                        localStorage.ordersStat = JSON.stringify(this.orders)
=======
>>>>>>> a90f05ca68e2264c685a9477281ef51e4d16983b
                    } 
                })
                .catch( error => {
                    this.error = error.response.data.error;
                }); 
            },
        }
    }

</script>