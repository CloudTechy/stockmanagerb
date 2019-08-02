<template>
  <div>
    <nav-component></nav-component>
    <sidebar-component></sidebar-component>
    <!-- <div class="modal fade" id="addOrderComponent"><add-order-component></add-order-component></div> -->
    <div class="content-wrapper" style="min-height: 606px;">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-12">
              <h1 class="m-0 text-dark">orders</h1>
            </div>
          </div>
        </div>
      </div>

      <div class="content">
        <div  class="container-fluid">
            <div class="card card-success card-outline m-auto ">

              <div class="no-print">
                <div class="card-header  mb-2">
                  <h5 class="card-title font-weight-bold text-secondary">Make an Order</h5>
                </div>
                <show-orders-component></show-orders-component>
              </div>
              <hr>

<<<<<<< HEAD
=======
        <!-- <div class="row">
            <div class="card-body box-profile">
              <div class="row container  mb-3 mt-3">
                <div class="col-lg-12 col-sm-12 mb-3 ">
                  <div class="card-header">
                      <h5 class="card-title">{{ title }}</h5>
                  </div>
                  <h2 class="card-title small"> &nbsp;</h2>
                  <div class="">
                      <div class="input-group input-group-sm float-right" >
                        <input  v-model="search" type="text" ref = 'search' name="table_search" class="p-2 form-control" placeholder="Search orders">
                     </div>
                    </div> 
                    <div class="clearfix"> </div>
                    <ul class="orders-list order-list-in-card pl-2 pr-2">

                      <li ref = 'order' v-if = "loading == false" v-for = "order in  $root.myFilter(orders,search).slice(0,5)" class="item">
                          <div class="order-img">
                            <img v-bind:src=" 'img/' + order.image" alt="order Image" class="img-size-50">
                          </div>
                          <div class="order-info">
                            <a href="javascript:void(0)" class="order-title"> <span class="small">{{order.name }}</span>
                              <span class="badge badge-warning float-right"> <span style="text-decoration: line-through">N</span> {{$root.numeral(order.price) }}</span></a>
                            <span class="order-description small">
                              {{ order.category +  ", " + order.brand + ", " + order.size + ", " + order.unit}}.
                            </span>
                            <span v-bind:class = "{badge: true,small : true,'badge-success' :order.stock > 0, 'badge-danger': order.stock == 0, 'float-right' : true}"> {{ order.stock }}</span>
                            <span  class="text-primary order-description small">Quantity
                            </span>
                            <h3 href="javascript:void(0)" class="users-list-name font-weight-bold"> {{'order ID: ' + order.id }} </h3>
                          </div>
                        </li>
                      <li class="p-4 m-3 border border-info" v-if="loading == false && $root.myFilter(orders,search).length == 0">
                        <h4 class="text-center small text-secondary">orders Not Found</h4>
                      </li>
                    </ul>
                </div>
              </div>
            </div>
          </div> -->

>>>>>>> a90f05ca68e2264c685a9477281ef51e4d16983b
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
              products : [],
              orderStat: {},
              pending: [],
              not_paid: [],
              paid: [],
              order_type : [],
              purchase_type:[],
              loading : true,
              error : '',
              search : '',
              form: new Form(),
              owed : '',
              title : 'RECENTLY ADDED',
            }
        },
        beforeDestroy() {
            window.dispatchEvent(new Event('close_sidebar_min'))
        },
        created(){
            Fire.$on('order_created', (data)=> {
                this.loadorders();

            })
            Fire.$on('order_deleted', (data)=> {
                this.loadorders();
            })
            Fire.$on('order_edited', (data)=> {
                this.loadorders();
            })
            Fire.$on('view', (data)=> {
                this.search = data.id;
                this.title = "order DETAILS"
                this.$refs.search.focus()
            })
<<<<<<< HEAD
            //this.loadorders();
            //this.loadorderStat();
        },
        methods: {
            loadorders(){
                this.form.get('./api/attributeproducts')
=======
            this.loadorders();
            this.loadorderStat();
        },
        methods: {
            loadorders(){
                this.form.get('./api/attributeorders')
>>>>>>> a90f05ca68e2264c685a9477281ef51e4d16983b
                .then (response =>{
                  if(response.data.status == true){
                    this.loading = false;
                      this.orders = response.data.data.item;
                  }
                  else{
                    console.log("load supplier did not return positive response");
                  }
                    
                })
                .catch (error => {
                    this.error = error.response.data.error;
                    console.log(error);
                }); 
            },
            loadorderStat(){
              this.form.get('./api/statistics/orders?count')
              .then (response =>{
                  if(response.data.status == true){
                    this.loading = false;
                    this.orderStat = response.data.data.item[0];
                  }
                  else{
                    console.log(response.data);
                  }
                })
              .catch (error => {
                  this.error = error.response.data.error;
                  console.log(error);
              });
            },

            collapse(id){
              $('#' + id).toggle('collapse')
            },
        }

    }

</script>

<style  type="text/css">
  
  
   
</style>