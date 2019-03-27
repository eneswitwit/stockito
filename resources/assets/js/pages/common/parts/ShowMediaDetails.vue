<template>
    <div class="card" v-if="media">
        <div class="card-header dashboard-card">
            Basic information
        </div>
        <div class="card-body">
            <table class="upload-ftp">

                <tr>
                    <td class="label">Title</td>
                    <td v-if="media.title"> {{ media.title }}</td>
                </tr>

                <tr>
                    <td class="label">File name </td>
                    <td v-if="media.origin_name"> {{ media.origin_name }}</td>
                </tr>

                <tr>
                    <td class="label">Category </td>
                    <td v-if="media.category"> {{ media.category.name }}</td>
                </tr>

                <tr>
                    <td class="label"> File type </td>
                    <td v-if="media.fileType "> {{ media.fileType }}</td>
                </tr>

                <tr>
                    <td class="label">Keywords </td>
                    <td v-if="media.keywords" style="word-break: keep-all !important;"> {{ media.keywords }}</td>
                </tr>

                <tr>
                    <td class="label"> Peoples Attribute </td>
                    <td v-if="media.peoples_attribute "> {{ media.peoples_attribute }}</td>
                </tr>

                <tr>
                    <td class="label">Artist/Copyright</td>
                    <td v-if="media.source"> {{ media.source }}</td>
                </tr>

                <tr>
                    <td class="label">Uploaded by </td>
                    <td v-if="media.created_by"> {{ media.created_by.email }}</td>
                </tr>

                <tr>
                    <td class="label"> Supplier </td>
                    <td v-if="media.supplier"> {{ media.supplier.name}}</td>
                </tr>

                <tr>
                    <td class="label"> License </td>
                    <td v-if="media.license && media.licenses">
                    <span :style="{color: media.license.color}"
                          class="license-badge" @click="showLicenseModal = true">
                        {{ media.licenses[0] ? media.licenses[0].type + ' license' : 'Set license' }}
                    </span>
                    </td>
                </tr>

            </table>
        </div>
    </div>
</template>

<script type="text/babel">
    import ColorLicensesDirective from '../../../directives/ColorLicensesDirective';
    import Modal from '../../../components/Modal/ModalLarge.vue';
    import ModalHeader from '../../../components/Modal/ModalHeader.vue';
    import ModalBody from '../../../components/Modal/ModalBody.vue';
    import SetUsageLicenseModal from '../../../components/Modals/SetUsageLicenseModal.vue';
    import ShowMediaDetails from '../../common/parts/ShowMediaDetails.vue';
    import {mapGetters} from 'vuex';
    import Card from '../../../components/Card.vue';
    import ShareMediaModalComponent from '../../../components/Modals/ShareMediaModalComponent.vue';
    import VideoImageComponent from '../../common/parts/VideoImageComponent.vue';
    import SetLicenseModalComponent from './../../common/parts/SetLicenseModalComponent.vue';
    import CheckCreativePermission from '../../common/parts/services/CheckCreativePermissionService';

    Vue.directive('color-license', ColorLicensesDirective);

    export default {

        middleware: ['auth', 'brand'],

        name: 'show-media-details',

        mixins: [CheckCreativePermission],

        computed: mapGetters({
            user: 'auth/user',
            selectedBrand: 'creative/selectedBrand'
        }),

        components: {
            Modal,
            ModalHeader,
            ModalBody,
            ShareMediaModalComponent,
            Card,
            ShowMediaDetails,
            ColorLicensesDirective,
            VideoImageComponent,
            SetLicenseModalComponent,
            SetUsageLicenseModal
        },

        props: {
            media: {
                'default': undefined
            },
            submit: {
                'default': true
            },
            show: {
                'default': false
            }
        },

        data: () => ({
            showLicenseModal: false,
            showShareModal: false,
            licenseTypes: false,
            license: false,
            parentLicense: false,
        }),

        methods: {
            async submitMedia(media) {
                await this.$store.dispatch('media/submitUpload', media);
            },
            onClose() {
                this.$emit('close');
            },
            showModal(license) {
                if (typeof license === "undefined") {
                    this.license = null;
                } else {
                    this.license = license;
                }
                this.parentLicense = this.media.licenses[0];
                this.showLicenseModal = true;
            }
        },

        directives: {
            ColorLicensesDirective
        }

    }
</script>