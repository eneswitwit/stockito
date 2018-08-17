
<div class="content body">
    <div class="table_scroll">
        <div class="panel panel-default">
            <table class="table table-bordered" id="brand-table" style="width: 100%">
                <thead>
                <tr>
                    <th>@lang('admin.brandname')</th>
                    <th>@lang('admin.product')</th>
                    <th>@lang('admin.subscription_exp_date')</th>
                    <th>@lang('admin.used_storage')</th>
                    <th class="text-right">@lang('admin.action')</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script>
    $(function() {
        var tableBrand = $('#brand-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.brand.data') !!}',
            columns: [
                {data: 'brand_name', name: 'brand_name'},
                {data: 'company_name', name: 'company_name'},
                {data: 'company_name', name: 'company_name'},
                {data: 'used_storage', name: 'used_storage'},
                {data: 'action', name: 'action'},
            ],
            columnDefs: [
                {
                    "render": function (data, type, row) {
                        return '<a href="/admin/customers/brand/' + row.id  +'"' +
                            'class="btn btn-xs btn-primary _edit_voucher"><i class="fa fa-pencil"></i></a>';
                    },
                    "targets": -1,
                    "orderable": false,
                    "searchable": false
                },
                {
                    "render": function (data, type, row) {
                        return row.format_used_storage;
                    },
                    "targets": -2,
                    "searchable": false
                }
            ]
        });
    });
</script>