<template>
    <card class="mb-4" v-if="selectedMedia.length > 0">


        <div class="alert alert-info text-center" v-if="selectedMedia.length > 1">
            You are editing {{ selectedMedia.length }} files at the same time
        </div>

        <form  @submit.prevent="uploadMultiple" v-if="selectedMedia.length === 1">
        <!-- v-on:submit.prevent="onSubmit" -->
            <div class="form-group">
                <label for="media-origin-name">Title</label>
                <input id="media-title" class="form-control" type="text" v-model="form.title" name="title"
                       :class="{ 'is-invalid': form.errors.has('title') }" @keydown.enter = "noEnter">
                <has-error :form="form" field="title"/>
            </div>

            <div class="form-group">
                <label for="media-origin-name">File Name</label>
                <input id="media-origin-name" class="form-control" type="text" v-model="form.originName"
                       name="originName" @keydown.enter = "noEnter">
                <has-error :form="form" field="originName"/>
            </div>

            <div class="form-group">
                <label for="media-file-type">File type</label>
                <select class="form-control" v-model="form.fileType" name="fileType" id="media-file-type">
                    <option value="" disabled selected hidden> Choose your file type... </option>
                    <option v-for="(type, key) in types" :value="key">{{ type }}</option>
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
                <label for="media-people-attributes">People Attributes</label>
                <v-select id="media-people-attributes"
                          :options="['No people','With people']"
                          v-model="form.peopleAttributes"
                ></v-select>
                <has-error :form="form" field="peopleAttributes"/>
            </div>

            <div class="form-group">
                <label for="media-keywords">Keywords</label>
                <input id="media-keywords" data-role="tagsinput" class="form-control" type="text"
                       v-model="form.keywords" name="keywords" :class="{ 'is-invalid': form.errors.has('keywords') }"
                       @keydown.enter = "noEnter">
                <has-error :form="form" field="keywords"/>
            </div>

            <div class="form-group">
                <label for="media-source">Artist/Copyright</label>
                <input id="media-source" class="form-control" type="text" v-model="form.source" name="source"
                       @keydown.enter = "noEnter">
                <has-error :form="form" field="source"/>
            </div>

            <div class="form-group">
                <label for="media-supplier">Supplier</label>
                <v-select id="media-supplier" :options="suppliersOptions" v-model="form.supplier"
                          taggable></v-select>
                <has-error :form="form" field="supplier"/>
            </div>

            <div class="form-group">
                <label for="">License</label>
                <button type="button" class="btn btn-primary brn-xs float-right" @click="showLicenseModal = true">
                    {{ media.license ? media.license.type : 'Set license' }}
                </button>
            </div>

            <div v-if="media.license" class="form-group">
                <input class="btn btn-primary" value="Submit" type="submit">
            </div>

        </form>


        <form action="" method="post" @submit.prevent="uploadMultiple" v-else-if="selectedMedia.length > 1">

            <div class="form-group">
                <label for="media-origin-name">Title</label>
                <input id="media-title-2" class="form-control" type="text" v-model="form.title" name="title"
                       :class="{ 'is-invalid': form.errors.has('title') }" @keydown.enter = "noEnter">
                <has-error :form="form" field="title"/>
            </div>

            <div class="form-group">
                <label for="media-origin-name">File Name</label>
                <input id="media-origin-name" class="form-control" type="text" v-model="form.multiTitle"
                       name="originName" readonly="readonly">
                <has-error :form="form" field="originName"/>
            </div>

            <div class="form-group">
                <label for="media-file-type">File type</label>
                <select class="form-control" v-model="form.fileType" name="fileType" id="media-file-type">
                    <option value="" disabled selected hidden> Choose your file type... </option>
                    <option v-for="(type, key) in types" :value="key">{{ type }}</option>
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
                <label for="media-people-attributes">People Attributes</label>
                <v-select id="media-people-attributes"
                          :options="['No people','With people']"
                          v-model="form.peopleAttributes"
                ></v-select>
                <has-error :form="form" field="peopleAttributes"/>
            </div>

            <div class="form-group">
                <label for="media-keywords">Keywords</label>
                <input id="media-keywords" data-role="tagsinput" class="form-control" type="text"
                       v-model="form.keywords" name="keywords" :class="{ 'is-invalid': form.errors.has('keywords') }"
                       @keydown.enter = "noEnter">
                <has-error :form="form" field="keywords"/>
            </div>

            <div class="form-group">
                <label for="media-source">Artist/Copyright</label>
                <input id="media-source" class="form-control" type="text" v-model="form.source" name="source"
                       @keydown.enter = "noEnter">
                <has-error :form="form" field="source"/>
            </div>

            <div class="form-group">
                <label for="media-supplier">Supplier</label>
                <v-select id="media-supplier" :options="suppliersOptions" v-model="form.supplier"
                          taggable></v-select>
                <has-error :form="form" field="supplier"/>
            </div>

            <div class="form-group">
                <label for="license-label">License</label>
                <button type="button" class="btn btn-primary brn-xs float-right" @click="showLicenseModal = true">
                    {{ media.license ? media.license.type : 'Set license' }}
                </button>
            </div>

            <div v-if="licenseSet" class="form-group">
                <input class="btn btn-primary" value="Submit" type="submit">
            </div>

        </form>

        <set-license-modal-component
                :show.sync="showLicenseModal"
                :media="media"
        ></set-license-modal-component>

    </card>
</template>

