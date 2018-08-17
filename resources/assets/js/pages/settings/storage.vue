<template>
    <card :title="$t('storage_and_ftp')">
        <card class="mb-3" title="Used Storage">
            <div class="text-center">{{ storage.usedFormated }} of {{ storage.allFormated }}</div>
            <div class="progress">
                <div class="progress-bar" role="progressbar" :style="{width: storage.used / storage.all * 100+'%'}" :aria-valuenow="storage.used / storage.all * 100" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </card>
        <div class="card">
            <div class="card-header">FTP Access</div>
            <table class="table">
                <tbody>
                <tr>
                    <th>Host:</th>
                    <td>{{ brand.ftp.host }}</td>
                </tr>
                <tr>
                    <th>User:</th>
                    <td>{{ brand.ftp.user }}</td>
                </tr>
                <tr>
                    <th>Password:</th>
                    <td>{{ brand.ftp.password }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </card>
</template>

<script>
  import axios from 'axios'

  export default {
    name: 'storage-and-ftp',
    scrollToTop: false,

    metaInfo () {
      return { title: this.$t('settings') }
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
        let { data } = await axios.get('/api/used-storage');
        this.storage = data;
      }
    }
  }
</script>
