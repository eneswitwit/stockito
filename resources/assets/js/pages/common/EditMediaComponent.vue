<template>
    <div v-if="media" class="card">
        <div class="card-body">
            <form @submit.prevent="saveMedia">
                <div class="row mb-4">
                    <div class="col-lg-3">
                        <card>
                            <div class="form-group">
                                <label for="media-origin-name">File Name</label>
                                <input id="media-origin-name" class="form-control" type="text" v-model="form.originName"
                                       name="originName">
                                <has-error :form="form" field="originName"/>
                            </div>
                            <div class="form-group">
                                <label for="media-file-type">File type</label>
                                <input id="media-file-type" class="form-control" type="text" v-model="form.fileType" name="fileType">
                                <has-error :form="form" field="fileType"/>
                            </div>
                            <div class="form-group">
                                <label for="media-category">Category</label>
                                <v-select id="media-category" :options="categoriesOptions" v-model="form.category" taggable></v-select>
                                <has-error :form="form" field="category"/>
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
                                <button v-color-license:background.border="media.licenses[0]"
                                         type="button" class="btn btn-success brn-xs" @click="showLicenseModal = true">{{ media.license
                                  ? media.license.type + ' - license'
                                  : 'Set license' }}
                                </button>
                            </div>
                            <set-license-modal-component :show.sync="showLicenseModal" :media="media" :license="media.license"></set-license-modal-component>
                        </card>
                    </div>
                    <div class="col-lg-7 flex-column">
                        <video-image-component v-bind:media="media"></video-image-component>
                    </div>
                    <div class="col-lg-2">
                        <div class="row mb-4">
                            <div class="col-lg-12">
                                <router-link class="btn btn-primary btn-block" :to="{ name: 'medias.show', props: { id: media.id }}">{{ $t('show') }}</router-link>
                                <button @click.prevent="saveMedia" class="btn btn-success btn-block">{{ $t('save') }}</button>
                            </div>
                        </div>
                        <div v-if="media.license && media.license.billFile" class="row">
                            <div class="col-lg-12 text-center">
                                <b>{{ $t('bill') }}</b>
                                <a :href="media.license.url" class="btn btn-link btn block">{{ media.license.billFileOriginName }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-3">
                        <card title="Meta data">
                            <table>
                                <tr>
                                    <td><b>File info:</b></td>
                                    <td>{{ media.imageInfo.width }}px x {{ media.imageInfo.height }}px</td>
                                </tr>
                                <tr>
                                    <td><b>File Size</b></td>
                                    <td>{{ media.imageInfo.fileSize }}</td>
                                </tr>
                                <tr>
                                    <td><b>Uploaded:</b></td>
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
                                          placeholder="This is a note concerning this upload.~~Futurama">
                                </textarea>
                                <has-error :form="form" field="notes"/>
                            </div>
                        </card>
                    </div>
                </div>
            </form>
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
    components: {
      SetLicenseModalComponent,
      Card,
      VideoImageComponent,
    },
    name: 'edit-media-component',
    async created() {
      await this.$store.dispatch('media/fetchMedia', this.$route.params.id);
      this.getCategories();
      this.setValues(this.media);
    },
    data: () => ({
      categories: [],
      showLicenseModal: false,
      form: new Vform({
        id: '',
        originName: '',
        fileType: '',
        category: '',
        keywords: '',
        source: '',
        notes: ''
      })
    }),
    computed: {
      media() {
        return this.$store.getters['media/media'];
      },
      categoriesOptions() {
        return this.categories.map((el) => {
          return { label: el.name, value: el.id }
        });
      },
      suppliersOptions() {
        return this.suppliers ? this.suppliers.map((el) => {
          return { label: el.name, value: el.id };
        }) : [];
      }
    },
    methods: {
      saveMedia() {
        this.$store.dispatch('media/updateMedia', { form: this.form }).then(() => {
          this.$swal('Successful updated', '', 'success');
        });
      },
      setValues(value) {
        this.form.id = value.id;
        this.form.originName = value.origin_name;
        this.form.fileType = value.fileType;
        this.form.keywords = value.keywords;
        this.form.notes = value.notes;
        if (value.category) {
          this.form.category = { label: value.category.name, value: value.category.id};
        }
        this.form.language = value.language;
        this.form.source = value.source;
      },
      async getCategories() {
        let { data } = await axios.get('/api/medias/categories');
        this.categories = data;
      },
    },
    directives: {
        ColorLicensesDirective
    }
  }
</script>