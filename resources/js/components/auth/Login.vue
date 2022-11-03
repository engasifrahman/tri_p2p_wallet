<template>
<div class="row login-form">
    <div class="col-sm-3">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title text-center my-2">LOGIN</h3>
                <form>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Email</label>
                        <input type="email" class="form-control" :class="{'is-invalid' : login_errors?.email?.[0]}" v-model="user.email" required>
                        <div class="invalid-feedback"> {{ login_errors?.email?.[0] }}</div>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Password</label>
                        <input type="password" class="form-control" :class="{'is-invalid' : login_errors?.password?.[0]}" v-model="user.password" required>
                        <div class="invalid-feedback"> {{ login_errors?.password?.[0] }}</div>
                    </div>
                    <div class=" text-center">
                        <button type="submit" class="btn btn-primary" @click.prevent="login">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</template>

<script>
    import { ref } from 'vue';
    import { useAxios } from '@/composables/useAxios.js';

    export default {
        name: "Login",
        async setup(){
            let req_url = ref('/login');
            let req_config = ref({
                method: 'POST',
                data: {}
            });

            const { excecuteAxios } = useAxios();

            return { req_url, req_config, excecuteAxios };
        },
        data: () => ({
            user: {
                email: 'eng.asifrahman@gmail.com',
                password: '12345678'
            },
            login_errors: {},
        }),
        created() {
            console.log('Login Page Created');

            if (this.authToken) {
                this.$router.push({name: 'dashboard'});
            }
        },
        methods: {
            async login() {
                this.$emitter.emit('loadingStatus', true);
                this.req_config.data = this.user;

                const { result, errors, is_finished } = await this.excecuteAxios(this.req_url, this.req_config);

                if(is_finished){
                    this.$emitter.emit('loadingStatus', false);

                    // console.log('login response :>> ', result);

                    if(!errors){
                        this.user.email = '';
                        this.user.password = '';

                        let authUser = Object.assign({}, result?.data?.user);
                        let authToken = result?.data?.token || '';

                        localStorage.setItem('auth_user', JSON.stringify(authUser));
                        localStorage.setItem('auth_token', authToken);

                        this.$emitter.emit('reloadAuthData', true);

                        this.$router.push({name: 'dashboard'});
                    } else if(errors.message === 'Validation failed!'){
                        // console.log('Login errors :>> ', errors);

                        this.login_errors = errors.errors;
                    }
                }
            }
        }
    };
</script>

<style scoped>
    .login-form{
        display: flex;
        justify-content: center;
        align-items: center;
        height: calc(100vh - 100px);
    }
</style>
