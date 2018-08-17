<template>
    <div>
        <div class="row" v-if="showPage">
            <div v-if="!showSearch" class="col-lg-12 mb-3">
                <button @click.prevent="showSearch = true" class="btn btn-primary">Advanced Search</button>
            </div>
            <div v-if="showSearch" class="col-lg-3">
                <search-media-component @closeAdvancedSearch="showSearch = $event"></search-media-component>
            </div>
            <div :class="showSearch ? 'col-lg-9' : 'col-lg-12'">
                <card class="mb-4" v-if="selectedMedia.length">
                    <button @click="shareMultipleMedia" class="btn btn-link">Share ({{ selectedMedia.length }} files)</button>
                    <button @click="downloadSelectedMedia" class="btn btn-link">Download ({{ selectedMedia.length }} files)</button>
                    <button @click="clearAll" class="btn btn-link float-right">Clear all</button>
                </card>
                <card>
                    <div v-show="!!medias.length" class="row">
                        <div v-for="(media, index) in medias" class="col-lg-4">
                            <div class="card mb-4">
                                <div class="img-wrapper">
                                    <div class="play-button" @click="showMediaModal(index)" :class="{play_visible: !checkVideoType(media)}">
                                        <img src="../../../view/player/play-button.png" alt="">
                                    </div>
                                    <div v-if="!isSearchOnly()" class="select-checkbox">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="multipleSelection[]" :value="media.id" class="custom-control-input" v-model="selectedMedia" :id="'select-checkbox-'+media.id">
                                            <label class="custom-control-label" :for="'select-checkbox-'+media.id"></label>
                                        </div>
                                    </div>
                                    <button v-if="(canAccess(media))" class="btn btn-link remove-btn" @click="removeMedia(media)">
                                        <fa icon="times" fixed-width/>
                                    </button>
                                    <div @click="showMediaModal(index)" class="wrapper-img" :class="{'wrapper-img-search': showSearch}">
                                        <img class="card-img-top" :src="media.thumbnail" alt="Card image cap">
                                    </div>
                                    <span v-if="media.license" class="license-label" :style="{color: media.license.color}">{{ media.license.type }}</span>
                                </div>
                                <div class="card-body control-buttons">
                                    <!--<router-link class="btn btn-link btn-sm" :to="{name: 'medias.show', params: {'id': media.id}}">{{ $t('details') }}</router-link>-->
                                    <button class="btn btn-link btn-sm" @click="showMediaModal(index)">{{ $t('details') }}</button>
                                    <button  v-if="canAccess(media) || isActiveEditing()" type="button" @click="shareOneMedia(media)" class="btn btn-link btn-sm">{{ $t('sharing') }}</button>
                                    <a v-if="canAccess(media) || isActiveEditing()" :href="media.downloadLink" class="btn btn-link btn-sm">{{ $t('download') }}</a>
                                    <router-link v-if="canAccess(media)" class="btn btn-link btn-sm" :to="{ name: 'medias.edit', params: {id: media.id} }">{{ $t('edit') }}</router-link>
                                    <!--<button class="btn btn-link btn-sm" @click="showMediaModal(index)">{{ $t('show') }}</button>-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-show="!medias.length" class="row">
                        <div v-if="isGotMedias" class="col-lg-12">
                            <!--You are haven't the media files. Please, <button type="button" class="btn btn-link btn-sm" @click="openedShareMediaModal = true">upload</button> new files.-->
                            You are haven't the media files. Please, upload new files.
                        </div>
                        <div v-else-if="!showPage" class="col-lg-12">
                            <loader></loader>
                        </div>
                    </div>
                </card>
            </div>
        </div>
        <show-media-details-modal v-bind:show="showDetailsModal" @close="showDetailsModal = false"/>
        <share-media-modal-component v-bind:show="openedShareMediaModal" v-bind:medias="shareMediaObjects" @close="openedShareMediaModal = false" />
    </div>
</template>

<script>
  import axios from 'axios';
  import * as types from '../../store/mutation-types';
  import Modal from '../../components/Modal/ModalLarge.vue';
  import ModalHeader from '../../components/Modal/ModalHeader.vue';
  import ModalBody from '../../components/Modal/ModalBody.vue';
  import ShareMediaModalComponent from '../../components/Modals/ShareMediaModalComponent.vue';
  import Card from '../../components/Card.vue';
  import SearchMediaComponent from './parts/SearchMediaComponent.vue';
  import Loader from "./parts/Loader";
  import ShowMediaDetailsModal from "../../components/Modals/ShowMediaDetailsModal";
  import CheckCreativePermission from '../../pages/common/parts/services/CheckCreativePermissionService';
  import VideoImageComponent from '../common/parts/VideoImageComponent.vue';

  export default {
    middleware: ['auth', 'subscribed'],
    components: {
      ShowMediaDetailsModal,
      Loader,
      SearchMediaComponent,
      Card,
      ShareMediaModalComponent,
      VideoImageComponent,
    },
    mixins: [CheckCreativePermission],
    name: 'medias',
    props: ['creative_brand_id'],
    created () {
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
      medias () {
        return this.$store.getters['media/medias'];
      },
    },
    methods: {
      async showMediaModal(index) {
        await this.$store.dispatch('media/setSelectedMedia', {mediaId: this.medias[index].id});
        this.showDetailsModal = true;
      },
      clearAll() {
        this.selectedMedia = [];
      },
      downloadSelectedMedia() {
        axios.get('/api/medias/download-multiple', {params: {media: this.selectedMedia}}).then((response) => {
          window.open(response.data.url);
        });
      },
      async getMedias() {
          if (this.creative_brand_id) {
              await this.$store.dispatch('media/getBrandMedias', {creative_brand_id: this.creative_brand_id});
          } else {
              await this.$store.dispatch('media/getMedias');
          }
          this.isGotMedias = true;
          this.showPage = true;
      },
      shareOneMedia(media) {
        this.shareMediaObjects = [media.id];
        this.openShareModal();
      },
      shareMultipleMedia() {
        this.shareMediaObjects = this.selectedMedia;
        this.openShareModal();
      },
      openShareModal() {
        this.openedShareMediaModal = true;
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
              this.$store.dispatch('media/removeMedia', {media: media});
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

<style lang="scss">

    .select-checkbox {
        position: absolute;
        width: 31px;
        height: 30px;
        z-index: 2;

        label {
            cursor: pointer;
            position: absolute;
            left: 7px;
            top: 3px;
        }
    }

    .img-wrapper {
        position: relative;
    }
    .license-label {
        display: block;
        position: absolute;
        right: 0;
        bottom: 0;
        background-color: rgba(0,0,0,.75);
        padding: 5px 10px;
    }
    .remove-btn {
        position: absolute;
        right: 0;
        top: 0;
        z-index:2;
    }
    .play-button img{
        width: 90px;
        height: 60px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translateY(-50%) translateX(-50%);
        z-index: 2;
        cursor: pointer;
        opacity: 0.8;
    }
    .control-buttons {
        text-align: center;
    }
    .play_visible {
        display: none;
    }
    .wrapper-img {
        cursor: pointer;
        height: 252px;
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
    .wrapper-img-search {
        height: 180px;
    }
</style>