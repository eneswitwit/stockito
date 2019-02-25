<template>

    <nav class="navbar navbar-expand-lg navbar-light">

        <div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2">
            <ul class="navbar-nav mr-auto stockito-logo">
                <li>
                    <a @click="showDashboard">
                        <img class="navbar-logo" :src="require('../../images/logo.png')"/>
                    </a>
                </li>
            </ul>
        </div>

        <div class="mx-auto order-0">
            <form class="navbar-brand mx-auto" @submit.prevent="submitSearch" v-if="user">
                <div class="input-group">
                    <input class="form-control" id="search" v-model="search" type="search" placeholder="Search"
                           aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary search-button" @click="submitSearch" type="button">
                            <fa style="color: grey !important;" icon="search"/>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="navbar-collapse collapse w-100 order-3 dual-collapse2">

            <div class="nav-item" style="margin:auto;">
                <span v-if="selectedBrand">
                    <img v-if="selectedBrand.logo" :class="'brand-logo-nav'" :src="logoConvert(selectedBrand.logo)">
                    {{ selectedBrand.company_name.substring(0,8) + '...' }}
                </span>
                <button v-if="user && canUpload()" class="btn btn-primary" @click="showUploadModal = true">Upload File</button>
            </div>

            <!-- Authenticated -->
            <div v-if="user" class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-dark"
                   href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                   style="color: #fff !important;">
                    {{ user.email }}
                </a>
                <div class="dropdown-menu">
                    <router-link :to="{ name: 'dashboard' }" class="dropdown-item pl-3">
                        <fa icon="chart-bar" fixed-width/>
                        {{ $t('dashboard') }}
                    </router-link>
                    <router-link v-if="user.creative" :to="{ name: 'creative.brands' }"
                                 class="dropdown-item pl-3">
                        <fa icon="certificate" fixed-width/>
                        {{ $t('brands') }}
                    </router-link>
                    <router-link :to="{ name: 'settings.profile' }" class="dropdown-item pl-3">
                        <fa icon="cog" fixed-width/>
                        {{ $t('settings') }}
                    </router-link>
                    <div style="padding: .25rem 1.5rem;" v-if="selectedBrand"> {{ selectedBrand.company_name }}</div>
                    <div v-if="user.creative && selectedBrand" style="padding: .25rem 1.5rem;"> <hr style="margin-top:0;"> </div>
                    <router-link
                            v-if="user && (user.brand || ((isHeadOfTeam() || isActiveEditing()) && selectedBrand))"
                            :to="{ name: 'brand.creatives' }" class="dropdown-item pl-3">
                        <fa icon="fire" fixed-width/>
                        {{ $t('creatives') }}
                    </router-link>
                    <router-link v-if="user.brand" :to="{ name: 'medias' }" class="dropdown-item pl-3">
                        <fa icon="image" fixed-width/>
                        {{ $t('media') }}
                    </router-link>
                    <router-link v-if="user.brand" class="dropdown-item pl-3" :to="{ name: 'licenses' }">
                        <fa icon="list-ul" fixed-width/>
                        {{ $t('licenses') }}
                    </router-link>
                    <router-link v-if="user.creative && selectedBrand"
                                 :to="{ name: 'creative.brand.medias', params: { creative_brand_id: selectedBrand.id  }}"
                                 class="dropdown-item pl-3">
                        <fa icon="image" fixed-width/>
                        {{ $t('media') }}
                    </router-link>
                    <router-link v-if="user.brand" :to="{ name: 'uploaded' }" class="dropdown-item pl-3">
                        <fa icon="image" fixed-width/>
                        {{ $t('uploads') }}
                    </router-link>
                    <router-link v-if="user.creative && !isSearchOnly() && selectedBrand"
                                 :to="{ name: 'uploaded', params: { creative_brand_id: selectedBrand.id  }}"
                                 class="dropdown-item pl-3">
                        <fa icon="image" fixed-width/>
                        {{ $t('uploads') }}
                    </router-link>


                    <div style="padding: .25rem 1.5rem;"> <hr style="margin:0;"> </div>
                    <a @click.prevent="logout" class="dropdown-item pl-3" href="#">
                        <fa icon="sign-out-alt" style="color: rgb(199,212,230);" fixed-width/>
                        {{ $t('logout') }}
                    </a>
                </div>
            </div>
            <!-- Guest -->
            <template v-else>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <router-link :to="{ name: 'login' }" style="color: #fff !important;" class="nav-link"
                                     active-class="active">
                            {{ $t('login') }}
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link :to="{ name: 'register' }" style="color: #fff !important;" class="nav-link"
                                     active-class="active">
                            {{ $t('register') }}
                        </router-link>
                    </li>
                </ul>
            </template>
            <upload-file-modal-component @close="closeModal"
                                         v-bind:modalShow="showUploadModal"></upload-file-modal-component>
        </div>
    </nav>
</template>

<script>
    import {mapGetters} from 'vuex'
    import LocaleDropdown from './LocaleDropdown'
    import Modal from './Modal/ModalLarge.vue';
    import ModalBody from './Modal/ModalBody.vue';
    import Card from './Card.vue';
    import UploadFileModalComponent from './Modals/UploadFIleModalComponent.vue';
    import CheckCreativePermission from '../pages/common/parts/services/CheckCreativePermissionService';

    export default {

        mixins: [CheckCreativePermission],

        data: () => ({
            appName: window.config.appName,
            showUploadModal: false,
            search: '',
            searchQuery: {
                selectedBrand: undefined,
                q: undefined
            }
        }),

        computed: {
            selectedBrand() {
                return this.$store.getters['creative/selectedBrand'];
            },
            user() {
                return this.$store.getters['auth/user'];
            },
            selectedBrand() {
                return this.$store.getters['creative/selectedBrand'];
            },
            creativeRole() {
                return this.$store.getters[ 'media/creativeRole'];
            }
        },

        components: {
            UploadFileModalComponent,
            Card,
            ModalBody,
            Modal,
            LocaleDropdown
        },

        methods: {

            showDashboard() {
                window.location.href = '/';
            },
            closeModal() {
                this.showUploadModal = false;
            },
            /*computed: {
                user() {
                    return this.$store.getter['auth/user'];
                }
            },*/
            async logout() {
                // Log out the user.
                this.$store.dispatch('creative/setSelectedBrand', {selectedBrand: null});
                await this.$store.dispatch('auth/logout');

                // Redirect to login.
                this.$router.push({name: 'login'})
            },
            submitSearch() {
                this.searchQuery.q = this.search;
                if (this.selectedBrand) {
                    this.searchQuery.selectedBrand = this.selectedBrand.id;
                }

                this.$store.dispatch('media/setFilterQuery', {query: this.searchQuery});
                if (document.getElementById('closeAdvancedSearch') !== null) {
                    document.getElementById('closeAdvancedSearch').click();
                }
                this.$store.dispatch('media/setFilter', {filter: ''});
                this.$router.push({name: 'medias'})
            },
            logoConvert(path) {
                return path.replace("public", "/storage");
            }
        }
    }
</script>

