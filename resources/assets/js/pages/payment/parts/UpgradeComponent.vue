<template>
    <form action="" method="post" id="payment-form" @submit.prevent="upgradeSubscription">
        <card class="upgrade-template" :title="'Upgrade your plan'" style="text-align: center;">

            <div id="card-wrapper-number" style="display: none;"></div>
            <div id="card-wrapper-cvc" style="display: none;"></div>
            <div id="card-wrapper-expiry" style="display: none;"></div>

            <span style="padding: 10px">
                The amount you have to pay in order to upgrade to Plan <strong> {{ plan.title }} </strong> is
                <div class="plan-info">
                    <mark>{{ this.upgradePrice() }} {{ plan.currencySymbol }} + {{ this.upgradeTax() }} {{ plan.currencySymbol }} VAT </mark>
                </div>
            </span>

            <v-button :loading="requesting" class="btn btn-primary" :class="{ 'd-none' : requesting}" type="submit">
                {{ $t('pay') }}
            </v-button>

            <img v-if="requesting" :src="require('../../../../images/loading.gif')"/>

            <div v-if="errors"> <strong style="color: red;"> Something went wrong. Please contact the support! </strong> </div>

        </card>
    </form>
</template>

<script>

    import axios from 'axios';
    import Moment from 'moment';

    export default {
        name: 'upgrade-component',

        data: () => ({
            user: null,
            errors: null,
            requesting: false
        }),

        props: {
            plan: {
                'default': {}
            },
            stripe: {
                'default': null
            },
            cards: {
                'default': []
            },
            currentPlan: {
                'default': null
            },
            taxRate: {
                'default': 0
            }
        },

        created() {
            this.getUser();
        },

        computed: {
            subscription() {
                return this.$store.getters['auth/user'].subscription;
            }
        },

        watch: {
            requesting: function() {
                return this.requesting;
            }
        },

        methods: {

            getUser() {
                this.user = this.$store.getters['auth/user'];
            },
            getDate(date, format) {
                return new Moment(date).format(format);
            },

            leftDays() {
                return new Moment(new Moment(this.user.subscription.created_at.date).add(365, 'days')).diff(new Moment(), 'days');
            },

            upgradePrice() {
                let dailyPricePlan = this.plan.price / (100 * 365);
                let dailyPriceCurrentPlan = this.currentPlan.price / (100 * 365);
                return ((dailyPricePlan - dailyPriceCurrentPlan) * this.leftDays()).toFixed(2);
            },

            upgradeTax() {
                return (this.upgradePrice()*this.taxRate/100).toFixed(2);
            },

            async upgradeSubscription() {

                this.requesting = true;

                axios.post('/api/subscription/upgrade', {
                    plan: this.plan,
                }).then(({data}) => {
                    if (data.success) {
                        this.$store.dispatch('auth/fetchUser').then(() => {
                            this.$swal('Successfully updated subscription', '', 'success');
                            this.$router.push({name: 'dashboard'});
                        });
                    } else {

                        this.errors = data.errors;
                        this.requesting = false;
                    }
                });
            }
        }
    }
</script>