<template>
    <div class="card-body nopadding" :id="media.thumbnail" style="display:none;">

        <div class="img-wrapper">

            <div class="play-button" @click="showMediaModal(index)"
                 :class="{play_visible: !checkVideoType(media)}">
                <img src="../../../../view/player/play-button.png" alt="">
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
                <img class="card-img-top" :src="media.thumbnail" alt="Card image cap"
                     @load="imageLoaded(media.thumbnail)">
            </div>

            <span v-if="media.license" class="license-label"
                  :style="{color: media.license.color}">{{ media.license.type }}</span>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Image",


        props: [
            'media'
        ],
    }
</script>

<style scoped>

</style>