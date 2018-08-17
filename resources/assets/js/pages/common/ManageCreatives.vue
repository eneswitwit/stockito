<template>
    <div>
        <div class="card">
            <div class="card-header">{{ $t('manage_creatives') }}</div>
            <div class="card-body">
                <div class="col-xs-12 table-responsive">
                    <datatable :columns="dataTable.columns" :data="creaitves">
                        <template slot-scope="{ row, columns }">
                            <tr :class="{info: dataTable.selectedRows.indexOf(row) !== -1}" @click="selectRow(row)">
                                <template>
                                    <datatable-cell v-for="(column, j) in columns" :key="j" :column="column" :row="row"></datatable-cell>
                                </template>
                            </tr>
                        </template>
                    </datatable>
                </div>
            </div>
            <div class="card-footer">
                <datatable-pager class="custom-pagination" v-model="dataTable.page" type="abbreviated" :per-page="dataTable.perPage"></datatable-pager>
            </div>
            <div class="card-footer">
                <button v-if="isBrand() || isHeadOfTeam()" class="btn btn-primary btn-block" @click="addCreative">{{ $t('invite_creative') }}</button>
            </div>
        </div>
        <invite-creative-modal :show="showModal" @close="showModal = false" @sendMessage="getCreatives"></invite-creative-modal>
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

        created () {
            this.getCreatives();
            if (this.isSearchOnly() || this.isActiveEditing()) {
                this.dataTable.columns.splice(5, 1);
            }
        },
        data: () => ({
            creaitves: [],
            dataTable: {
                selectedRows: [],
                columns: [
                    {label: 'Name', field: 'name', filterable: true},
                    {label: 'Email', field: 'email', filterable: true},
                    {label: 'Company', field: 'company'},
                    {label: 'Role', field: 'position'},
                    {label: 'Permission role', field: 'role'},
                    {label: 'Modify', component: ButtonEditCreativeComponent}
                ],
                page: 1,
                perPage: 10
            },
            showModal: false
        }),
        methods: {
            getCreatives () {
                let url = this.selectedBrand ? `api/brand/${this.selectedBrand.id}/creatives` : 'api/brand/creatives';
                axios.get(url).then(response => {
                    this.creaitves = response.data;
                });
            },
            addCreative() {
                this.showModal = true;
            },
            selectRow(row) {
                if(this.dataTable.selectedRows.indexOf(row) !== -1){
                    let index = this.dataTable.selectedRows.indexOf(row);
                    this.dataTable.selectedRows.splice(index, 1);

                    return;
                }

                this.dataTable.selectedRows.push(row);
            }
        }
    }
</script>

<style scoped>

</style>