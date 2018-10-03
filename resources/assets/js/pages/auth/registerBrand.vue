<template>
    <div class="container mt-4">
        <div class="row brand-registration">
            <div class="col-lg-12 m-auto">
                <form @submit.prevent="register" @keydown="form.onKeydown($event)">
                    <card :title="$t('brand_details')">
                        <!-- Name -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">{{ $t('brand_name') }}</label>
                            <div class="col-md-7">
                                <input v-model="form.brand_name" type="text" name="brand_name" class="form-control"
                                       :class="{ 'is-invalid': form.errors.has('brand_name') }">
                                <has-error :form="form" field="brand_name"/>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">{{ $t('email') }}</label>
                            <div class="col-md-7">
                                <input v-model="form.email" type="email" name="email" class="form-control"
                                       autocomplete="email"
                                       :class="{ 'is-invalid': form.errors.has('email') }">
                                <has-error :form="form" field="email"/>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">{{ $t('password') }}</label>
                            <div class="col-md-7">
                                <input v-model="form.password" type="password" name="password" class="form-control"
                                       autocomplete="current-password"
                                       :class="{ 'is-invalid': form.errors.has('password') }">
                                <has-error :form="form" field="password"/>
                            </div>
                        </div>

                    </card>

                    <card :title="$t('billing_address')">

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">{{ $t('company_name') }}</label>
                            <div class="col-md-7">
                                <input v-model="form.company_name" type="text" name="company_name" class="form-control"
                                       autocomplete="organization"
                                       :class="{ 'is-invalid': form.errors.has('company_name') }">
                                <has-error :form="form" field="company_name"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">{{ $t('address_line') }} 1</label>
                            <div class="col-md-7">
                                <input v-model="form.address_1" type="text" name="address_1" class="form-control"
                                       autocomplete="address-level1"
                                       :class="{ 'is-invalid': form.errors.has('address_1') }">
                                <has-error :form="form" field="address_1"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">{{ $t('address_line') }} 2</label>
                            <div class="col-md-7">
                                <input v-model="form.address_2" type="text" name="address_2" class="form-control"
                                       autocomplete="address-level2"
                                       :class="{ 'is-invalid': form.errors.has('address_2') }">
                                <has-error :form="form" field="address_2"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">{{ $t('city') }}</label>
                            <div class="col-md-7">
                                <input v-model="form.city" type="text" name="city" class="form-control"
                                       :class="{ 'is-invalid': form.errors.has('city') }">
                                <has-error :form="form" field="city"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">{{ $t('zip_code') }}</label>
                            <div class="col-md-7">
                                <input v-model="form.zip" type="text" name="zip" class="form-control"
                                       autocomplete="postal-code"
                                       :class="{ 'is-invalid': form.errors.has('zip') }">
                                <has-error :form="form" field="zip"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">{{ $t('country') }}</label>
                            <div class="col-md-7">
                                <select class="form-control" :class="{ 'is-invalid': form.errors.has('country_id') }"
                                        v-model="form.country_id" name="country_id">
                                    <option v-for="country in countries" :value="country.id">{{ country.name }}</option>
                                </select>
                                <has-error :form="form" field="country_id"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">{{ $t('european_uid_number') }}</label>
                            <div class="col-md-7">
                                <input v-model="form.eur_uid" type="text" name="eur_uid" class="form-control"
                                       :class="{ 'is-invalid': form.errors.has('eur_uid') }">
                                <has-error :form="form" field="eur_uid"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">{{ $t('homepage') }}</label>
                            <div class="col-md-7">
                                <input v-model="form.homepage" type="text" name="homepage" class="form-control"
                                       :class="{ 'is-invalid': form.errors.has('homepage') }">
                                <has-error :form="form" field="homepage"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">{{ $t('phone') }}</label>
                            <div class="col-md-7">
                                <input v-model="form.phone" type="text" name="phone" class="form-control"
                                       autocomplete="tel-national"
                                       :class="{ 'is-invalid': form.errors.has('phone') }">
                                <has-error :form="form" field="phone"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-7">
                                <h5>{{ $t('contact_person') }}</h5>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">{{ $t('first_name') }}</label>
                            <div class="col-md-7">
                                <input v-model="form.contact_first_name" type="text" name="contact_first_name"
                                       class="form-control"
                                       autocomplete="given-name"
                                       :class="{ 'is-invalid': form.errors.has('contact_first_name') }">
                                <has-error :form="form" field="contact_first_name"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">{{ $t('last_name') }}</label>
                            <div class="col-md-7">
                                <input v-model="form.contact_last_name" type="text" name="contact_last_name"
                                       class="form-control"
                                       autocomplete="family-name"
                                       :class="{ 'is-invalid': form.errors.has('contact_last_name') }">
                                <has-error :form="form" field="contact_last_name"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">{{ $t('title') }}</label>
                            <div class="col-md-7">
                                <input v-model="form.contact_title" type="text" name="contact_title"
                                       class="form-control"
                                       :class="{ 'is-invalid': form.errors.has('contact_title') }">
                                <has-error :form="form" field="contact_title"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-7 offset-md-3 d-flex">
                                <!--<stripe-checkout v-if="selectedPlan !== null" stripe-key="pk_test_liDTANMEZoxjT5okqvFofeUO" :product-id="selectedPlan.plan.stripe_id">-->
                                <!--{{ $t('continue_to_payment') }}-->
                                <!--</stripe-checkout>-->
                                <v-button :loading="form.busy">{{ $t('continue_to_payment') }}</v-button>
                            </div>
                        </div>
                    </card>
                </form>

                <modal-email :show="showModal" @close="showModal = false"></modal-email>
                <modal-error :show="showError" @close="showError = false"></modal-error>

            </div>
        </div>
    </div>
