<template>
    <div>
        <nav-component></nav-component>
        <sidebar-component></sidebar-component>

        <div class="content-wrapper" style="min-height: 606px;">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <h1 class="m-0 text-dark">Customer Invoice Order</h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="container-fluid">
                    <div class="card card-success card-outline m-auto ">
                        <div class="no-print">
                            <div class="card-header  mb-2">
                                <h5 class="card-title font-weight-bold text-secondary">Available Inventory <i
                                        class="fa fa-shopping-cart "></i></h5>
                            </div>
                            <show-orders-component></show-orders-component>
                        </div>
                        <hr>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>
<script>
import NavComponent from '@/components/NavComponent.vue';
import SidebarComponent from '@/components/SidebarComponent.vue';
import ShowOrdersComponent from '@/components/order/ShowOrdersComponent.vue';

export default {
    components: {
        NavComponent,
        SidebarComponent,
        ShowOrdersComponent
    },
    mounted() {

    },
    data() {
        var d = new Date();
        return {
            month: d.getMonth() + 1,
            year: d.getFullYear(),
            products: [],
            orderStat: {},
            pending: [],
            not_paid: [],
            paid: [],
            order_type: [],
            purchase_type: [],
            loading: true,
            error: '',
            search: '',
            form: new Form(),
            owed: '',
            title: 'RECENTLY ADDED',
        }
    },
    beforeDestroy() {
        window.dispatchEvent(new Event('close_sidebar_min'))
    },
    created() {
        // Fire.$on('order_created', (data)=> {
        //     this.loadorders();

        // })
        // Fire.$on('order_deleted', (data)=> {
        //     this.loadorders();
        // })
        // Fire.$on('order_edited', (data)=> {
        //     this.loadorders();
        // })
        // Fire.$on('view', (data)=> {
        //     this.search = data.id;
        //     this.title = "order DETAILS"
        //     this.$refs.search.focus()
        // })
        // //this.loadorders();
        // //this.loadorderStat();
    },
    methods: {
        loadorders() {
            this.form.get('./attributeproducts')
                .then(response => {
                    if (response.data.status == true) {
                        this.loading = false;
                        this.orders = response.data.data.item;
                    }
                    else {
                        console.log("load supplier did not return positive response");
                    }

                })
                .catch(error => {
                    this.error = error.response.data.error;
                    console.log(error);
                });
        },
        loadorderStat() {
            this.form.get('./statistics/orders?count')
                .then(response => {
                    if (response.data.status == true) {
                        this.loading = false;
                        this.orderStat = response.data.data.item[0];
                    }
                    else {
                        console.log(response.data);
                    }
                })
                .catch(error => {
                    this.error = error.response.data.error;
                    console.log(error);
                });
        },

        collapse(id) {
            $('#' + id).toggle('collapse')
        },
    }

}

</script>
<style type="text/css"></style>
