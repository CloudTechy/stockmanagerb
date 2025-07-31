<template>
    <div class="card card-success card-outline">
        <div class="card-header">
            <h3 class="card-title">Recent Purchases</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
        </div>
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper  container-fluid dt-bootstrap4">
                <div class="row">
                    <div class="col-6 col-md-6">
                        <div class="dataTables_length" id="example1_length">
                            <label>Entries:
                                <select v-model="rowsPerPage" @change="loadPurchaseDetails" aria-controls="dataTables-example" class="form-control input-sm">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </label>
                        </div>
                    </div>
                    <div class="col-6 col-md-6">
                        <div id="example1_filter" class="dataTables_filter float-right">
                            <label>Search:<input v-model="search" type="Ex: 2020-02-20" class="form-control form-control-sm" placeholder="search" aria-controls="example1">
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive-sm table-responsive ">
                            <table class="table table-bordered table-small table-hover table-striped dataTable">
                                <thead class="text-center ">
                                    <tr role="row">
                                        <th>Supplier</th>
                                        <th>Item(s)</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Operator</th>
                                    </tr>
                                </thead>
                                <tbody id="body">
                                    <tr v-for="purchasedetail,index in pageLoader(current_page)">
                                        <td class="text-capitalize">{{ purchasedetail.supplier_name }}</td>
                                        <td class="text-capitalize">{{ purchasedetail.Total_quantity }}</td>
                                        <td class="text-capitalize"><span style="text-decoration: line-through">N</span>{{ $root.numeral( purchasedetail.Total_amount) }}</td>
                                        <!--  <td class="text-center">
                                            <span v-bind:class="{badge:true, 'badge-danger':purchasedetail.status == 'not-paid',  'badge-success' : purchasedetail.status == 'paid', 'badge-warning':purchasedetail.status == 'pending' }">
                                                {{ purchasedetail.status }}
                                            </span>
                                        </td> -->
                                        <td class="text-capitalize small">{{ purchasedetail.date }}</td>
                                        <td class="text-capitalize">{{ purchasedetail.user }}</td>
                                    </tr>
                                    <tr v-if="pageLoader(current_page).length > 0">
                                        <td colspan="1">
                                            <span class="small font-weight-bold text-success"> {{ "Summary for " + pageLoader(current_page).length + " product(s)" }}</span>
                                        </td>
                                        <td colspan="1">
                                            <span class="font-weight-bold badge badge-success">
                                                {{ pageLoader(current_page).sum('Total_quantity') }}
                                            </span>
                                        </td>
                                        <td colspan="3">
                                            <span class="small font-weight-bold text-success">
                                                <span style="text-decoration: line-through">N</span>{{ $root.numeral(pageLoader(current_page).sum('Total_amount')) }}</span>
                                        </td>
                                    </tr>
                                    <tr v-if="loading == false && pageLoader(current_page).length == 0">
                                        <td colspan="9">
                                            <h4 class="text-center m-1 p-2 border border-info small text-success">No recent added products</h4>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 col-md-6">
                        <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">Showed {{ start + 1 }} to {{ end > length ? length : end }} of {{ length }} entries
                        </div>
                    </div>
                    <div class="col-6 col-md-6">
                        <div class="dataTables_paginate paging_simple_numbers float-right" id="example1_paginate">
                            <ul class="pagination">
                                <li class="paginate_button page-item previous" ref="prev" id="example1_previous">
                                    <a href="#" @click.prevent="pageLoaderB(-1)" aria-controls="example1" data-dt-idx="0" tabindex="0" class="page-link">Previous
                                    </a>
                                </li>
                                <li class="paginate_button bg-primary p-2 text-center page-item" style="display: inline; min-width: 70px" v-if="Math.floor(pages) > 6">
                                    {{current_page + ' of ' + Math.floor(pages)}}
                                </li>
                                <li v-else v-for="value in Math.floor(pages)" v-bind:class="classObject(value)" class="paginate_button page-item">
                                    <a aria-controls="example1" data-dt-idx="1" tabindex="0" class="page-link" @click.prevent="pageLoader(value)" href="#">{{ value }}</a>
                                </li>
                                <li class="paginate_button page-item next" ref="next" id="example1_next">
                                    <a @click.prevent="pageLoaderB(1)" href="#" aria-controls="example1" data-dt-idx="7" tabindex="0" class="page-link">Next
                                    </a>
                                </li>
                            </ul>
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
        if (localStorage.purchasedetails) {
            this.purchasedetails = JSON.parse(localStorage.purchasedetails)
            this.loading = false;
        }
    },
    data() {
        var d = new Date();
        return {
            month: d.getMonth() + 1,
            year: d.getFullYear(),
            purchasedetails: [],
            loading: true,
            error: '',
            search: '',
            rowsPerPage: 5,
            current_page: 1,
            length: 0,
            pages: 0,
            form: new Form()
        }

    },
    created() {
        this.loadPurchaseDetails();
        // Echo.channel('purchase')
        //     .listen('UpdatePurchase', (e) => {
        //         this.loadPurchaseDetails();
        //     });
    },

    computed: {

        start() {
            if (this.pages > 0 && this.current_page >= this.pages) {
                this.current_page = this.pages
            }
            return parseInt(this.rowsPerPage * (this.current_page - 1));
        },
        end() {
            return parseInt(this.rowsPerPage * (this.current_page - 1)) + parseInt(this.rowsPerPage);
        }
    },
    methods: {

        loadPurchaseDetails() {
            this.form.get('/purchases')
                .then(response => {
                    if (response.data.status == true) {
                        this.purchasedetails = response.data.data.item.length != 0 ? response.data.data.item : [];
                        localStorage.purchasedetails = JSON.stringify(this.purchasedetails)
                    } else {
                        console.log(response.data.data);
                    }
                })
                .catch(error => {
                    console.log(error.response.data.data.error)
                });
        },
        classObject(value) {
            if (value <= 1 && this.current_page == 1) {
                this.$refs.prev.classList.add('disabled')
            } else if (value <= 1 && this.current_page > 1) {
                this.$refs.prev.classList.remove('disabled')
            }
            if (this.current_page == this.pages) {
                this.$refs.next.classList.add('disabled')
            } else {
                this.$refs.next.classList.remove('disabled')
            }
            if (value == this.current_page) {
                return "active";
            }
        },
        pageLoader(pageNumber) {
            if (this.pages > 6) {
                this.$refs.prev.classList.remove('disabled')
                this.$refs.next.classList.remove('disabled')
            }
            this.current_page = pageNumber;
            this.loading = false;
            var data = this.$root.created_atFilter(this.purchasedetails, this.search)
            this.length = data.length;
            this.pages = Math.ceil(data.length / this.rowsPerPage);
            return data.slice(this.start, this.end);
        },
        pageLoaderB(amount) {
            if (this.current_page <= 1 && amount == -1) {
                this.current_page = 1;
            } else if (this.current_page >= this.page) {
                this.current_page = this.page;
            } else {
                this.current_page += amount;
            }
        },
        
    }
}

</script>
