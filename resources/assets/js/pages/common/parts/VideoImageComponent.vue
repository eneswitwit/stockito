<template>
    <div v-if="checkPlay">
        <div v-if="!checkMediaType(media)">
            <img class="img-fluid" :src="media.thumbnail" alt="">
        </div>
        <div v-if="checkMediaType(media)">
            <video-player class="vjs-custom-skin big-size-video"
                          preload="auto"
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
                },
                    {
                        type: 'video/mp4',
                        src: ''
                    }],
                poster: ''
            },
        }),
        methods: {
            checkMediaType(media) {
                console.log(media.content_type);
                if (media.content_type.substring(0, 5) === 'video' || media.content_type === 'application/octet-stream') {
                    this.playerOptions.sources[0].src = media.downloadLink + '/mp4';
                    //this.playerOptions.sources[1].src = media.downloadLink + '.mp4';
                    if (media.content_type === 'video/quicktime') {
                        //this.playerOptions.sources[0].type = false;
                    }
                    return true;
                } else {
                    return false;
                }
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
