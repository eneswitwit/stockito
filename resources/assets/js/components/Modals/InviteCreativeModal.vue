<template>
    <div>
        <modal v-bind:show="show">
            <modal-header v-if="isBrand()" @close="onClose">Invite a creative to your brand</modal-header>
            <modal-header v-if="isHeadOfTeam()" @close="onClose">Invite a creative to {{ this.selectedBrand.brand_name }} </modal-header>
            <modal-body>
                <template>
                    <div v-if="mailSuccess" class="alert alert-success" role="alert">
                        Invitation sent
                    </div>
                    <form @submit.prevent="sendRequest" @keydown="form.onKeydown($event)">
                        <div class="form-group">
                            <label class="control-label" for="user-email">Email</label>
                            <input v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }"
                                   id="user-email" type="email" class="form-control" name="email">
                            <has-error :form="form" field="email"></has-error>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="user-role">Role</label>
                            <input v-model="form.role" :class="{ 'is-invalid': form.errors.has('role') }" id="user-role"
                                   type="text" class="form-control" name="role">
                            <has-error :form="form" field="role"></has-error>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-radio">
                                <input v-model="form.position" type="radio" id="customRadio1" name="position"
                                       class="custom-control-input" value="3">
                                <label class="custom-control-label" for="customRadio1">Search Only User</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input v-model="form.position" type="radio" id="activeEditUser" name="position"
                                       class="custom-control-input" value="2">
                                <label class="custom-control-label" for="activeEditUser">Active Editing User</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input v-model="form.position" type="radio" id="headOfTeam" name="position"
                                       class="custom-control-input" value="1">
                                <label class="custom-control-label" for="headOfTeam">Head of Team</label>
                                <has-error :form="form" field="position"></has-error>
                            </div>
                        </div>

                        <div class="form-group text-center">
                            <button class="btn btn-primary btn-sm btn-block" :disabled="form.busy" type="submit">
                                Invite
                            </button>
                        </div>
                    </form>
                </template>
            </modal-body>
        </modal>
    </div>
</template>

<script>
    import {Form, HasError, AlertError} from 'vform'
    import Modal from '../../components/Modal/ModalLarge.vue';
    import ModalHeader from '../../components/Modal/ModalHeader.vue';
    import ModalBody from '../../components/Modal/ModalBody.vue';
    import CheckCreativePermission from '../../pages/common/parts/services/CheckCreativePermissionService';
    import {mapGetters} from 'vuex';

    export default {
        name: 'invite-creative-modal',

        components: {
            Modal,
            ModalHeader,
            ModalBody
        },

        middleware: ['auth', 'subscribed'],

        mixins: [CheckCreativePermission],

        computed: mapGetters({
            user: 'auth/user',
            selectedBrand: 'creative/selectedBrand',
        }),

        data: () => ({
            mailSuccess: false,
            form: new Form({
                email: '',
                role: '',
                position: 3
            })
        }),

        props: {
            media: {},
            show: {
                'default': false
            }
        },

        methods: {
            async sendRequest() {
                let url = this.selectedBrand ? `/api/brand/${this.selectedBrand.id}/invite-creative` : '/api/brand/invite-creative';
                let {data} = await this.form.post(url);
                if (data.success) {
                    this.mailSuccess = true;
                    this.$emit('sendMessage');
                }
            },
            onClose() {
                this.mailSuccess = false;
                this.form.reset();
                this.$emit('close')
            }
        }
    }
</script>