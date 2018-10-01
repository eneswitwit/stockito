<template>
    <div class="container mt-4 mb-4">
        <div class="card">
            <div class="card-body nopadding">
                <div class="row">
                    <div v-if="!!media" class="col-lg-12">
                        <div class="row mb-2">

                            <div class="col-lg-4 flex-column">
                                <show-media-details v-bind:media="media" v-bind:submit="false"></show-media-details>
                                <div class="row mb-2 mt-2">
                                    <div class="col-lg-12">
                                        <router-link v-if="canAccess(media)" class="btn btn-primary btn-block"
                                                     :to="{ name: 'medias.edit', params: {id: media.id} }">{{ $t('edit') }}
                                        </router-link>
                                        <button v-if="canAccess(media)|| isActiveEditing()" class="btn btn-primary btn-block"
                                                @click="shareMediaObjects = [media.id]; showShareModal = true">{{
                                            $t('share') }}
                                        </button>
                                        <a v-if="canAccess(media) || isActiveEditing()" :href="media.downloadLink"
                                           class="btn btn-primary btn-block">{{ $t('download')
                                            }}</a>
                                    </div>
                                </div>
                                <card class="mb-2" title="Meta data">
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

                                <card class="mb-2" title="Note" v-if="media.notes && media.notes.length">
                                    {{ media.notes }}
                                </card>

                                <card title="License Bill" v-if="media.license">
                                    <div v-if="media.license.billFile" class="row">
                                        <div class="col-lg-12 text-center">
                                            <a :href="media.license.url" class="btn btn-link btn block">
                                                {{ media.license.billFileOriginName }}
                                            </a>
                                        </div>

                                    </div>
                                </card>
                            </div>

                            <div class="col-lg-8 flex-column">
                                <video-image-component v-bind:media="media"></video-image-component>
                            </div>

                        </div>

                        <div v-if="media.licenses && media.licenses.length" class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Usage</th>
                                            <th>Printrun</th>
                                            <th>Expiration</th>
                                            <th>Invoice</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="license in media.licenses">
                                            <td>{{ license.usage ? license.usage : '-' }}</td>
                                            <td>{{ license.printrun ? license.printrun : '-' }}</td>
                                            <td v-color-license:color="license">
                                                {{ license.expiredAt ? license.expiredAt.dMY : '-' }}
                                            </td>
                                            <td>
                                                {{ license.invoiceNumber ? license.invoiceNumber : '' }}
                                            </td>
                                            <td class="text-right">
                                                <button type="button"
                                                        class="btn btn-primary btn-sm"
                                                        @click="showLicense(license)">
                                                    {{ $t('edit') }}
                                                </button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <set-license-modal-component
                            :show.sync="showLicenseModal"
                            :media="media"
                            :license="this.currentLicense"
                            :selectedMedia="selectedMedia">
                        {{ $t('edit') }}
                    </set-license-modal-component>
                    <share-media-modal-component
                            v-if="media"
                            :medias="shareMediaObjects"
                            :show="showShareModal"
                            @close="showShareModal = false">
                    </share-media-modal-component>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import ShowMediaDetails from './parts/ShowMediaDetails.vue';
    import Card from '../../components/Card.vue';
    import ShareMediaModalComponent from '../../components/Modals/ShareMediaModalComponent.vue';
    import CheckCreativePermission from './parts/services/CheckCreativePermissionService';
    import ColorLicensesDirective from '../../directives/ColorLicensesDirective';
    import VideoImageComponent from '../../pages/common/parts/VideoImageComponent.vue';
    import SetLicenseModalComponent from './parts/SetLicenseModalComponent.vue';

    Vue.directive('color-license', ColorLicensesDirective);

    export default {
        components: {
            ShareMediaModalComponent,
            Card,
            ShowMediaDetails,
            VideoImageComponent,
            SetLicenseModalComponent
        },
        mixins: [CheckCreativePermission],
        name: 'show-media-component',
        created() {
            axios.get('/api/medias/' + this.$route.params.id).then(response => {
                this.media = response.data;
                this.selectedMedia = [this.media.id];
            });

        },
        data: () => ({
            showLicenseModal: false,
            currentLicense: null,
            shareMediaObjects: [],
            media: undefined,
            showShareModal: false,
            selectedMedia: []
        }),
        directives: {
            ColorLicensesDirective
        },
        methods: {
            showLicense(license) {
                this.currentLicense = license;
                this.showLicenseModal = true;
            }
        }
    }
</script>