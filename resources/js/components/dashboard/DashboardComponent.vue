<template>
  <div>
    <nav-component></nav-component>
    <sidebar-component></sidebar-component>
    <div class="content-wrapper" style="min-height: 606px;">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-12">
              <h1 class="m-0 text-dark">Dashboard</h1>
            </div>
          </div>
        </div>
      </div>

      <div class="content">
        <div  class="container-fluid">

            <dashboard-info-component> </dashboard-info-component>
            <div class="row">
               
                <div class="col-lg-8">
                    <revenue-line-chart-component></revenue-line-chart-component>
                    <dashboard-order-component></dashboard-order-component>
                    <dashboard-purchase-component></dashboard-purchase-component>
                    <dashboard-transaction-component></dashboard-transaction-component>
                </div>
                
                <div class="col-lg-4">
                    <daily-orders-component></daily-orders-component>
                    <pending-orders-component></pending-orders-component>
                    <new-product-component></new-product-component>
                    <today-sales-component></today-sales-component>
                    <top-product-component></top-product-component>
                    <invoice-donut-component></invoice-donut-component>
                </div> 

            </div>

            <div class="row">
                <div class="col-lg-6 col-12">
                    <top-debtor-component></top-debtor-component>
                </div>
                <div class="col-lg-6 col-12">
                    <top-debt-component></top-debt-component>
                </div>
            </div>

          
        </div>
      </div>

    </div>
  </div>
  
</template>
<script>
    export default {
      // beforeCreate: function () {
      //   if (!this.$session.exists()) {
      //       this.$router.push('/login')
      //     }
          
      //   },
    }
</script>

