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
                  <RevenueLineChartComponent/>
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
import NavComponent from '@/components/NavComponent.vue';
import SidebarComponent from '@/components/SidebarComponent.vue';
import DailyOrdersComponent from '@/components/dashboard/DailyOrdersComponent.vue';
import DashboardInfoComponent from '@/components/dashboard/DashboardInfoComponent.vue';
import DashboardOrderComponent from '@/components/dashboard/DashboardOrderComponent.vue';
import DashboardPurchaseComponent from '@/components/dashboard/DashboardPurchaseComponent.vue';
import DashboardTransactionComponent from '@/components/dashboard/DashboardTransactionComponent.vue';
import InvoiceDonutComponent from '@/components/dashboard/InvoiceDonutComponent.vue';
import NewProductComponent from '@/components/dashboard/NewProductComponent.vue';
import PendingOrdersComponent from '@/components/dashboard/PendingOrdersComponent.vue';
import RevenueLineChartComponent from '@/components/dashboard/RevenueLineChartComponent.vue';
import TodaySalesComponent from '@/components/dashboard/TodaySalesComponent.vue';
import TopDebtComponent from '@/components/dashboard/TopDebtComponent.vue';
import TopDebtorComponent from '@/components/dashboard/TopDebtorComponent.vue';
import TopProductComponent from '@/components/dashboard/TopProductComponent.vue';

export default {
  name: 'DashboardPage',
  components: {
    NavComponent,
    SidebarComponent,
    DailyOrdersComponent,
    DashboardInfoComponent,
    DashboardOrderComponent,
    DashboardPurchaseComponent,
    DashboardTransactionComponent,
    InvoiceDonutComponent,
    NewProductComponent,
    PendingOrdersComponent,
    RevenueLineChartComponent,
    TodaySalesComponent,
    TopDebtComponent,
    TopDebtorComponent,
    TopProductComponent
  }
}
    
</script>

