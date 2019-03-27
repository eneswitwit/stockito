<template>
    <div class="container mt-4">

        <form @submit.prevent="saveMedia" v-if="media">
            <div class="row mb-2">
                <div class="col-lg-4">
                    <card :title="'Basic information'" :class="'mb-2'">

                        <div class="form-group">
                            <label for="media-title">Title</label>
                            <input id="media-title" class="form-control" type="text"
                                   v-model="form.title"
                                   name="title">
                            <has-error :form="form" field="title"/>
                        </div>

                        <div class="form-group">
                            <label for="media-origin-name">File Name</label>
                            <input id="media-origin-name" class="form-control" type="text"
                                   v-model="form.originName"
                                   name="originName">
                            <has-error :form="form" field="originName"/>
                        </div>

                        <div class="form-group">
                            <label for="media-file-type">File type</label>
                            <select class="form-control" v-model="form.fileType" name="fileType"
                                    id="media-file-type">
                                <option v-for="(type, key) in types" :value="type">{{ type }}</option>
                            </select>
                            <has-error :form="form" field="fileType"/>
                        </div>

                        <div class="form-group">
                            <label for="media-category">Category</label>
                            <v-select id="media-category" :options="categoriesOptions" v-model="form.category"
                                      taggable></v-select>
                            <has-error :form="form" field="category"/>
                        </div>

                        <div class="form-group">
                            <label for="media-people-attributes">Peoples Attribute</label>
                            <select id="media-people-attributes" class="form-control"
                                    v-model="form.peoplesAttribute">
                                <option :value="'No people'"> No people</option>
                                <option :value="'With people'"> With people</option>
                            </select>
                            <has-error :form="form" field="peoplesAttribute"/>
                        </div>

                        <div class="form-group">
                            <label for="media-keywords">Keywords</label>
                            <input id="media-keywords" class="form-control" type="text" v-model="form.keywords"
                                   name="keywords">
                            <has-error :form="form" field="keywords"/>
                        </div>

                        <div class="form-group">
                            <label for="media-source">Artist/Copyright</label>
                            <input id="media-source" class="form-control" type="text" v-model="form.source"
                                   name="source">
                            <has-error :form="form" field="source"/>
                        </div>

                        <div class="form-group">
                            <label for="media-supplier">Supplier</label>
                            <v-select id="media-supplier" :options="suppliersOptions" v-model="form.supplier"
                                      taggable></v-select>
                            <has-error :form="form" field="supplier"/>
                        </div>

                    </card>

                </div>

                <div class="col-lg-8 flex-column">
                    <video-image-component v-bind:media="media"></video-image-component>
                </div>

            </div>

            <div class="row mb-2">
                <div class="col-md-12">
                    <div class="card mb-2">
                        <div class="card-header">
                            Note
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <textarea class="form-control"
                                          v-model="form.notes"
                                          placeholder="Write a note concerning this upload">
                                </textarea>
                                <has-error :form="form" field="notes"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <button @click.prevent="saveMedia" class="btn btn-primary btn-block">
                        {{ $t('save') }}
                    </button>
                </div>
            </div>

        </form>

    </div>
</template>

<script>

    import Card from '../../components/Card.vue';
    import Vform from 'vform';
    import axios from 'axios';
    import SetLicenseModalComponent from './parts/SetLicenseModalComponent.vue';
    import ColorLicensesDirective from '../../directives/ColorLicensesDirective';
    import VideoImageComponent from '../../pages/common/parts/VideoImageComponent.vue';

    Vue.directive('color-license', ColorLicensesDirective);

    export default {

        middleware: [
            'auth',
            'subscribed'
        ],

        components: {
            SetLicenseModalComponent,
            Card,
            VideoImageComponent,
        },

        name: 'edit-media-component',

        async created() {
            await this.$store.dispatch('media/fetchMedia', this.$route.params.id);
            this.getCategories();
            this.getSuppliers();
            this.getTypes();
            this.setValues(this.media);
            this.selectedMedia = [this.media.id];
        },

        data: () => ({
            categories: [],
            showLicenseModal: false,
            suppliers: [],
            types: [],
            selectedMedia: [],
            form: new Vform({
                id: '',
                title: '',
                originName: '',
                fileType: '',
                category: '',
                peoplesAttribute: '',
                keywords: '',
                source: '',
                supplier: '',
                notes: ''
            })
        }),

        computed: {
            media() {
                return this.$store.getters['media/media'];
            },

            categoriesOptions() {
                return this.categories ? this.categories.map((el) => {
                    return {label: el.name, value: el.id};
                }) : [];
            },

            suppliersOptions() {
                return this.suppliers ? this.suppliers.map((el) => {
                    return {label: el.name, value: el.id};
                }) : [];
            },
            brand() {
                return this.$store.getters['auth/brand'] ? this.$store.getters['auth/brand'] : this.$store.getters['creative/selectedBrand'];
            }
        },

        methods: {
            saveMedia() {
                this.$store.dispatch('media/updateMedia', {form: this.form}).then(() => {
                    this.$swal('Successfully Updated', '', 'success');
                });
            },
            setValues(value) {
                this.form.title = value.title;
                this.form.id = value.id;
                this.form.originName = value.origin_name;
                this.form.fileType = value.fileType;
                this.form.keywords = value.keywords;
                this.form.notes = value.notes;
                if (value.category.id) {
                    this.form.category = {label: value.category.name, value: value.category.id};
                } else {
                    this.form.category = value.category;
                }
                this.form.peoplesAttribute = value.peoples_attribute;
                this.form.language = value.language;
                this.form.source = value.source;
                if(value.supplier.id) {
                    this.form.supplier = value.supplier.name;
                } else {
                    this.form.supplier = value.supplier;
                }
            },

            async getCategories() {
                let {data} = await axios.get('/api/brands/' + this.brand.id + '/categories');
                this.categories = data;
            },

            getSuppliers() {
                axios.get('/api/brands/' + this.brand.id + '/suppliers').then(({data}) => {
                    this.suppliers = data;
                });
            },

            getTypes() {
                axios.get('/api/medias/types').then(({data}) => {
                    this.types = data;
                });
            }

        },

        directives: {
            ColorLicensesDirective
        }
    }
</script>