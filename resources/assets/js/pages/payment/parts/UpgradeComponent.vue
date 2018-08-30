<template>
    <form action="" method="post" id="payment-form" @submit.prevent="upgradeSubscription">
        <card class="upgrade-template" :title="'Upgrade your plan'">
            The amount you have to pay in order to upgrade to Plan {{ plan.title }} is {{ this.upgradePrice() }} {{ plan.currencySymbol }}.
            <div class="form-group text-center">
                <button class="btn btn-primary" type="submit">{{ $t('pay') }}
                </button>
            </div>
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
                let dailyPricePlan = this.plan.price/(100*365);
                let dailyPriceCurrentPlan = this.currentPlan.price/(100*365);
                return ((dailyPricePlan-dailyPriceCurrentPlan)*this.leftDays()).toFixed(2);
            },

            async upgradeSubscription() {

                this.requesting = true;
                axios.post('/api/subscription/upgrade', {
                    plan: this.plan,
                }).then(({data}) => {
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
</script>