<template>
    <div class="container mt-4">

        <new-subscription-component v-if="currentPlan === null"
                                    :plan="plan"
                                    :cards="cards"
                                    :stripe="stripe"
                                    :user="user"
                                    :taxRate="taxRate"
        ></new-subscription-component>

        <div v-else>

            <upgrade-component v-if="upgrade()"
                               :plan="plan"
                               :currentPlan="currentPlan"
                               :stripe="stripe"
                               :cards="cards"
                               :user="user"
                               :taxRate="taxRate"
            ></upgrade-component>

            <downgrade-component v-else
                                 :plan="plan"
                                 :currentPlan="currentPlan"
                                 :stripe="stripe"
                                 :cards="cards"
                                 :taxRate="taxRate"
            ></downgrade-component>

        </div>
    </div>

</template>

<script>

    import axios from 'axios';
    import NewSubscriptionComponent from './parts/NewSubscriptionComponent.vue';
    import DowngradeComponent from './parts/DowngradeComponent.vue';
    import UpgradeComponent from './parts/UpgradeComponent.vue';

    export default {

        components: {
            NewSubscriptionComponent,
            DowngradeComponent,
            UpgradeComponent
        },

        name: 'payment',

        data: () => ({
            plan: null,
            cards: [],
            currentPlan: null,
            stripe: Stripe('pk_test_liDTANMEZoxjT5okqvFofeUO'),
            user: null,
            taxRate: 0
        }),

        created() {
            this.getPlan();
            this.getCurrentPlan();
            this.getCards();
            this.getUser();
            this.getTaxRate();
        },

        methods: {

            getUser() {
                this.user = this.$store.getters['auth/user'];
            },

            getPlan() {
                axios.get('/api/plans/' + this.$route.params.id).then(({data}) => {
                    this.plan = data;
                });
            },

            getCards() {
                axios.get('/api/cards').then(({data}) => {
                    this.cards = data;
                });
            },

            getTaxRate() {
                axios.get('/api/subscription/tax').then(({data}) => {
                    this.taxRate = data;
                });
            },

            getCurrentPlan() {
                var subscription = this.$store.getters['auth/user'].subscription;
                if (subscription) {
                    var plan_id = subscription.plan.id;
                    axios.get('/api/plans/' + plan_id).then(({data}) => {
                        this.currentPlan = data;
                    });
                }
            },

            upgrade() {
                if (this.plan === null) {
                    this.getPlan()
                }
                return this.plan.price > this.currentPlan.price;
            }

        }
    }
</script>

