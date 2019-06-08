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
          <span class="info-box-text">Profit This Month</span>
        </div>
      </div>
    </div>
   
    <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box mb-3">
        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-warehouse"></i></span>
        <div class="info-box-content">
        	<span class="info-box-number">{{stock.total}}</span>
          <span class="info-box-text">Total Stock</span>
        </div>
      </div>
    </div>


    <div class="clearfix hidden-md-up"></div>

    <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box mb-3">
        <span class="info-box-icon bg-success elevation-1"><i class="fa fa-shopping-cart"></i></span>
        <div class="info-box-content">
          <span class="info-box-number">{{orders.count}}</span>
          <span class="info-box-text">Total Sales this Month</span>
        </div>    
      </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box mb-3">
        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-money-check"></i></span>
        <div class="info-box-content">
          <span class="info-box-number"> N{{owed}} </span>
          <span class="info-box-text">Owed This month</span>
        </div>
      </div>
    </div>

  </div>

</template>

<script>
  export default {
    mounted(){
      this.bootstrap();
    },
    watch : {
       	orderDetails : function(){
       		this.percentProfit = Math.round(this.orderDetails.profit/ this.orderDetails.cost * 100);
       	}
     },
    created(){
      Fire.$on('product_created', data => {
        this.bootstrap();
      })
      Fire.$on('order_created', data => {
        this.bootstrap();
      })
    },
    data() { 
      var d = new Date();
    	return {
		 	  month : d.getMonth() + 1,
		    year : d.getFullYear(),
      	orderDetails : [],
      	percentProfit : 0,
      	stock : {total:0},
      	orders: {count:0},
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
	    	this.form.get('./api/statistics/transactions?revenue&month='+this.month+'&year='+this.year)
		  .then(response  => {
		    	this.orderDetails = response.data.data.item[0];
		    })
		    .catch( error => {
		      this.error = error.response.data.error;
		    }); 
	    },
	    loadStock(){
	    	this.form.get('./api/statistics/products?total')
		  .then(response  => {
		    	this.stock = response.data.data.item[0];
		    })
		    .catch( error => {
		      this.error = error.response.data.error;
		    });  
	    },
	    loadOrders(){
	    	this.form.get('./api/statistics/transactions?status=paid&month='+this.month+'&year='+this.year)
		  .then(response  => {
		    	this.orders = response.data.data.item[0];
		    })
		    .catch( error => {
		      this.error = error.response.data.error;
		    }); 
	    },
	    loadOwing(){
	    	this.form.get('./api/statistics/customers?owing&month='+this.month+'&year='+this.year)
		  	.then(response  => {
    			this.owed = numeral(response.data.data.item.length > 0 ? response.data.data.item[0].owing : 0).format('0,0.00');
          this.$Progress.finish();
		    })
		    .catch( error => {
         console.log(error);
		    }); 
	    }
    },
  }
</script>


