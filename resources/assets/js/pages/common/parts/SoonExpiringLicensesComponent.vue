<template>
    <div class="card">
        <div class="card-header dashboard-card">
            {{ $t('soon_expiring_licenses') }}
        </div>
        <ul class="list-group">
            <li v-for="license in licenses" class="list-group-item d-flex justify-content-between align-items-center">
                License {{ license.type }} expiring {{ license.expiredAt.dMY }} ({{ diffDate(license.expiredAt.original.date) }})
            </li>
        </ul>
        <div class="card-footer">
            <router-link :to="{name: 'licenses'}">{{ $t('see_all') }}</router-link>
        </div>
    </div>
</template>

<script>
  import axios from 'axios';
  import moment from 'moment';

  export default {
    name: 'soon-expiring-licenses-component',
    created () {
      this.getSoonExpiringLicenses();
    },
    data: () => ({
      licenses: []
    }),
    methods: {
      getSoonExpiringLicenses () {
        axios.get('api/licenses/soon-expiring').then(response => {
          this.licenses = response.data;
        });
      },
      dateExpiring (time) {
        return new moment(time).format('DD.MM.YYYY')
      },
      diffDate (time) {
        return new moment(time).fromNow(true);
      },
    }
  }
</script>