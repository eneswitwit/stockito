<template>
    <div>
        <b-row>
            <b-col>
                <browse-activity></browse-activity>
            </b-col>
            <b-col>
                <soon-expiring-licenses-component></soon-expiring-licenses-component>
                <br/>
                <used-storage-widget-component></used-storage-widget-component>
                <br/>
                <subscription-details-component></subscription-details-component>
            </b-col>
        </b-row>
    </div>
</template>

<script>
    /** import */
    import BrowseActivity from '../common/parts/BrowseActivities.vue';
    import SoonExpiringLicensesComponent from '../common/parts/SoonExpiringLicensesComponent.vue';
    import SubscriptionDetailsComponent from './parts/SubscriptionDetailsComponent.vue';
    import UsedStorageWidgetComponent from '../common/parts/UsedStorageWidgetComponent.vue';

    /** export */
    export default {

        /** components */
        components: {
            UsedStorageWidgetComponent,
            SubscriptionDetailsComponent,
            SoonExpiringLicensesComponent,
            BrowseActivity
        },

        /** component name */
        name: 'dashboard',
    }
</script>