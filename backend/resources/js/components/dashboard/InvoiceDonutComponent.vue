<template>
    <div class="card card-info ">
        <div class="card-header">
            <h3 class="card-title">Invoice Status Chart</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
        </div>
        <div class="card-body">
            <donut-chart v-if = "loading == false" 
              id="donut"
              v-bind:data="donutData"
              colors='[ "#FF6384", "#36A2EB", "#FFCE56" ]'
              resize="true" >
            </donut-chart>
        </div>
        <div v-if = "loading" class="text-center overlay">
            <div style = "position:absolute; top:50%; left:50%; ">
                <i class="fas fa-sync-alt fa-spin"></i>
            </div>
        </div>
    </div>
</template>

<script>

    import { DonutChart } from 'vue-morris'

    const COLORS = [ '#42B8E0', '#33658A', '#F6AE2D', '#F26419', '#0E3A53' ]
    export default {
        mounted() {
            this.loadPendingInvoices();
            },
        components: {
            DonutChart
        },
         data() { 
            var d = new Date();
            return {
            month : d.getMonth() + 1,
            year : d.getFullYear(),
            day: d.getDate(),
            donutData: [
              { label: 'Pending', value: 0 },
              { label: 'Paid', value: 0 },
              { label: 'Not Paid', value: 0 }
            ],
            products : [],
            loading : true,
            error : '',
            form : new Form(),
            }
        },
        created(){
          Fire.$on('transaction_created', data => {
            this.loadPendingInvoices();
          })
          // Echo.channel('transaction')
          //   .listen('UpdateTransaction', (e) => {
          //       this.loadPendingInvoices();
          //   });
        },
        methods: {
            loadPendingInvoices(){                
                this.form.get('./statistics/transactions?status=pending&day='+this.day+'&year='+this.year)
                .then(response => {
                    
                    if(response.data.status == true){
                        
                        this.donutData[0].value = response.data.data.item.length !=0 ? response.data.data.item[0].count : 0;
                        this.loadPaidInvoices();
                    } 
                })
                .catch(error => {
                    this.error = error.response.data.error;
                }); 
            },
            loadPaidInvoices(){
                this.form.get('./statistics/transactions?status=paid&day='+this.day+'&year='+this.year)
                .then(response => {
                    
                    if(response.data.status == true){
                        this.donutData[1].value = response.data.data.item.length !=0 ? response.data.data.item[0].count : 0;
                        this.loadNotPaidInvoices();
                    } 
                })
                .catch(error => {
                    this.error = error.response.data.error;
                }); 
            },
            loadNotPaidInvoices(){
                this.form.get('./statistics/transactions?status=not-paid&day='+this.day+'&year='+this.year)
                .then(response => {
                    
                    if(response.data.status == true){
                        this.donutData[2].value = response.data.data.item.length !=0 ? response.data.data.item[0].count : 0;
                        this.loading = false; 
                    } 
                })
                .catch(error => {
                    this.error = error.response.data.error;
                }); 
            },
            
        }
    }

</script>