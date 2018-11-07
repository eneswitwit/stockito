<template>

    <div>

        <a @click.prevent="showSearch = true">
            <div v-if="!showSearch" class="advanced-search">

                <div class="advanced-search-text">
                    <p>Advanced</p>
                    <p style="font-size: 19px; font-weight: 500;">Search</p>
                </div>

            </div>
        </a>

        <div class="container-fluid">
            <div class="row show-page" v-if="showPage">

                <div v-if="showSearch" class="col-lg-3 nopadding">
                    <search-media-component @closeAdvancedSearch="showSearch = $event"></search-media-component>
                </div>

                <div :class="showSearch ? 'col-lg-9 nopadding' : 'col-lg-12 nopadding'">

                    <card class="mb-4" v-if="selectedMedia.length">
                        <button @click="deleteMultipleMedia" class="btn btn-link">Delete ({{ selectedMedia.length }}
                            files)
                        </button>
                        <button @click="shareMultipleMedia" class="btn btn-link">Share ({{ selectedMedia.length }}
                            files)
                        </button>
                        <button @click="downloadSelectedMedia" class="btn btn-link">Download ({{ selectedMedia.length }}
                            files)
                        </button>
                        <button @click="clearAll" class="btn btn-link float-right">Clear all</button>
                    </card>

                    <div class="row uploaded-media" v-show="!!medias.length">
                        <div class="card media-card" v-for="(media, index) in medias" v-bind:id="media.id">
                            <div class="card-body nopadding">

                                <div class="img-wrapper">
                                    <div class="play-button" @click="showMediaModal(index)"
                                         :class="{play_visible: !checkVideoType(media)}">
                                        <img src="../../../view/player/play-button.png" alt="">
                                    </div>
                                    <div v-if="!isSearchOnly()" class="select-checkbox">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="multipleSelection[]" :value="media.id"
                                                   class="custom-control-input" v-model="selectedMedia"
                                                   :id="'select-checkbox-'+media.id">
                                            <label class="custom-control-label"
                                                   :for="'select-checkbox-'+media.id"></label>
                                        </div>
                                    </div>
                                    <div @click="showMediaModal(index)" class="wrapper-img"
                                         :class="{'wrapper-img-search': showSearch}">
                                        <img class="card-img-top" :src="media.thumbnail" alt="Card image cap">
                                    </div>
                                    <span v-if="media.license" class="license-label"
                                          :style="{color: media.license.color}">{{ media.license.type }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-show="!medias.length" class="row">
                        <div class="container mt-4">
                            <div v-if="isGotMedias" class="col-lg-12">
                                <div class="container mt-4">
                                    <!--You are haven't the media files. Please, <button type="button" class="btn btn-link btn-sm" @click="openedShareMediaModal = true">upload</button> new files.-->
                                    You don't have any media files. You can start uploading by just clicking the upload
                                    button in
                                    the top section of the site.
                                </div>
                            </div>

                            <div v-else-if="!showPage" class="col-lg-12">
                                <loader></loader>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <show-media-details-modal v-bind:show="showDetailsModal" @close="showDetailsModal = false"/>
            <share-media-modal-component v-bind:show="openedShareMediaModal" v-bind:medias="shareMediaObjects"
                                         @close="openedShareMediaModal = false"/>
        </div>
    </div>
</template>

<script>

    /** import */
    import axios from 'axios';
    import ShareMediaModalComponent from '../../components/Modals/ShareMediaModalComponent.vue';
    import Card from '../../components/Card.vue';
    import SearchMediaComponent from './parts/SearchMediaComponent.vue';
    import Loader from "./parts/Loader";
    import ShowMediaDetailsModal from "../../components/Modals/ShowMediaDetailsModal";
    import CheckCreativePermission from '../../pages/common/parts/services/CheckCreativePermissionService';
    import VideoImageComponent from '../common/parts/VideoImageComponent.vue';

    export default {

        middleware: [
            'auth',
            'subscribed'
        ],

        components: {
            ShowMediaDetailsModal,
            Loader,
            SearchMediaComponent,
            Card,
            ShareMediaModalComponent,
            VideoImageComponent,
        },

        mixins: [
            CheckCreativePermission
        ],

        name: 'medias',

        props: [
            'creative_brand_id'
        ],

        created() {
            this.getMedias();
        },

        data: () => ({
            showSearch: false,
            openedShareMediaModal: false,
            shareMediaObjects: [],
            selectedMedia: [],
            isGotMedias: false,
            showDetailsModal: false,
            showMedia: false,
            showPage: false
        }),

        computed: {
            checkLoadPage() {
                if (this.isCreative && !this.isSelectedBrand) {
                    this.$router.push({name: 'dashboard'});
                    return false;
                } else if (this.isBrand || this.isCreative) {
                    return true;
                }
            },
            medias() {
                return this.$store.getters['media/medias'];
            },
        },

        methods: {

            /**
             *
             * @param index
             * @returns {Promise<void>}
             */
            async showMediaModal(index) {
                await this.$store.dispatch('media/setSelectedMedia', {mediaId: this.medias[index].id});
                this.showDetailsModal = true;
            },

            /**
             * clear all
             */
            clearAll() {
                this.selectedMedia = [];
            },

            /**
             * download
             */
            downloadSelectedMedia() {
                axios.get('/api/medias/download-multiple', {params: {media: this.selectedMedia}}).then((response) => {
                    window.open(response.data.url);
                });
            },

            /**
             * Retrieve media files
             *
             * @returns {Promise<void>}
             */
            async getMedias() {
                if (this.creative_brand_id) {
                    await this.$store.dispatch('media/getBrandMedias', {creative_brand_id: this.creative_brand_id});
                } else {
                    await this.$store.dispatch('media/getMedias');
                }
                this.isGotMedias = true;
                this.showPage = true;
            },

            /**
             * Share one media file
             *
             * @param media
             */
            shareOneMedia(media) {
                this.shareMediaObjects = [media.id];
                this.openShareModal();
            },

            /**
             * Share multiple media files
             */
            shareMultipleMedia() {
                this.shareMediaObjects = this.selectedMedia;
                this.openShareModal();
            },

            /**
             * Open share
             */
            openShareModal() {
                this.openedShareMediaModal = true;
            },


            /**
             * Delete multiple media files
             */
            deleteMultipleMedia() {
                this.$swal({
                    title: "Delete files",
                    text: "Are you sure?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it.",
                    closeOnConfirm: true
                }).then(() => {
                    axios.delete('/api/medias/remove-multiple', {params: {media: this.selectedMedia}}).then(({data}) => {
                        if (data.success) {
                            this.$store.dispatch('media/removeMedias', {medias: this.selectedMedia});
                            this.clearAll();
                        }
                    });
                })
            },

            /**
             * Check video type
             *
             * @param media
             * @returns {boolean}
             */
            checkVideoType(media) {
                return media.content_type === 'video/mp4' ? true : false;
            }
        }
    }
</script>

