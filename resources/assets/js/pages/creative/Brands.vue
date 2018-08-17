<template>
    <div>
        <div class="card">
            <div class="card-header">Brands Involved</div>
            <div class="card-body">
                <div class="col-xs-12 table-responsive">
                    <datatable v-if="brands" :columns="dataTable.columns" :data="brands">
                        <template slot-scope="{ row, columns }">
                            <tr>
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
        </div>
    </div>
</template>

<script>
    import SelectRowComponent from '../common/parts/SelectRowComponent.vue';
    import SelectAllComponent from '../common/parts/SelectAllComponent.vue';
    import BrandActionsComponent from './parts/BrandActionsComponent.vue';

    export default {
        components: {
            SelectRowComponent,
            SelectAllComponent,
            BrandActionsComponent
        },
        middleware: ['auth', 'subscribed'],
        name: 'brands',
        created () {
            this.setApiBrands();
        },
        data: () => ({
            dataTable: {
                columns: [
                    {label: 'Brandname', field: 'brand_name', filterable: false},
                    {label: '', component: BrandActionsComponent},
                ],
                page: 1,
                perPage: 10
            }
        }),
        computed: {
            brands() {
                return this.$store.getters['creative/getBrands'];
            }
        },
        methods: {
             async setApiBrands () {
                 await this.$store.dispatch('creative/setApiBrands');
            }
        },
    }
</script>

<style lang="scss">
    .custom-pagination {
        ul {
            li {
                a {
                    position: relative;
                    display: block;
                    padding: .5rem .75rem;
                    margin-left: -1px;
                    line-height: 1.25;
                    color: #007bff;
                    background-color: #fff;
                    border: 1px solid #dee2e6;
                }

                &.active {
                    a {
                        z-index: 1;
                        color: #fff;
                        background-color: #007bff;
                        border-color: #007bff;
                    }
                }
            }
        }
    }
</style>