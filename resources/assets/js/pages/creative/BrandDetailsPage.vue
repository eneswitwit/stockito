<template>

    <div class="container mt-5 mb-4">
        <div class="card">
            <div class="card-header dashboard-card"> {{ selectedBrand.company_name }}</div>
            <div class="card-body">
                <b-row class="mt-4" v-if="selectedBrand" :title="selectedBrand.brand_name">
                    <b-col>
                        <img v-if="selectedBrand.logo" class="card-img-top" :src="logoConvert(selectedBrand.logo)">
                    </b-col>
                    <b-col>
                        <router-link class="btn btn-primary mr-2"
                                     :to="{ name: 'creative.brand.medias', params: { creative_brand_id: selectedBrand.id  }}">
                            Media Files
                        </router-link>

                        <router-link class="btn btn-primary" v-if="!isSearchOnly()"
                                     :to="{ name: 'creative.brand.uploaded', params: { creative_brand_id: selectedBrand.id  }}">
                            Uploads
                        </router-link>

                        <h5 class="card-title main-heading mt-4"> Brand Details </h5>

                        <table class="creative-table">
                            <tr>
                                <td><strong>{{ $t('company_name') }} </strong></td>
                                <td>{{ selectedBrand.company_name }}</td>
                            </tr>
                            <tr>
                                <td><strong>{{ $t('email') }} </strong></td>
                                <td>{{ selectedBrand.user.email }}</td>
                            </tr>
                        </table>

                        <h5 class="card-title main-heading mt-4"> Brand Team </h5>

                        <table class="creative-table" v-if="selectedBrand.creatives && selectedBrand.creatives.length">
                            <tr>
                                <td><strong>{{ $t('head_of_team') }} </strong></td>
                                <td> {{headOfTeam}}</td>
                            </tr>

                            <tr v-for="(member,index) in selectedBrand.creatives">
                                <td><strong v-if="index === 0"> {{ $t('team_members') }} </strong></td>
                                <td>{{ member.first_name + ' ' + member.last_name }}</td>
                            </tr>

                        </table>

                    </b-col>
                </b-row>
            </div>
        </div>
    </div>

</template>

<script>

    import {HEAD_OF_TEAM} from '../common/parts/services/constants';
    import CheckCreativePermission from '../common/parts/services/CheckCreativePermissionService';

    export default {

        name: "BrandDetailsPage",

        mixins: [CheckCreativePermission],

        mounted() {
            window.scrollTo(0, document.body.scrollHeight);
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

        methods: {
            logoConvert(path) {
                return path.replace("public", "/storage");
            }
        }

    }
</script>
