<template>
    <card v-if="media">
        <form @submit.prevent="submitMedia(media)">
            <div class="form-group">
                <label for="media-origin-name">Title</label>
                <input id="media-title" class="form-control" type="text" v-model="form.title" name="title">
                <has-error :form="form" field="title"/>
            </div>
            <div class="form-group">
                <label for="media-origin-name">File Name</label>
                <input id="media-origin-name" class="form-control" type="text" v-model="form.originName" name="originName">
                <has-error :form="form" field="originName"/>
            </div>
            <div class="form-group">
                <label for="media-file-type">File type</label>
                <select class="form-control" v-model="form.fileType" name="fileType" id="media-file-type">
                    <option v-for="(type, key) in types" :value="key">{{ type }}</option>
                </select>
                <has-error :form="form" field="fileType"/>
            </div>
            <div class="form-group">
                <label for="media-category">Category</label>
                <v-select id="media-category" :options="categoriesOptions" v-model="form.category" taggable></v-select>
                <has-error :form="form" field="category"/>
            </div>
            <div class="form-group">
                <label for="media-people-attributes">People Attributes</label>
                <v-select id="media-people-attributes" :options="peopleAttributesOptions" v-model="form.peopleAttributes" taggable multiple></v-select>
                <has-error :form="form" field="peopleAttributes"/>
            </div>
            <div class="form-group">
                <label for="media-keywords">Keywords</label>
                <input id="media-keywords" class="form-control" type="text" v-model="form.keywords" name="keywords">
                <has-error :form="form" field="keywords"/>
            </div>
            <div class="form-group">
                <label for="media-source">Source</label>
                <input id="media-source" class="form-control" type="text" v-model="form.source" name="source">
                <has-error :form="form" field="source"/>
            </div>
            <div class="form-group">
                <label for="media-supplier">Supplier</label>
                <v-select id="media-supplier" :options="suppliersOptions" v-model="form.supplier" taggable></v-select>
                <has-error :form="form" field="supplier"/>
            </div>
            <div class="form-group">
                <label for="">License</label>
                <button type="button" class="btn btn-success brn-xs" @click="showLicenseModal = true">{{ media.license
                  ? media.license.type + ' - license'
                  : 'Set license' }}
                </button>
            </div>
            <div v-if="media.license" class="form-group">
                <button class="btn btn-primary" type="submit">Submit</button>
            </div>
        </form>
        <set-license-modal-component :show.sync="showLicenseModal" :media="media"></set-license-modal-component>
    </card>
</template>

<script type="text/babel">
  import axios from 'axios';
  import Vform from 'vform';
  import SetLicenseModalComponent from './SetLicenseModalComponent.vue';

  export default {
    middleware: ['auth', 'subscribed'],
    components: {
      SetLicenseModalComponent,
    },
    name: 'edit-media-details',
    props: {
      media: {
        'default': undefined
      }
    },
    created() {
      this.getCategories();
      this.getPeopleAttributes();
      this.getSuppliers();
      this.getTypes();
      this.setValues(this.media);
    },
    data: () => ({
      showLicenseModal: false,
      categories: [],
      peopleAttributes: [],
      suppliers: [],
      types: [],
      form: new Vform({
        title: '',
        originName: '',
        fileType: '',
        keywords: '',
        category: '',
        peopleAttributes: '',
        supplier: '',
        language: '',
        source: '',
      }),
    }),
    watch: {
      media(value) {
        this.setValues(value);
      }
    },
    methods: {
      setValues(value) {
        this.form.title = value.title;
        this.form.originName = value.origin_name;
        this.form.fileType = value.fileType;
        this.form.keywords = value.keywords;
        if (value.category) {
          this.form.category = { label: value.category.name, value: value.category.id };
        }
        this.form.language = value.language;
        this.form.source = value.source;
      },
      async getCategories() {
        let { data } = await axios.get('/api/brands/'+this.brand.id+'/categories');
        this.categories = data;
      },
      async submitMedia(media) {
        await this.$store.dispatch('media/submitUpload', {media, form: this.form});
        this.form.reset();
        this.$emit('submitted');
      },
      getPeopleAttributes() {
        axios.get('/api/brands/'+this.brand.id+'/people-attributes').then(({ data }) => {
          this.peopleAttributes = data;
        });
      },
      getSuppliers() {
        axios.get('/api/brands/'+this.brand.id+'/suppliers').then(({ data }) => {
          this.suppliers = data;
        });
      },
      getTypes() {
        axios.get('/api/medias/types').then(({ data }) => {
          this.types = data;
        });
      }
    },
    computed: {
      brand() {
        return this.$store.getters['auth/brand'] ? this.$store.getters['auth/brand'] : this.$store.getters['creative/selectedBrand'];
      },
      peopleAttributesOptions() {
        return this.peopleAttributes ? this.peopleAttributes.map((el) => {
          return { label: el.name, value: el.id };
        }) : [];
      },
      categoriesOptions() {
        return this.categories ? this.categories.map((el) => {
          return { label: el.name, value: el.id };
        }) : [];
      },
      suppliersOptions() {
        return this.suppliers ? this.suppliers.map((el) => {
          return { label: el.name, value: el.id };
        }) : [];
      }
    }
  };
</script>