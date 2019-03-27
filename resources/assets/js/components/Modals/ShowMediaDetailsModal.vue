<template>
    <modal v-bind:mediaModal="true" v-bind:show="show">
        <modal-header @close="onClose">Media Details</modal-header>
        <modal-body v-if="media[0]">
            <div class="row mb-4">

                <div class="col-lg-4 flex-column">
                    <show-media-details v-bind:media="media[0]" v-bind:submit="false"></show-media-details>
                    <div class="row mb-2 mt-2">
                        <div class="col-lg-12">
                            <router-link v-if="canAccess(media[0]) && canEdit(media[0])" class="btn btn-primary btn-block"
                                         :to="{ name: 'medias.edit', params: {id: media[0].id} }">{{ $t('edit') }}
                            </router-link>
                            <button v-if="canAccess(media[0]) && canShare()" class="btn btn-primary btn-block"
                                    @click="shareMediaObjects = [media[0].id]; showShareModal = true">{{
                                $t('share') }}
                            </button>
                            <a v-if="canAccess(media[0]) && canDownload()"
                               @click="downloadSelectedMedia(media[0].id)"
                               class="btn btn-primary btn-block">{{ $t('download') }}</a>
                        </div>
                    </div>

                    <div class="card mb-2">
                        <div class="card-header dashboard-card">
                            Meta Data
                        </div>
                        <div class="card-body" v-if="media[0].imageInfo">
                            <table class="widget-table upload-ftp">
                                <tr>
                                    <td class="label">File info</td>
                                    <td>{{ media[0].imageInfo.width }}px x {{ media[0].imageInfo.height }}px</td>
                                </tr>
                                <tr>
                                    <td class="label"> File size</td>
                                    <td>{{ media[0].imageInfo.fileSize }}</td>
                                </tr>
                                <tr>
                                    <td class="label"> Uploaded</td>
                                    <td>{{ media[0].uploadedAt }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="card mb-2" v-if="media[0].notes && media[0].notes.length">
                        <div class="card-header dashboard-card">
                            Note
                        </div>
                        <div class="card-body">
                            {{ media[0].notes }}
                        </div>
                    </div>

                </div>


                <div class="col-lg-8 flex-column">
                    <video-image-component
                            v-bind:media="media[0]"
                            v-bind:stopPlayer="show">
                    </video-image-component>
                </div>

            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <table class="table">
                            <thead>
                            <tr v-if="media[0].licenses && media[0].licenses[0]">
                                <th>
                                    License Type
                                </th>
                                <th v-show="media[0].licenses[0].license_type == 2">
                                    Usage
                                </th>
                                <th v-show="[1,2].indexOf(parseInt(media[0].licenses[0].license_type)) != -1">
                                    Printrun
                                </th>
                                <th v-show="media[0].licenses[0].license_type == 4">
                                    Any Limitations
                                </th>
                                <th v-show="media[0].licenses[0].license_type == 3">
                                    Territory
                                </th>
                                <th>
                                    Expiration
                                </th>
                                <th>
                                    Invoice Number
                                </th>
                                <th>
                                    Invoice
                                </th>
                                <th></th>
                            </tr>
                            </thead>


                            <tbody v-if="media[0].licenses && media[0].licenses[0]">
                            <tr v-if="media[0].licenses.length > 0" v-for="license in usageLicenses">
                                <td>
                                    <span :style="{color: media[0].license.color}"
                                          class="license-badge" @click="showLicenseModal = true">
                                        {{ media[0].licenses[0] ? media[0].licenses[0].type : '-' }}
                                    </span>
                                </td>
                                <td v-show="media[0].licenses[0].license_type == 2">
                                    {{ license.usage && license.usage !== 'null' ? license.usage : '-' }}
                                </td>
                                <td v-show="[1,2].indexOf(parseInt(media[0].licenses[0].license_type)) != -1">
                                    {{ license.printrun && license.printrun !== 'null' ? license.printrun : '-' }}
                                </td>
                                <td v-show="media[0].licenses[0].license_type == 4">
                                    {{ license.any_limitations && license.any_limitations !== 'null' ?
                                    license.any_limitations : '-' }}
                                </td>
                                <td v-show="media[0].licenses[0].license_type == 3">
                                    {{ license.territory && license.territory !== 'null' ? license.territory : '-' }}
                                </td>
                                <td v-color-license:color="license">
                                    {{ license.expiredAt && license.expiredAt !== 'null' ? license.expiredAt.dMY : '-'
                                    }}
                                </td>
                                <td>
                                    {{ license.invoiceNumber && license.invoiceNumber !== 'null' ? license.invoiceNumber
                                    : '-' }}
                                </td>
                                <td>
                                    <div v-if="license.billFile" class="row">
                                        <a :href="license.url" class="btn btn-link btn block">
                                            {{ license.billFileOriginName }}
                                        </a>
                                    </div>
                                </td>
                                <td v-if="canAccess(media[0]) && canEdit(media[0])" class="text-right">
                                    <button type="button"
                                            class="btn btn-primary btn-sm"
                                            v-on:click="showModal(license)"
                                            v-on:close="onClose">
                                        {{ $t('edit') }}
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <button @click="showModal()" v-if="canEdit()" class="btn btn-primary mt-2"> Add new license</button>
                </div>
            </div>


            <div v-if="media[0].licenses && media[0].licenses[0]">
                <set-usage-license-modal
                        v-if="media[0].licenses"
                        :show.sync="showLicenseModal"
                        :parentLicense="media[0].licenses[0]"
                        :license="license"
                        @saved="savedLicense"
                ></set-usage-license-modal>
            </div>

            <share-media-modal-component
                    v-if="media[0]"
                    v-bind:medias="shareMediaObjects"
                    v-bind:show="showShareModal"
                    @close="showShareModal = false"
            ></share-media-modal-component>

        </modal-body>
    </modal>
</template>

<script>
    import axios from 'axios';
    import Modal from '../../components/Modal/ModalLarge.vue';
    import ModalHeader from '../../components/Modal/ModalHeader.vue';
    import ModalBody from '../../components/Modal/ModalBody.vue';
    import SetUsageLicenseModal from '../../components/Modals/SetUsageLicenseModal.vue';
    import {mapGetters} from 'vuex';
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
            SetLicenseModalComponent,
            SetUsageLicenseModal
        },

        mixins: [CheckCreativePermission],

        computed: mapGetters({
            user: 'auth/user',
            selectedBrand: 'creative/selectedBrand',
            media: 'media/selectedMedia',
        }),

        watch: {
            show() {
                this.media;
                this.setLicenses();
            },
            showLicenseModal() {
                this.media;
                this.setLicenses();
            }
        },

        data: () => ({
            shareMediaObjects: [],
            showShareModal: false,
            showLicenseModal: false,
            licenseTypes: false,
            license: false,
            parentLicense: false,
            selectedMedia: [],
            usageLicenses: [],
            mainLicense: null
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
                this.clearAll();
            },

            /**
             * clear all
             */
            clearAll() {
                this.$store.dispatch('media/resetSelectedMedia');
            },

            showModal(license) {
                if (typeof license === "undefined") {
                    this.license = null;
                } else {
                    this.license = license;
                }
                if (this.media[0].licenses && this.media[0].licenses[0]) {
                    this.parentLicense = this.media[0].licenses[0];
                }
                this.showLicenseModal = true;
            },

            setLicenses() {
                if (this.media[0]) {
                    if (this.media[0].licenses[0]) {
                        this.mainLicense = this.media[0].licenses[0];
                    }
                    axios.get('/api/medias/' + this.media[0].id).then(({data}) => {
                        if (data.licenses) {
                            if (data.licenses[0] && data.licenses[0].usageLicenses) {
                                this.usageLicenses = data.licenses[0].usageLicenses;
                            }
                        }
                    });
                }

            },

            savedLicense(licenses) {
                if (this.media[0]) {
                    axios.get('/api/medias/' + this.media[0].id).then(({data}) => {
                        if (data.licenses && data.licenses[0] && data.licenses[0].usageLicenses) {
                            this.usageLicenses = data.licenses[0].usageLicenses;
                        }
                    });
                }

                this.license = null;
                this.$emit('update:show', false)
            },

            /**
             * download
             */
            downloadSelectedMedia(id) {
                axios.get('/api/medias/download-multiple', {params: {media: [id]}}).then((response) => {
                    window.open(response.data.url);
                });
            },
        },

        directives: {
            ColorLicensesDirective
        }
    }
</script>
