<template>
   <div>
      <div class="card">
         <div class="card-header">{{ $t('payment_details') }}</div>
         <div class="card-body">
            <div class="row">
               <div class="col-md-2"><p>{{ $t('creditcard') }}</p></div>
               <div class="col-md-5"><p>{{ user.cardBrand }}</p></div>
            </div>
            <div class="row">
               <div class="col-md-2"><p>{{ $t('card_number') }}</p></div>
               <div class="col-md-5"><p>**** **** **** {{ user.cardLastFour }}</p></div>
            </div>
            <div class="row">
               <div class="col-md-2"><p>{{ $t('expiration') }}</p></div>
               <div class="col-md-5"><p>{{ user.trialEndsAtCard }}</p></div>
            </div>
            <div class="row">
               <div class="col-md-2"><p>{{ $t('cvc') }}</p></div>
               <div class="col-md-5"><p> </p></div>
            </div>
            <router-link :to="{name: 'payment', params: {id: user.subscription.product.plans[0].id }}" class="btn btn-primary btn-block btn-edit">Edit</router-link>
         </div>
   </div>
   </div>
</template>

<script>
    import axios from 'axios';

  export default {
    name: 'payment-details',
    data: () => ({
    }),
    computed: {
       user () { return this.$store.getters['auth/user'] },
    },
    methods: {

    }
  }
</script>

<style lang="scss">
   .btn-edit {
      border-radius: 5px;
      margin-top: 25px;
   }
</style>