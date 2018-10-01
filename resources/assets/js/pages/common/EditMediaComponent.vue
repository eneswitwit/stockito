<template>
    <div class="container-fluid mt-4">
        <div v-if="media" class="card">
            <div class="card-body">
                <form @submit.prevent="saveMedia">
                    <div class="row mb-4">
                        <div class="col-lg-3">
                            <card>

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
                                        <option :value="'No people'" selected> No people</option>
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

                                <div class="form-group">
                                    <label>License</label>
                                    <button v-color-license:background.border="media.licenses[0]"
                                            type="button"
                                            class="btn btn-success brn-xs"
                                            @click="showLicenseModal = true">
                                        {{ media.license ? media.license.type + ' - license' : 'Set license' }}
                                    </button>
                                </div>

                                <set-license-modal-component :show.sync="showLicenseModal"
                                                             :selectedMedia="selectedMedia"
                                                             :media="media"
                                                             :license="media.license"
                                ></set-license-modal-component>

                            </card>
                        </div>

                        <div class="col-lg-7 flex-column">
                            <video-image-component v-bind:media="media"></video-image-component>
                        </div>

                        <div class="col-lg-2">
                            <div class="row mb-4">
                                <div class="col-lg-12">

                                    <router-link class="btn btn-primary btn-block"
                                                 :to="{ name: 'medias.show', props: { id: media.id }}">{{ $t('show') }}
                                    </router-link>
                                    <button @click.prevent="saveMedia" class="btn btn-primary btn-block">
                                        {{ $t('save') }}
                                    </button>

                                </div>
                            </div>

                            <div v-if="media.license && media.license.billFile" class="row">
                                <div class="col-lg-12 text-center">
                                    <b>{{ $t('bill') }}</b>
                                    <a :href="media.license.url" class="btn btn-primary btn block">{{
                                        media.license.billFileOriginName }}</a>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-lg-3">
                            <card title="Meta data">
                                <table class="widget-table">
                                    <tr>
                                        <td><b>File info</b></td>
                                        <td>{{ media.imageInfo.width }}px x {{ media.imageInfo.height }}px</td>
                                    </tr>
                                    <tr>
                                        <td><b>File size</b></td>
                                        <td>{{ media.imageInfo.fileSize }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Uploaded</b></td>
                                        <td>{{ media.uploadedAt }}</td>
                                    </tr>
                                </table>
                            </card>
                        </div>

                        <div class="col-lg-9">
                            <card title="Note">
                                <div class="form-group">

                                <textarea class="form-control"
                                          v-model="form.notes"
                                          placeholder="Write a note concerning this upload">
                                </textarea>
                                    <has-error :form="form" field="notes"/>
                                </div>
                            </card>
                        </div>

                    </div>
                </form>
            </div>
        </div>
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
                return this.categories.map((el) => {
                    return {label: el.name, value: el.id}
                });
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
                if (value.category) {
                    this.form.category = {label: value.category.name, value: value.category.id};
                }
                this.form.peoplesAttribute = value.peoples_attribute;
                this.form.language = value.language;
                this.form.source = value.source;
                this.form.supplier = value.supplier.name;
            },
            async getCategories() {
                let {data} = await axios.get('/api/medias/categories');
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