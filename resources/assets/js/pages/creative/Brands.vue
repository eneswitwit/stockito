<template>


    <div class="container">
        <div class="card mt-5">

            <div class="card-header dashboard-card">
                Brands Involved
            </div>

            <div class="card-body" style="padding:0;">
                <div class="col-xs-12 table-responsive">
                    <datatable v-if="brands" :columns="dataTable.columns" :data="brands">
                        <template slot-scope="{ row, columns }">
                            <tr>
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
        <datatable-pager class="custom-pagination mt-2" v-model="dataTable.page" type="abbreviated"
                         :per-page="dataTable.perPage"></datatable-pager>
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
        created() {
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
            async setApiBrands() {
                await this.$store.dispatch('creative/setApiBrands');
            }
        },
    }
</script>

