<template>
    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-8 m-auto">
                <card :title="$t('register_creative')">
                    <form @submit.prevent="register" @keydown="form.onKeydown($event)">

                        <!-- First Name -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">{{ $t('first_name') }}</label>
                            <div class="col-md-7">
                                <input v-model="form.first_name" type="text" name="first_name" class="form-control"
                                       :class="{ 'is-invalid': form.errors.has('first_name') }">
                                <has-error :form="form" field="first_name"/>
                            </div>
                        </div>

                        <!-- Last Name -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">{{ $t('last_name') }}</label>
                            <div class="col-md-7">
                                <input v-model="form.last_name" type="text" name="last_name" class="form-control"
                                       :class="{ 'is-invalid': form.errors.has('last_name') }">
                                <has-error :form="form" field="last_name"/>
                            </div>
                        </div>

                        <!-- Company -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">{{ $t('company') }}</label>
                            <div class="col-md-7">
                                <input v-model="form.company" type="text" name="company" class="form-control"
                                       :class="{ 'is-invalid': form.errors.has('company') }">
                                <has-error :form="form" field="company"/>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">{{ $t('email') }}</label>
                            <div class="col-md-7">
                                <input v-model="form.email" type="email" name="email" class="form-control"
                                       :class="{ 'is-invalid': form.errors.has('email') }">
                                <has-error :form="form" field="email"/>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">{{ $t('password') }}</label>
                            <div class="col-md-7">
                                <input v-model="form.password" type="password" name="password" class="form-control"
                                       :class="{ 'is-invalid': form.errors.has('password') }">
                                <has-error :form="form" field="password"/>
                            </div>
                        </div>

                        <!-- Password Confirmation -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">{{ $t('confirm_password') }}</label>
                            <div class="col-md-7">
                                <input v-model="form.password_confirmation" type="password" name="password_confirmation"
                                       class="form-control"
                                       :class="{ 'is-invalid': form.errors.has('password_confirmation') }">
                                <has-error :form="form" field="password_confirmation"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-7 offset-md-3 d-flex">
                                <!-- Submit Button -->
                                <v-button :loading="form.busy">
                                    {{ $t('register') }}
                                </v-button>
                            </div>
                        </div>

                    </form>
                </card>

                <modal-email :show="showModal" @close="showModal = false"></modal-email>
                <modal-error :show="showError" @close="showError = false"></modal-error>

            </div>
        </div>
    </div>
</template>

<script>
    import Form from 'vform';
    import ModalEmail from '../../components/Modal/ModalEmail';
    import ModalError from '../../components/Modal/ModalError';

    export default {
        middleware: 'guest',
        name: 'registerCreative',
        components: {
            ModalEmail,
            ModalError,
        },
        metaInfo() {
            return {title: this.$t('register')}
        },
        data: () => ({
            form: new Form({
                first_name: '',
                last_name: '',
                company: '',
                email: '',
                password: '',
                password_confirmation: '',
                invite_token: ''
            }),
            showModal: false,
            showError: false,
        }),
        created() {
            let invite_token = this.getUrlParams('invite_token');

            if (invite_token) {
                this.form.invite_token = invite_token;
            }
        },
        methods: {
            async register() {
                // Register the user.
                const {data} = await this.form.post('/api/register/creative');

                if (data.error) {
                    this.showError = true;
                } else {
                    this.showModal = true;
                }

                // // Register the user.
                // const {data} = await this.form.post('/api/register/creative');
                //
                // // Log in the user.
                // const {data: {token}} = await this.form.post('/api/login');
                //
                // Save the token.
                // this.$store.dispatch('auth/saveToken', {token});

                // Update the user.
                // await this.$store.dispatch('auth/updateUser', {user: data});
                //
                // // Redirect home.
                // this.$router.push({name: 'dashboard'})
            },

            getUrlParams(prop) {
                let params = {};
                let search = decodeURIComponent(window.location.href.slice(window.location.href.indexOf('?') + 1));
                let definitions = search.split('&');

                definitions.forEach(function (val, key) {
                    let parts = val.split('=', 2);
                    params[parts[0]] = parts[1];
                });

                return (prop && prop in params) ? params[prop] : false;
            },
        }
    }
</script>

<style scoped>

</style>