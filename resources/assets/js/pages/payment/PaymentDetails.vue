<template>
    <div class="container mt-4">
        <div class="card mb-3">
            <div class="card-header dashboard-card">{{ $t('payment_details') }}</div>
            <div class="card-body">
                <div class="row" style="padding: 20px;">
                    <div class="col-md-2"><p style="font-weight: 500;">{{ $t('creditcard') }}</p></div>
                    <div class="col-md-5"><p>{{ user.cardBrand }}</p></div>
                </div>
                <div class="row" style="padding: 20px;">
                    <div class="col-md-2"><p style="font-weight: 500;">{{ $t('card_number') }}</p></div>
                    <div class="col-md-5"><p>**** **** **** {{ user.cardLastFour }}</p></div>
                </div>
                <div class="row" style="padding: 20px;">
                    <div class="col-md-2"><p style="font-weight: 500;">{{ $t('expiration') }}</p></div>
                    <div class="col-md-5"><p>{{ user.trialEndsAtCard }}</p></div>
                </div>
            </div>
        </div>
        <router-link :to="{name: 'change.creditcard'}" class="btn btn-primary btn-block btn-edit">
            Change Creditcard
        </router-link>
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
