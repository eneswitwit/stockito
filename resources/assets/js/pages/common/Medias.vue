<template>

    <div>

        <div class="row" v-if="!showPage">
            <div class="col-md-12" style="text-align:center;">
                <img :src="require('../../../images/loading.gif')" height="300px"/>
            </div>
        </div>

        <a @click.prevent="showSearch = true">
            <div v-if="!showSearch" class="advanced-search">

                <div class="advanced-search-text">
                    <p>Advanced</p>
                    <p style="font-size: 19px; font-weight: 500;">Search</p>
                </div>

            </div>
        </a>

        <div class="container-fluid">
            <div class="row show-page">

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

                    <div class="row uploaded-media" v-show="!!mediasDisplayed.length">

                        <div v-for="(media, index) in mediasDisplayed" v-bind:id="media.id">

                            <div class="card media-card">

                                <img :id="media.thumbnail + '-load'" :src="require('../../../images/loading.gif')"
                                     height="300px"/>

                                <div class="card-body nopadding" :id="media.thumbnail" style="display:none;">

                                    <div class="img-wrapper">

                                        <div class="play-button" @click="showMediaModal(media.id)"
                                             :class="{play_visible: !checkVideoType(media)}">
                                            <img src="../../../view/player/play-button.png" alt="">
                                        </div>

                                        <div v-if="!isSearchOnly()" class="select-checkbox">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" name="multipleSelection[]" v-bind:value="media"
                                                       class="custom-control-input" v-model="selectedMedia"
                                                       :id="'select-checkbox-'+media.id"
                                                        @change="toggleCheckbox(media)">
                                                <label class="custom-control-label"
                                                       :for="'select-checkbox-'+media.id"></label>
                                            </div>
                                        </div>

                                        <div @click="showMediaModal(media.id)" class="wrapper-img"
                                             :class="{'wrapper-img-search': showSearch}">
                                            <img class="card-img-top" :src="media.thumbnail" alt="Card image cap"
                                                 @load="imageLoaded(media.thumbnail)">
                                        </div>

                                        <span v-if="media.license" class="license-label"
                                              :style="{color: media.license.color}">{{ media.license.type }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-show="!mediasDisplayed.length" class="row">
                        <div class="container mt-4">
                            <div v-if="isGotMedias" class="col-lg-12">
                                <div class="container mt-4">
                                    <!--You are haven't the media files. Please, <button type="button" class="btn btn-link btn-sm" @click="openedShareMediaModal = true">upload</button> new files.-->
                                    You don't have any media files. You can start uploading by just clicking the upload
                                    button in
                                    the top section of the site.
                                </div>
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

    const MAX = 20;

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

        data: () => ({
            showSearch: false,
            showPage: false,
            openedShareMediaModal: false,
            shareMediaObjects: [],
            isGotMedias: false,
            showDetailsModal: false,
            isLoading: false,
            counter: 0
        }),

        computed: {
            /*checkLoadPage() {
                if (this.isCreative && !this.selectedBrand) {
                    this.$router.push({name: 'dashboard'});
                    return false;
                } else if (this.isBrand || (this.isCreative && this.selectedBrand)) {
                    return true;
                } else {
                    this.$router.push({name: 'dashboard'});
                    return false;
                }
            },*/
            medias() {
                return this.$store.getters['media/medias'];
            },
            mediasDisplayed() {
                return this.$store.getters['media/mediasDisplayed'];
            },
            selectedBrand() {
                return this.$store.getters['creative/selectedBrand'];
            },
            selectedMedia: {
                get: function() {
                    return this.$store.getters['media/selectedMedia'];
                },
                set: function() {}
            }
        },

        watch: {
            counter: function () {
                if (this.counter === MAX) {
                    this.counter = 0;
                    this.isLoading = false;
                }
            }
        },

        async beforeMount() {
            this.clearAll();
            //this.checkAccess();
            this.getInitialMedia();
        },

        methods: {

            toggleCheckbox(media) {
                this.$store.dispatch('media/addSelectedMedia', {media: media});
            },

            checkAccess() {
                if (this.$store.getters['auth/user'].brand) {
                    return true;
                } else if (!this.$store.getters['auth/user'].brand && this.selectedBrand) {
                    return true;
                } else {
                    this.$router.push({name: 'dashboard'});
                    return false;
                }
            },

            async getInitialMedia() {
                var t0 = performance.now();
                if (this.selectedBrand) {
                    await this.$store.dispatch('media/getBrandMediaStep', {
                        taken: 0,
                        toTake: 5,
                        creative_brand_id: this.selectedBrand.id
                    });
                } else {
                    await this.$store.dispatch('media/getMediasStep', {taken: 0, toTake: 5});
                }
                var t1 = performance.now();
                console.log("Call getinitialmedia " + (t1 - t0) + " milliseconds.");
                this.showPage = true;
                this.isGotUploads = true;
            },

            imageLoaded(id) {
                var loading = document.getElementById(id + "-load");
                if (loading && loading.parentNode) {
                    loading.parentNode.removeChild(loading);
                }
                var wrapper = document.getElementById(id);
                if (wrapper && wrapper.style.display === "none") {
                    this.counter++;
                    wrapper.style.display = "block";
                }
            },

            async getMediaStep(taken, toTake) {
                if (this.selectedBrand) {
                    await this.$store.dispatch('media/getBrandMediaStep', {
                        taken: taken,
                        toTake: toTake,
                        creative_brand_id: this.selectedBrand.id
                    });
                } else {
                    await this.$store.dispatch('media/getMediasStep', {taken: taken, toTake: toTake});
                }
            },

            mediaNextStep() {
                this.isLoading = true;
                this.getMediaStep(this.mediasDisplayed.length, MAX);
            },

            scroll() {
                window.onscroll = () => {
                    let bottomOfWindow = Math.abs(document.documentElement.scrollTop + window.innerHeight - document.documentElement.offsetHeight) < 3;
                    if (bottomOfWindow && !this.isLoading) {
                        this.mediaNextStep();
                    }
                };
            },

            /**
             *
             * @param index
             * @returns {Promise<void>}
             */
            async showMediaModal(id) {
                await this.$store.dispatch('media/setSelectedMedia', {mediaId: id});
                this.showDetailsModal = true;
            },

            /**
             * clear all
             */
            clearAll() {
                this.$store.dispatch('media/resetSelectedMedia');
            },

            /**
             * download
             */
            downloadSelectedMedia() {
                var selectedMedia = this.getSelectedMediaArray();
                axios.get('/api/medias/download-multiple', {params: {media: selectedMedia}}).then((response) => {
                    window.open(response.data.url);
                });
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
                var selectedMedia = this.getSelectedMediaArray();
                this.shareMediaObjects = selectedMedia;
                this.openShareModal();
            },

            /**
             * Open share
             */
            openShareModal() {
                this.openedShareMediaModal = true;
            },

            getSelectedMediaArray() {
                var mediaSubmit = [];
                this.selectedMedia.forEach(function (media) {
                    mediaSubmit.push(media.id);
                });
                return mediaSubmit;
            },


            /**
             * Delete multiple media files
             */
            deleteMultipleMedia() {
                var selectedMedia = this.getSelectedMediaArray();
                this.$swal({
                    title: "Delete files",
                    text: "Are you sure?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it.",
                    closeOnConfirm: true
                }).then(() => {
                    axios.delete('/api/medias/remove-multiple', {params: {media: selectedMedia}}).then(({data}) => {
                        if (data.success) {
                            this.$store.dispatch('media/removeMedias', {medias: selectedMedia});
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
                return (media.content_type === 'video/mp4' || media.content_type === 'video/quicktime');
            }
        },

        mounted() {
            this.scroll();
        }
    }
</script>

