<template>
	<div>
		<h1>Stockito Creative dashboard</h1>
		<browse-activity></browse-activity>
		<br>
		<soon-expiring-licenses-component></soon-expiring-licenses-component>
		<br>
		<brands-widget></brands-widget>
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

<style scoped>

</style>