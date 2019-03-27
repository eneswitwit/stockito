<template>
    <div v-if="row.id && (selectedBrand ? (row.id !== user.creative.id) : true)">
        <button @click="showEditModal" v-if="isHeadOfTeam()" class="btn btn-primary" style="word-break: keep-all;">Edit</button>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';
    import CheckCreativePermission from '../../common/parts/services/CheckCreativePermissionService';

    export default {

        mixins: [CheckCreativePermission],

        data: () => ({

        }),
        computed: mapGetters({
            user: 'auth/user',
            selectedBrand: 'creative/selectedBrand',
        }),
        name: 'edit-creative-component',
        props: [
            'row',
            'name'
        ],
        mounted() {

        },
        methods: {
            showEditModal() {
                let creativeId = this.row.id;
                let brandId = this.selectedBrand.id;
                console.log(brandId);
                this.$store.dispatch('creative/setEditCreative', { creativeId: creativeId, brandId: brandId});
            },
        }
    }
</script>