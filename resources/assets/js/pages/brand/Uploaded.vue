<template>
    <div>
        <card>
            <div v-show="!!medias.length" class="row">
                <div class="col-lg-9">
                    <div class="row">
                        <div v-for="media in medias" class="col-lg-6">
                            <div class="card media-card mb-4" @click="showMedia = media" :class="{'text-white bg-dark': showMedia && showMedia.id == media.id}">
                                <div class="card-body">
                                    <img class="card-img-top" :src="media.thumbnail" alt="Card image cap">
                                    <span v-if="media.license" class="license-label" :style="{color: media.license.color}">{{ media.license.type }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <edit-media-details :media="showMedia"></edit-media-details>
                </div>
            </div>
            <div v-show="!medias.length" class="row">
                <div class="col-lg-12">
                    You are haven't the uploaded files. Please, overview your <router-link :to="{name: 'medias'}" class="btn btn-link btn sm">media</router-link> files
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

  export default {
    middleware: ['auth', 'subscribed'],
    components: {
      EditMediaDetails,
      Card},
    name: 'uploaded',
    created () {
      this.getMedias();
    },
    data: () => ({
      showMedia: null
//        medias: []
    }),
    computed: {
      medias () {
        return this.$store.getters['media/uploads'];
      }
    },
    methods: {
      async getMedias() {
        await this.$store.dispatch('media/getUploads');
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
    }
</style>