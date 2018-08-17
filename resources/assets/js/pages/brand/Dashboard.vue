<template>
    <div>
        <h1>Stockito Brand dashboard</h1>
        <browse-activity></browse-activity>
        <br/>
        <soon-expiring-licenses-component></soon-expiring-licenses-component>
        <br/>
        <used-storage-widget-component></used-storage-widget-component>
        <br/>
        <subscription-details-component></subscription-details-component>
    </div>
</template>

<script>
    import BrowseActivity from '../common/parts/BrowseActivities.vue';
    import SoonExpiringLicensesComponent from '../common/parts/SoonExpiringLicensesComponent.vue';
    import SubscriptionDetailsComponent from './parts/SubscriptionDetailsComponent.vue';
    import UsedStorageWidgetComponent from '../common/parts/UsedStorageWidgetComponent.vue';

    export default {
      components: {
        UsedStorageWidgetComponent,
        SubscriptionDetailsComponent,
        SoonExpiringLicensesComponent,
        BrowseActivity
      },
      name: 'dashboard',
    }
</script>