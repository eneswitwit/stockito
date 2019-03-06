<template>

    <div class="container-fluid">

        <div class="row" v-if="!showPage">
            <div class="col-md-12" style="text-align:center;">
                <img :src="require('../../../images/loading.gif')" height="300px"/>
            </div>
        </div>


        <div class="row show-page" v-if="showPage">

            <div class="col-lg-9">
                <div class="row">
                    <div class="col-lg-12">
                        <card class="mb-4" v-if="selectedMedia.length">

                            <button @click="deleteMultipleMedia" class="btn btn-link">
                                Delete ({{ selectedMedia.length }} files)
                            </button>
                            <button @click="shareMultipleMedia" class="btn btn-link">
                                Share ({{ selectedMedia.length }} files)
                            </button>
                            <button @click="downloadSelectedMedia" class="btn btn-link">
                                Download ({{ selectedMedia.length }} files)
                            </button>
                            <button @click="clearAll" class="btn btn-link float-right">
                                Clear all
                            </button>

                        </card>
                    </div>
                </div>

                <div class="row uploaded-media" v-if="!!mediaDisplayed.length">

                    <div v-for="(media, index) in mediaDisplayed" v-bind:id="media.id">

                        <div class="card media-card" @click="showMedia = media">

                            <img :id="media.thumbnail + '-load'" :src="require('../../../images/loading.gif')"
                                 height="300px"/>

                            <div class="card-body nopadding" @click="toggleCheckbox(media)" :id="media.thumbnail"
                                 style="display:none;">

                                <div class="img-wrapper">

                                    <div class="play-button" :class="{play_visible: !checkVideoType(media)}">
                                        <img src="../../../view/player/play-button.png" alt="">
                                    </div>

                                    <div class="select-checkbox">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="multipleSelection[]" v-bind:value="media"
                                                   class="custom-control-input" v-model.lazy.number="selectedMedia"
                                                   :id="'select-checkbox-'+media.id"
                                                   @change="checkCheckbox(media.thumbnail)">
                                            <label class="custom-control-label"
                                                   :for="'select-checkbox-'+media.id"></label>
                                        </div>
                                    </div>

                                    <div class="wrapper-img">
                                        <img class="card-img-top" :src="media.thumbnail" alt="Card image cap"
                                             @load="imageLoaded(media.thumbnail)">
                                    </div>

                                    <span v-if="media.license" class="license-label"
                                          :style="{color: getLicenseColor(media.license.type)}">{{ media.license.type }}</span>

                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="row" v-else>
                    <div class="col-lg-12">
                        <div v-if="isGotUploads">
                            <div class="alert alert-info text-center" role="alert"
                                 style="padding: 20px;margin-top: 1px;">
                                You did not upload any files. You can overview your
                                <router-link :to="{name: 'medias'}"> Media</router-link>
                                files or upload new files.
                            </div>
                        </div>
                        <div v-else>
                            <loader/>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-lg-3">

                <processing-component @refreshing="refreshList"></processing-component>

                <card class="mb-4 text-center">
                    <button class="btn btn-link" @click.prevent="refreshList">Refresh</button>
                </card>

                <edit-media-details @submitted="showMedia = null" v-if="showMedia && !!mediaDisplayed.length"
                                    :media="showMedia"
                                    :medias="mediaDisplayed"
                                    v-bind:getMedias="getMedias"
                                    v-bind:refreshList="refreshList"
                                    v-bind:clearAll="clearAll"
                                    v-bind:selectedBrand="selectedBrand">
                </edit-media-details>

            </div>
            <share-media-modal-component v-bind:show="openedShareMediaModal" v-bind:medias="shareMediaObjects"
                                         @close="openedShareMediaModal = false"/>
        </div>
    </div>

</template>

