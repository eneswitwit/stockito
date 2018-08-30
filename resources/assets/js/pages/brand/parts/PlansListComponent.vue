<template>

    <div v-if="currentPlan === null">
        <b-row>
            <div v-for="plan in plans" :key="plan.id" @click="changePlan(plan)">
                <b-col>
                    <card :title="plan.title" class="text-center"
                          :class="{'bg-primary': selectedPlanId === plan.id}">
                        <p class="card-text">Up to {{ plan.storageGb }} storage</p>
                        <p class="card-text text-success">{{ plan.period }} {{ plan.price }} {{ plan.currencySymbol
                            }}</p>
                    </card>
                </b-col>
            </div>
        </b-row>
    </div>

    <div v-else>
        <b-row>
            <h2> Downgrade</h2>
            <div v-for="plan in plans" :key="plan.id" @click="changePlan(plan)">
                <b-col v-if="downgradePlan(plan)">
                    <card :title="plan.title" class="text-center"
                          :class="{'bg-primary': selectedPlanId === plan.id}">
                        <p class="card-text">Up to {{ plan.storageGb }} storage</p>
                        <p class="card-text text-success">{{ plan.period }} {{ plan.price }} {{ plan.currencySymbol
                            }}</p>
                    </card>
                </b-col>
            </div>
        </b-row>
        <b-row>
            <h2> Upgrade </h2>
            <div v-for="plan in plans" :key="plan.id" @click="changePlan(plan)">
                <b-col v-if="upgradePlan(plan)">
                    <card :title="plan.title" class="text-center"
                          :class="{'bg-primary': selectedPlanId === plan.id}">
                        <p class="card-text">Up to {{ plan.storageGb }} storage</p>
                        <p class="card-text text-success">{{ plan.period }} {{ plan.price }} {{ plan.currencySymbol
                            }}</p>
                    </card>
                </b-col>
            </div>
        </b-row>
    </div>
</template>

<script>
    import Card from '../../../components/Card';

    export default {
        name: 'plans-list-component',
        components: {Card},
        data() {
            return {
                selectedPlanId: null
            };
        },
        props: {
            plans: {
                'default': []
            },
            currentPlan: {
                'default': null
            }
        },
        methods: {
            changePlan(plan) {
                this.selectedPlanId = plan.id;
                this.$emit('select-plan', {plan});
            },
            downgradePlan(plan) {
                return plan.stripe_id !== this.currentPlan.stripe_id && plan.price*100 < this.currentPlan.price;
            },
            upgradePlan(plan) {
                return plan.stripe_id !== this.currentPlan.stripe_id && plan.price*100 > this.currentPlan.price;
            }
        }
    }
</script>
