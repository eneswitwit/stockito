
<div class="content body">
    <div class="table_scroll">
        <div class="panel panel-default">
            <table class="table table-bordered" id="creative-table" style="width: 100%">
                <thead>
                <tr>
                    <th>@lang('admin.first_name')</th>
                    <th>@lang('admin.last_name')</th>
                    <th>@lang('admin.email')</th>
                    <th>@lang('admin.company')</th>
                    <th class="text-right">@lang('admin.action')</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script>
    $(function() {
        var tableCreative = $('#creative-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.creative.data') !!}',
            columns: [
                {data: 'first_name', name: 'first_name'},
                {data: 'last_name', name: 'last_name'},
                {data: 'email', name: 'email'},
                {data: 'company', name: 'company'},
                {data: 'action', name: 'action'},
            ],
            columnDefs: [
                {
                    "render": function (data, type, row) {
                        return '<a href="/admin/customers/creative/' + row.id  +'"' +
                            'class="btn btn-xs btn-primary _edit_voucher"><i class="fa fa-pencil"></i></a>';
                    },
                    "targets": -1,
                    "orderable": false,
                    "searchable": false
                }
            ]
        });
    });
</script>