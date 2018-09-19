<template>
    <div class="container mt-4">
        <brand-dashboard v-if="isBrand"></brand-dashboard>
        <creative-dashboard v-else-if="isCreative"></creative-dashboard>
    </div>
</template>

<script>
    import BrandDashboard from './brand/Dashboard.vue';
    import CreativeDashboard from './creative/Dashboard';

    export default {
        components: {
            CreativeDashboard,
            BrandDashboard
        },
        middleware: ['auth', 'subscribed'],

        computed: {
            isBrand() {
                return this.$store.getters['auth/user'] ? this.$store.getters['auth/user'].brand : false;
            },
            isCreative() {
                return this.$store.getters['auth/user'] ? this.$store.getters['auth/user'].creative : false;
            }
        },

        metaInfo() {
            return {title: this.$t('home')}
        }
    }
</script>
