<template>
    <div class="container mt-4 mb-4">
        <div class="card mb-2">

            <div class="card-header dashboard-card">{{ $t('manage_creatives') }}</div>

            <div class="card-body card-body-table" style="word-break: break-word;">
                <div class="col-xs-12 table-responsive">

                    <datatable :columns="dataTable.columns" :data="creaitves">
                        <template slot-scope="{ row, columns }">
                            <tr :class="{info: dataTable.selectedRows.indexOf(row) !== -1}" @click="selectRow(row)">
                                <template>
                                    <datatable-cell v-for="(column, j) in columns" :key="j" :column="column"
                                                    :row="row"></datatable-cell>
                                </template>
                            </tr>
                        </template>
                    </datatable>

                </div>
            </div>
        </div>

        <div>
            <datatable-pager class="custom-pagination" v-model="dataTable.page" type="abbreviated"
                             :per-page="dataTable.perPage"></datatable-pager>
        </div>

        <button v-if="isBrand()" class="btn btn-primary btn-block" @click="addCreative">{{ $t('invite_creative') }}
        </button>

        <button v-if="isHeadOfTeam()" class="btn btn-primary btn-block" @click="addCreative">Invite Creative to {{ selectedBrand.company_name }}
        </button>

        <invite-creative-modal :show="showModal" @close="showModal = false"
                               @sendMessage="getCreatives"></invite-creative-modal>
        <edit-creative-modal @editCreative="getCreatives"></edit-creative-modal>

    </div>
</template>

<script>

    import axios from 'axios';
    import ButtonEditCreativeComponent from '../brand/parts/ButtonEditCreativeComponent.vue';
    import InviteCreativeModal from '../../components/Modals/InviteCreativeModal';
    import EditCreativeModal from '../../components/Modals/EditCreativeModal';
    import CheckCreativePermission from './parts/services/CheckCreativePermissionService';
    import {mapGetters} from 'vuex';

    export default {

        name: "ManageCreatives",

        components: {
            EditCreativeModal,
            InviteCreativeModal,
            ButtonEditCreativeComponent
        },

        middleware: ['auth', 'subscribed'],

        mixins: [CheckCreativePermission],

        computed: mapGetters({
            user: 'auth/user',
            selectedBrand: 'creative/selectedBrand',
        }),

        created() {
            this.setSelectedBrand();
            this.getCreatives();
            if (this.isSearchOnly() || this.isActiveEditing()) {
                this.dataTable.columns.splice(5, 1);
            }
        },

        beforeMount() {
            this.setSelectedBrand();
        },

        data: () => ({
            creaitves: [],
            dataTable: {
                selectedRows: [],
                columns: [
                    {label: 'Name', field: 'name', filterable: true},
                    {label: 'Email', field: 'email', filterable: true},
                    {label: 'Company', field: 'company', sortable: false},
                    {label: 'Role', field: 'position', sortable: false},
                    {label: 'Permission role', field: 'role', sortable: false},
                    {label: 'Modify', component: ButtonEditCreativeComponent}
                ],
                page: 1,
                perPage: 10
            },
            showModal: false
        }),

        methods: {

            getSelectedBrandId() {
                var url = window.location.href;
                var page = "manage-creatives/";
                var index = url.indexOf(page);
                var substring = url.substring(index + page.length, url.length);

                var selectedBrandId = null;
                if (substring !== '') {
                    selectedBrandId = parseInt(substring);
                } else {
                    selectedBrandId = this.selectedBrand ? this.selectedBrand.id : null;
                }
                return selectedBrandId;

            },


            setSelectedBrand() {
                var selectedBrandId = this.getSelectedBrandId();
                if (selectedBrandId) {
                    this.$store.dispatch('creative/setSelectedBrandId', {selectedBrandId});
                }
            },

            getCreatives() {
                var selectedBrandId = this.getSelectedBrandId();
                var urlMain = 'https://stockito.com/';
                let url = selectedBrandId ? `api/brand/${selectedBrandId}/creatives` : 'api/brand/creatives';

                axios.post(urlMain + url).then(response => {

                    this.creaitves = response.data;
                });
            },
            addCreative() {
                this.showModal = true;
            },
            selectRow(row) {
                if (this.dataTable.selectedRows.indexOf(row) !== -1) {
                    let index = this.dataTable.selectedRows.indexOf(row);
                    this.dataTable.selectedRows.splice(index, 1);

                    return;
                }

                this.dataTable.selectedRows.push(row);
            }
        }
    }
</script>
