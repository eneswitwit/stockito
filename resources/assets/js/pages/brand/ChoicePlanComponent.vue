<template>
    <div class="container mt-4">
        <b-row v-if="selectedPlanId" :class="'text-right mt-4 mb-4'">
            <b-col>
                <router-link class="btn btn-success btn-block" :to="{name: 'payment', params: {id: selectedPlanId}}">
                    {{ $t('pay') }}
                </router-link>
            </b-col>
        </b-row>
        <b-row>
            <b-col>
                <plans-list-component @select-plan="changePlan" :plans="plans"
                                      :currentPlan="currentPlan"></plans-list-component>
            </b-col>
        </b-row>
    </div>

</template>

<script>

    import Card from '../../components/Card.vue';
    import axios from 'axios';
    import PlansListComponent from './parts/PlansListComponent.vue';

    export default {
        components: {
            PlansListComponent,
            Card
        },

        name: 'choice-plan-component',
        created() {
            this.getCurrentPlan();
            this.getPlans();
        },

        data: () => ({
            plans: [],
            currentPlan: null,
            selectedPlanId: null
        }),

        methods: {

            getPlans() {
                axios.get('/api/plans/all').then(({data}) => {
                    this.plans = data;
                });
            },

            changePlan(data) {
                this.selectedPlanId = data.plan.id;
            },

            getCurrentPlan() {
                var subscription = this.$store.getters['auth/user'].subscription;
                if (subscription !== null) {
                    var plan_id = subscription.plan.id;
                    axios.get('/api/plans/' + plan_id).then(({data}) => {
                        this.currentPlan = data;
                    });
                }
            }

        }
    }
</script>