</template>

<script>
    import Form from 'vform'
    import Card from '../../components/Card.vue';
    import axios from 'axios';
    import {StripeCheckout} from 'vue-stripe';
    import PlansListComponent from '../brand/parts/PlansListComponent.vue';
    import ModalEmail from '../../components/Modal/ModalEmail';
    import ModalError from '../../components/Modal/ModalError';

    export default {
        middleware: 'guest',

        components: {
            PlansListComponent,
            Card,
            StripeCheckout,
            ModalEmail,
            ModalError,
        },

        metaInfo() {
            return {title: this.$t('register')};
        },

        created() {
            this.getPlans();
            this.getCountries();
        },

        data: () => ({
            form: new Form({
                name: '',
                email: '',
                password: '',
                companyname: '',
                address_1: '',
                address_2: '',
                city: '',
                country_id: '',
                zip: '',
                eur_uid: '',
                homepage: '',
                phone: '',
                firstname: '',
                lastname: '',
                title: '',
                plan_id: ''
            }),
            plans: [],
            countries: [],
            productId: null,
            selectedPlan: null,
            showModal: false,
            showError: false,
        }),

        methods: {
            async register() {
                // Register the user.
                const {data} = await this.form.post('/api/register/brand');

                if (data.error) {
                    this.showError = true;
                } else {
                    this.showModal = true;
                }

                // // Log in the user.
                // const {data: {token}} = await this.form.post('/api/login');
                //
                // Save the token.
                //this.$store.dispatch('auth/saveToken', {token});

                // Update the user.
                // await this.$store.dispatch('auth/updateUser', {user: data});
                //
                // // Redirect home.
                // this.$router.push({name: 'payment', params : {id: this.form.plan_id} });
            },
            getPlans() {
                axios.get('/api/plans/monthly').then(({data}) => {
                    this.plans = data;
                });
            },
            getCountries() {
                axios.get('/api/countries').then(({data}) => {
                    this.countries = data;
                });
            },
            changePlan(data) {
                this.form.plan_id = data.plan.id;
            }
        },
    };
</script>

<style lang="scss">
    .brand-registration {
        .card {
            .card-header {
                font-size: 18px;
                text-align: center;
                font-weight: bold;
            }
        }
    }
</style>