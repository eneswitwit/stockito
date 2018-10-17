<template>
    <div class="container mt-4">
        <form action="" method="post" id="payment-form" @submit.prevent="changeCreditcard">
            <div class="card mb-3">
                <div class="card-header dashboard-card">
                    Change primary payment source
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div id="card-wrapper-number" style="padding: 10px;"></div>
                        <div id="card-wrapper-cvc" style="padding: 10px;"></div>
                        <div id="card-wrapper-expiry" style="padding: 10px;"></div>
                        <div class="errors"> {{ error }}</div>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary btn-block" :disabled="requesting" type="submit">
                Submit
            </button>
        </form>
    </div>
</template>

<script>

    import debounce from 'lodash/debounce'

    export default {

        name: 'change-creditcard',

        data: () => ({
            error: '',
            errors: {},
            stripe: Stripe('pk_test_liDTANMEZoxjT5okqvFofeUO')
        }),

        updated: debounce(function () {
            this.$nextTick(() => {
                this.mountStripe();
            })
        }, 1050),

        mounted: function () {
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
        },

        methods: {

            changeCreditcard() {

            }
            ,

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
        }
    }

</script>
