<template>
    <card :title="'Basic information'" v-if="media">
        <table class="widget-table">

            <tr>
                <td><label class="font-weight-bold">Title</label></td>
                <td v-if="media.title"> {{ media.title }}</td>
            </tr>

            <tr>
                <td><label class="font-weight-bold">File name</label></td>
                <td v-if="media.origin_name"> {{ media.origin_name }}</td>
            </tr>

            <tr>
                <td><label class="font-weight-bold">Category</label></td>
                <td v-if="media.category"> {{ media.category.name }}</td>
            </tr>

            <tr>
                <td><label class="font-weight-bold">File type</label></td>
                <td v-if="media.fileType "> {{ media.fileType }}</td>
            </tr>

            <tr>
                <td><label class="font-weight-bold">Keywords</label></td>
                <td v-if="media.keywords"> {{ media.keywords }}</td>
            </tr>

            <tr>
                <td><label class="font-weight-bold"> Peoples Attribute </label></td>
                <td v-if="media.peoples_attribute "> {{ media.peoples_attribute }}</td>
            </tr>

            <tr>
                <td><label class="font-weight-bold"> Artist/Copyright </label></td>
                <td v-if="media.source"> {{ media.source }}</td>
            </tr>

            <tr>
                <td><label class="font-weight-bold"> Uploaded by </label></td>
                <td v-if="media.created_by"> {{ media.created_by.email }}</td>
            </tr>

            <tr>
                <td><label class="font-weight-bold"> Supplier </label></td>
                <td v-if="media.supplier"> {{ media.supplier.name}}</td>
            </tr>

            <tr>
                <td><label class="font-weight-bold"> License </label></td>
                <td>
                    <span :style="{background: media.license.color}"
                            class="license-badge" @click="showLicenseModal = true">
                        {{ media.licenses[0] ? media.licenses[0].type + ' - license' : 'Set license' }}
                    </span>
                </td>
            </tr>

        </table>

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