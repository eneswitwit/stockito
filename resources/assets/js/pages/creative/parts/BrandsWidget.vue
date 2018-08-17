<template>
	<div>
		<card class="mt-4" :title="$t('choose_brand')">
			<div v-if="brands.length" class="row brands-block">
				<div v-for="(brand, index) in brands" class="mt-3 col-md-4">
					<div v-bind:class="[(selectedBrand ? selectedBrand.id === brand.id : false) ? 'bg-success' : '']" v-on:click="selectBrand(index)" class="card h-100">
						<div class="card-body">
							{{ brand.brand_name }}
						</div>
					</div>
				</div>
			</div>
		</card>
		<transition name="fade">
			<brand-details v-if="selectedBrand"></brand-details>
		</transition>
	</div>
</template>

<script>
    import Card from '../../../components/Card';
    import axios from 'axios';
    import BrandDetails from './BrandDetails';
    import {mapGetters} from 'vuex';

    export default {
        components: {BrandDetails, Card},

        name: 'brands-widget',

        computed: mapGetters({
            user: 'auth/user',
            selectedBrand: 'creative/selectedBrand',
        }),

        data: () => ({
            brands: [],
        }),

        created() {
            this.getBrands();
        },

        methods: {
            getBrands() {
                axios.get('api/brands').then(response => {
                    this.brands = response.data;
                });
            },
            selectBrand(index) {
                let selectedBrand = this.brands[index];
                this.$store.dispatch('creative/setSelectedBrand', { selectedBrand });
            },
        },
    }
</script>

<style scoped>
	.fade-enter-active, .fade-leave-active {
		transition: opacity .5s;
	}
	.fade-enter, .fade-leave-to {
		opacity: 0;
	}
</style>