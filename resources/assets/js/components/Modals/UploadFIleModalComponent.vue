<template>
    <div>
        <modal id="uploadModal" v-bind:show="modalShow">
            <modal-header @close="closeModal">Upload</modal-header>
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
                        <card>
                            <p>Photos (JPEG and JPG files)</p>
                            <ul>
                                <li> minimal image resolution is 4MP</li>
                                <li> maximal image resolution is 100 MP</li>
                                <li> maximal file size is 45 MB</li>
                            </ul>
                        </card>
                        <br/>
                        <card>
                            <p>Vector files (AI and EPS ­files)</p>
                        </card>
                        <br/>
                        <card v-if="brand">
                            <p> Videos (SFTP Upload) </p>
                            <p> Host stockito.com </p>
                            <p> Port 2222 </p>
                            <p> User {{ brand.ftp.user }} </p>
                            <p> Password Your account password </p>
                            <ul>
                                <li> Only MP4 files allowed </li>
                            </ul>
                        </card>

                    </div>
                </div>
            </modal-body>
        </modal>
    </div>
</template>

<script>
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
            countUploadFiles: false
        }),
        props: ['modalShow'],
        computed: {
            token() {
                return this.$store.getters['auth/token'];
            },
            brand() {
                return this.$store.getters['auth/brand'];
            },
            selectedBrand() {
                return this.$store.getters['creative/selectedBrand'];
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
            }
        }
    }
</script>