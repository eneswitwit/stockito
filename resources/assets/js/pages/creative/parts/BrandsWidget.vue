<template>
	<div>

		<card class="mt-4" :title="$t('choose_brand')">
			<div v-if="brands.length" class="row brands-block">
				<div v-for="(brand, index) in brands" class="col-md-2 text-center">
					<div v-bind:class="[(selectedBrand ? selectedBrand.id === brand.id : false) ? 'btn-info' : '']" v-on:click="selectBrand(index)" class="card h-100">
						<div class="card-body">
							{{ brand.brand_name }}
						</div>
					</div>
				</div>
			</div>

			<hr v-if="selectedBrand">

			<transition name="fade">
				<brand-details v-if="selectedBrand"></brand-details>
			</transition>

		</card>


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
