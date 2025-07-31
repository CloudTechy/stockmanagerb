<template>
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">Invoice Status Chart</h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-widget="collapse">
            <i class="fa fa-minus"></i>
          </button>
          <button type="button" class="btn btn-tool" data-widget="remove">
            <i class="fa fa-times"></i>
          </button>
        </div>
      </div>
  
      <div class="card-body">
        <!-- Donut Chart -->
        <div v-if="!loading">
          <Doughnut :data="chartData" :options="donutChartOptions" />
        </div>
      </div>
  
      <div v-if="loading" class="text-center overlay">
        <div class="position-absolute-center">
          <i class="fas fa-sync-alt fa-spin"></i>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { Doughnut } from 'vue-chartjs';
  import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement, CategoryScale, LinearScale } from 'chart.js';
  
  // Register Chart.js components
  ChartJS.register(Title, Tooltip, Legend, ArcElement, CategoryScale, LinearScale);
  
  export default {
    name: 'InvoiceStatusChart',
    components: {
      Doughnut
    },
    data() {
      const now = new Date();
      return {
        day: now.getDate(),
        month: now.getMonth() + 1,
        year: now.getFullYear(),
        loading: true,
        error: '',
        colors: ['#FF6384', '#36A2EB', '#FFCE56'],
        chartData: {
          labels: ['Pending', 'Paid', 'Not Paid'],  // Labels for the chart
          datasets: [{
            data: [0, 0, 0],  // Initial data for Pending, Paid, Not Paid
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'], // Color configuration
            borderWidth: 1
          }]
        },
        donutChartOptions: {
          responsive: true,
          plugins: {
            legend: {
              position: 'top',
            },
            tooltip: {
              callbacks: {
                label: function(tooltipItem) {
                  return tooltipItem.raw + ' Invoices';  // Tooltip label formatter
                }
              }
            }
          }
        },
        form: new Form(),
      };
    },
    mounted() {
      this.fetchInvoiceStats();
    },
    created() {
      Fire.$on('transaction_created', this.fetchInvoiceStats);
    },
    methods: {
      fetchInvoiceStats() {
        this.loading = true;
        this.getInvoiceCount('pending', 0)
          .then(() => this.getInvoiceCount('paid', 1))
          .then(() => this.getInvoiceCount('not-paid', 2))
          .then(() => {
            this.loading = false;
          })
          .catch((error) => {
            this.error = error.response?.data?.error || 'Failed to load data';
            this.loading = false;
          });
      },
  
      getInvoiceCount(status, index) {
        const url = `/statistics/transactions?status=${status}&day=${this.day}&year=${this.year}`;
        return this.form.get(url).then((response) => {
          if (response.data.status) {
            const count = response.data.data.item?.[0]?.count || 0;
            this.chartData.datasets[0].data[index] = count; // Update chart data dynamically
          }
        });
      },
    },
  };
  </script>
  
  <style scoped>
  .position-absolute-center {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
  }
  </style>
  