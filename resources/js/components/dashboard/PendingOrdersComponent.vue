<template>
    <div class="info-box mb-3 bg-warning">
      <span class="info-box-icon"><i class="fa fa-tag"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Today Pending Orders</span>
        <span class="info-box-number">{{ orders[0].count }}</span>
      </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.loadOrders();
            },
         data() { 
            var d = new Date();
            return {
            day: d.getDate(),
            month : d.getMonth() + 1,
            year : d.getFullYear(),
            orders : [{count:0}],
            loading : true,
            error : '',
            form : new Form(),
            }
        },
        created(){
          Fire.$on('order_created', data => {
            this.loadOrders();
          })
        },
        methods: {
            loadOrders(){
                this.form.get('./api/statistics/transactions?status=pending&day='+this.day+'&year='+this.year)
                .then(response => {
                    if(response.data.status == true){
                        this.loading = false;
                        this.orders = response.data.data.item.length !=0 ? response.data.data.item : [{count:0}];
                    } 
                })
                .catch(error => {
                    this.error = error.response.data.error;
                }); 
            },
        }
    }

</script>