 <template>

	<div class="row">

    <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box">
        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-chart-line"></i></span>
        <div class="info-box-content">
        	<span class="info-box-number">
            {{percentProfit}}
            <small>%</small>
          </span>
          <span class="info-box-text">Monthly Profit</span>
        </div>
      </div>
    </div>
   
    <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box mb-3">
        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-warehouse"></i></span>
        <div class="info-box-content">
        	<span class="info-box-number">{{stock.total}}</span>
          <span class="info-box-text">Stock</span>
        </div>
      </div>
    </div>


    <div class="clearfix hidden-md-up"></div>

    <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box mb-3">
        <span class="info-box-icon bg-success elevation-1"><i class="fa fa-shopping-cart"></i></span>
        <div class="info-box-content">
          <span class="info-box-number">{{orders.order}}</span>
          <span class="info-box-text">Monthly Sales</span>
        </div>    
      </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box mb-3">
        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-money-check"></i></span>
        <div class="info-box-content">
          <span class="info-box-number"> N{{owed}} </span>
<<<<<<< HEAD
          <span class="info-box-text">Owed</span>
=======
          <span class="info-box-text">Owed This month</span>
>>>>>>> a90f05ca68e2264c685a9477281ef51e4d16983b
        </div>
      </div>
    </div>

  </div>

</template>

<script>
  export default {
    mounted(){
<<<<<<< HEAD
      if(localStorage.orderDetailsStat){
        this.orderDetails =  JSON.parse(localStorage.orderDetailsStat)
        this.loading = false;
      }
      if(localStorage.ordersInfoStat){
        this.orders =  JSON.parse(localStorage.ordersInfoStat)
        this.loading = false;
      }
      if(localStorage.stockStat){
        this.stock =  JSON.parse(localStorage.stockStat)
        this.loading = false;
      }
      if(localStorage.owedStat){
        this.owed =  JSON.parse(localStorage.owedStat)
        this.loading = false;
      }
=======
      
>>>>>>> a90f05ca68e2264c685a9477281ef51e4d16983b
    },
    watch : {
       	orderDetails : function(){
          if(this.orderDetails){
       		 this.percentProfit = Math.round(this.orderDetails.profit/ this.orderDetails.cost * 100);
<<<<<<< HEAD
           console.log('test');
=======
>>>>>>> a90f05ca68e2264c685a9477281ef51e4d16983b
          }
       	}
     },
    created(){
      Fire.$on('product_created', data => {
        this.bootstrap();
      })
      Fire.$on('order_created', data => {
        this.bootstrap();
      })
      this.bootstrap();
<<<<<<< HEAD
      Echo.channel('order')
        .listen('UpdateOrder', (e) => {
            this.bootstrap();
        });
      Echo.channel('purchase')
        .listen('UpdatePurchase', (e) => {
            this.bootstrap();
        });
=======
>>>>>>> a90f05ca68e2264c685a9477281ef51e4d16983b
    },
    data() { 
      var d = new Date();
    	return {
		 	  month : d.getMonth() + 1,
		    year : d.getFullYear(),
      	orderDetails : '',
      	percentProfit : 0,
      	stock : {total:0},
      	orders: {order:0},
      	owed : 0,
      	error : '',
        form : new Form(),
      }
    },
	  methods: {
			bootstrap(){
      this.$Progress.start();
			this.loadStock();
			this.loadOrderDetails();
			this.loadOrders();
			this.loadOwing();
		   },
	    loadOrderDetails(){
	    	this.form.get('./api/statistics/transactions?order_revenue&month='+this.month+'&year='+this.year)
		  .then(response  => {
        this.orderDetails = response.data.data.item.length !=0 ? response.data.data.item[0] :'';
<<<<<<< HEAD
        localStorage.orderDetailsStat = JSON.stringify(this.orderDetails)
=======
>>>>>>> a90f05ca68e2264c685a9477281ef51e4d16983b
		    })
		    .catch( error => {
		      this.error = error.response.data.error;
		    }); 
	    },
	    loadStock(){
	    	this.form.get('./api/statistics/products?total')
		  .then(response  => {
		    	this.stock = response.data.data.item[0];
<<<<<<< HEAD
          localStorage.stockStat = JSON.stringify(this.stock)
=======
>>>>>>> a90f05ca68e2264c685a9477281ef51e4d16983b
		    })
		    .catch( error => {
		      this.error = error.response.data.error;
		    });  
	    },
	    loadOrders(){
	    	this.form.get('./api/statistics/transactions?type=order&month='+this.month+'&year='+this.year)
		  .then(response  => {
         this.orders = response.data.data.item.length !=0 ? response.data.data.item[0] :{count:0};
<<<<<<< HEAD
         localStorage.ordersInfoStat = JSON.stringify(this.orders)
=======
>>>>>>> a90f05ca68e2264c685a9477281ef51e4d16983b
		    })
		    .catch( error => {
		      this.error = error.response.data.error;
		    }); 
	    },
	    loadOwing(){
<<<<<<< HEAD
	    	this.form.get('./api/statistics/customers?owing')
		  	.then(response  => {
    			this.owed = numeral(response.data.data.item.length > 0 ? response.data.data.item[0].owing : 0).format('0,0.00');
          localStorage.owedStat = JSON.stringify(this.owed)
=======
	    	this.form.get('./api/statistics/customers?owing&month='+this.month+'&year='+this.year)
		  	.then(response  => {
    			this.owed = numeral(response.data.data.item.length > 0 ? response.data.data.item[0].owing : 0).format('0,0.00');
>>>>>>> a90f05ca68e2264c685a9477281ef51e4d16983b
          this.$Progress.finish();
		    })
		    .catch( error => {
         console.log(error);
		    }); 
	    }
    },
  }
</script>


