<template>
    <card class="mt-4" v-if="selectedBrand" :title="$t('brand') + ': ' + selectedBrand.brand_name">
        <div class="card-body">
            <h5 class="card-title mb-4">{{ $t('brand_contact_details') }}</h5>
            <p class="card-text">
                <router-link class="nav-link" :to="{ name: 'creative.brand.medias', params: { creative_brand_id: selectedBrand.id  }}">Brand Media's</router-link>
            </p>
            <p class="card-text">
                <strong>{{ $t('company_name') }}: </strong> {{ selectedBrand.company_name }}
            </p>
            <p class="card-text">
                <strong>{{ $t('email') }}: </strong> {{ selectedBrand.user.email }}
            </p>
            <h5 class="card-title mt-4">{{ $t('my_team') }}</h5>
            <p class="card-text">
                <strong>{{ $t('head_of_team') }}: {{headOfTeam}}</strong>
            </p>
            <p class="card-text">
                <strong>{{ $t('uploaded_files') }}: </strong>
            </p>
            <p class="card-text">
                <strong>{{ $t('team_members') }}: </strong>
            </p>
            <ul v-if="selectedBrand.creatives && selectedBrand.creatives.length" class="list-group list-group-flush">
                <li v-for="member in selectedBrand.creatives" class="list-group-item">{{ member.first_name + ' ' + member.last_name }}</li>
            </ul>
        </div>
    </card>
</template>

<script>
    import {HEAD_OF_TEAM} from '../../common/parts/services/constants';

    export default {
        name: "BrandDetails",
        mounted() {
            window.scrollTo(0,document.body.scrollHeight);
        },
        computed: {
            selectedBrand() {
                return this.$store.getters['creative/selectedBrand'];
            },
            headOfTeam() {
                let headOfTeam = this.selectedBrand.creatives.filter(creative => creative.pivot.position === HEAD_OF_TEAM).pop();
                return headOfTeam ? `${headOfTeam.first_name} ${headOfTeam.last_name}` : '';
            }
        },
        /*methods: {
          headOfTeam() {
              let headOfTeam = this.selectedBrand.creatives.filter(creative => creative.pivot.position === HEAD_OF_TEAM).pop();
              return headOfTeam ? `${headOfTeam.first_name} ${headOfTeam.last_name}` : '';
          }
        }*/
    }
</script>

<style scoped>

</style>