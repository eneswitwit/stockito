<template>
  <div class="row">
    <div class="col-md-3">
      <card :title="$t('settings')" class="settings-card">
        <ul class="nav flex-column nav-pills">
          <li v-for="tab in tabs" class="nav-item">
            <router-link :to="{ name: tab.route }" class="nav-link" active-class="active">
              <fa :icon="tab.icon" fixed-width/>
              {{ tab.name }}
            </router-link>
          </li>
        </ul>
      </card>
    </div>

    <div class="col-md-9">
      <transition name="fade" mode="out-in">
        <router-view/>
      </transition>
    </div>
  </div>
</template>

<script>
export default {
  middleware: 'auth',

  computed: {
    tabs () {
      let menu = [{
        icon: 'user',
        name: this.$t('profile'),
        route: 'settings.profile'
      }, {
        icon: 'lock',
        name: this.$t('password'),
        route: 'settings.password'
      }];

      if (this.$store.getters['auth/isBrand']) {
        menu.push(
//        {
//          icon: 'credit-card',
//          name: this.$t('payment_details'),
//          route: 'settings.payments'
//        },
          {
            icon: 'hdd',
            name: this.$t('storage_and_ftp'),
            route: 'settings.storage-ftp'
          },
          {
            icon: 'file-alt',
            name: this.$t('subscription_and_invoices'),
            route: 'settings.subscription-invoices'
          },
        );
      }

      return menu;
    }
  }
}
</script>

<style>
.settings-card .card-body {
  padding: 0;
}
</style>
