<template>

    <div class="row signup-form">
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title text-center my-2">SIGNUP</h3>
                    <form>
                        <div class="mb-3">
                            <label for="" class="form-label">Name</label>
                            <input type="text" class="form-control" :class="{'is-invalid' : signup_errors?.name?.[0]}" v-model="user.name" required>
                            <div class="invalid-feedback"> {{ signup_errors?.name?.[0] }}</div>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Email</label>
                            <input type="email" class="form-control" :class="{'is-invalid' : signup_errors?.email?.[0]}" v-model="user.email" required>
                            <div class="invalid-feedback"> {{ signup_errors?.email?.[0] }}</div>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Phone</label>
                            <input type="email" class="form-control" :class="{'is-invalid' : signup_errors?.phone?.[0]}" v-model="user.phone" required>
                            <div class="invalid-feedback"> {{ signup_errors?.phone?.[0] }}</div>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Currency</label>
                            <select class="form-select" :class="{'is-invalid' : signup_errors?.currency?.[0]}" v-model="user.currency">
                                <option disabled selected value="">Choose Currency</option>
                                <option>USD</option>
                                <option>EUR</option>
                            </select>
                            <div class="invalid-feedback"> {{ signup_errors?.currency?.[0] }}</div>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Password</label>
                            <input type="password" class="form-control" :class="{'is-invalid' : signup_errors?.password?.[0]}" v-model="user.password" required>
                            <div class="invalid-feedback"> {{ signup_errors?.password?.[0] }}</div>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" :class="{'is-invalid' : signup_errors?.password_confirmation?.[0]}" v-model="user.password_confirmation" required>
                            <div class="invalid-feedback"> {{ signup_errors?.password_confirmation?.[0] }}</div>
                        </div>
                        <div class="text-center">
                            <button type="reset" class="btn btn-warning me-3">Reset</button>
                            <button type="submit" class="btn btn-primary ms-3" @click.prevent="signup">Signup</button>
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
        name: 'Signup',
        async setup(){
            let req_url = ref('/registration');
            let req_config = ref({
                method: 'POST',
                data: {}
            });

            const { excecuteAxios } = useAxios();

            return { req_url, req_config, excecuteAxios };
        },
        data: () => ({
            user: {
                name: '',
                email: '',
                phone: '',
                currency: '',
                password: '',
                password_confirmation: ''
            },
            signup_errors: {},
        }),
        created() {
            console.log('Signup Page Created');

            if (this.authToken) {
                this.$router.push({name: 'dashboard'});
            }
        },
        methods: {
            async signup() {
                this.$emitter.emit('loadingStatus', true);
                this.req_config.data = this.user;

                const { result, errors, is_finished } = await this.excecuteAxios(this.req_url, this.req_config);

                if(is_finished){
                    this.$emitter.emit('loadingStatus', false);

                    // console.log('Signup response :>> ', result);

                    if(!errors){
                        this.$router.push({name: 'login'});
                    } else if(errors.message === 'Validation failed!'){

                        this.signup_errors = errors.errors;
                    }
                }
            }
        }
    };
</script>

<style scoped>
    .signup-form{
        display: flex;
        justify-content: center;
        align-items: center;
        height: calc(100vh - 100px);
    }
</style>
