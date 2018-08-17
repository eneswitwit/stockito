<template>
    <div>
        <button class="btn btn-primary close-search" @click="closeAdvancedSearch">Close Advanced Search</button>
        <card title="Advanced Search Options">
            <div class="form-group">
                <label for="filter-license-type">License Type</label>
                <select id="filter-license-type" v-model="localFilter.licenseType" @change="setFilter" class="form-control">
                    <option :value="undefined"></option>
                    <option v-for="(license, key) in licenses" :value="key">{{ license }}</option>
                </select>
            </div>
            <div class="form-group">
                <label for="filter-category">Category</label>
                <select id="filter-category" v-model="localFilter.categoryId" @change="setFilter" class="form-control">
                    <option :value="undefined"></option>
                    <option v-for="category in categories" :value="category.id">{{ category.name }}</option>
                </select>
            </div>
            <div class="form-group">
                <label for="filter-people-attributes">People Attributes</label>
                <select id="filter-people-attributes" v-model="localFilter.peopleAttributes" @change="setFilter" class="form-control" multiple>
                    <option :value="0"></option>
                    <option v-for="peopleAttribute in peopleAttributes" :value="peopleAttribute.id">{{ peopleAttribute.name }}</option>
                </select>
            </div>
            <div class="form-group">
                <label for="filter-supplier">Supplier</label>
                <select id="filter-supplier" v-model="localFilter.supplierId" @change="setFilter" class="form-control">
                    <option :value="undefined"></option>
                    <option v-for="supplier in suppliers" :value="supplier.id">{{ supplier.name }}</option>
                </select>
            </div>
            <div class="form-group">
                <label for="filter-orientation">Orientation</label>
                <select id="filter-orientation" v-model="localFilter.orientation" @change="setFilter" class="form-control">
                    <option :value="undefined"></option>
                    <option value="portrait">Portrait</option>
                    <option value="landscape">Landscape</option>
                </select>
            </div>
        </card>
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
      peopleAttributes: [],
      localFilter: {
        licenseType: undefined,
        categoryId: undefined,
        supplierId: undefined,
        peopleAttributes: [],
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
        axios.get('/api/licenses/types').then(({ data }) => {
          this.licenses = data;
        });
      },
      getCategories() {
          axios.get(`/api/brands/${this.brandId}/categories`).then(({ data }) => {
          this.categories = data;
        });
      },
      getPeopleAttributes() {
        axios.get(`/api/brands/${this.brandId}/people-attributes`).then(({ data }) => {
          this.peopleAttributes = data;
        });
      },
      getSuppliers() {
        axios.get(`/api/brands/${this.brandId}/suppliers`).then(({ data }) => {
          this.suppliers = data;
        });
      },
      setFilter() {
        if (this.selectedBrand) {
            this.localFilter.selectedBrand = this.selectedBrand.id
        }
        this.$store.dispatch('media/setFilter', { filter: this.localFilter });
      },
      closeAdvancedSearch() {
        this.$emit('closeAdvancedSearch', false);
        this.$store.dispatch('media/setFilter', {filter: ''});
      }
    }
  }
</script>
