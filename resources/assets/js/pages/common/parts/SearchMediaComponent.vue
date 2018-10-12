<template>
    <div>
        <div class="card">
            <div class="card-header">
                Advanced Search Options
                <button class="btn btn-link remove-btn" @click="closeAdvancedSearch">
                    <fa icon="times" fixed-width/>
                </button>
            </div>

            <div class="card-body">

                <div class="form-group">
                    <label for="filter-license-type">License Type</label>
                    <select id="filter-license-type"
                            v-model="localFilter.licenseType"
                            @change="setFilter"
                            class="form-control">
                        <option :value="undefined"></option>
                        <option v-for="(license, key) in licenses" :value="key">{{ license }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="filter-category">Category</label>
                    <select id="filter-category"
                            v-model="localFilter.categoryId"
                            @change="setFilter"
                            class="form-control">
                        <option :value="undefined"></option>
                        <option v-for="category in categories" :value="category.id">{{ category.name }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="filter-people-attributes">People Attributes</label>
                    <select id="filter-people-attributes"
                              @change="setFilter"
                              v-model="localFilter.peoplesAttribute"
                              class="form-control"
                        ><option :value="undefined"></option>
                        <option :value="'No people'"> No people </option>
                        <option :value="'With people'"> With people </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="filter-supplier">Supplier</label>
                    <select id="filter-supplier" v-model="localFilter.supplierId" @change="setFilter"
                            class="form-control">
                        <option :value="undefined"></option>
                        <option v-for="supplier in suppliers" :value="supplier.id">{{ supplier.name }}</option>
                    </select>
                </div>
                <!--<div class="form-group">
                    <label for="filter-orientation">Orientation</label>
                    <select id="filter-orientation" v-model="localFilter.orientation" @change="setFilter"
                            class="form-control">
                        <option :value="undefined"></option>
                        <option value="portrait">Portrait</option>
                        <option value="landscape">Landscape</option>
                    </select>
                </div>-->
            </div>
        </div>
    </div>
</template>

<script>
    import Card from '../../../components/Card.vue';
    import axios from 'axios';

    export default {
        components: {Card},
        name: 'search-media-component',
        created() {
            this.getLicenses();
            this.getCategories();
            this.getPeopleAttributes();
            this.getSuppliers();
        },
        data: () => ({
            licenses: [],
            categories: [],
            suppliers: [],
            peoplesAttribute: [],
            localFilter: {
                licenseType: undefined,
                categoryId: undefined,
                supplierId: undefined,
                peoplesAttribute: [],
                selectedBrand: undefined
            }
        }),
        computed: {
            filter() {
                return this.$store.getters['media/filter'];
            },
            selectedBrand() {
                return this.$store.getters['creative/selectedBrand'];
            },
            brand() {
                return this.$store.getters['auth/brand'];
            },
            brandId() {
                return this.brand ? this.brand.id : this.selectedBrand.id;
            }
        },
        methods: {
            getLicenses() {
                axios.get('/api/licenses/types').then(({data}) => {
                    this.licenses = data;
                });
            },
            getCategories() {
                axios.get(`/api/brands/${this.brandId}/categories`).then(({data}) => {
                    this.categories = data;
                });
            },
            getPeopleAttributes() {
                axios.get(`/api/brands/${this.brandId}/people-attributes`).then(({data}) => {
                    this.peopleAttributes = data;
                });
            },
            getSuppliers() {
                axios.get(`/api/brands/${this.brandId}/suppliers`).then(({data}) => {
                    this.suppliers = data;
                });
            },
            setFilter() {
                if (this.selectedBrand) {
                    this.localFilter.selectedBrand = this.selectedBrand.id
                }
                this.$store.dispatch('media/setFilter', {filter: this.localFilter});
            },
            closeAdvancedSearch() {
                this.$emit('closeAdvancedSearch', false);
                this.$store.dispatch('media/setFilter', {filter: ''});
            }
        }
    }
</script>
