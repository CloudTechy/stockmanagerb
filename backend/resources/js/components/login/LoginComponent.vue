<template>
    <div id="login" class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <div class="login100-pic js-tilt" data-tilt="" style="will-change: transform; transform: perspective(300px) rotateX(0deg) rotateY(0deg);">
                    <h5 class=" text-center login100-form-title">My StockManager</h5>
                    <img :src="'../img/img-01.png'" alt="IMG">
                </div>
                <form class="" role="form" name="form" id="form" ref="form" @submit.prevent='loginUser'>
                    <span class="text-success login100-form-title">
                        User Login
                    </span>
                    <div class="wrap-input100">
                        <input v-model="email" required="" class="input100 is-invalid" type="email" ref="email" name="email" placeholder="Email">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="text-center text-danger m-3 p-0 mt-0">
                        <span id="error" class="txt1">
                            <div class="  m-0 p-0 text-danger" v-if="error.email != undefined">{{ error.email[0] }}</div>
                        </span>
                    </div>
                    <div class="wrap-input100">
                        <input class="input100" v-model="password" required="" ref="password" type="password" name="pass" placeholder="Password">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="text-center text-danger p-0 mt-0">
                        <span class="txt1">
                            <div class="m-0 p-0 text-danger" v-if="error.email != undefined">{{ error.password[0] }}</div>
                        </span>
                    </div>
                    <div class="container-login100-form-btn">
                        <button type="submit" :disabled="false" class="login100-form-btn">
                            Login
                        </button>
                    </div>
                    <div class="text-center p-t-12">
                        <span class="txt1">
                            <strong>Welcome!</strong> please login to continue
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
    import { 
      HasError,
      AlertError,
      AlertErrors, 
      AlertSuccess
    } from 'vform'
    import Vue from 'vue'
    Vue.component(HasError.name, HasError)
    Vue.component(AlertError.name, AlertError)
    Vue.component(AlertErrors.name, AlertErrors)
    Vue.component(AlertSuccess.name, AlertSuccess)
    
    export default {
        mounted() {
            
        },
        data() { 
           
            return {
                form: new Form(),
                email: null,
                password: null,
                success: false,
                has_error: false,
                error: '',
                name : 'dashboard',
            }

        },
        beforeRouteEnter (to, from, next) {
            next(vm => {
                // vm.path = !from.path  ||  from.path == '/payment' ? "/orders" : from.path
                // vm.name = from.path
                // if(vm.$route.params.to || vm.$route.query.to){
                //     vm.path = vm.$route.params.to || vm.$route.query.to
                //     vm.name = from.path
                // }
                // console.log(vm.name, vm.path)
              });
        }, 
        methods: {
            loginUser(){
                let loader = this.$loading.show({});
                // get the redirect object
                var redirect = this.$auth.redirect()
                var app = this
                this.$auth.login({
                  data: {
                    email: app.email,
                    password: app.password
                  },
                  success: function() {
                    // handle redirection
                    if (redirect) {
                        app.name = redirect.from.name
                    } 
                    app.$root.alert('success', 'Success',' Login success')
                    loader.hide();
                    app.success = true
                    this.$router.push({name: app.name})
                  },
                  error: function(res) {
                    loader.hide();
                    app.has_error = true
                    app.error = res.response.data.error
                    if(res.response){
                        app.$root.alert('error',app.error,res.response.data.message)
                        if(app.error.email){
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
<style type="text/css" scoped="true">
#error>.help-block .invalid-feedback {
    display: block
}

@import 'css/main.css';
@import 'css/animate.css';
@import 'css/hamburgers.min.css';
@import 'css/select2.min.css';
@import 'css/util.css';
@import 'css/bootstrap.min.css';

</style>
