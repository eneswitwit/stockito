<template>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">{{ $t('payment_details') }}</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2"><p class="font-weight-bold">{{ $t('creditcard') }}</p></div>
                    <div class="col-md-5"><p>{{ user.cardBrand }}</p></div>
                </div>
                <div class="row">
                    <div class="col-md-2"><p class="font-weight-bold">{{ $t('card_number') }}</p></div>
                    <div class="col-md-5"><p>**** **** **** {{ user.cardLastFour }}</p></div>
                </div>
                <div class="row">
                    <div class="col-md-2"><p class="font-weight-bold">{{ $t('expiration') }}</p></div>
                    <div class="col-md-5"><p>{{ user.trialEndsAtCard }}</p></div>
                </div>
                <router-link :to="{name: 'change.creditcard'}" class="btn btn-primary btn-block btn-edit">
                    Change Creditcard
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        name: 'payment-details',
        data: () => ({}),
        computed: {
            user() {
                return this.$store.getters['auth/user']
            },
        },
        methods: {}
    }
</script>
