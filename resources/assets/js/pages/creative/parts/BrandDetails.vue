<template>

    <div class="container mt-2 mb-4">
        <card class="mt-4" v-if="selectedBrand" :title="selectedBrand.brand_name">
            <div class="card-body">

                <div class="row mb-4">
                    <router-link class="btn btn-primary mr-2"
                                 :to="{ name: 'creative.brand.medias', params: { creative_brand_id: selectedBrand.id  }}">
                        Media Files
                    </router-link>

                    <router-link class="btn btn-primary"
                                 :to="{ name: 'creative.brand.uploaded', params: { creative_brand_id: selectedBrand.id  }}">
                        Uploads
                    </router-link>
                </div>

                <hr>

                <h5 class="card-title mb-2"> Brand Details </h5>

                <img v-if="selectedBrand.logo" class="card-img-top" :src="logoConvert(selectedBrand.logo)">

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

                <hr>

                <h5 class="card-title mt-4"> Brand Team </h5>

                <table class="creative-table" v-if="selectedBrand.creatives && selectedBrand.creatives.length">
                    <tr>
                        <td><strong>{{ $t('head_of_team') }} </strong></td>
                        <td> {{headOfTeam}}</td>
                    </tr>

                    <tr v-for="(member,index) in selectedBrand.creatives">
                        <td> <strong v-if="index === 0"> {{ $t('team_members') }} </strong></td>
                        <td>{{ member.first_name + ' ' + member.last_name }}</td>
                    </tr>

                </table>

            </div>
        </card>

    </div>
</template>

<script>

    import {HEAD_OF_TEAM} from '../../common/parts/services/constants';

    export default {

        name: "BrandDetails",

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
