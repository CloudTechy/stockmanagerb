<template>
    <!-- Main content -->
    <section id='section' class="invoice">
        <!-- title row -->
        <div class=" m-1 text-right no-print"><button @click.prevent="$root.back()" class="mr-auto btn btn-primary"><i class="fas fa-chevron-left fa-fw"></i>Back</button></div>
        <div class="row">
            <div class="col-12  mt-2">
                <h2 class="page-header">
                    <small class="float-right ">Date: {{ date }}</small>
                    <i class="fa fa-globe mt-"></i> <span class="text-uppercase ">Big Star - </span>
                    <span> {{ invoice.status == 'not-paid' ? ' INVOICE ' : ' RECEIPT' }}</span>
                </h2>
            </div>
            <!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                <b class="">Payment status:  </b> <span class="text-uppercase">{{  invoice.status }}</span><br>
                <span v-if = "invoice.status != 'not-paid'"><b class="">Paid by:  </b> <span class="text-uppercase font-weight-bold">{{  invoice.payment_mode }}</span></span><br>
                <b>Invoice no: </b> <span class="small">{{ invoice.id }}</span>
                <br>
                <span v-if="invoice.status != 'not-paid'"><b>Payment date: </b> {{ invoice.transaction_updated || "N/A"}}<br></span><br>
                <span v-if="invoice.due_date"><b>Payment Due:</b> {{ invoice.due_date || "N/A"}}<br></span><br>
                <!-- 
                <b>{{ invoice.type == 'order' ? 'Order ID: ' : 'Purchase ID: ' }} </b> <span class="small">{{ invoice.type == 'order' ? invoice.order_id : invoice.purchase_id }}</span><br> -->
            </div>
            <div class="col-sm-4 invoice-col">
                <b>{{ invoice.type == 'order' ? 'FROM ' : 'TO ' }}</b>
                <address>
                    Big Star IND CO LTD<br>
                    <span class="small">Zone 15 NO 76, New Motorcycle Spare Parts Nnewi</span><br>
                    <span class="small">08039303292</span><br>
                    <b>Cashier:</b> {{ $auth.user().names || "N/A" }}<br>
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                <b>{{invoice.type == 'order' ? 'TO ' : 'FROM ' }}</b>
                <address>
                    <b>{{invoice.type == 'order' ? 'Customer: ' : 'Supplier: ' }} </b> {{ invoice.name}}<br>
                    <b>Phone number: </b>{{ invoice.number || "N/A" }}<br>
                </address>
            </div>
            <!-- /.col -->
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- Table row -->
        <div class="row">
            <div class="col-12 table-responsive">
                <table class="table table-striped table-valign-middle">
                    <thead>
                        <tr>
                            <th class="text-center">Qty</th>
                            <th class="text-center">Item(s)</th>
                            <th class="text-center">Price</th>
                            <th class="text-center">Discount</th>
                            <th class="text-center">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="order in invoice.details">
                            <td class="text-center">{{ order.quantity }}</td>
                            <td class="text-center">{{ order.product }}</td>
                            <td class="text-center"><span class="" style="text-decoration: line-through">N</span>{{ $root.numeral(order.price) }}</td>
                            <td class="text-center">{{ order.discount }}</td>
                            <td class="text-center"><span class="" style="text-decoration: line-through">N</span>{{ $root.numeral(order.amount)}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.row -->
        <div class="row">
            <div style="width: 50vw" class="card-body col-md-6 col-sm-12 offset-md-6 offset-sm-0 ">
                <p v-if="invoice.due_date != null" class="lead text-center">{{'Amount Due ' + invoice.due_date }}</p>
                <table class="table table-valign-middle">
                    <tbody>
                        <tr v-if="invoice.type == 'order'">
                            <td class="font-weight-bold">Discount: </td>
                            <td><span class="" style="text-decoration: line-through">N</span>{{ $root.numeral(invoice.details.sum('discount')) }}</td>
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
                        <tr>
                            <td class="font-weight-bold">Change: </td>
                            <td>
                                <span class="" style="text-decoration: line-through">N</span>{{ $root.numeral(invoice.balance) }}
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
            number: 'NOT AVAILABLE',
            email: 'NOT AVAILABLE',
            invoice: {},
        }
    },
    computed: {
        date() {
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0');
            var yyyy = today.getFullYear();

            return dd + '/' + mm + '/' +  yyyy;
        },
    },
    mounted() {

        this.$nextTick(() => {

            this.invoice = this.$root.invoice

        });
    },
    beforeRouteEnter(to, from, next) {
        next(vm => {
            vm.invoice = vm.$root.invoice
        });
    },
    beforeCreate() {
        if (this.$root.invoice == undefined) {
            this.$router.push('/invoices')
        } else {
            setTimeout(function() { window.print() }, 2000);
        }
    },
    methods: {

    }
}

</script>
