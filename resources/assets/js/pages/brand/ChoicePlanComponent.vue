<template>
    <div class="container mt-4 mb-2">

        <b-row>
            <b-col>
                <plans-list-component @select-plan="changePlan" :plans="plans"
                                      :currentPlan="currentPlan"></plans-list-component>
            </b-col>
        </b-row>

        <b-row>
            <b-col>
                <card :title="'Terms and Conditions'" :class="'mt-2 mb-4'">
                    <div class="form-check">
                        <input required="true" type="checkbox" v-model="checked" class="form-check-input col-md-2"
                               name="terms_conditions" id="terms_conditions">
                        <label class="form-check-label" for="terms_conditions">
                            I have read and accept the Stockito <u>
                            <router-link :to="{ name: 'gdpr.terms-conditions' }">
                                Terms and Conditions
                            </router-link>
                        </u> <strong>
                            (required) </strong>
                        </label>
                    </div>
                </card>
            </b-col>
        </b-row>

        <b-row v-if="selectedPlanId && checked" :class="'text-right mb-4'">
            <b-col id="pay">
                <router-link class="btn btn-success btn-block"
                             :to="{name: 'payment', params: {id: selectedPlanId}}">
                    {{ $t('pay') }}
                </router-link>
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
            this.getUser();
            this.getTaxRate();
        },

        data: () => ({
            plans: [],
            currentPlan: null,
            selectedPlanId: null,
            checked: false,
            user: null,
            taxRate: 0
        }),

        watch: {
            checked() {
                setTimeout(function () {
                    var pay = document.getElementById('pay');
                    if(pay) {
                        document.getElementById('pay').scrollIntoView();
                    }
                    window.scrollTo(0,document.body.scrollHeight);
                }, 60);
            }
        },

        methods: {

            getUser() {
                this.user = this.$store.getters['auth/user'];
            },

            getPlans() {
                axios.get('/api/plans/all').then(({data}) => {
                    this.plans = data;
                });
            },

            getTaxRate() {
                axios.get('/api/subscription/tax').then(({data}) => {
                    this.taxRate = data;
                });
            },

            changePlan(data) {
                this.selectedPlanId = data.plan.id;
                document.getElementById('terms_conditions').scrollIntoView();
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