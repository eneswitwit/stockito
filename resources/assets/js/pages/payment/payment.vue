<template>
    <div>
        <form action="" method="post" id="payment-form" @submit.prevent="paySubscription">
            <card v-if="plan">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <h2 class="plan-title">Pay for {{ plan.title }} subscription plan</h2>
                            <!--<table class="table">
                                <tr>
                                    <td>{{ plan.title }}</td>
                                    <td>{{ plan.price / 100 }} {{ plan.currencySymbol }}</td>
                                </tr>
                            </table>-->
                            <div class="plan-info">
                                <span>Plan title</span>
                                <mark>{{ plan.price / 100 }} {{ plan.currencySymbol }}</mark>
                            </div>
                            <div class="form-group text-center">
                                <button class="btn btn-primary" :disabled="requesting" type="submit">{{ $t('pay') }}</button>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div v-if="cards" class="form-group">
                                <select v-model="selectedCard" class="form-control" name="" id="">
                                  <option value=""></option>
                                   <option v-for="card in cards" :value="card.id">****-****-****-{{ card.last4}}</option>
                                </select>
                            </div>
                            <div v-if="cards" class="form-group text-center">
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
                                <input id="voucher" class="form-control" :class="{'is-invalid': errors.voucher}" type="text" name="voucher" v-model="voucher">
                                <div v-if="errors.voucher" class="invalid-feedback">{{ errors.voucher }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </card>
        </form>
    </div>
</template>

<script>
    import axios from 'axios';
    const stripe = Stripe('pk_test_liDTANMEZoxjT5okqvFofeUO');

  export default {
    name: 'payment',
    data: () => ({
      plan: {},
      cards: [],
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
    created () {
      this.getPlan();
      this.getCards();
    },
    mounted() {

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

      const elements = stripe.elements();
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
      getPlan() {
        axios.get('/api/plans/' + this.$route.params.id).then(({ data }) => {
          this.plan = data;
        });
      },
      getCards() {
        axios.get('/api/cards').then(({ data }) => {
          this.cards = data;
        });
      },
      async paySubscription () {

        if (this.selectedCard) {
          axios.post('/api/subscription/pay-subscription', {plan: this.plan, voucher: this.voucher, selectedCard: this.selectedCard}).then(({ data }) => {
            this.requesting = false;
            if (data.success) {
              this.$store.dispatch('auth/fetchUser').then(() => {
                this.$router.push({name: 'dashboard'});
              });
            } else {
              this.errors = data.errors;
            }
          });
        } else {
          const {token, error} = await stripe.createToken(this.cardNumber);

          if (error) {
            this.error = error.message;
          } else {
            this.token = token;
            this.error = '';

            this.requesting = true;
            axios.post('/api/subscription/pay-subscription', {token: this.token, plan: this.plan, voucher: this.voucher, selectedCard: this.selectedCard}).then(({ data }) => {
              this.requesting = false;
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
      }
    }
  }
</script>

<style lang="scss">
    .errors {
        width: 100%;
        margin-top: .25rem;
        color: #dc3545;
        font-size: 80%;
    }

    .plan-title {
        text-align: center;
        margin-bottom: 30px;
    }

    .plan-info {
      text-align: center;
    }

    .plan-info span {
      display: block;
      font-size: 20px;
      line-height: 22px;
      margin-bottom: 15px;
    }

    .plan-info mark {
      display: block;
      font-size: 25px;
      line-height: 27px;
      color: #007bff;
      padding: 0;
      background: none;
      margin-bottom: 15px;
    }

    .StripeElement {
      display: block;
      width: 100%;
      padding: 0.375rem 0.75rem;
      font-size: 1rem;
      line-height: 1.5;
      color: #495057;
      background-color: #fff;
      border: 1px solid #ced4da;
      border-radius: 0.125rem;
      margin-bottom: 15px;
    }
</style>
