<template>
    <div class="container">
        <creative-form v-if="user && user.creative"></creative-form>
        <brand-form v-if="user && user.brand"></brand-form>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'
    import CreativeForm from './creativeForm';
    import BrandForm from './brandForm';

    export default {
        components: {
            CreativeForm,
            BrandForm
        },
        scrollToTop: false,

        computed: mapGetters({
            user: 'auth/user'
        })
    }
</script>