<script>

    import Card from '../../components/Card.vue';
    import axios from 'axios';
    import EditMediaDetails from './parts/EditMediaDetails.vue';
    import Loader from "./parts/Loader";
    import ProcessingComponent from './parts/ProcessingComponent';
    import VideoImageComponent from '../common/parts/VideoImageComponent.vue';
    import ShareMediaModalComponent from '../../components/Modals/ShareMediaModalComponent.vue';

    const MAX = 20;

    export default {

        middleware: [
            'auth',
            'subscribed'
        ],

        components: {
            ProcessingComponent,
            Loader,
            EditMediaDetails,
            Card,
            VideoImageComponent,
            ShareMediaModalComponent
        },

        name: 'uploaded',

        data: () => ({
            showMedia: null,
            isGotUploads: false,
            showPage: false,
            showSearch: false,
            openedShareMediaModal: false,
            shareMediaObjects: [],
            isGotMedias: false,
            isLoading: false,
            counter: 0,
            imagesLoading: true
        }),

        computed: {

            medias() {
                return this.$store.getters['media/uploads'];
            },
            mediaDisplayed() {
                return this.$store.getters['media/mediaDisplayed'];
            },
            selectedBrand() {
                return this.$store.getters['creative/selectedBrand'];
            },
            brandLogged() {
                return this.$store.getters['auth/brand'];
            },
            selectedMedia() {
                return this.$store.getters['media/selectedMedia'];
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

        beforeMount() {
            this.clearAll();
            this.getInitialMedia();
        },

        methods: {

            getSelectedBrandId() {
                var url = window.location.href;
                var page = "uploaded/";
                var index = url.indexOf(page);
                if (index === -1) {
                    page = "medias/";
                    index = url.indexOf(page)
                }
                var substring = url.substring(index + page.length, url.length);

                var selectedBrandId = null;
                if (substring !== '') {
                    selectedBrandId = parseInt(substring);
                } else {
                    selectedBrandId = this.selectedBrand ? this.selectedBrand.id : null;
                }
                return selectedBrandId;

            },


            setSelectedBrand() {
                if (!!this.brandLogged) {
                    var selectedBrandId = this.getSelectedBrandId();
                    if (selectedBrandId) {
                        this.$store.dispatch('creative/setSelectedBrandId', {selectedBrandId});
                    }
                }
            },

            checkCheckbox(id) {
                var card = document.getElementById(id);
                card.click();
            },

            getSelectedMediaArray() {
                var mediaSubmit = [];
                this.selectedMedia.forEach(function (media) {
                    mediaSubmit.push(media.id);
                });
                return mediaSubmit;
            },

            imageLoaded(id) {
                this.imagesLoading = false;
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
                var selectedBrandId = this.getSelectedBrandId();
                await this.$store.dispatch('media/getUploadsStep', {
                    taken: taken,
                    toTake: toTake,
                    selectedBrandId: selectedBrandId
                });
            },

            async getInitialMedia() {
                var selectedBrandId = this.getSelectedBrandId();
                await this.$store.dispatch('media/getUploadsStep', {
                    taken: 0,
                    toTake: 25,
                    selectedBrandId: selectedBrandId
                });
                this.isGotUploads = true;
                this.showPage = true;
            },

            mediaNextStep() {
                this.isLoading = true;
                this.getMediaStep(this.mediaDisplayed.length, MAX);
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
             * Open share
             */
            openShareModal() {
                this.openedShareMediaModal = true;
            },

            /**
             * clear all
             */
            clearAll() {
                this.$store.dispatch('media/resetSelectedMedia');
            },


            /**
             * get media files
             *
             * @returns {Promise<void>}
             */
            async getMedias() {
                var selectedBrandId = this.getSelectedBrandId();
                await this.$store.dispatch('media/getUploads', {selectedBrandId: selectedBrandId});
                this.isGotUploads = true;
                this.showPage = true;
            },

            /**
             * refresh list
             */
            refreshList() {
                var url = window.location.href;
                var index = url.indexOf("uploaded/");
                var substring = url.substring(index + 9, url.length);

                var selectedBrandId = null;
                if (substring !== '') {
                    selectedBrandId = parseInt(substring);
                } else {
                    selectedBrandId = this.selectedBrand ? this.selectedBrand.id : null;
                }

                this.$store.dispatch('media/getProcessing', {selectedBrandId: selectedBrandId});
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
                            this.refreshList();
                        } else {
                        }
                    });
                })
            },

            /**
             * check video type
             *
             * @param media
             * @returns {boolean}
             */
            checkVideoType(media) {
                return (media.content_type === 'video/mp4' || media.content_type === 'video/quicktime');
            },

            toggleCheckbox(media) {
                this.$store.dispatch('media/addSelectedMedia', {media: media});
            },

            getLicenseColor(license) {
                switch (license) {
                    case 'RF':
                        return 'green';

                    case 'RE':
                        return 'orange';

                    case 'RM':
                        return 'red';

                    case 'BO':
                        return 'blue';

                }
            },

        },

        mounted() {
            this.scroll();
        }
    }
</script>
