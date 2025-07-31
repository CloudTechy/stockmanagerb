<template>
    <div id="login" class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <div class="login100-pic js-tilt" data-tilt>
                    <h5 class="text-center login100-form-title">My StockManager</h5>
                    <img src="/img/img-01.png" alt="IMG" />
                </div>

                <form ref="form" @submit.prevent="loginUser">
                    <span class="text-success login100-form-title">User Login</span>

                    <div class="wrap-input100">
                        <input v-model="form.email" type="email" required class="input100" name="email"
                            placeholder="Email" :class="{ 'is-invalid': form.errors.has('email') }" />
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="text-danger text-center" v-if="form.errors.has('email')">
                        {{ form.errors.get('email') }}
                    </div>

                    <div class="wrap-input100">
                        <input v-model="form.password" type="password" required class="input100" name="password"
                            placeholder="Password" :class="{ 'is-invalid': form.errors.has('password') }" />
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="text-danger text-center" v-if="form.errors.has('password')">
                        {{ form.errors.get('password') }}
                    </div>

                    <div class="container-login100-form-btn">
                        <button type="submit" class="login100-form-btn">Login</button>
                    </div>

                    <div class="text-center p-t-12">
                        <span class="txt1"><strong>Welcome!</strong> please login to continue</span>
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
                email: '',
                password: ''
            })
        }
    },
    methods: {
        loginUser() {
            let loader = this.$loading.show({});
            let app = this
            this.$auth.login({
                data: app.form,
                success: function () {
                    const redirect = app.$auth.redirect();
                    app.$root.alert('success', 'Success', 'Login success');
                    loader.hide();
                    app.success = true;

                    if (redirect && redirect.from && redirect.from.name !== 'login') {
                        const routeName = redirect?.from?.name || 'orders'
                        this.$router.replace({ name: routeName })
                    } else {
                        app.$router.replace({ name: 'orders' }).catch(err => {
                            if (err.name !== 'NavigationDuplicated') throw err;
                        });
                    }
                }  ,
                error: function (res) {
                    loader.hide();
                    app.has_error = true
                    app.error = res.response.data.error
                    if (res.response) {
                        app.$root.alert('error', app.error, res.response.data.message)
                        if (app.error.email) {
                            app.$refs.email.classList.add('is-invalid');
                        }
                        if (app.error.description) {
                            app.$refs.password.classList.add('is-invalid');
                        }
                    }
                    else {
                        app.$root.alert('error', '', res)
                    }
                },
                rememberMe: true,
                fetchUser: true
            })
        }
    }
}
</script>

<style scoped>
@import 'css/bootstrap.min.css';
@import 'css/animate.css';
@import 'css/hamburgers.min.css';
@import 'css/select2.min.css';
@import 'css/util.css';
@import 'css/main.css';

.is-invalid {
    border-color: #dc3545;
}

.text-danger {
    font-size: 0.875rem;
    margin-top: 0.25rem;
}
</style>