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
                                                     :to="{ name: 'medias.edit', params: {id: media.id} }">
                                            {{ $t('edit') }}
                                        </router-link>
                                        <button v-if="canAccess(media)|| isActiveEditing()"
                                                class="btn btn-primary btn-block"
                                                @click="shareMediaObjects = [media.id]; showShareModal = true">
                                            {{$t('share') }}
                                        </button>
                                        <a v-if="canAccess(media) || isActiveEditing()" :href="media.downloadLink"
                                           class="btn btn-primary btn-block">
                                            {{ $t('download') }}
                                        </a>
                                    </div>
                                </div>

                                <div class="card mb-2">
                                    <div class="card-header dashboard-card">
                                        Meta Data
                                    </div>
                                    <div class="card-body">
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
                                    </div>
                                </div>

                                <div class="card mb-2" v-if="media.notes && media.notes.length">
                                    <div class="card-header dashboard-card">
                                        Note
                                    </div>
                                    <div class="card-body">
                                        {{ media.notes }}
                                    </div>
                                </div>
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
                                            <th v-show="media.licenses[0].license_type == 2">
                                                Usage
                                            </th>
                                            <th v-show="[1,2].indexOf(parseInt(media.licenses[0].license_type)) != -1">
                                                Printrun
                                            </th>
                                            <th v-show="media.licenses[0].license_type == 4">
                                                Any Limitations
                                            </th>
                                            <th v-show="media.licenses[0].license_type == 3">
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
                                        <tbody>


                                        <tr v-if="media.licenses.length > 0"
                                            v-for="license in media.licenses[0].usageLicenses">

                                            <td v-show="media.licenses[0].license_type == 2">
                                                {{ license.usage ? license.usage : '-' }}
                                            </td>
                                            <td v-show="[1,2].indexOf(parseInt(media.licenses[0].license_type)) != -1">
                                                {{ license.printrun || license.printrun === "null" ? license.printrun :
                                                '-' }}
                                            </td>
                                            <td v-show="media.licenses[0].license_type == 4">
                                                {{ license.any_limitations }}
                                            </td>
                                            <td v-show="media.licenses[0].license_type == 3">
                                                {{ license.territory }}
                                            </td>
                                            <td v-color-license:color="license">
                                                {{ license.expiredAt ? license.expiredAt.dMY : '-' }}
                                            </td>
                                            <td>
                                                {{ license.invoiceNumber ? license.invoiceNumber : '' }}
                                            </td>
                                            <td>
                                                <div v-if="license.billFile" class="row">
                                                    <a :href="license.url" class="btn btn-link btn block">
                                                        {{ license.billFileOriginName }}
                                                    </a>
                                                </div>
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
                                    <button @click="showModal()" class="btn btn-primary">
                                        Add new license
                                    </button>
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