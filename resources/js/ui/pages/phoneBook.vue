<template>
</template>

<script>
    import {mapActions, mapMutations, mapState} from "vuex";

    export default {
        name: "phoneBook",
        methods: {
            ...mapMutations({
//                setUsername: 'phoneBook/setUsername',
//                setPassword: 'phoneBook/setPassword',
//                togglePassword: 'phoneBook/togglePassword'
            }),
            ...mapActions({
//                login: 'phoneBook/login'
            }),
        },
        computed: {
            ...mapState({
//                isLoggedIn: state => state.login.isLoggedIn,
//                pending: state => state.phoneBook.pending,
//                showPassword: state => state.phoneBook.showPassword,
//                password: state => state.phoneBook.password,
//                username: state => state.phoneBook.username

            })

        }
    }
</script>
