<template>
    <form action="" method="post" id="payment-form" @submit.prevent="downgradeSubscription">

        <card class="downgrade-template" :title="'Downgrade your plan'">
            Your plan will automatically downgraded to Plan {{ plan.title }} after the expiration of the current plan in
            {{ leftDays() }} days
            <button class="btn btn-primary float-right" type="submit">{{ 'Submit' }}
            </button>
        </card>

    </form>
</template>

<script>

    import axios from 'axios';
    import Moment from 'moment';

    export default {
        name: 'downgrade-component',

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

            async downgradeSubscription() {

                this.requesting = true;
                axios.post('/api/subscription/downgrade', {
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