<script type="text/babel">

    import axios from 'axios';
    import Vform from 'vform';
    import SetLicenseModalComponent from './SetLicenseModalComponent.vue';


    export default {
        xmiddleware: ['auth', 'subscribed'],

        components: {
            SetLicenseModalComponent,
        },

        name: 'edit-media-details',

        props: {
            media: {
                'default': undefined
            },
            getMedias: {
                'default': function () {
                }
            },
            clearAll: {
                'default': function () {
                }
            },
            refreshList: {
                'default': function () {
                }
            },
            medias: {
                'default': undefined
            }
        },

        created() {
            this.getCategories();
            this.getPeopleAttributes();
            this.getSuppliers();
            this.getTypes();
            this.setValues(this.media);
            this.checkLicenseIsSet();
        },

        data: () => ({
            showLicenseModal: false,
            categories: [],
            suppliers: [],
            types: [],
            selectedMedias: [],
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
                orientation: '',
                multiTitle: 'Will be set automatically',
                mediaId: []
            }),
            selected: '',
            licenseSet: false
        }),

        watch: {

            media(value) {
                this.setValues(value);
            },
            selectedMedia() {
                this.setMultipleValues();
                this.checkLicenseIsSet();
            },
            showLicenseModal() {
                this.checkLicenseIsSet();
            },

            licenseSet() {
                this.checkLicenseIsSet();
            }
        },

        computed: {

            selectedMedia() {
                return this.$store.getters['media/selectedMedia'];
            },
            brand() {
                return this.$store.getters['auth/brand'] ? this.$store.getters['auth/brand'] : this.$store.getters['creative/selectedBrand'];
            },
            peopleAttributesOptions() {
                return this.peopleAttributes ? this.peopleAttributes.map((el) => {
                    return {label: el.name, value: el.id};
                }) : [];
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
            }
        },

        methods: {

            async uploadMultiple() {
                this.form.mediaId = this.getSelectedMediaArray();
                try {
                    await this.$store.dispatch('media/submitMultipleUpload', {form: this.form});
                    this.$emit('submitted');
                    this.form.reset();
                    this.clearAll();
                    this.selectedMedia = [];
                }
                catch (err) {
                }
            },

            setMultipleValues() {
                let value = [];
                value['title'] = null;
                value['origin_name'] = null;
                value['fileType'] = null;
                value['keywords'] = null;
                value['category'] = null;
                value['source'] = null;
                value['language'] = null;

                if (this.selectedMedia.length > 0) {
                    this.selectedMedia.forEach(function (mediaElement) {
                        if (mediaElement.title) {
                            if (value['title'] === null) {
                                value['title'] = mediaElement.title;
                            } else {
                                if (!value['title'].includes(mediaElement.title)) {
                                    value['title'] = value['title'] + ',' + mediaElement.title;
                                }
                            }
                        }
                        if (mediaElement.origin_name) {
                            value['origin_name'] = mediaElement.origin_name;
                        }
                        if (mediaElement.fileType) {
                            value['fileType'] = mediaElement.fileType;
                        }
                        if (mediaElement.keywords) {
                            if (value['keywords'] === null) {
                                value['keywords'] = mediaElement.keywords;
                            } else {
                                if (!value['keywords'].includes(mediaElement.keywords)) {
                                    value['keywords'] = value['keywords'] + ',' + mediaElement.keywords;
                                }
                            }
                        }
                        if (mediaElement.category) {
                            value['category'] = mediaElement.category.id;
                        }
                        if (mediaElement.source) {
                            if (value['source'] === null) {
                                value['source'] = mediaElement.source;
                            } else {
                                if (!value['source'].includes(mediaElement.source)) {
                                    value['source'] = value['source'] + ',' + mediaElement.source;
                                }
                            }
                        }
                        value['language'] = null;
                    });
                    this.setValues(value);
                }
            },

            setValues(value) {
                this.form.title = value.title;
                this.form.originName = value.origin_name;
                this.form.fileType = value.fileType;
                this.form.keywords = value.keywords;
                if (value.category) {
                    this.form.category = {label: value.category.name, value: value.category.id};
                }
                if (value.supplier) {
                    this.form.supplier = value.supplier;
                }
                this.form.language = value.language;
                this.form.source = value.source;

            },

            async getCategories() {
                let {data} = await axios.get('/api/brands/' + this.brand.id + '/categories');
                this.categories = data;
            },

            async submitMedia(media) {

                try {
                    const {data} = await this.$store.dispatch('media/submitUpload', {media: media, form: this.form});
                    this.form.reset();
                    this.$emit('submitted');
                    this.clearAll();
                }
                catch (err) {
                }

            },

            async submitMultipleMedia() {
                let mediaIdSlug = this.arrayToSlug(this.getSelectedMediaArray());
                await this.$store.dispatch('media/submitMultipleUpload', {mediaArray: mediaIdSlug, form: this.form});
                this.form.reset();
                this.$emit('submitted');
            },

            getPeopleAttributes() {
                axios.get('/api/brands/' + this.brand.id + '/people-attributes').then(({data}) => {
                    this.peopleAttributes = data;
                });
            },

            getSuppliers() {
                axios.get('/api/brands/' + this.brand.id + '/suppliers').then(({data}) => {
                    this.suppliers = data;
                });
            },

            getTypes() {
                axios.get('/api/medias/types').then(({data}) => {
                    console.log(data);
                    this.types = data;
                });
            },

            checkLicenseIsSet() {
                var isSet = true;
                this.selectedMedia.forEach(function (media) {
                    if (!media.license) {
                        isSet = false;
                    }
                });
                this.licenseSet = isSet;
            },

            getSelectedMediaArray() {
                var mediaSubmit = [];
                this.selectedMedia.forEach(function(media) {
                    mediaSubmit.push(media.id);
                });
                return mediaSubmit;
            },

            noEnter(e) {
                e.preventDefault();
                return false;
            }

        }
    };
</script>