<template>

    <modal :show.sync="show" @close="$emit('update:show', false)">

        <modal-header class="card-header" @close="$emit('update:show', false)">
            Set License
        </modal-header>

        <modal-body>
            <license-form-component
                    v-if="selectedMedia"
                    @saved="savedLicense"
                    :media="media"
                    :license="media.license"
            ></license-form-component>
        </modal-body>

    </modal>
</template>

<script>

    import DatePicker from 'vue-datepicker';
    import Modal from '../../../components/Modal/ModalLarge.vue';
    import ModalHeader from '../../../components/Modal/ModalHeader.vue';
    import ModalBody from '../../../components/Modal/ModalBody.vue';
    import LicenseFormComponent from './forms/LicenseFormComponent.vue';

    export default {

        name: 'set-license-modal-component',

        components: {
            LicenseFormComponent,
            ModalBody,
            ModalHeader,
            Modal,
            DatePicker
        },

        props: [
            'show',
            'media',
            'license'
        ],

        computed: {
            selectedMedia() {
                return this.$store.getters['media/selectedMedia'];
            },
        },

        methods: {
            savedLicense(licenses) {
                this.$store.dispatch('media/attachLicenses', {uploads: this.selectedMedia, licenses: licenses}).then(() => {
                    this.$emit('update:show', false);
                });
                this.$emit('update:show', false)
            },

            submitAddLicense() {
                this.form.startDate = this.date.startDate.time;
                this.form.expireDate = this.date.expireDate.time;
                this.form.post('/api/medias/' + this.media.id + '/add-license').then(({data}) => {

                    this.$store.dispatch('media/updateUpload', {upload: data}).then(() => {
                        this.$emit('update:show', false);
                    });
                });

            }
        }
    }
</script>