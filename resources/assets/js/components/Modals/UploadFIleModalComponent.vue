<template>
    <div>
        <modal id="uploadModal" v-bind:show="modalShow">

            <modal-header @close="closeModal">
                Upload
            </modal-header>

            <modal-body>
                <div v-if="errors && errors.file" class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-danger" role="alert">
                            <p v-for="e in errors.file">{{ e }}</p>
                        </div>
                    </div>
                </div>
                <div class="row d-flex">
                    <div class="col-lg-6">
                        <vue2-dropzone id="dropzone"
                                       ref="myVueDropzone"
                                       @vdropzone-success="successUploaded"
                                       @vdropzone-sending="uploadFile"
                                       @vdropzone-error="uploadError"
                                       @vdropzone-files-added="progressUploadFiles"
                                       :options="dropzoneOptions"></vue2-dropzone>
                    </div>
                    <div class="col-lg-6">

                        <div class="card">
                            <div class="card-body">
                                <p class="description-upload">Photos (JPEG and JPG files)</p>
                                <ul class="ul-description-upload">
                                    <li> minimal image resolution is 4MP</li>
                                    <li> maximal image resolution is 100 MP</li>
                                    <li> maximal file size is 45 MB</li>
                                </ul>
                            </div>
                        </div>

                        <br/>

                        <div class="card">
                            <div class="card-body">
                                <p class="description-upload">
                                    Vector files (AI and EPS ­files)
                                </p>
                            </div>
                        </div>

                        <br/>
                        <div class="card" v-if="brand">
                            <div class="card-body">
                                <p class="description-upload"> Videos (SFTP Upload) </p>
                                <table class="upload-ftp">
                                    <tr>
                                        <td class="label">
                                            Host
                                        </td>
                                        <td style="word-break: break-word !important;">
                                            stockito.com
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label">
                                            Port
                                        </td>
                                        <td>
                                            2222
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label">
                                            User
                                        </td>
                                        <td style="word-break: break-word !important;">
                                            Your Accounts Email
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label">
                                            Password
                                        </td>
                                        <td style="word-break: break-word !important;">
                                            Your Accounts Password
                                        </td>
                                    </tr>
                                </table>
                                 <span class="ftp-info"> Only MP4 and MOV files are allowed </span>
                            </div>
                        </div>
                        <div class="card" v-if="selectedBrand">
                            <div class="card-body">
                                <p class="description-upload"> Videos (SFTP Upload) </p>
                                <table class="upload-ftp">
                                    <tr>
                                        <td class="label">
                                            Host
                                        </td>
                                        <td style="word-break: break-word !important;">
                                            stockito.com
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label">
                                            Port
                                        </td>
                                        <td>
                                            2222
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label">
                                            User
                                        </td>
                                        <td style="word-break: break-word !important;">
                                            {{  ftp.userid }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label">
                                            Password
                                        </td>
                                        <td style="word-break: break-word !important;">
                                            {{  ftp.passwd }}
                                        </td>
                                    </tr>
                                </table>
                                <span class="ftp-info"> Only MP4 and MOV files are allowed </span>
                            </div>
                        </div>
                    </div>
                </div>
            </modal-body>
        </modal>
    </div>
</template>

<script>
    import axios from 'axios';
    import Modal from '../Modal/ModalLarge.vue';
    import ModalBody from '../Modal/ModalBody.vue';
    import ModalHeader from '../Modal/ModalHeader.vue';
    import vue2Dropzone from 'vue2-dropzone';

    require('vue2-dropzone/dist/vue2Dropzone.css');

    export default {

        components: {
            ModalHeader,
            ModalBody,
            Modal,
            vue2Dropzone
        },

        name: 'upload-file-modal-component',

        data: () => ({
            showFtpSettings: false,
            filesUploaded: false,
            dropzoneOptions: {
                url: '/api/medias/upload',
                thumbnailWidth: 150,
                maxFilesize: 45,
                parallelUploads: 1,
                createImageThumbnails: true,
            },
            errors: {},
            countUploadFiles: false,
            ftp: null
        }),

        props: ['modalShow'],

        created() {
            this.getFTPUser();
        },

        computed: {
            token() {
                return this.$store.getters['auth/token'];
            },
            brand() {
                return this.$store.getters['auth/brand'];
            },
            creative() {
                return this.$store.getters['auth/creative'];
            },
            user() {
                return this.$store.getters['auth/user'];
            },
            selectedBrand() {
                return this.$store.getters['creative/selectedBrand'];
            }
        },

        watch: {
            selectedBrand: function() {
                this.getFTPUser();
            }
        },

        methods: {

            closeModal() {
                this.$refs.myVueDropzone.removeAllFiles();
                if (this.filesUploaded) {
                    this.$router.push({name: 'uploaded'});
                }
                this.$emit('close');
            },

            uploadError(file, message, xhr) {
                this.errors = message.errors;
            },

            async successUploaded(file, response) {
                this.errors = {};
                await this.$store.dispatch('media/addUpload', {media: response.data});
                this.filesUploaded = true;
                this.countUploadFiles--;
                if (this.countUploadFiles === 0) {
                    this.closeModal();
                }
            },

            uploadFile(file, xhr, formData) {
                if (this.selectedBrand) {
                    formData.append('brandId', this.selectedBrand.id);
                }
                xhr.setRequestHeader('Authorization', 'Bearer ' + this.token);
            },

            progressUploadFiles(files) {
                this.countUploadFiles = files.length;
            },

            getFTPUser() {

                let selectedBrandId = this.selectedBrand ? this.selectedBrand.id : null;

                var url = '';
                if(selectedBrandId !== null) {
                    url = `api/ftp/${this.user.id}/${selectedBrandId}`;
                } else {
                    url = `api/ftp/${this.user.id}`;
                }

                axios.get(url).then(response => {
                    this.ftp = response.data;
                });

            },
        }
    }
</script>