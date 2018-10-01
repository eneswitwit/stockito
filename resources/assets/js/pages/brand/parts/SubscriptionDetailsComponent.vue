<template>

    <div>
        <card :title="'Subscription'">

            <table class="widget-table mb-4" v-if="user.subscribed">
                <tr>
                    <td>
                        <span class="font-weight-bold"> Current plan </span>
                    </td>
                    <td>
                        {{ user.subscription.product.name }}
                    </td>
                </tr>

                <tr>
                    <td>
                        <span class="font-weight-bold">Subscription expires </span>
                    </td>
                    <td>
                        {{ expiriesDate(user.subscription.created_at.date) }}
                    </td>
                </tr>

                <tr>
                    <td>
                        <span class="font-weight-bold"> Plan will expire in </span>
                    </td>
                    <td>
                        {{ leftDate() }} days
                    </td>
                </tr>
            </table>


            <div class="form-group">

                <span class="mb-4">
                    <div v-if="getIfDowngrade()" class="alert alert-warning" role="alert">
                        <p>Plan will be downgraded automatically after expiration.</p>
                    </div>
                    <div class="alert alert-info">
                        <p v-if="user.subscribed && !user.onGracePeriod"> Plan will automatically renew itself</p>
                        <p v-if="user.subscribed && user.onGracePeriod"> Plan will not renew itself automatically</p>
                    </div>
                </span>

                <button v-if="user.subscribed && !user.onGracePeriod" class="btn btn-warning"
                        @click="cancelSubscription">Cancel
                </button>
                <button v-if="user.subscribed && user.onGracePeriod" class="btn btn-success"
                        @click="resumeSubscription">Resume
                </button>
                <!--<button v-if="!subscription.autoCharge" class="btn btn-primary" @click="showRenewModal = true">{{ $t('renewal') }}</button>-->
                <router-link :to="{'name': 'select-plan'}" class="btn btn-primary">
                    {{ user.subscribed ? 'Change plan' : 'Subscribe' }}
                </router-link>
                <router-link :to="{name: 'payment.details' }" class="btn btn-info">{{ $t('payment_details') }}
                </router-link>
            </div>

        </card>

    </div>
</template>

<script>

    /** import **/
    import axios from 'axios';
    import Moment from 'moment';
    import {DAY_INTERVAL, MONTH_INTERVAL, YEAR_INTERVAL} from './../../common/parts/services/constants';
    import ModalHeader from '../../../components/Modal/ModalHeader.vue';
    import ModalBody from '../../../components/Modal/ModalBody.vue';
    import Modal from '../../../components/Modal/ModalLarge.vue';
    import Card from '../../../components/Card';


    /** export **/
    export default {

        name: 'subscription-details-component',

        components: {
            Card,
            Modal,
            ModalBody,
            ModalHeader
        },

        /** data **/
        data: () => ({
            showRenewModal: false,
            endsDate: null,
            user: null,
            subscription: null,
        }),

        /** created **/
        created() {
            this.getUser();
            this.getSubscription();
        },

        /** methods **/
        methods: {


            /**
             *
             * @returns {void}
             */
            getUser() {
                this.user = this.$store.getters['auth/user'];
            },

            /**
             *
             * @returns {void}
             */
            getSubscription() {
                this.subscription = this.$store.getters['auth/user'].subscription;
            },

            /**
             *
             * @returns {boolean}
             */
            getIfDowngrade() {
                if (this.subscription) {
                    return this.subscription.downgrade_to_stripe_plan !== null;
                }
            },

            /**
             *
             * @param {string} date
             * @param {string} format
             * @returns {*|string}
             */
            getDate(date, format) {
                return new Moment(date).format(format);
            },

            /**
             *
             * @returns {number}
             */
            leftDate() {
                return new Moment(this.endsDate).diff(new Moment(), 'days');
            },

            /**
             *
             * @param date
             * @returns {*}
             */
            expiriesDate(date) {
                let days;
                let period = this.user.subscription.plan.period.toLowerCase();
                if (period === DAY_INTERVAL) {
                    days = 1;
                } else if (period === MONTH_INTERVAL) {
                    days = 30;
                } else if (period === YEAR_INTERVAL) {
                    days = 365;
                }
                this.endsDate = new Moment(date).add(days, 'days');
                return this.endsDate.format('Do MMMM, YYYY');
            },

            /**
             *
             * @returns {void}
             */
            cancelSubscription() {
                axios.post('/api/subscription/cancel-subscription').then(({data}) => {
                    this.$store.dispatch('auth/updateUser', {user: data.data});
                    this.$swal(data.message, '', 'success');
                });
            },

            /**
             *
             * @returns {void}
             */
            resumeSubscription() {
                axios.post('/api/subscription/resume-subscription').then(({data}) => {
                    this.$store.dispatch('auth/updateUser', {user: data.data});
                    this.$swal(data.message, '', 'success');
                });
            }

        }
    }
</script>