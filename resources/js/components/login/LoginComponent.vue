<template>
    <div id="login" class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <div class="login100-pic js-tilt" data-tilt="" style="will-change: transform; transform: perspective(300px) rotateX(0deg) rotateY(0deg);">
                    <h5 class=" text-center login100-form-title">My StockManager</h5>
                    <img :src="'../img/img-01.png'" alt="IMG">
                </div>
                <form class="" role="form" name="form" id="form" ref="form" @keydown="form.onKeydown($event)" @submit.prevent='loginUser'>
                    <span class="text-success login100-form-title">
                        User Login
                    </span>
                    <div class="wrap-input100">
                        <input v-model="form.email" required="" class="input100 is-invalid" type="email" ref="email" name="email" placeholder="Email">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="text-center text-danger m-3 p-0 mt-0">
                        <span id="error" class="txt1">
                            <div class="  m-0 p-0 text-danger" v-if="error.email != undefined">{{ error.email[0] }}</div>
                            <has-error :form="form" field="email"></has-error>
                        </span>
                    </div>
                    <div class="wrap-input100">
                        <input class="input100" v-model="form.password" required="" ref="password" type="password" name="pass" placeholder="Password">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="text-center text-danger p-0 mt-0">
                        <span class="txt1">
                            <div class="m-0 p-0 text-danger" v-if="error.email != undefined">{{ error.password[0] }}</div>
                            <has-error :form="form" field="password"></has-error>
                        </span>
                    </div>
                    <div class="container-login100-form-btn">
                        <button type="submit" :disabled="form.busy" class="login100-form-btn">
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
     
    Vue.component(HasError.name, HasError)
    Vue.component(AlertError.name, AlertError)
    Vue.component(AlertErrors.name, AlertErrors)
    Vue.component(AlertSuccess.name, AlertSuccess)
    
    export default {
        mounted() {
            
        },
        data() { 
           
            return {
                form : new Form({
                    email: '',
                    password: '',
                }),
                error : '',
                path : '/orders'
            }

        },
        beforeRouteEnter (to, from, next) {
            next(vm => {
                vm.path = !from.path  ||  from.path == '/payment' ? "/orders" : from.path
                if(vm.$route.params.to || vm.$route.query.to){
                    vm.path = vm.$route.params.to || vm.$route.query.to
                }
              });
        }, 
        methods: {
            loginUser(){
                var email =  this.form.email;
                this.$Progress.start();
                this.form.post('./api/login')
                .then(response => {
                    if(response.data.status == true){
                        this.form.reset()
                        var token = response.data.data.token
                        window.axios.defaults.headers.common['Authorization'] = 'Bearer '+ token;
                        this.token = token;
                        this.$session.start()
                        this.$session.set('token',token)
                        localStorage.token = 'Bearer '+ token
                        this.$Progress.finish()
                        if(window.axios.defaults.headers.common['Authorization'] =='Bearer '+ token){
                        console.log('user logged in')
                        this.loadUser(email)
                        //Fire.$emit('user_login', email)
                        }
                        else{
                            console.log('user not logged in');
                        }
                    }
                    else{
                        this.$Progress.fail()
                        this.$root.alert('error','error','An unexpected error occured, Try again Later')
                        
                    }
                })
                .catch( error => {
                    this.$Progress.fail()
                    if(error.response){
                        this.$root.alert('error','error',error.response.data.message)
                        var error = error.response.data.error;
                        this.error = error
                        console.log(error);
                        if(error.email){
                            this.$refs.email.classList.add('is-invalid');
                        }
                        if (error.description) {
                            this.$refs.password.classList.add('is-invalid');
                        }
                    }
                    else {
                        console.log(error)
                       this.$root.alert('error','error','Server is not running');
                    }
                }); 
            },
            loadUser(email){
                this.$Progress.start()
                this.form.get('./api/users/?email='+email)
                .then(response => {
                    if(response.data.status == true){
                        var user = response.data.data.item[0];
                        if(user.activated == 0){
                            console.log("user not activated")
                        window.axios.defaults.headers.common['Authorization'] = '';
                        this.$root.alert('error','error','Account deactivated')
                        localStorage.removeItem("token")
                      }
                      else{
                        this.$session.start()
                        this.$session.set('user',user)
                        localStorage.user = JSON.stringify(user)
                        this.$root.alert('success','success','logged in')
                       //Fire.$emit('user_login_confirmed',response.data.data.item[0])

                        this.$router.push(this.path) 
                        
                      }
                    }
                    else{
                      this.$Progress.fail()
                      this.$root.alert('error','error','An unexpected error occured, Try again Later')
                    }
                })
                .catch( error => {

                    if(error.response){
                        this.$Progress.fail()
                        this.$root.alert('error','error',error.response.data.message)
                        var error = error.response.data.error;
                        console.log(error);
                    }
                    else{
                        console.log(error);
                        this.$root.alert('error','error','Server is not running');
                    }
                }); 
         },

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
