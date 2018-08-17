<template>
    <div v-if="row.id && (selectedBrand ? (row.id !== user.creative.id) : true)">
       <button @click="showEditModal" class="btn btn-primary">Edit</button>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';
    export default {
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
                this.$store.dispatch('creative/setEditCreative', { creativeId: creativeId });
            }
        }
    }
</script>