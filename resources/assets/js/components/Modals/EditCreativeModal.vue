<template>
    <div>
        <modal v-bind:show="showModal">
            <modal-header @close="onClose">Edit Creative</modal-header>
            <modal-body>
                <div class="card">
                    <div class="card-body">
                        <template>
                            <div v-if="editSuccess" class="alert alert-success" role="alert">
                                Creative has been edited
                            </div>
                            <form @submit.prevent="sendRequest" @keydown="form.onKeydown($event)">
                                <div class="form-group">
                                    <label class="control-label" for="user-role">Role</label>
                                    <input v-model="form.role" :class="{ 'is-invalid': form.errors.has('role') }" id="user-role" type="text" class="form-control" name="role">
                                    <has-error :form="form" field="role"></has-error>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-radio">
                                        <input v-model="form.position" type="radio" id="editSearchOnly" name="position" class="custom-control-input" value="3">
                                        <label class="custom-control-label" for="editSearchOnly">Search Only User</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input v-model="form.position" type="radio" id="editActiveEditUser" name="position" class="custom-control-input" value="2">
                                        <label class="custom-control-label" for="editActiveEditUser">Active Editing User</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input v-model="form.position" type="radio" id="editHeadOfTeam" name="position" class="custom-control-input" value="1">
                                        <label class="custom-control-label" for="editHeadOfTeam">Head of Team</label>
                                        <has-error :form="form" field="position"></has-error>
                                    </div>
                                </div>

                                <div class="form-group text-center">
                                    <button class="btn btn-primary btn-lg" :disabled="form.busy" type="submit">Update</button>
                                </div>
                            </form>
                        </template>
                    </div>
                </div>
            </modal-body>
        </modal>
    </div>
</template>

<script>
    import { Form, HasError, AlertError } from 'vform'
    import Modal from '../../components/Modal/ModalLarge.vue';
    import ModalHeader from '../../components/Modal/ModalHeader.vue';
    import ModalBody from '../../components/Modal/ModalBody.vue';

    export default {
        name: 'edit-creative-modal',
        components: {
            Modal,
            ModalHeader,
            ModalBody
        },
        data: () => ({
            editSuccess: false,
        }),
        props: {
            media: {},
            show: {
                'default': false
            }
        },
        computed: {
            form() {
                let form = new Form({
                    id: '',
                    brandCreativeId: '',
                    role: '',
                    position: ''
                });
                return Object.assign(form, this.$store.state.creative.editCreative);
            },
            showModal() {
                return this.$store.getters['creative/showEditModal'];
            }
        },
        methods: {
            async sendRequest() {
                let { data } = await this.form.post('/api/brand/edit-creative');
                if (data.success) {
                    this.editSuccess = true;
                    this.$emit('editCreative');
                }
            },
            onClose() {
                this.editSuccess = false;
                this.form.reset();
                this.$store.dispatch('creative/hideEditModal');
            }
        }
    }
</script>