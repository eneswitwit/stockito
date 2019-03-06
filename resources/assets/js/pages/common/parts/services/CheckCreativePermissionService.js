import {mapGetters} from 'vuex';
import {HEAD_OF_TEAM, ACTIVE_EDITING, SEARCH_ONLY} from './constants';

export default {
    computed: Object.assign(mapGetters({
        selectedBrand: 'creative/selectedBrand',
        user: 'auth/user',
    }), {
        brandCreative() {
            return this.selectedBrand.creatives.filter(creative => creative.user_id === this.user.id).pop();
        }
    }),

    methods: {
        isBrand() {
            return this.$store.getters['auth/user'].brand ? this.$store.getters['auth/user'].brand : false;
        },
        isCreative() {
            return !!this.brandCreative;
        },
        isHeadOfTeam() {
            if (!this.selectedBrand) {
                return false;
            }
            return this.brandCreative.pivot.position === HEAD_OF_TEAM;
        },
        isActiveEditing() {
            if (!this.selectedBrand) {
                return false;
            }
            return this.brandCreative.pivot.position === ACTIVE_EDITING;
        },
        isSearchOnly() {
            if (!this.selectedBrand) {
                return false;
            }
            return this.brandCreative.pivot.position === SEARCH_ONLY;
        },
        canEdit(media = null) {
            if (media !== null) {
                if (this.user.creative && !this.selectedBrand) {
                    return false;
                }
                return this.isHeadOfTeam() || this.isActiveEditing() || this.user.brand;
            }
        },
        canUpload() {
            if (this.user.creative && !this.selectedBrand) {
                return false;
            }
            return this.isHeadOfTeam() || this.isActiveEditing() || this.user.brand;
        },
        canAccess(media) {
            /*if (this.isBrand() || this.isHeadOfTeam()) {
                return true;
            }

            let creative = this.user ? this.user.creative : null;
            if (creative === null && !this.selectedBrand) {
                return false;
            }
            console.log('this is media');
            console.log(media);
            if (media.created_by.id === this.user.id) {
                return this.isActiveEditing() || this.user.brand;
            }*/
            return true
        },
    }
}
