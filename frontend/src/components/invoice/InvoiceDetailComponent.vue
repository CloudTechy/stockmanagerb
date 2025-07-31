<template>
    <div class="container">
        <div class="card-body">
            <table class="table table-small table-striped table-valign-middle">
                <thead>
                    <tr>
                        <th>Qty</th>
                        <th>Items</th>
                        <th>Price</th>
                        <th>Discount</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="order in invoice.details">
                        <td class="small">{{ order.quantity }}</td>
                        <td class="small">{{ order.product }}</td>
                        <td class="small"><span class="" style="text-decoration: line-through">N</span>{{ $root.numeral(order.price) }}</td>
                        <td class="small">{{ order.discount }}</td>
                        <td class="small"><span class="" style="text-decoration: line-through">N</span>{{ $root.numeral(order.amount)}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="row">
            <div style="width: 50vw" class="offset-6 col-6 card-body ">
                <p v-if="invoice.due_date != null" class="lead text-center">{{'Amount Due ' + invoice.due_date }}</p>
                <table class="table table-small table-valign-middle">
                    <tbody>
                        <tr v-if="invoice.type == 'order'">
                            <td class="font-weight-bold">Discount: </td>
                            <td>{{ invoice.details.sum('discount') }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Amount: </td>
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
    </div>
</template>
<script>
export default {
    props: ['invoice'],
}
</script>
