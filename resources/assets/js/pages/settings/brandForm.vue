<template>
    <form @submit.prevent="update" @keydown="form.onKeydown($event)">

        <card class="mb-4" :title="$t('brand_details')">
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

            <div class="card col-md-7 offset-md-3 p-0">
                <img v-if="brandImage" class="card-img-top" :src="brandImage" alt="Card image cap">
                <div class="card-body">
                    <div class="form-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="brand-logo" @change="setFile">
                            <label class="custom-file-label" for="brand-logo">Choose logo</label>
                        </div>
                    </div>
                </div>
            </div>

            <!--&lt;!&ndash; Password &ndash;&gt;-->
            <!--<div class="form-group row">-->
            <!--<label class="col-md-3 col-form-label text-md-right">{{ $t('password') }}</label>-->
            <!--<div class="col-md-7">-->
            <!--<input v-model="form.password" type="password" name="password" class="form-control"-->
            <!--autocomplete="current-password"-->
            <!--:class="{ 'is-invalid': form.errors.has('password') }">-->
            <!--<has-error :form="form" field="password"/>-->
            <!--</div>-->
            <!--</div>-->

        </card>

        <card class="mb-4" :title="$t('billing_address')">

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
        <card class="mb-4" title="Contact Person">
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
                    <input v-model="form.contact_title" type="text" name="contact_title" class="form-control"
                           :class="{ 'is-invalid': form.errors.has('contact_title') }">
                    <has-error :form="form" field="contact_title"/>
                </div>
            </div>

        </card>

        <div class="form-group row">
            <div class="col-md-2 offset-md-10 d-flex">
                <v-button :loading="form.busy">{{ $t('update') }}</v-button>
            </div>
        </div>

    </form>
</template>

<script>
    import Form from 'vform';
    import axios from 'axios';

    export default {
        name: 'brand-form',
        scrollToTop: false,

        metaInfo() {
            return {title: this.$t('settings')};
        },

        data: () => ({
            countries: [],
            form: new Form({
                brand_name: '',
                email: '',
                company_name: '',
                address_1: '',
                address_2: '',
                city: '',
                country_id: '',
                zip: '',
                eur_uid: '',
                homepage: '',
                phone: '',
                contact_first_name: '',
                contact_last_name: '',
                contact_title: ''
            }),
            base64: ''
        }),

        computed: {
            user() {
                return this.$store.getters['auth/user']
            },
            brand() {
                return this.$store.getters['auth/brand']
            },
            brandImage() {
                return this.base64 ? this.base64 : this.brand.logo;
            }
        },

        created() {
            this.getCountries();

            this.form.keys().forEach(key => {
                this.form[key] = this.brand[key];
            });

            this.form.country_id = this.brand.country_id;
            this.form.contact_first_name = this.brand.contact.first_name;
            this.form.contact_last_name = this.brand.contact.last_name;
            this.form.contact_title = this.brand.contact.title;
            this.form.email = this.user.email;
        },

        methods: {
            async update() {
                if (this.form.logo) {
                    this.form['_method'] = 'PUT';
                    const {data} = await this.form.post('/api/settings/profile/brand');
                    this.$store.dispatch('auth/updateUser', {user: data});
                } else {
                    const {data} = await this.form.put('/api/settings/profile/brand');
                    this.$store.dispatch('auth/updateUser', {user: data});
                }
                this.$swal('Successfully updated');
            },
            async getCountries() {
                let {data} = await axios.get('/api/countries');
                this.countries = data;
            },
            setFile(event) {
                let file = event.target.files[0];

                let formData = new FormData();
                formData.append('image', file);

                axios.post('/api/img-to-base64', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(({data}) => {
                    this.base64 = data;
                });

                this.form.logo = file;
            }
        },
    };
</script>
