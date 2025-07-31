<template>
  <div class="card card-info">
    <div class="card-header">
      <h3 class="card-title text-center">Monthly Revenue Tracking</h3>
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
      <!-- No Activity Message -->
      <div class="text-center text-secondary p-2" v-if="statistics.length === 0">
        No Activity
      </div>

      <!-- Line Chart -->
      <div v-else>
        <LineChart :data="lineChartData" :options="lineChartOptions" />
      </div>
    </div>

    <div class="card-footer bg-warning">
      <div class="row">
        <div class="col-sm-4 col-4">
          <div class="description-block border-right">
            <h5 class="description-header">
              <span style="text-decoration: line-through">N</span>
              {{ numeral(revenueStat.amount) }}
            </h5>
            <span class="description-text">INCOME</span>
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
            <span class="description-text">NET PROFIT</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Line } from 'vue-chartjs'; // import Line from vue-chartjs
import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement } from 'chart.js';
import numeral from 'numeral';

// Register Chart.js components
ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement);

export default {
  mounted() {
    if (localStorage.revenueStat) {
      this.revenueStat = JSON.parse(localStorage.revenueStat);
    }
    if (localStorage.statistics) {
      this.statistics = JSON.parse(localStorage.statistics);
    }
    if (this.revenueStat && this.statistics) {
      this.loading = false;
      this.renderChart();
    }
    this.loadStat();
    this.$Progress.start();
  },
  components: {
    LineChart: Line, // Rename Line to LineChart
  },
  data() {
    const d = new Date();
    return {
      month: d.getMonth() + 1,
      year: d.getFullYear(),
      day: d.getDate(),
      statistics: [],
      loading: true,
      error: '',
      revenueStat: [],
      months: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
      lineChartData: {
        labels: [], // Months
        datasets: [], 
      },
      lineChartOptions: {
        responsive: true,
        plugins: {
          legend: {
            position: 'top',
          },
          tooltip: {
            callbacks: {
              label: function (tooltipItem) {
                return tooltipItem.raw + ' Amount'; // Tooltip custom label
              },
            },
          },
        },
        scales: {
          x: {
            type: 'category',
            labels: this.months,
          },
          y: {
            beginAtZero: true,
          },
        },
      },
      form: new Form(),
    };
  },
  created() {
    Fire.$on('order_created', this.loadStat);
    Fire.$on('product_created', this.loadStat);
  },
  methods: {
    loadStat() {
      this.form
        .get(`/statistics/transactions?date=month&transactionType=order&year=${this.year}`)
        .then((response) => {
          if (response.data.status === true) {
            this.statistics = response.data.data.item.length !== 0 ? response.data.data.item : [];
            localStorage.statistics = JSON.stringify(this.statistics);
            this.renderChart();
          }
          this.loadRevenueStat();
        })
        .catch((error) => {
          console.error(error);
          this.error = error.response?.data?.error || 'An error occurred while loading information.';
        });
    },

    loadRevenueStat() {
      this.form
        .get(`/statistics/transactions?order_revenue&year=${this.year}&month=${this.month}`)
        .then((response) => {
          if (response.data.status === true) {
            this.revenueStat = response.data.data.item.length !== 0 ? response.data.data.item[0] : [];
            localStorage.revenueStat = JSON.stringify(this.revenueStat);
          }
        })
        .catch((error) => {
          console.error(error);
          this.error = error.response?.data?.error || 'An error occurred while loading information.';
        });
    },

    rand(limit) {
      return Math.ceil(Math.random() * limit);
    },

    renderChart() {
      const transactions = [];
      const income = [];

      // Prepare the data for the chart
      for (let i = 0; i < this.statistics.length; i++) {
        transactions.push(this.statistics[i].transactions);
        income.push(this.statistics[i].amount);
      }

      this.lineChartData.labels = this.statistics.map((stat) => `${this.year}-${stat.month}`);
      this.lineChartData.datasets = [
        {
          label: 'Transactions',
          data: transactions,
          borderColor: '#42B8E0',
          backgroundColor: 'rgba(66, 184, 224, 0.2)',
          fill: true,
        },
        {
          label: 'Income',
          data: income,
          borderColor: '#33658A',
          backgroundColor: 'rgba(51, 101, 138, 0.2)',
          fill: true,
        },
      ];
    },

    numeral(value) {
      return numeral(value).format('0,0');
    },
  },
};
</script>
