<template>
    <div>

        <b-row :class="'mb-4'">
            <b-col>
                <brands-widget></brands-widget>
            </b-col>
        </b-row>
        <b-row>
            <b-col>
                <browse-activity></browse-activity>
            </b-col>
            <b-col>
                <soon-expiring-licenses-component></soon-expiring-licenses-component>
                <br>
            </b-col>
        </b-row>


    </div>
</template>

<script>
    import BrowseActivity from '../common/parts/BrowseActivities';
    import BrandsWidget from './parts/BrandsWidget';
    import SoonExpiringLicensesComponent from '../common/parts/SoonExpiringLicensesComponent';

    export default {
        components: {
            SoonExpiringLicensesComponent,
            BrandsWidget,
            BrowseActivity
        },
        middleware: ['auth', 'subscribed'],
        name: "CreativeDashboard"
    }
</script>
