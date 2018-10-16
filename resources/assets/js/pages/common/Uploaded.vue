<template>

    <div class="container-fluid" v-if="showPage">
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

                <div class="row uploaded-media" v-if="!!medias.length">

                    <div class="card media-card"
                         v-for="(media, index) in medias"
                         v-bind:id="media.id"
                         @click="showMedia = media"
                    >

                        <div class="card-body nopadding" @click="toggleCheckbox(media.id)">

                            <div class="img-wrapper">

                                <div class="play-button" :class="{play_visible: !checkVideoType(media)}">
                                    <img src="../../../view/player/play-button.png" alt="">
                                </div>

                                <div class="select-checkbox">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="multipleSelection[]" :value="media.id"
                                               class="custom-control-input" v-model="selectedMedia"
                                               :id="'select-checkbox-'+media.id">
                                        <label class="custom-control-label"
                                               :for="'select-checkbox-'+media.id"></label>
                                    </div>
                                </div>

                                <div class="wrapper-img">
                                    <img class="card-img-top" :src="media.thumbnail" alt="Card image cap">
                                </div>

                                <span v-if="media.license" class="license-label"
                                      :style="{color: media.license.color}">{{ media.license.type }}</span>

                            </div>
                        </div>
                    </div>

                </div>

                <div class="row" v-else>
                    <div class="col-lg-12">
                        <div v-if="isGotUploads">
                            <div class="alert alert-info text-center" role="alert" style="padding: 20px;margin-top: 1px;">
                                You did not upload any files. You can overview your <router-link :to="{name: 'medias'}"> Media </router-link>
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

                <edit-media-details @submitted="showMedia = null" v-if="showMedia && !!medias.length"
                                    :media="showMedia"
                                    :medias="medias"
                                    :selectedMedia="selectedMedia"
                                    v-bind:getMedias="getMedias"
                                    v-bind:refreshList="refreshList"
                                    v-bind:clearAll="clearAll">
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

        created() {
            this.getMedias();
        },

        data: () => ({
            showMedia: null,
            isGotUploads: false,
            showPage: false,
            selectedMedia: [],
            showSearch: false,
            openedShareMediaModal: false,
            shareMediaObjects: [],
            isGotMedias: false
        }),

        computed: {

            medias() {
                return this.$store.getters['media/uploads'];
            },

            selectedBrand() {
                return this.$store.getters['creative/selectedBrand'];
            }
        },

        watch: {
            selectedMedia: function () {
                return this.selectedMedia;
            }
        },

        methods: {

            /**
             * Open share
             */
            openShareModal() {
                this.openedShareMediaModal = true;
            }
            ,

            /**
             * clear all
             */
            clearAll() {
                this.selectedMedia = [];
            }
            ,


            /**
             * get media files
             *
             * @returns {Promise<void>}
             */
            async getMedias() {
                let selectedBrandId = this.selectedBrand ? this.selectedBrand.id : null;
                await
                    this.$store.dispatch('media/getUploads', {selectedBrandId: selectedBrandId});
                this.isGotUploads = true;
                this.showPage = true;
            }
            ,

            /**
             * refresh list
             */
            refreshList() {
                this.getMedias();
                let selectedBrandId = this.selectedBrand ? this.selectedBrand.id : null;
                this.$store.dispatch('media/getProcessing', {selectedBrandId: selectedBrandId});
            }
            ,

            /**
             * download
             */
            downloadSelectedMedia() {
                axios.get('/api/medias/download-multiple', {params: {media: this.selectedMedia}}).then((response) => {
                    window.open(response.data.url);
                });
            }
            ,

            /**
             * Share one media file
             *
             * @param media
             */
            shareOneMedia(media) {
                this.shareMediaObjects = [media.id];
                this.openShareModal();
            }
            ,

            /**
             * Share multiple media files
             */
            shareMultipleMedia() {
                this.shareMediaObjects = this.selectedMedia;
                this.openShareModal();
            }
            ,


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
                            this.refreshList();
                        }
                    });
                })
            }
            ,

            /**
             * check video type
             *
             * @param media
             * @returns {boolean}
             */
            checkVideoType(media) {
                return media.content_type === 'video/mp4' ? true : false;
            }
            ,

            /**
             * when picture is clicked add to selected media
             *
             * @param {int} id
             * @returns {void}
             */
            toggleCheckbox(id) {
                let itemIndex = this.selectedMedia.indexOf(id);
                if (itemIndex > -1) {
                    this.selectedMedia.splice(itemIndex, 1);
                } else {
                    this.selectedMedia.push(id);
                }
            }
        }
    }
</script>
