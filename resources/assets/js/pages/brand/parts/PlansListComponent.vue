<template>

    <div>
        <div v-if="currentPlan === null">
            <card :title="'Select your plan'">
                <b-row>
                    <b-col v-for="plan in plans" :key="plan.id" @click="changePlan(plan)">
                        <div class="card pricing" :class="{'bg-primary': selectedPlanId === plan.id}">
                            <div class="card-body">
                                <small class="text-muted"> {{ plan.title }}</small>
                                <h5 class="card-title">{{ plan.price }} {{ plan.currencySymbol }}</h5>
                                <p class="card-text"> yearly </p>
                                <ul class="list-unstyled">
                                    <li> {{ plan.storageGb }} GB Storage</li>
                                    <li>Team Collaboration</li>
                                    <li>Analytics &amp; Reports</li>
                                </ul>
                                <p></p>
                                <a href="#" class="btn btn-xl btn-outline-primary">Choose this plan</a>
                            </div>
                        </div>
                    </b-col>
                </b-row>
            </card>
        </div>

        <div v-else>
            <card :title="'Downgrade'" class="mt-2">
                <b-row>
                    <div v-for="plan in plans" :key="plan.id" @click="changePlan(plan)">
                        <b-col v-if="downgradePlan(plan)" @click="changePlan(plan)">
                            <div class="card pricing" :class="{'bg-primary': selectedPlanId === plan.id}">
                                <div class="card-body">
                                    <small class="text-muted"> {{ plan.title }}</small>
                                    <h5 class="card-title">{{ plan.price }} {{ plan.currencySymbol }}</h5>
                                    <p class="card-text"> yearly </p>
                                    <ul class="list-unstyled">
                                        <li> {{ plan.storageGb }} GB Storage</li>
                                        <li>Team Collaboration</li>
                                        <li>Analytics &amp; Reports</li>
                                    </ul>
                                    <p></p>
                                    <a href="#" class="btn btn-xl btn-outline-primary">Choose this plan</a>
                                </div>
                            </div>
                        </b-col>
                    </div>

                </b-row>
            </card>
            <card :title="'Upgrade'" class="mt-2">
                <b-row>
                    <div v-for="plan in plans" :key="plan.id" @click="changePlan(plan)">
                        <b-col v-if="upgradePlan(plan)" @click="changePlan(plan)">
                            <div class="card pricing" :class="{'bg-primary': selectedPlanId === plan.id}">
                                <div class="card-body">
                                    <small class="text-muted"> {{ plan.title }}</small>
                                    <h5 class="card-title">{{ plan.price }} {{ plan.currencySymbol }}</h5>
                                    <p class="card-text"> yearly </p>
                                    <ul class="list-unstyled">
                                        <li> {{ plan.storageGb }} GB Storage</li>
                                        <li>Team Collaboration</li>
                                        <li>Analytics &amp; Reports</li>
                                    </ul>
                                    <p></p>
                                    <a href="#" class="btn btn-xl btn-outline-primary">Choose this plan</a>
                                </div>
                            </div>
                        </b-col>
                    </div>
                </b-row>
            </card>
        </div>
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
                return plan.stripe_id !== this.currentPlan.stripe_id && plan.price * 100 < this.currentPlan.price;
            },
            upgradePlan(plan) {
                return plan.stripe_id !== this.currentPlan.stripe_id && plan.price * 100 > this.currentPlan.price;
            }
        }
    }
</script>
