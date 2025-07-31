<template>

    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h3 class="modal-title display-4 font-weight-bold small ">Edit Supplier Account</h3>
                <button type="button" @click="closeAddComponent" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="card card-primary card-outline">
                <form role="form" ref="form" @keydown="form.onKeydown($event)" @submit.prevent='add'>
                    <div class="card-body">
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-4">
                                        <fieldset class="border border-warning p-2">
                                            <legend class="w-auto small font-weight-bold border bg-warning">Quick Fill
                                            </legend>
                                            <div class="form-group">
                                                <label for="name">Full Names</label>
                                                <input type="text" v-model="form.name" class="form-control" ref="name"
                                                    placeholder="Enter name">
                                                <has-error :form="form" field="name"></has-error>
                                            </div>
                                            <div class="form-group">
                                                <label for="number">Number</label>
                                                <input type=text v-model="form.number" class="form-control" ref="number"
                                                    placeholder="Enter number">
                                                <has-error :form="form" field="number"></has-error>
                                            </div>
                                            <div class="form-group">
                                                <label for="email">Email </label>
                                                <input type="email" v-model="form.email" class="form-control"
                                                    ref="email" placeholder="Enter email">
                                                <has-error :form="form" field="email"></has-error>
                                            </div>

                                        </fieldset>

                                    </div>
                                    <div class="col-md-4 ml-auto">
                                        <fieldset class="border border-warning p-2">
                                            <legend class="w-auto small font-weight-bold border bg-warning">Optional
                                            </legend>
                                            <div class="form-group">
                                                <label for="country">country </label>
                                                <input type="country" v-model="form.country" class="form-control"
                                                    ref="country" placeholder="Enter country">
                                                <has-error :form="form" field="country"></has-error>
                                            </div>
                                            <div class="form-group">
                                                <label for="city"> city </label>
                                                <input type="text" max="150" v-model="form.city" class="form-control"
                                                    ref="city" placeholder="Enter city">
                                                <has-error :form="form" field="city"></has-error>
                                            </div>
                                            <div class="form-check">

                                                <input v-model="form.is_stock_available" ref="is_stock_available"
                                                    class="form-check-input" type="checkbox" id="is_stock_available">
                                                <label for="is_stock_available">Is stock available </label>
                                                <has-error :form="form" field="is_stock_available"></has-error>
                                            </div>
                                            <div class="form-group">
                                                <label for="address">Address </label>
                                                <input type="text" max="150" v-model="form.address" class="form-control"
                                                    ref="address" placeholder="Enter address">
                                                <has-error :form="form" field="address"></has-error>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="col-md-4 ml-auto">
                                        <fieldset class="border border-warning p-2">
                                            <legend class="w-auto small font-weight-bold border bg-warning">Bank Details
                                            </legend>
                                            <div class="form-group">
                                                <label for="bank_name">bank_name </label>
                                                <select v-model="form.bank_name" ref="bank_name"
                                                    class="custom-select mr-sm-2" id="inlineFormCustomSelect">
                                                    <option selected>Choose...</option>
                                                    <option v-for="bank in banks" v-bind:value="bank.name">
                                                        {{ bank.name }} </option>
                                                </select>
                                                <has-error :form="form" field="bank_name"></has-error>
                                            </div>
                                            <div class="form-group">
                                                <label for="acc_name">Account Name </label>
                                                <input type="text" max="150" v-model="form.acc_name"
                                                    class="form-control" ref="acc_name"
                                                    placeholder="Enter account name">
                                                <has-error :form="form" field="acc_name"></has-error>
                                            </div>
                                            <div class="form-group">
                                                <label for="acc_number">Account Number </label>
                                                <input type="text" max="150" v-model="form.acc_number"
                                                    class="form-control" ref="acc_number"
                                                    placeholder="Enter account number">
                                                <has-error :form="form" field="acc_number"></has-error>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border bordr border-top-0 border-primary">
                        <button @click="closeAddComponent" type="button" ref="closeButton" class="btn btn-danger"
                            data-dismiss="modal">Close</button>
                        <button type="submit" :disabled="form.busy" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>

