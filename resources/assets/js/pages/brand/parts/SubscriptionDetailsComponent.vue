<template>
    <div>
        <card>
            <h4>Subscription</h4>
            <hr>
            <p v-if="user.subscribed">Current plan: {{ user.subscription.product.name }}</p>
            <p v-if="user.subscribed">Subscription expires: {{ expiriesDate(user.subscription.created_at.date) }}</p>
            <p v-if="user.subscribed">Plan will expire in: {{ leftDate() }} days</p>
            <p v-if="user.subscribed">Size: <span class="badge badge-primary">{{ user.subscription.product.storageFormated }}</span> of photos and video</p>

            <div class="form-group">
                <p>{{ user.onGracePeriod }}</p>
                <button v-if="user.subscribed && !user.onGracePeriod" class="btn btn-warning" @click="cancelSubscription">Cancel</button>
                <button v-if="user.subscribed && user.onGracePeriod" class="btn btn-success" @click="resumeSubscription">Resume</button>
                <!--<button v-if="!subscription.autoCharge" class="btn btn-primary" @click="showRenewModal = true">{{ $t('renewal') }}</button>-->
                <router-link :to="{'name': 'select-plan'}" class="btn btn-primary">{{ user.subscribed ? 'Change plan' : 'Subscribe' }}</router-link>
                <router-link :to="{name: 'payment.details' }" class="btn btn-info">{{ $t('payment_details') }}</router-link>
            </div>
        </card>

        <!--<modal v-bind:show="showRenewModal">-->
            <!--<modal-header>{{ $t('renew_your_subscription') }}</modal-header>-->
            <!--<modal-body>-->
                <!--<p v-if="subscription.ends_at">Your subscription for {{ subscription.plan.title }} plan will expire in {{ leftDate(subscription.ends_at.date) }} Days. Renew it now, before it's to late.</p>-->
                <!--<div class="form-group text-center">-->
                    <!--<button class="btn btn-default">{{ $t('renew') }}</button>-->
                <!--</div>-->
            <!--</modal-body>-->
        <!--</modal>-->
    </div>
</template>

<script>
  import axios from 'axios';
  import Moment from 'moment';
  import {DAY_INTERVAL, MONTH_INTERVAL} from './../../common/parts/services/constants';
  import ModalHeader from '../../../components/Modal/ModalHeader.vue';
  import ModalBody from '../../../components/Modal/ModalBody.vue';
  import Modal from '../../../components/Modal/ModalLarge.vue';
  import Card from '../../../components/Card';


  export default {
    components: {
      Card,
      Modal,
      ModalBody,
      ModalHeader
    },
    name: 'subscription-details-component',
    data: () => ({
       showRenewModal: false,
       endsDate: null,
       user: null
    }),
    created () {
      this.getUser();
    },
    computed: {
      subscription() {
        return this.$store.getters['auth/user'].subscription;
      }
    },
    methods: {
      getUser() {
        this.user = this.$store.getters['auth/user'];
      },
      getDate(date, format) {
        return new Moment(date).format(format);
      },
      leftDate() {
        return new Moment(this.endsDate).diff(new Moment(), 'days');
      },
      expiriesDate(date) {
        let days;
        let period = this.user.subscription.plan.period.toLowerCase();
        if (period === DAY_INTERVAL) {
          days = 1;
        } else if (period === MONTH_INTERVAL) {
          days = 30;
        }
        this.endsDate = new Moment(date).add(days, 'days');
        return this.endsDate.format('Do MMMM, YYYY');
      },
      cancelSubscription() {
        axios.post('/api/subscription/cancel-subscription').then(({data}) => {
          this.$store.dispatch('auth/updateUser', {user: data.data});
          this.$swal(data.message, '', 'success');
        });
      },
      resumeSubscription() {
        axios.post('/api/subscription/resume-subscription').then(({data}) => {
          this.$store.dispatch('auth/updateUser', {user: data.data});
          this.$swal(data.message, '', 'success');
        });
      },
    }
  }
</script>