<template>
    <form action="" method="post" id="payment-form" @submit.prevent="downgradeSubscription">

        <card class="downgrade-template" :title="'Downgrade your plan'" style="text-align: center;">
            <p style="padding: 20px;">
                Your plan will automatically downgraded to Plan {{ plan.title }} after the expiration of the current
                plan in
                {{ leftDays() }} days.
                To avoid troubles with the downgrade make sure that you only use the storage that the plan offers that you are downgrading to in {{ leftDays() }} days.
            </p>
            <button class="btn btn-primary" type="submit">
                {{ 'Submit' }}
            </button>
            <a href="/dashboard" class="btn btn-warning">
                {{ 'Cancel' }}
            </a>
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
            requesting: false,
            errors: {}
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

            cancel() {
                this.$router.push({name: 'dashboard'});
            },

            async downgradeSubscription() {

                axios.post('/api/subscription/downgrade', {
                    plan: this.plan,
                }).then(({data}) => {
                    this.requesting = false;
                    if (data.success) {
                        this.$store.dispatch('auth/fetchUser').then(() => {
                            this.$swal('Successfully updated subscription', '', 'success');
                            this.$router.push({name: 'dashboard'});
                        });
                    } else {
                        this.errors = data.errors;
                    }
                });

            },

            getUser() {
                this.user = this.$store.getters['auth/user'];
            },
            getDate(date, format) {
                return new Moment(date).format(format);
            },

            leftDays() {
                return new Moment(new Moment(this.user.subscription.created_at.date).add(365, 'days')).diff(new Moment(), 'days');
            }
        }
    }
</script>