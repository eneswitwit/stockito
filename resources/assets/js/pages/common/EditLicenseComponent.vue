<template>
    <card v-if="license" :title="'License ' + license.type + ' for ' + (license.media ? license.media.origin_name : '')">
        <license-form-component v-if="license" :media="license.media" :license="license"></license-form-component>
    </card>
</template>

<script>
  import Card from '../../components/Card.vue';
  import DatePicker from 'vue-datepicker';
  import LicenseFormComponent from './parts/forms/LicenseFormComponent.vue';

  export default {
    components: {
      LicenseFormComponent,
      Card,
      DatePicker
    },
    name: 'edit-license-component',
    created() {
      this.$store.dispatch('license/fetchLicense', this.$route.params.id);
    },
    computed: {
      license() {
        return this.$store.getters['license/license'];
      }
    }
  }
</script>