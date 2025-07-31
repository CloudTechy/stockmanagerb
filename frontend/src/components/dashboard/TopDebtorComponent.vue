<template>
    <div class="card card-danger card-outline">
      <div class="card-header">
        <h5 class="card-title">Top {{year}} Debtors</h5>
        <div class="card-tools">
          <div class="input-group input-group-sm" style="width: 120px;">
            <input  v-model="search" type="text" name="table_search" class="form-control float-md-right" placeholder="Search Customer">
          </div>
        </div>
      </div>

      <!-- /.card-header -->
      <div class="card-body table-responsive p-0">
            <table class="table table-small table-striped  table-bordered table-hover">
                <thead class="text-center">
                    <tr>
                    <th>Customer</th>
                    <th>Amount <br> 
                        (<span style="text-decoration: line-through">N</span>)
                    </th>
                    <th>Meter</th>
                    <th style="width: 40px">Percent Increase</th>
                  </tr>
            </thead>
           <tbody id='tbody'>
                 <tr v-for = "debtor in filteredDebtors.slice(0,5)">
                    <td>{{debtor.name}}</td>
                    <td>{{numeral(debtor.owing)}}</td>
                    <td>
                    <div class="progress progress-xs progress-striped ">
                        <div v-bind:class="{'progress-bar' : true, 'bg-danger': getPercent(debtor.owing) >= 70,'bg-warning': getPercent(debtor.owing) >= 30, 'bg-success': getPercent(debtor.owing) >= 0} " v-bind:style="{width: getPercent(debtor.owing) + '%'}"></div>
                        <div v-bind:class="{'progress-bar' : true, 'bg-danger': getPercent(debtor.owing) >= 70,'bg-warning': getPercent(debtor.owing) >= 30, 'bg-success': getPercent(debtor.owing) > 0} " v-bind:style="{width: getPercent(debtor.owing) + '%'}"></div>
                      </div>
                    </td>
                    <td class="text-center"><span v-bind:class="{badge:true,'bg-danger': getPercent(debtor.owing) >= 70,'bg-warning': getPercent(debtor.owing) >= 30, 'bg-success': getPercent(debtor.owing) >= 0}">{{ getPercent(debtor.owing) }}%</span>
                    </td>
                </tr>
                <tr v-if = "loading == false && filteredDebtors == 0">
                    <td colspan="4">
                        <h4  class="text-center m-1 p-2 border border-success small text-success">CONGRATS!!! You have no debtor</h4>
                    </td>
                </tr>
            </tbody>
        </table>
      </div>
      <!-- /.card-body -->
        <div class="card-footer clearfix">
           <router-link to="/customers" class="btn btn-sm btn-secondary float-right">More Info</router-link>
        </div>
        <div v-if = "loading" class=" overlay">
            <div style = "position:absolute; top:50%; left:50%; "><i class="fas fa-sync-alt fa-spin"></i></div>
        </div>
    </div>

</template>

<script>
   
    export default {
        mounted() {
          if(localStorage.debtorsStat){
            this.debtors =  JSON.parse(localStorage.debtorsStat)
            this.loading = false;
          }
          this.loadDebtor();
        },
        data() { 
          var d = new Date();
          return {
          month : d.getMonth() + 1,
          year : d.getFullYear(),
          debtors : [],
          loading : true,
          search : '',
          error : '',
          totalDebtors: 0,
          form : new Form()
          }
        },
        computed: {

            filteredDebtors (){
                var data = [];
              if(this.search){
              data =  this.debtors.filter((item)=>{
                return item.name.toLowerCase().includes(this.search.toLowerCase());
              })
              }else{
                data = this.debtors;
              }
              return data;
            },

        },
        created(){
          Fire.$on('product_created', data => {
            this.loadDebtor();
          })
          // Echo.channel('order')
          //   .listen('UpdateOrder', (e) => {
          //       this.loadDebtor();
          //   });
        },
        methods: {
            loadDebtor(){
                axios.get('/statistics/customers?amount&year='+this.year)
                .then(response => {
                    if(response.data.status == true){
                        this.debtors = response.data.data.item.length !=0 ? response.data.data.item : [];
                        localStorage.debtorsStat = JSON.stringify(this.debtors)
                        this.totalDebtors = response.data.data.item.length !=0 ? this.debtors.sum("owing") : 0;
                        this.loading = false;
                    }
                })
                .catch(error => {
                    if(error.response){
                        this.error = error.response.data.data.error;
                    } else {
                        this.error = 'An error occurred while loading information.';
                        console.log(error);
                    }
                }); 
            },
            getPercent(owed){
                var percentDebtors = this.totalDebtors != 0 ? parseInt(owed / this.totalDebtors * 100)  : 0;
               
                return percentDebtors;
            },
            numeral(value){
                return numeral(value).format('0,0');
            },
        }
    }

</script>