export default {
    mounted() {
        this.loadBanks();
    },
    data() {
        return {
            form: new Form({
                name: "",
                address: "",
                city: "",
                number: "",
                email: "",
                country: "",
                is_stock_available: true,
                bank_name: "",
                acc_number: "",
                acc_name: "",
            }),
            banks: '',
            supplier: '',
        }
    },
    props: {

        supplier_prop: {
            type: Object,
            default: () => ({})
        }
    },

    created() {
        Fire.$on('edit_supplier', (data) => {
            this.form.fill(data);
            this.supplier = data
            this.form.number = data.phone
            this.form.bank_name = data.bank_details[0].bank != undefined ? '' : data.bank_details[0].bank
            this.form.acc_name = data.bank_details[0].account_name != undefined ? '' : data.bank_details[0].account_name
            this.form.acc_number = data.bank_details[0].account_number != undefined ? '' : data.bank_details[0].account_number
        })
        this.supplier = this.$props.supplier_prop
        this.form.fill(this.supplier)
        this.form.number = this.supplier.phone
        this.form.bank_name = this.supplier.bank_details[0] != undefined ? '' : this.supplier.bank_details[0].bank
        this.form.acc_name = this.supplier.bank_details[0] != undefined ? '' : this.supplier.bank_details[0].account_name
        this.form.acc_number = this.supplier.bank_details[0] != undefined ? '' : this.supplier.bank_details[0].account_number

    },
    beforeDestroy() {
        this.$refs.closeButton.click();
        this.form.reset();
    },
    methods: {
        add() {
            this.$Progress.start();
            console.log(this.form.city);
            this.form.patch('/suppliers/' + this.supplier.id)
                .then(response => {
                    this.$refs.closeButton.click()
                    window.dispatchEvent(new Event('close_sidebar_min'));
                    if (response.data.status == true) {
                        Fire.$emit('supplier_edited', response.data.data)
                        this.form.reset()
                        this.$Progress.finish()
                        this.$root.alert('success', 'success', 'supplier edited')
                    }
                    else {
                        this.$Progress.fail()
                        this.$root.alert('error', 'error', 'An unexpected error occured, Try again Later')
                    }
                })
                .catch(error => {
                    this.$Progress.fail()
                    this.$root.alert('error', 'error', error.response.data.message)
                    var error = error.response.data.error;
                    console.log(error);
                    if (error.name) {
                        this.$refs.name.classList.add('is-invalid');
                    }
                    if (error.email) {
                        this.$refs.email.classList.add('is-invalid');
                    }
                    if (error.number) {
                        this.$refs.number.classList.add('is-invalid');
                    }
                    if (error.city) {
                        this.$refs.city.classList.add('is-invalid');
                    }
                    if (error.country) {
                        this.$refs.country.classList.add('is-invalid');
                    }
                    if (error.acc_name) {
                        this.$refs.acc_name.classList.add('is-invalid');
                    }
                    if (error.acc_number) {
                        this.$refs.acc_number.classList.add('is-invalid');
                    }
                    if (error.bank_name) {
                        this.$refs.bank_name.classList.add('is-invalid');
                    }
                    if (error.is_stock_available) {
                        this.$refs.is_stock_available.classList.add('is-invalid');
                    }
                    if (error.address) {
                        this.$refs.address.classList.add('is-invalid');
                    }
                });
        },
        closeAddComponent() {
            window.dispatchEvent(new Event('close_sidebar_min'));
            return true;
        },
        loadBanks() {
            this.form.get('/banks/')
                .then(response => {
                    if (response.data.status == true) {
                        this.banks = response.data.data.item
                    }
                })
                .catch(error => {
                    var error = error.response.message;
                    console.log(error);
                })

        }
    }
}

</script>