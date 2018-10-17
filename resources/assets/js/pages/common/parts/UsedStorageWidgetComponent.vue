<template>
    <div class="card mb-3">
        <div class="card-header dashboard-card">
            Used Storage
        </div>
        <div class="card-body">
            <div class="text-center">{{ sData.usedFormated }} of {{ sData.allFormated }}</div>
            <div class="progress">
                <div class="progress-bar" role="progressbar" :style="{width: sData.used / sData.all * 100+'%'}"
                     :aria-valuenow="sData.used / sData.all * 100" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div>
</template>

<script>
    import Card from '../../../components/Card.vue';
    import axios from 'axios';
    import Modal from '../../../components/Modal/ModalLarge.vue';
    import ModalHeader from '../../../components/Modal/ModalHeader.vue';
    import ModalBody from '../../../components/Modal/ModalBody.vue';

    export default {
        components: {
            ModalBody,
            ModalHeader,
            Modal,
            Card
        },
        name: 'used-storage-widget-component',
        created() {
            this.getUsedStorageData();
        },
        data: () => ({
            sData: {},
            show: false
        }),
        computed: {
            user() {
                return this.$store.getters['auth/user'];
            }
        },
        methods: {
            getUsedStorageData() {
                axios.get('/api/used-storage').then(({data}) => {
                    this.sData = data;
                });
            }
        }
    }
</script>