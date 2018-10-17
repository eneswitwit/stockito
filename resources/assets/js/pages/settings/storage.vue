<template>

    <div>
        <div class="card mb-3">
            <div class="card-header dashboard-card">
                Used Storage
            </div>

            <div class="card-body">

                <div class="text-center">{{ storage.usedFormated }} of {{ storage.allFormated }}</div>
                <div class="progress">
                    <div class="progress-bar" role="progressbar" :style="{width: storage.used / storage.all * 100+'%'}"
                         :aria-valuenow="storage.used / storage.all * 100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>

        <div class="card">

            <div class="card-header dashboard-card">
                SFTP Access
            </div>

            <div class="card-body">
                <table class="table">
                    <tbody>
                    <tr>
                        <th>Host</th>
                        <td>stockito.com</td>
                    </tr>
                    <tr>
                        <th>Port</th>
                        <td>2222</td>
                    </tr>
                    <tr>
                        <th>User</th>
                        <td>{{ brand.ftp.user }}</td>
                    </tr>
                    <tr>
                        <th>Password</th>
                        <td> Your account password</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</template>

<script>
    import axios from 'axios'

    export default {
        name: 'storage-and-ftp',
        scrollToTop: false,

        metaInfo() {
            return {title: this.$t('settings')}
        },

        created() {
            this.getStorage();
        },

        data: () => ({
            storage: {},
        }),
        computed: {
            brand() {
                return this.$store.getters['auth/brand'];
            }
        },
        methods: {
            async getStorage() {
                let {data} = await axios.get('/api/used-storage');
                this.storage = data;
            }
        }
    }
</script>
