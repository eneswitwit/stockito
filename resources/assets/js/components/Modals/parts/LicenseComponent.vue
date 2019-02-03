<template>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <table class="table">
                    <thead>
                    <tr>
                        <th>
                            License Type
                        </th>
                        <th v-show="media.licenses[0].license_type == 2">
                            Usage
                        </th>
                        <th v-show="[1,2].indexOf(parseInt(media.licenses[0].license_type)) != -1">
                            Printrun
                        </th>
                        <th v-show="media.licenses[0].license_type == 4">
                            Any Limitations
                        </th>
                        <th v-show="media.licenses[0].license_type == 3">
                            Territory
                        </th>
                        <th>
                            Expiration
                        </th>
                        <th>
                            Invoice Number
                        </th>
                        <th>
                            Invoice
                        </th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>


                    <tr v-if="parentLicense != null" v-for="license in usageLicenses">
                        <td>
                                    <span :style="{color: media.license.color}"
                                          class="license-badge" @click="showLicenseModal = true">
                                        {{ media.licenses[0] ? media.licenses[0].type : '-' }}
                                    </span>
                        </td>
                        <td v-show="media.licenses[0].license_type == 2">
                            {{ license.usage && license.usage !== 'null' ? license.usage : '-' }}
                        </td>
                        <td v-show="[1,2].indexOf(parseInt(media.licenses[0].license_type)) != -1">
                            {{ license.printrun && license.printrun !== 'null' ? license.printrun : '-' }}
                        </td>
                        <td v-show="media.licenses[0].license_type == 4">
                            {{ license.any_limitations && license.any_limitations !== 'null' ?
                            license.any_limitations : '-' }}
                        </td>
                        <td v-show="media.licenses[0].license_type == 3">
                            {{ license.territory && license.territory !== 'null' ? license.territory : '-' }}
                        </td>
                        <td v-color-license:color="license">
                            {{ license.expiredAt && license.expiredAt !== 'null' ? license.expiredAt.dMY : '-'
                            }}
                        </td>
                        <td>
                            {{ license.invoiceNumber && license.invoiceNumber !== 'null' ? license.invoiceNumber
                            : '-' }}
                        </td>
                        <td>
                            <div v-if="license.billFile" class="row">
                                <a :href="license.url" class="btn btn-link btn block">
                                    {{ license.billFileOriginName }}
                                </a>
                            </div>
                        </td>
                        <td v-if="canAccess(media)" class="text-right">
                            <button type="button"
                                    class="btn btn-primary btn-sm"
                                    v-on:click="showModal(license)"
                                    v-on:close="onClose">
                                {{ $t('edit') }}
                            </button>
                        </td>
                    </tr>


                    </tbody>
                </table>
            </div>
            <button @click="showModal()" class="btn btn-primary mt-2"> Add new license</button>
        </div>
    </div>


    <set-usage-license-modal
            :show.sync="showLicenseModal"
            :parentLicense="parentLicense"
            :usageLicense="usageLicense"
    ></set-usage-license-modal>

</template>

<script>

    import Modal from '../../../components/Modal/ModalLarge.vue';
    import ModalHeader from '../../../components/Modal/ModalHeader.vue';
    import ModalBody from '../../../components/Modal/ModalBody.vue';
    import SetUsageLicenseModal from '../../../components/Modals/SetUsageLicenseModal.vue';
    import Card from '../../../components/Card.vue';
    import CheckCreativePermission from '../../../pages/common/parts/services/CheckCreativePermissionService';
    import ColorLicensesDirective from '../../../directives/ColorLicensesDirective';
    import SetLicenseModalComponent from './../../pages/common/parts/SetLicenseModalComponent.vue';

    Vue.directive('color-license', ColorLicensesDirective);

    export default {

        name: "LicenseComponent",

        components: {
            Modal,
            ModalHeader,
            ModalBody,
            Card,
            ColorLicensesDirective,
            SetLicenseModalComponent,
            SetUsageLicenseModal
        },

        mixins: [CheckCreativePermission],

        props: [
            'show',
            'usageLicenses',
            'parentLicense'
        ],

        data: () => ({
            showLicenseModal: false,
            licenseTypes: false,
            license: false,
        }),

        methods: {

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
