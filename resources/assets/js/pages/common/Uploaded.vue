<template>
    <div>
        <card v-if="showPage">
            <div class="row">
                <div class="col-lg-9">
                    <div class="row">
                        <div class="col-lg-12" v-if="!!medias.length">
                            <div class="row">
                                <div v-for="media in medias" class="col-lg-6">
                                    <div class="card media-card mb-4" @click="showMedia = media" :class="{'text-white bg-dark': showMedia && showMedia.id == media.id}">
                                        <div class="card-body">
                                            <div class="img-wrapper">
                                                <div class="play-button" :class="{play_visible: !checkVideoType(media)}">
                                                    <img src="../../../view/player/play-button.png" alt="">
                                                </div>
                                                <button class="btn btn-link remove-btn" @click="removeMedia(media)">
                                                    <fa icon="times" fixed-width/>
                                                </button>
                                                <div class="wrapper-img">
                                                    <img class="card-img-top" :src="media.thumbnail" alt="Card image cap">
                                                </div>
                                            </div>
                                            <span v-if="media.license" class="license-label" :style="{color: media.license.color}">{{ media.license.type }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12" v-else>
                            <div v-if="isGotUploads">
                                You are haven't the uploaded files. Please, overview your <router-link :to="{name: 'medias'}" class="btn btn-link btn sm">media</router-link> files
                            </div>
                            <div v-else>
                                <loader />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <processing-component @refreshing="refreshList"></processing-component>
                    <card class="mb-4">
                        <button class="btn btn-link" @click.prevent="refreshList">Refresh</button>
                    </card>
                    <edit-media-details @submitted="showMedia = null" v-if="showMedia && !!medias.length" :media="showMedia"></edit-media-details>
                </div>
            </div>

        </card>
    </div>
</template>

<script>
  import Card from '../../components/Card.vue';
  import axios from 'axios';
  import * as types from '../../store/mutation-types';
  import EditMediaDetails from './parts/EditMediaDetails.vue';
  import Loader from "./parts/Loader";
  import ProcessingComponent from './parts/ProcessingComponent';
  import VideoImageComponent from '../common/parts/VideoImageComponent.vue';

  export default {
    middleware: ['auth', 'subscribed'],
    components: {
      ProcessingComponent,
      Loader,
      EditMediaDetails,
      Card,
      VideoImageComponent,
    },
    name: 'uploaded',
    created () {
      this.getMedias();
    },
    data: () => ({
      showMedia: null,
      isGotUploads: false,
      showPage: false
    }),
    computed: {
      medias () {
        return this.$store.getters['media/uploads'];
      },
      selectedBrand() {
        return this.$store.getters['creative/selectedBrand'];
      }
    },
    methods: {
      async getMedias() {
        let selectedBrandId = this.selectedBrand ? this.selectedBrand.id : null;
        await this.$store.dispatch('media/getUploads', {selectedBrandId: selectedBrandId});
        this.isGotUploads = true;
        this.showPage = true;
      },
      refreshList (){
        this.getMedias();
        let selectedBrandId = this.selectedBrand ? this.selectedBrand.id : null;
        this.$store.dispatch('media/getProcessing', {selectedBrandId: selectedBrandId});
      },
      removeMedia(media) {
        this.$swal({
          title: "Delete this file?",
          text: "Are you sure?",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          confirmButtonText: "Yes, Delete it!",
          closeOnConfirm: true
        }).then(() => {
          axios.delete('/api/medias/'+media.id).then(({ data }) => {
            if (data.success) {
              this.$store.dispatch('media/removeUpload', {media: media});
            }
          });
        })
      },
      checkVideoType(media) {
         return media.content_type === 'video/mp4' ? true : false;
      }
    }
  }
</script>

<style scoped lang="scss">
    .media-card {
        cursor: pointer;

        .license-label {
            display: block;
            position: absolute;
            right: 15px;
            bottom: 15px;
            background-color: rgba(0,0,0,.75);
            padding: 5px 10px;
        }

        .img-wrapper {
            position: relative;
            width: 353px;

            img {
                cursor: pointer;
            }
        }
        .remove-btn {
            position: absolute;
            right: 0;
            top: 0;
            z-index: 15;
        }
        .wrapper-img {
            cursor: pointer;
            height: 264px;
            position: relative;
            background: #000;
        }
        .card-img-top {
            position: absolute;
            top: 50%;
            -webkit-transform: translateY(-50%);
            -moz-transform: translateY(-50%);
            -ms-transform: translateY(-50%);
            -o-transform: translateY(-50%);
            transform: translateY(-50%);
        }
        .play-button img{
            width: 90px;
            height: 60px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translateY(-50%) translateX(-50%);
            z-index: 5;
            cursor: pointer;
            opacity: 0.8;
        }
        .play_visible {
            display: none;
        }
    }
</style>