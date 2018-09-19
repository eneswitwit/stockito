<template>
    <modal v-bind:mediaModal="true" v-bind:show="show">
        <modal-header @close="onClose">Media Details</modal-header>
        <modal-body v-if="media">
            <div class="row mb-4">


                <div class="col-lg-3 flex-column">
                    <show-media-details v-bind:media="media" v-bind:submit="false"></show-media-details>
                </div>


                <div class="col-lg-7 flex-column">
                    <video-image-component
                            v-bind:media="media"
                            v-bind:stopPlayer="show">
                    </video-image-component>
                </div>


                <div class="col-lg-2">
                    <div class="row mb-4">
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
                    <div v-if="media.license && media.license.billFile" class="row">
                        <div class="col-lg-12 text-center">
                            <b>{{ $t('bill') }}</b>
                            <a :href="media.license.url" class="btn btn-link btn block">{{
                                media.license.billFileOriginName }}</a>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row mb-4">
                <div class="col-lg-3 flex-column">
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
                <div class="col-lg-9 flex-column">
                    <card title="Note">
                        {{ media.notes }}
                    </card>
                </div>
            </div>


            <div class="row">
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
                                <td>{{ license.printrun || license.printrun === "null" ? license.printrun : '-' }}</td>
                                <td v-color-license:color="license">{{ license.expiredAt ? license.expiredAt.dMY : '-'
                                    }}
                                </td>
                                <td>{{ license.invoiceNumber  ? license.invoiceNumber : '' }}
                                </td>
                                <td v-if="canAccess(media)" class="text-right">
                                    <button type="button"
                                            class="btn btn-primary btn-sm"
                                            @click="showModal(license)">
                                        {{ $t('edit') }}
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <set-license-modal-component
                    :show.sync="showLicenseModal"
                    :media="media"
                    :license="currentLicense"
                    :selectedMedia="selectedMedia"
            ></set-license-modal-component>

            <share-media-modal-component
                    v-if="media"
                    v-bind:medias="shareMediaObjects"
                    v-bind:show="showShareModal"
                    @close="showShareModal = false"
            ></share-media-modal-component>

        </modal-body>
    </modal>
</template>

<script>
    import Modal from '../../components/Modal/ModalLarge.vue';
    import ModalHeader from '../../components/Modal/ModalHeader.vue';
    import ModalBody from '../../components/Modal/ModalBody.vue';
    import {mapGetters} from 'vuex';
    import axios from 'axios';
    import ShowMediaDetails from '../../pages/common/parts/ShowMediaDetails.vue';
    import Card from '../../components/Card.vue';
    import ShareMediaModalComponent from '../../components/Modals/ShareMediaModalComponent.vue';
    import CheckCreativePermission from '../../pages/common/parts/services/CheckCreativePermissionService';
    import ColorLicensesDirective from '../../directives/ColorLicensesDirective';
    import VideoImageComponent from '../../pages/common/parts/VideoImageComponent.vue';
    import SetLicenseModalComponent from './../../pages/common/parts/SetLicenseModalComponent.vue';

    Vue.directive('color-license', ColorLicensesDirective);


    export default {
        name: 'ShowMediaDetailsModal',
        components: {
            Modal,
            ModalHeader,
            ModalBody,
            ShareMediaModalComponent,
            Card,
            ShowMediaDetails,
            ColorLicensesDirective,
            VideoImageComponent,
            SetLicenseModalComponent
        },
        mixins: [CheckCreativePermission],
        computed: mapGetters({
            user: 'auth/user',
            selectedBrand: 'creative/selectedBrand',
            media: 'media/selectedMedia',
        }),
        data: () => ({
            shareMediaObjects: [],
            showShareModal: false,
            showLicenseModal: false,
            currentLicense: false,
            selectedMedia: []
        }),
        props: {
            mediaId: {},
            show: {
                'default': false
            },
        },
        methods: {
            onClose() {
                this.$emit('close');
            },
            showModal(license) {
                this.selectedMedia = [this.mediaId];
                this.currentLicense = license;
                this.showLicenseModal = true;
            }
        },
        directives: {
            ColorLicensesDirective
        }
    }
</script>

<style scoped>

</style>