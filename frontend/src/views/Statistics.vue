<template>
    <div>
      <nav-component></nav-component>
      <sidebar-component></sidebar-component>
      <div class="content-wrapper" style="min-height: 606px;">
        <div class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-12">
                <h1 class="m-0 text-dark">STATISTICS REPORT</h1>
              </div>
            </div>
          </div>
        </div>
  
        <div class="content">
          <div class="container-fluid">
            <div class="card card-success card-outline m-auto">
              <div class="card-header">
                <h3 class="card-title mt-1 m-2 text-center">Year Revenue Tracking</h3>
              </div>
  
              <div class="card-body">
                <div class="text-center text-secondary p-2" v-if="statistics.length == 0">
                  No Activity
                </div>
                <line-chart
                  v-else
                  :data="chartData"
                  :options="chartOptions"
                ></line-chart>
              </div>
  
              <div class="card-footer bg-warning">
                <div class="row">
                  <div class="col-sm-4 col-4">
                    <div class="description-block border-right">
                      <h5 class="description-header">
                        <span style="text-decoration: line-through">N</span>
                        {{ numeral(revenueStat.amount) }}
                      </h5>
                      <span class="description-text">REVENUE</span>
                    </div>
                  </div>
  
                  <div class="col-sm-4 col-4">
                    <div class="description-block border-right">
                      <h5 class="description-header">
                        <span style="text-decoration: line-through">N</span>
                        {{ numeral(revenueStat.cost) }}
                      </h5>
                      <span class="description-text">COST</span>
                    </div>
                  </div>
  
                  <div class="col-sm-4 col-4">
                    <div class="description-block">
                      <h5 class="description-header">
                        <span style="text-decoration: line-through">N</span>
                        {{ numeral(revenueStat.profit) }}
                      </h5>
                      <span class="description-text">PROFIT</span>
                    </div>
                  </div>
                </div>
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
  import { Line } from "vue-chartjs";
  import { Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend } from "chart.js";
  
  // Registering chart.js components
  ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend);
  
  const COLORS = ['#42B8E0', '#33658A', '#F6AE2D', '#F26419', '#0E3A53'];
  
  export default {
    components: {
      LineChart: Line,
        NavComponent,
        SidebarComponent,
    },
    mounted() {
      this.loadStat();
      this.$Progress.start();
    },
    data() {
      var d = new Date();
      return {
        month: d.getMonth() + 1,
        year: d.getFullYear(),
        day: d.getDate(),
        statistics: [],
        loading: true,
        error: '',
        revenueStat: '',
        months: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        lineData: [],
        series: [],
        labels: [],
        lineColors: [],
        form: new Form(),
      };
    },
    created() {
      Fire.$on('order_created', data => {
        this.loadStat();
      });
      Fire.$on('product_created', data => {
        this.loadStat();
      });
    },
    methods: {
      loadStat() {
        this.form.get('/statistics/transactions?date=month&transactionType=order&year=' + this.year)
          .then(response => {
            if (response.data.status == true) {
              this.statistics = response.data.data.item.length != 0 ? response.data.data.item : [];
              this.renderChart();
            }
            this.loadRevenueStat();
          })
          .catch(error => {
            console.log(error);
            this.error = error.response.data.data.error;
          });
      },
      loadRevenueStat() {
        this.form.get('/statistics/transactions?order_revenue&year=' + this.year)
          .then(response => {
            if (response.data.status == true) {
              this.revenueStat = response.data.data.item.length != 0 ? response.data.data.item[0] : '';
            }
          })
          .catch(error => {
            console.log(error);
            this.error = error.response.data.data.error;
          });
      },
      renderChart() {
        this.series = ['transaction', 'income'];
        this.labels = ['Transaction', 'Income'];
        this.lineColors = ['#42B8E0', '#33658A'];
  
        const months = this.months;
        this.lineData = this.statistics.map(item => ({
          month: `${this.year}-${item.month}`,
          transaction: item.transactions,
          income: item.amount,
        }));
  
        this.chartData = {
          labels: months,
          datasets: [
            {
              label: 'Transactions',
              data: this.lineData.map(item => item.transaction),
              borderColor: this.lineColors[0],
              fill: false,
            },
            {
              label: 'Income',
              data: this.lineData.map(item => item.income),
              borderColor: this.lineColors[1],
              fill: false,
            }
          ]
        };
  
        this.chartOptions = {
          responsive: true,
          plugins: {
            legend: {
              position: 'top',
            },
            tooltip: {
              mode: 'index',
              intersect: false,
            },
          },
          scales: {
            x: {
              title: {
                display: true,
                text: 'Month',
              },
            },
            y: {
              title: {
                display: true,
                text: 'Amount',
              },
              beginAtZero: true,
            },
          }
        };
      },
      numeral(value) {
        return numeral(value).format('0,0');
      },
    }
  };
  </script>
  
  <style scoped>
  /* Add styles as needed */
  </style>
  