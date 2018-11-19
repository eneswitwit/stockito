<template>

    <form action="" method="post" id="payment-form" @submit.prevent="paySubscription">
        <card v-if="plan" >
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <h2 class="plan-title">Pay for <span class="plan-name"> {{ plan.title }} </span>
                            subscription plan</h2>
                        <div class="plan-info">
                            <mark>{{ plan.price / 100 }} {{ plan.currencySymbol }}</mark>
                        </div>
                        <div class="form-group text-center">
                            <button class="btn btn-primary" :class="{ 'd-none' : requesting}" type="submit">{{ $t('pay') }}
                            </button>
                            <img v-if="requesting" :src="require('../../../../images/loading.gif')"/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div v-if="cards.length > 0" class="form-group">
                            <select v-model="selectedCard" class="form-control" name="" id="">
                                <option value="">Select your credit card</option>
                                <option v-for="card in cards" :value="card.id">****-****-****-{{ card.last4}}
                                </option>
                            </select>
                        </div>
                        <div v-if="cards.length > 0" class="form-group text-center">
                            <h3>- OR -</h3>
                        </div>

                        <div class="form-group">
                            <div id="card-wrapper" class="row">
                                <div class="col-lg-12">
                                    <div id="card-wrapper-number"></div>
                                </div>
                                <div class="col-lg-6">
                                    <div id="card-wrapper-cvc"></div>
                                </div>
                                <div class="col-lg-6">
                                    <div id="card-wrapper-expiry"></div>
                                </div>
                            </div>
                            <div class="errors">{{ error }}</div>
                        </div>

                        <div class="form-group">
                            <label for="voucher">Voucher code</label>
                            <input id="voucher" class="form-control" :class="{'is-invalid': errors.voucher}"
                                   type="text" name="voucher" v-model="voucher">
                            <div v-if="errors.voucher" class="invalid-feedback">{{ errors.voucher }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </card>
    </form>
</template>

<script>

    import axios from 'axios';
    import debounce from 'lodash/debounce'

    export default {

        name: 'new-subscription-component',

        data: () => ({
            selectedCard: null,
            voucher: '',
            token: '',
            error: '',
            cardNumber: null,
            cardExpiry: null,
            cardCvc: null,
            errors: {},
            requesting: false
        }),

        props: {
            plan: {
                'default': {}
            },
            cards: {
                'default': []
            },
            stripe: {
                'default': null
            }
        },

        updated: debounce(function () {
            this.$nextTick(() => {
                this.mountStripe();
            })
        }, 450),


        methods: {

            async paySubscription() {

                this.requesting = true;
                if (this.selectedCard) {
                    axios.post('/api/subscription/pay-subscription', {
                        plan: this.plan,
                        voucher: this.voucher,
                        selectedCard: this.selectedCard
                    }).then(({data}) => {
                        if (data.success) {
                            this.$store.dispatch('auth/fetchUser').then(() => {
                                this.$router.push({name: 'dashboard'});
                            });
                        } else {
                            this.errors = data.errors;
                        }
                    });
                } else {
                    const {token, error} = await this.stripe.createToken(this.cardNumber);

                    if (error) {
                        this.error = error.message;
                    } else {
                        this.token = token;
                        this.error = '';

                        axios.post('/api/subscription/pay-subscription', {
                            token: this.token,
                            plan: this.plan,
                            voucher: this.voucher,
                            selectedCard: this.selectedCard
                        }).then(({data}) => {
                            if (data.success) {
                                this.$store.dispatch('auth/fetchUser').then(() => {
                                    this.$router.push({name: 'dashboard'});
                                });
                            } else {
                                this.errors = data.errors;
                            }
                        });
                    }
                }
            },

            mountStripe() {

                let elementStyles = {
                    base: {
                        color: '#000',
                        fontWeight: 400,
                        fontFamily: 'Quicksand, Open Sans, Segoe UI, sans-serif',
                        fontSize: '14px',
                        fontSmoothing: 'antialiased',

                        ':focus': {
                            color: '#424770',
                        },

                        '::placeholder': {
                            color: '#9BACC8',
                        },

                        ':focus::placeholder': {
                            color: '#CFD7DF',
                        },
                    },
                    invalid: {
                        color: '#000',
                        ':focus': {
                            color: '#FA755A',
                        },
                        '::placeholder': {
                            color: '#FFCCA5',
                        },
                    },
                };

                const elements = this.stripe.elements();

                this.cardNumber = elements.create('cardNumber', {
                    style: elementStyles
                });

                this.cardExpiry = elements.create('cardExpiry', {
                    style: elementStyles
                });

                this.cardCvc = elements.create('cardCvc', {
                    style: elementStyles
                });

                this.cardNumber.mount('#card-wrapper-number');
                this.cardCvc.mount('#card-wrapper-cvc');
                this.cardExpiry.mount('#card-wrapper-expiry');

                this.cardNumber.addEventListener('change', ({error}) => {
                    if (error) {
                        this.error = error.message;
                    } else {
                        this.error = '';
                    }
                });
            }

        },
    }
</script>