<template>
  
    <!-- Main content -->
    <section id = 'section' class="invoice"> 
      <!-- title row -->
      <div class=" m-1 text-right no-print"><button @click.prevent = "$root.back()" class="mr-auto btn btn-primary"><i class="fas fa-chevron-left fa-fw"></i>Back</button></div>

      <div class="row">
        <div class="col-12">
          <h2 class="page-header">
            <i class="fa fa-globe"></i> Big Star IND CO LTD.
            <small class="float-right">Date: {{ date }}</small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
          {{ invoice.type == 'order' ? 'From ' : 'To '  }}
          <address>
            <strong>Big Star IND CO LTD</strong><br>
            Zone 15 NO 76, New Motorcycle Spare Parts Nnewi<br>
            Staff: {{ user.names }}<br>
            Phone: {{ user.number }}<br>
            Email: {{ user.email }} 
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          {{ invoice.type == 'order' ? 'To ' : 'From '  }}
          <address>
            <strong>{{ invoice.name }}</strong><br>
            Phone: {{ invoice.number }}<br>
            Email: {{ invoice.email }}
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          <b>Invoice {{ invoice.id }}</b><br>
          <br>
          <b>{{ invoice.type == 'order' ? 'Order ID: ' : 'Purchase ID: ' }} </b> {{ invoice.type == 'order' ? invoice.order_id : invoice.purchase_id }}<br>
          <b>Payment Due:</b> {{ invoice.due_date }}<br>
          <b>Status:</b> {{ invoice.status }}<br>
        </div>
      </div>

      <div class="row">
        <div class="col-12 table-responsive">
          <table class="table table-striped table-valign-middle">
            <thead>
              <tr>
                <th>Qty</th>
                <th>PKU</th>
                <th>Price</th>
                <th>Discount</th>
                <th>Subtotal</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for =  "order in invoice.details">
                <td>{{ order.quantity }}</td>
                <td>{{ order.product }}</td>
                <td><span class="" style="text-decoration: line-through">N</span>{{ $root.numeral(order.price) }}</td>
                <td>{{ order.discount }}</td>
                <td><span class="" style="text-decoration: line-through">N</span>{{ $root.numeral(order.amount)}}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="row">
        <div style="width: 50vw" class="offset-6 col-6 card-body ">
          <p v-if="invoice.due_date != null" class="lead text-center">{{'Amount Due ' + invoice.due_date }}</p>
          <table class="table table-valign-middle">
            <tbody>
              <tr v-if = "invoice.type == 'order'">
                <td class="font-weight-bold">Discount: </td>
                <td>{{ invoice.details.sum('discount') }}</td>
              </tr>
              <tr>
                <td class="font-weight-bold">Total: </td>
                <td>
                  <span class="" style="text-decoration: line-through">N</span>{{ $root.numeral(invoice.total) }}
                </td>
              </tr>
              <tr>
                <td class="font-weight-bold">Payment: </td>
                <td>
                  <span class="" style="text-decoration: line-through">N</span>{{ $root.numeral(invoice.payment) }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  
</template>

<script>
  export default {
    data() {
      return {
        number : 'NOT AVAILABLE',
        email : 'NOT AVAILABLE',
        invoice : {}
      }
    },
    computed : {
      date(){
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0');
        var yyyy = today.getFullYear();

        return mm + '/' + dd + '/' + yyyy;
      },
      user(){
        return this.$session.get('user')
      }
    },
    mounted(){

      this.$nextTick(() => {

        this.invoice = this.$root.invoice
        
      });
    },
    beforeRouteEnter (to, from, next) {
      next(vm => {
            vm.invoice = vm.$root.invoice
      });
  }, 
  beforeCreate(){
    if(this.$root.invoice == undefined){
          this.$router.push('/invoices')
        }
      else {
        setTimeout(function(){ window.print() }, 2000);
      }
      },
  methods : {
    
  }
  }
</script>