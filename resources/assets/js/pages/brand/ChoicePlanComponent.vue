<template>
    <card :title="'Select your plan'">
        <div class="row">
            <div class="col-lg-12">
                <plans-list-component @select-plan="changePlan" :plans="plans"></plans-list-component>
            </div>
            <div v-if="selectedPlanId" class="col-lg-12 text-right">
                <router-link class="btn btn-primary" :to="{name: 'payment', params: {id: selectedPlanId}}">{{ $t('pay') }}</router-link>
            </div>
        </div>
    </card>
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
      this.getPlans();
    },
    data: () => ({
      plans: [],
      selectedPlanId: null
    }),
    methods: {
      getPlans() {
        axios.get('/api/plans/monthly').then(({ data }) => {
          this.plans = data;
        });
      },
      changePlan(data) {
        this.selectedPlanId = data.plan.id;
      }
    }
  }
</script>