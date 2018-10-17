<template>
    <div class="container mt-4">

        <div v-if="this.dataTable.selectedRows.length" class="button-top">
            <button class="btn btn-primary btn-block" @click="exportAsPDF">{{ $t('export_as_pdf') }}</button>
        </div>

        <div class="card mb-4">

            <div class="card-header dashboard-card">
                {{ $t('licenses') }}
            </div>

            <div class="card-body card-body-table">
                <div class="col-xs-12 table-responsive licenses-table">

                    <datatable :columns="dataTable.columns" :data="licenses">
                        <template slot-scope="{ row, columns }">
                            <tr :class="{info: dataTable.selectedRows.indexOf(row) !== -1}" @click="selectRow(row)">
                                <template>
                                    <datatable-cell
                                            v-for="(column, j) in columns"
                                            :key="j" :column="column"
                                            :row="row"
                                    ></datatable-cell>
                                </template>
                                <td>
                                    <button type="button"
                                            v-if="!isActiveEditing()"
                                            class="btn btn-primary btn-sm"
                                            @click="showModal(row)">
                                        {{ $t('edit') }}
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </datatable>

                </div>
            </div>

            <set-license-modal-component :show.sync="showLicenseModal"
                                         :media="media"
                                         :license="license"
                                         :selectedMedia="selectedMedia"
            ></set-license-modal-component>

        </div>

        <datatable-pager
                class="custom-pagination mb-6"
                v-model="dataTable.page"
                type="abbreviated"
                :per-page="dataTable.perPage">
        </datatable-pager>

    </div>
</template>

<script>
    import axios from 'axios';
    import SelectRowComponent from './parts/SelectRowComponent.vue';
    import SelectAllComponent from './parts/SelectAllComponent.vue';
    import ButtonEditRowComponent from './parts/ButtonEditRowComponent.vue';
    import CheckCreativePermission from '../../pages/common/parts/services/CheckCreativePermissionService';
    import {mapGetters} from 'vuex';
    import SetLicenseModalComponent from './parts/SetLicenseModalComponent.vue';

    export default {
        components: {
            SelectRowComponent,
            SelectAllComponent,
            ButtonEditRowComponent,
            SetLicenseModalComponent
        },

        mixins: [CheckCreativePermission],
        name: 'licenses-component',

        async created() {

            this.getLicenses();
            let user = this.$store.getters['auth/user'];
            if (user.creative) {
                this.dataTable.columns.splice(7, 0, {
                    label: 'Brand', field: 'brandName'
                });
            }

            window.eventBus.$on('selected', (row) => {
                if (this.dataTable.selectedRows.indexOf(row) === -1) {
                    this.dataTable.selectedRows.push(row);
                }
            });

            window.eventBus.$on('deselected', (row) => {
                if (this.dataTable.selectedRows.indexOf(row) !== -1) {
                    let index = this.dataTable.selectedRows.indexOf(row);

                    this.selectedRows.splice(index, 1);
                }
            });

            window.eventBus.$on('select-all', (selected) => {
                this.dataTable.selectedRows = this.licenses;
            });

            window.eventBus.$on('deselect-all', (selected) => {
                this.dataTable.selectedRows = [];
            });
        },

        computed: mapGetters({
            selectedBrand: 'creative/selectedBrand',
        }),

        data: () => ({

            showLicenseModal: false,
            media: undefined,
            license: undefined,
            licenses: [],
            selectedMedia: [],
            dataTable: {
                rows: [].slice(0, 10),
                selectedRows: [],
                columns: [
                    {label: '', component: SelectRowComponent, headerComponent: SelectAllComponent},
                    {
                        label: 'File', representedAs: function (media) {
                            return '<img class="preview-img" src="' + media.media.thumbnail + '" />';
                        }, interpolate: true, filterable: false, sortable: false
                    },
                    {label: 'Number', field: 'id', filterable: true},
                    {label: 'License', field: 'type', filterable: false},
                    {label: 'Origin', field: 'origin', filterable: false},
                    {label: 'Added By', field: 'createdBy', filterable: false},
                    {
                        label: 'Expiration date', representedAs: function (license) {
                            var expirationDate = license.expiredAt.dMY;
                            var daysDifference = license.expiredAt.difference;
                            if (expirationDate === undefined) {
                                return '<span style="color: green"> unlimited </span>';
                            } else {
                                if (daysDifference < 0) {
                                    return '<span style="color: red"> expired at ' + expirationDate + '</span>';
                                } else if (daysDifference < 60) {
                                    return '<span style="color: orange"> ' + expirationDate + ' </span>';
                                } else {
                                    return '<span style="color: green"> ' + expirationDate + ' </span>';
                                }
                            }
                        }, interpolate: true, filterable: false
                    },
                ],
                page: 1,
                perPage: 10
            }
        }),

        watch: {
            dataTable: {
                selectedRows: function () {
                    for (let row of this.rows) {
                        if (this.selectedRows.indexOf(row) === -1) {
                            return;
                        }
                    }
                    eventBus.$emit('all-selected');
                }
            }
        },

        methods: {

            exportAsPDF() {

                let url = this.selectedBrand ? `api/licenses/export/${this.selectedBrand.id}` : 'api/licenses/export';
                axios({
                    url: url,
                    method: 'GET',
                    responseType: 'blob',
                    params: {
                        ids: _.pluck(this.dataTable.selectedRows, 'id')
                    }
                }).then((response) => {
                    let header = response.headers['content-disposition'];
                    let filename = header.match(/filename="(.+)"/)[1] ? header.match(/filename="(.+)"/)[1] : 'licenses-export.pdf';
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', filename);
                    document.body.appendChild(link);
                    link.click();
                });
            },

            getLicenses() {
                let url = this.selectedBrand ? `api/licenses/${this.selectedBrand.id}` : 'api/licenses';
                axios.get(url).then(response => {
                    this.licenses = response.data;
                });
            },

            selectRow(row) {
                if (this.dataTable.selectedRows.indexOf(row) !== -1) {
                    let index = this.dataTable.selectedRows.indexOf(row);
                    this.dataTable.selectedRows.splice(index, 1);
                    return;
                }
                this.dataTable.selectedRows.push(row);
            },

            showModal(row) {
                this.media = row.media;
                this.selectedMedia = [this.media.id];
                this.license = row.media.license;
                this.showLicenseModal = true;
            },

            transformExpiredDate(expirationDate, daysDifference) {
                if (expirationDate === undefined) {
                    return '<span style="color: green"> unlimited </span>';
                } else {
                    if (daysDifference < 0) {
                        return '<span style="color: red"> expired at ' + expirationDate + '</span>';
                    } else if (daysDifference < 60) {
                        return '<span style="color: yellow"> ' + expirationDate + ' </span>';
                    } else {
                        return '<span style="color: green"> ' + expirationDate + ' </span>';
                    }
                }
            }

        }
    }
</script>
