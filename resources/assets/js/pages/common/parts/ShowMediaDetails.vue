<template>
    <card v-if="media">
        <div class="form-group">
            <label for="">Title</label>
            <p>{{ media.title }}</p>
        </div>
        <div class="form-group">
            <label for="">File Name</label>
            <p>{{ media.origin_name }}</p>
        </div>
        <div class="form-group">
            <label for="media-origin-camera">Camera:</label>
            {{ media.exif ? media.exif.camera : '' }}
        </div>
        <div v-if="media.category" class="form-group">
            <label for="media-origin-gps">Category:</label>
            {{ media.category.name }}
        </div>
        <div class="form-group">
            <label for="media-origin-gps">Keywords:</label>
            {{ media.keywords }}
        </div>
        <div class="form-group input-group-sm">
            <label for="media-origin-aperture">Source:</label>
            {{ media.source }}
        </div>
        <div class="form-group">
            <label for="media-origin-copyright">Copyright:</label>
            {{ media.exif ? media.exif.copyright : '' }}
        </div>
        <div class="form-group">
            <label for="media-origin-author">Author:</label>
            {{ media.exif ? media.exif.author : '' }}
        </div>
        <div v-if="media.supplier" class="form-group">
            <label for="media-origin-author">Supplier:</label>
            {{ media.supplier.name }}
        </div>
        <div v-if="media.licenses && media.licenses.length" class="form-group">
            <label for="">License</label>
            <button v-color-license:background.border="media.licenses[0]" type="button"
                    class="btn btn-success brn-xs" @click="showLicenseModal = true">{{ media.licenses[0]
                ? media.licenses[0].type + ' - license'
                : 'Set license' }}
            </button>
        </div>
    </card>
</template>

<script type="text/babel">
    import axios from 'axios';
    import ColorLicensesDirective from '../../../directives/ColorLicensesDirective';

    Vue.directive('color-license', ColorLicensesDirective);

    export default {
        middleware: ['auth', 'brand'],
        name: 'show-media-details',
        props: {
            media: {
                'default': undefined
            },
            submit: {
                'default': true
            }
        },
        data: () => ({}),
        methods: {
            async submitMedia(media) {
                await this.$store.dispatch('media/submitUpload', media);
            }
        },
        directives: {
            ColorLicensesDirective
        }
    }
</script>