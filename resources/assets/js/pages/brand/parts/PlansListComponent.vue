<template>
    <div class="align-items-start mb-4">
        <div v-for="plan in plans" class="col-lg-4" @click="changePlan(plan)">
            <card v-if="plan.stripe_id !== currentPlanId" class="text-center"
                  :class="{'bg-primary text-white': selectedPlanId === plan.id}">
                <h5>{{ plan.title }}</h5>
                <p class="card-text">Up to {{ plan.storageGb }} storage</p>
                <p class="card-text text-success">{{ plan.period }} {{ plan.price }} {{ plan.currencySymbol }}</p>
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
            currentPlanId: {
                'default' : 0
            }
        },
        methods: {
            changePlan(plan) {
                this.selectedPlanId = plan.id;
                this.$emit('select-plan', {plan});
            }
        }
    }
</script>
