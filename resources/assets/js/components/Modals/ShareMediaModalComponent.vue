<template>
    <div>
        <modal v-bind:show="show">
            <modal-header @close="mailSuccess = false; $emit('close'); shareUrl = '';">{{ $t('sharing') }}
            </modal-header>
            <modal-body>
                <ul class="nav nav-tabs card-header-tabs mb-1">
                    <li class="nav-item" id="shareNav">
                        <a @click="shareType = 'url'" class="nav-link" :class="{'active': shareType === 'url'}"
                        id="shareNav" href="#">URL</a>
                    </li>
                    <li class="nav-item" id="shareNav">
                        <a @click="shareType = 'email'" class="nav-link" :class="{'active': shareType === 'email'}"
                        id="shareNav" href="#">Email</a>
                    </li>
                </ul>
                <template v-if="shareType === 'url'">
                    <h4>Sharing Options</h4>
                    <div class="form-group">
                        <div class="custom-control custom-radio">
                            <input id="link-never-expires" type="radio" @change="shareUrl = ''"
                                   class="custom-control-input" value="LINK_NEVER_EXPIRES" name="url_share_type"
                                   v-model="share.type">
                            <label class="custom-control-label" for="link-never-expires">Link never expires</label>
                        </div>
                    </div>
                    <div class="form-group form-inline">
                        <div class="custom-control custom-radio">
                            <input id="time-limited-link" class="custom-control-input" @change="shareUrl = ''"
                                   type="radio" value="TIME_LIMITED_LINK" name="url_share_type" v-model="share.type">
                            <label class="custom-control-label d-inline-block" for="time-limited-link">Time-limited
                                Link. Expires in <input v-model="share.days" type="text"
                                                        class="form-control form-control-sm col-md-2 ml-sm-2 mr-sm-2"
                                                        @input="shareUrl = ''"
                                                        :disabled="share.type !== 'TIME_LIMITED_LINK'"> days</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-radio">
                            <input id="one-time-link" class="custom-control-input" @change="shareUrl = ''" type="radio"
                                   value="ONE_TIME_LINK" name="url_share_type" v-model="share.type">
                            <label class="custom-control-label" for="one-time-link">One-time link</label>
                        </div>
                    </div>
                    <div v-show="!shareUrl" class="form-group">
                        <button class="btn btn-primary" @click="shareMedia">Get Link</button>
                    </div>
                    <div v-show="!!shareUrl" class="form-group">
                        <label for="share-media-link"> <span class="font-weight-bold"> Link </span> </label>
                        <div class="input-group">
                            <input id="share-media-link" type="text" v-model="shareUrl" class="form-control">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" v-clipboard:copy="shareUrl" type="button">Copy
                                    Link
                                </button>
                            </div>
                        </div>
                    </div>
                </template>

                <template v-if="shareType === 'email'">
                    <div v-if="mailSuccess" class="alert alert-success" role="alert">
                        Mail sent
                    </div>
                    <form @submit.prevent="sendShareEmail">
                        <div class="form-group">
                            <label for="">Email address</label>
                            <input @input="mailSuccess = false" class="form-control"
                                   :class="{ 'is-invalid': shareEmail.errors.has('email') }" type="email"
                                   v-model="shareEmail.email" name="email">
                            <has-error :form="shareEmail" field="email"/>
                        </div>
                        <div class="form-group">
                            <label for="">Text</label>
                            <textarea @input="mailSuccess = false" class="form-control"
                                      :class="{ 'is-invalid': shareEmail.errors.has('text') }" name="text" id=""
                                      cols="30" rows="10" v-model="shareEmail.text"></textarea>
                            <has-error :form="shareEmail" field="text"/>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary btn-sm btn-block" type="submit">Send</button>
                        </div>
                    </form>
                </template>

            </modal-body>
        </modal>

    </div>
</template>

<script>
    import axios from 'axios';
    import Form from 'vform';
    import Modal from '../../components/Modal/ModalLarge.vue';
    import ModalHeader from '../../components/Modal/ModalHeader.vue';
    import ModalBody from '../../components/Modal/ModalBody.vue';

    export default {
        name: 'share-media-modal-component',
        components: {
            Modal,
            ModalHeader,
            ModalBody
        },

        data: () => ({
            shareType: 'url',
            shareUrl: '',
            share: {
                type: 'LINK_NEVER_EXPIRES',
                days: 1,
                medias: []
            },
            shareEmail: new Form({
                email: '',
                text: 'Hi, I\'d like to share some files with you.'
            }),
            mailSuccess: false
        }),

        props: {
            medias: {
                'default': []
            },
            show: {
                'default': false
            }
        },

        methods: {
            async shareMedia() {
                this.share.medias = this.medias;
                let {data} = await axios.post('/api/medias/share', this.share);

                if (data.success) {
                    this.shareUrl = data.data.link;
                }
            },
            async sendShareEmail() {
                this.shareEmail.medias = this.medias;
                let {data} = await this.shareEmail.post('/api/medias/share-to-email');
                if (data.success) {
                    this.mailSuccess = true
                }
            }
        }
    }
</script>