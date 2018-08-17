<template>
    <div class="row align-items-start mb-4">
        <div v-for="plan in plans" class="col-lg-4" @click="changePlan(plan)">
            <card class="text-center" :class="{'bg-primary text-white': selectedPlanId === plan.id}">
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
      }
    },
    methods: {
      changePlan(plan) {
        this.selectedPlanId = plan.id;
        this.$emit('select-plan', { plan });
      }
    }
  }
</script>

<style scoped lang="scss">
    .row {
        margin-bottom: 25px;

        &:last-child {
            margin-bottom: 0;
        }

        .card {
            cursor: pointer;
        }
    }
</style>