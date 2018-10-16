<template>
    <div class="container mt-4 mb-4">
        <div class="row brand-registration">
            <div class="col-lg-12 m-auto">
                <form @submit.prevent="register" @keydown="form.onKeydown($event)">

                    <card :title="$t('brand_details')" class="mb-2">

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

                    <card :title="$t('billing_address')" class="mb-2">

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
                    </card>

                    <card :title="'Contact Details'" class="mb-2">

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

                    </card>

                    <card :title="'Policy'" :class="'mb-2'">
                        <div class="form-check">
                            <input required="true" v-model="form.terms_conditions" type="checkbox" class="form-check-input col-md-2"
                                   name="terms_conditions" id="terms_conditions">
                            <label class="form-check-label" for="terms_conditions">
                                I have read and accept the Stockito <u><router-link :to="{ name: 'gdpr.terms-conditions' }">
                                Terms and Conditions </router-link></u> <strong>
                                (required) </strong>
                            </label>
                        </div>
                        <div class="form-check">
                            <input required="true" type="checkbox" v-model="form.privacy_policy" class="form-check-input col-md-2"
                                   id="privacy_policy" name="privacy_policy">
                            <label class="form-check-label" for="privacy_policy">
                                I agree to Stockito <u><router-link :to="{ name: 'gdpr.privacy-policy' }">
                                Privacy Policy </router-link></u> <strong>(required) </strong>
                            </label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" v-model="form.newsletter" class="form-check-input col-md-2"
                                   id="newsletter" name="newsletter">
                            <label class="form-check-label" for="newsletter">
                                I would like to recieve the Stockito Newsletter and Special Promotions
                            </label>
                        </div>
                    </card>

                    <v-button :loading="form.busy" :class="'btn-block mb-8'"> Confirm</v-button>

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
                address_2: null,
                city: '',
                country_id: '',
                zip: '',
                eur_uid: '',
                homepage: '',
                phone: '',
                firstname: '',
                lastname: '',
                title: null,
                plan_id: '',
                terms_conditions: '',
                privacy_policy: '',
                newsletter: ''
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
