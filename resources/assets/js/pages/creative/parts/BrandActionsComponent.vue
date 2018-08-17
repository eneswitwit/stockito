<template>
    <div class="pull-right" v-if="row.id">
        <button @click="changeView" class="btn btn-success btn-sm">Change view</button>
        <button @click="setSelectedBrand" class="btn btn-primary btn-sm">Details</button>
        <button @click="removeCreativeBrand" class="btn btn-warning btn-sm">Remove</button>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        name: 'brand-actions-component',
        props: [
            'row',
            'name',
        ],
        mounted() {
        },
        methods: {
            setSelectedBrand() {
                let selectedBrand = this.row;
                this.$store.dispatch('creative/setSelectedBrand', {selectedBrand});
                this.$router.push({name: 'brand.details.show'})
            },
            changeView() {
                let selectedBrand = this.row;
                this.$store.dispatch('creative/setSelectedBrand', {selectedBrand});
                this.$router.push({name: 'creative.brand.medias', params: {creative_brand_id: selectedBrand.id}})
            },
            removeCreativeBrand() {
                this.$swal({
                    title: "Delete this brand?",
                    text: "Are you sure?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Yes, Delete it!",
                    closeOnConfirm: true
                }).then(() => {
                    axios.delete('/api/creative/brand/' + this.row.id).then(({data}) => {
                        this.$store.dispatch('creative/setBrands', {brands: data});
                    });
                })
            }
        }
    }
</script>