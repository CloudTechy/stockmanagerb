<template>
  <div>
    <nav-component></nav-component>
    <sidebar-component></sidebar-component>
    <div class="content-wrapper" style="min-height: 606px;">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-12">
              <h1 class="m-0 text-dark">Transactions</h1>
            </div>
          </div>
        </div>
      </div>

      <div class="content">
        <div  class="container-fluid">
            <div class="card card-success card-outline m-auto ">
              <div class="card-header">
                  <h5 class="card-title"> Summary</h5>
              </div>
        
              <div class="card-body box-profile">
                <div class="row container text-center  mb-2 mt-4">
                  <div class="col-sm-6 col-md-3 mb-3 text-center">
                    <div class="circle border-success mb-lg-4"><div class="inner-circle border-secondary"><p  class=" circle-text ">{{ transactions.length }}</p></div></div>
                    <h4 class="profile-username ">Transactions</h4>
                  </div>
                  <div class="col-sm-6 col-md-3 mb-3 text-center">
                    <div class="circle border-success mb-lg-4"><div class="inner-circle border-secondary"><p class="circle-text ">{{ order_type.length }}</p></div></div>
                    <h4 class="profile-username ">Ordered</h4>
                  </div>
                  <div class="col-sm-6 col-md-3 mb-3 text-center">
                    <div class="circle border-success mb-lg-4"><div class="inner-circle border-secondary"><p class="circle-text ">{{ purchase_type.length }}</p></div></div>
                    <h4 class="profile-username ">Purchased</h4>
                  </div>
                  <div class="col-sm-6 col-md-3 mb-3 text-center">
                    <div class="circle border-success mb-lg-4"><div class="inner-circle border-secondary"><p class="circle-text bg-success font-weight-bold"><span class="" style="text-decoration: line-through">N</span>{{ $root.numeral(transactions.sum('payment')) }}</p></div></div>
                    <h4 class="profile-username bg-success  font-weight-bold">Revenue</h4>
                  </div>
                </div>
              </div>
              <hr>

              <div>
                <div class="card-header mb-2">
                  <h5 class="card-title  text-primary">View Transactions</h5>
                </div>
                <show-transactions-component></show-transactions-component>
              </div>
              <hr>

             
          <div style="position: fixed; bottom: 25px; right: 25px">
            <button type="button" @click.prevent = "$root.addTransactionComponent()"  class="btn btn-success  rounded-circle" title="Add newG transaction" id = "add" data-toggle="modal" data-target="#addTransactionComponent" ><i class="fa fa-plus"></i> </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
  
</template>

<script>
  
    export default {
        mounted() {
            },
         data() { 
            var d = new Date();
            return {
              month : d.getMonth() + 1,
              year : d.getFullYear(),
              transactions : [],
              purchase_type: [],
              order_type: [],
              revenue: 0,
              loading : true,
              error : '',
              search : '',
              form: new Form(),
              owed : '',
            }
        },
        beforeDestroy() {
            window.dispatchEvent(new Event('close_sidebar_min'))
        },
        created(){
            Fire.$on('transaction_created', (data)=> {
                this.loadTransactions();
            })
            Fire.$on('transaction_deleted', (data)=> {
                this.loadTransactions();
            })
            Fire.$on('transaction_edited', (data)=> {
                this.loadTransactions();
            })
            Fire.$on('view', (data)=> {
                this.search = data.id;
                this.title = "TRANSACTIONS DETAILS"
                this.$refs.search.focus()
            })
            this.loadTransactions();
        },
        methods: {
            loadTransactions(){
                this.form.get('./api/transactions')
                .then (response =>{
                  if(response.data.status == true){
                    this.loading = false;
                      this.transactions = response.data.data.item;
                      response.data.data.item.forEach(this.count);
                  }
                  else{
                    console.log("load transaction did not return positive response");
                  }
                    
                })
                .catch (error => {
                    this.error = error.response.data.error;
                    console.log(error);
                }); 
            },
            count(transaction){
              if (transaction.type == 'order') {
                this.purchase_type.push(transaction);
              }
              else if (transaction.type == 'purchase') {
                this.order_type.push(transaction);
              }
            },
            
        }

    }

</script>

<style  type="text/css">
  
  
   
</style>