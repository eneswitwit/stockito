<template>
    <div v-if="checkPlay">
        <div v-if="!checkMediaType(media)">
            <img class="img-fluid" :src="media.url" alt="">
        </div>
        <div v-if="checkMediaType(media)">
            <video-player class="vjs-custom-skin big-size-video"
                          :class="{'search-page-video-size': showSearch}"
                          ref="videoPlayer"
                          :options="playerOptions"
                          :playsinline="true">
            </video-player>
        </div>
    </div>
</template>

<script type="text/babel">
    import axios from 'axios';
    import {videoPlayer} from 'vue-video-player'

    export default {
        name: 'video-image-component',
        props: [
            'media',
            'stopPlayer',
            'bigSizeWindow',
            'showSearch'
        ],
        components: {
            videoPlayer
        },
        computed: {
            checkPlay() {
                if (!this.stopPlayer) {
                    this.playerOptions.sources[0].src = null;
                }
                return true;
            }
        },
        data: () => ({
            playerOptions: {
                height: '500',
                width: '800',
                autoplay: false,
                controls: true,
                muted: false,
                language: 'en',
                playbackRates: [0.1, 1, 1.5, 2],
                sources: [{
                    type: 'video/mp4',
                    src: ''
                }],
                poster: ''
            },
        }),
        methods: {
            checkMediaType(media) {
                if (media.content_type === 'video/mp4') {
                    this.playerOptions.sources[0].src = media.downloadLink;
                    return true;
                }
                return false;
            }
        }
    }
</script>

<style lang="scss">
    $big_video_height: 183px;
    $min_video_height: 150px;

    .big-size-video {
        .video-js {
            height: 540px;
        }
    }
    .search-page-video-size {
        .video-js {
            height: $big_video_height;
        }
    }

    @media (max-width: 1200px) {
        .vjs-custom-skin {
            .video-js {
                height: 210px;
            }
        }
        .big-size-video {
            .video-js {
                height: 500px;
            }
        }
        .search-page-video-size {
            .video-js {
                height: $min_video_height;
            }
        }
    }
</style>
