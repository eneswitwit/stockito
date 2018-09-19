<template>
    <card :title="'Basic information'" v-if="media">
        <div class="form-group">
            <label class="font-weight-bold" for="">Title</label>
            {{ media.title }}
        </div>
        <div class="form-group">
            <label class="font-weight-bold" for="">File Name</label>
            {{ media.origin_name }}
        </div>
        <div v-if="media.category" class="form-group">
            <label class="font-weight-bold" for="media-origin-gps">Category</label>
            {{ media.category.name }}
        </div>
        <div v-if="media.fileType" class="form-group">
            <label class="font-weight-bold" for="media-origin-gps">File Type</label>
            {{ media.fileType }}
        </div>
        <div v-if="media.keywords !== null" class="form-group">
            <label class="font-weight-bold" for="media-origin-gps">Keywords</label>
            {{ media.keywords }}
        </div>
        <div v-if="media.peoples_attribute !== null" class="form-group">
            <label class="font-weight-bold" for="media-origin-gps">Peoples Attribute</label>
            {{ media.peoples_attribute }}
        </div>
        <div v-if="media.source !== null" class="form-group input-group-sm">
            <label class="font-weight-bold" for="media-origin-aperture">Artist/Copyright</label>
            {{ media.source }}
        </div>
        <div class="form-group">
            <label class="font-weight-bold" for="media-origin-author">Uploaded by</label>
            {{ media.created_by.email }}
        </div>
        <div v-if="media.supplier" class="form-group">
            <label class="font-weight-bold" for="media-origin-author">Supplier</label>
            {{ media.supplier.name }}
        </div>
        <div v-if="media.licenses && media.licenses.length" class="form-group">
            <label class="font-weight-bold" for="">License</label>
            <button v-color-license:background.border="media.licenses[0]" type="button"
                    class="btn btn-success brn-xs" @click="showLicenseModal = true">{{ media.licenses[0]
                ? media.licenses[0].type + ' - license'
                : 'Set license' }}
            </button>
        </div>
    </card>
</template>

<script type="text/babel">
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