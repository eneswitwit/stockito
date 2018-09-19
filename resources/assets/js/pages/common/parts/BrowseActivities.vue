<template>
    <div class="card">
        <div class="card-header">
            {{ $t('browse_activity') }}
        </div>
        <ul class="list-group">
            <li v-for="activity in firstActivities" class="list-group-item d-flex">
                <span class="badge badge-primary badge-pill">{{ showTime(activity.createdAt.date) }}</span>

                <span class="activity-text">
                    <span class="font-weight-bold">
                        {{ activity.user }}
                    </span>
                    {{ activity.text }}
                </span>
            </li>
        </ul>
        <div class="card-footer">
            <a href="#" @click="modalShow = true">{{ $t('see_all') }}</a>
        </div>

        <modal v-bind:show="modalShow">
            <div class="modal-header">
                <div class="form-inline">
                    <div class="mb-2 mr-sm-2">
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" id="licensesFilter" type="checkbox"
                                   v-model="filterActivities" :value="1">
                            <label class="custom-control-label" for="licensesFilter">Licenses</label>
                        </div>
                    </div>
                    <div class="mb-2 mr-sm-2">
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" id="uploadedMediaFilter" type="checkbox"
                                   v-model="filterActivities" :value="2">
                            <label class="custom-control-label" for="uploadedMediaFilter">Uploaded Media</label>
                        </div>
                    </div>
                    <div class="mb-2 mr-sm-2">
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" id="deleteMediaFilter" type="checkbox"
                                   v-model="filterActivities" :value="4">
                            <label class="custom-control-label" for="deleteMediaFilter">Deleted Media</label>
                        </div>
                    </div>
                    <div class="mb-2 mr-sm-2">
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" id="editMediaFilter" type="checkbox"
                                   v-model="filterActivities" :value="3">
                            <label class="custom-control-label" for="editMediaFilter">Edited Media</label>
                        </div>
                    </div>
                    <div class="mb-2 mr-sm-2">
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" id="manageCreativesFilter" type="checkbox"
                                   v-model="filterActivities" :value="5">
                            <label class="custom-control-label" for="manageCreativesFilter">Manage Creatives</label>
                        </div>
                    </div>
                </div>
                <button type="button" class="close" @click="modalShow = false" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <modal-body>
                <ul class="list-group">
                    <li v-for="activity in filteredActivities" class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="activity-text">
                            <span class="font-weight-bold">
                                {{ activity.user }}
                            </span>
                            {{ activity.text }}
                        </span>
                        <span class="badge badge-primary badge-pill">{{ showTime(activity.createdAt.date) }}</span>
                    </li>
                </ul>
            </modal-body>
        </modal>
    </div>
</template>

<script>
    import axios from 'axios';
    import moment from 'moment';
    import Modal from '../../../components/Modal/ModalLarge.vue';
    import ModalHeader from '../../../components/Modal/ModalHeader.vue';
    import Checkbox from '../../../components/Checkbox.vue';
    import ModalBody from '../../../components/Modal/ModalBody.vue';

    export default {
        components: {
            ModalBody,
            Checkbox,
            ModalHeader,
            Modal
        },

        name: 'browse-activity',

        created() {
            this.getActivities();
        },

        data: () => ({
            activities: [],
            modalShow: false,
            filterActivities: [1, 2, 3, 4, 5]
        }),

        computed: {
            firstActivities() {
                return this.activities.slice(0, 10);
            },
            filteredActivities() {
                return this.activities.filter(element => this.filterActivities.indexOf(element.type) !== -1);
            }
        },

        methods: {
            getActivities() {
                axios.get('api/activities').then(response => {
                    this.activities = response.data;
                });
            },

            showTime(time) {
                return new moment(time).format('h:mm a DD.MM.YYYY')
            }

        }
    }
</script>
