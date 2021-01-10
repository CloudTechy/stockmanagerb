<template>
    <div id="login" class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <div class="login100-pic js-tilt" data-tilt="" style="will-change: transform; transform: perspective(300px) rotateX(0deg) rotateY(0deg);">
                    <h5 class=" text-center login100-form-title">My StockManager</h5>
                    <img :src="'../img/img-01.png'" alt="IMG">
                </div>
                <form class="" role="form" name="form" id="form" ref="form" @submit.prevent='login'>
                    <span class="text-success login100-form-title">
                        User Login
                    </span>
                    <div class="wrap-input100">
                        <input v-model="username" @input="setUsername" required="" class="input100 is-invalid" type="email" ref="email" name="email" placeholder="Email">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="text-center text-danger m-3 p-0 mt-0">
                        <!-- <span id="error" class="txt1">
                            <div class="  m-0 p-0 text-danger" v-if="error.email != undefined">{{ error.email[0] }}</div>
                            <has-error :form="form" field="email"></has-error>
                        </span> -->
                    </div>
                    <div class="wrap-input100">
                        <input class="input100" v-model="password" @input="setPassword" required="" ref="password" type="password" name="pass" placeholder="Password">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="text-center text-danger p-0 mt-0">
                        <!-- <span class="txt1">
                            <div class="m-0 p-0 text-danger" v-if="error.email != undefined">{{ error.password[0] }}</div>
                            <has-error :form="form" field="password"></has-error>
                        </span> -->
                    </div>
                    <div class="container-login100-form-btn">
                        <button type="submit" class="login100-form-btn">
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
    import {mapActions, mapMutations, mapState} from "vuex";
    import router from '../../router';
    export default {
        name: "login",
        methods: {
            ...mapMutations({
                setUsername: 'login/setUsername',
                setPassword: 'login/setPassword',
                togglePassword: 'login/togglePassword',
                loginMode: 'login/loginMode',
                signUpMode: 'login/signUpMode',
                toggleMode: 'login/toggleMode'
            }),
            ...mapActions({
                loginOrSignUp: 'login/loginOrSignUp'
            }),
        },
        computed: {
            ...mapState({
                isLoggedIn: state => state.login.isLoggedIn,
                pending: state => state.login.pending,
                showPassword: state => state.login.showPassword,
                password: state => state.login.password,
                username: state => state.login.username,
                mode: state => state.login.mode
            })
        }
    }
</script>
<style scoped>
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