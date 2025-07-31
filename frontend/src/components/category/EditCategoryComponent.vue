<template>

    <div class="modal-dialog  modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Category</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">

                <form role="form" ref="form" @keydown="form.onKeydown($event)" @submit.prevent='editCategoryAxios'>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="category_name" class="text-capitalize">Category Name</label>
                            <input type="text" v-model="form.name" required="" class="form-control" ref="category_name"
                                placeholder="Enter category name">
                            <p v-if="errors.name" v-for="error in errors.name" class="text-danger m-0 p-2">{{ error }}</p>
                        </div>
                        <div class="form-group">
                            <label for="category_description" class="text-capitalize">Category Description</label>
                            <input v-model="form.description" type="text" class="form-control"
                                ref="category_description" placeholder="Enter category description">
                            <p v-if="errors.description" v-for="error in errors.description"
                                class="text-danger m-0 p-2">{{ error }}</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" ref="closeButton" class="btn btn-danger"
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
    data() {
        return {
            form: new Form({
                name: '',
                description: 'No Description Yet',
            }),
            category: '',
            errors: {}
        }
    },
    created() {
        Fire.$on('edit_category', (data) => { this.form.fill(data); this.category = data })
    },
    beforeDestroy() {
        this.$refs.closeButton.click()
        this.form.reset()
    },
    methods: {
        editCategoryAxios() {
            this.$Progress.start();
            this.form.patch('/categories/' + this.category.name)
                .then(response => {
                    this.$refs.closeButton.click()

                    if (response.data.status == true) {
                        Fire.$emit('category_edited')
                        this.form.reset()
                        this.$refs.category_name.classList.remove('is-invalid')
                        this.$refs.category_description.classList.remove('is-invalid');
                        this.$Progress.finish()
                        this.$root.alert('success', 'success', 'category edited')
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
                    this.errors = error
                    console.log(error);
                    if (error.name) {
                        this.$refs.category_name.classList.add('is-invalid');
                    }
                    if (error.description) {
                        this.$refs.category_description.classList.add('is-invalid');
                    }
                });
        },

    }

}

</script>