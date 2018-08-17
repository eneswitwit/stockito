<div class="content body">
    <div class="table_scroll">
        <div class="panel panel-default">
            <table class="table table-bordered" id="finance-table" style="width: 100%">
                <thead>
                <tr>
                    <th>@lang('admin.finance_number')</th>
                    <th>@lang('admin.finance_brand')</th>
                    <th>@lang('admin.finance_product')</th>
                    <th>@lang('admin.finance_date')</th>
                    <th>@lang('admin.finance_paid')</th>
                    <th>@lang('admin.finance_paid_with')</th>
                    <th>@lang('admin.finance_invoice')</th>
                    <th class="text-right">@lang('admin.action')</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script>
    $(function() {
        var tableFinance = $('#finance-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.finance.data') !!}',
            columns: [
                {data: 'number', name: 'number'},
                {data: 'customer', name: 'customer'},
                {data: 'subscription', name: 'subscription'},
                {data: 'date', name: 'date'},
                {data: 'date', name: 'date'},
                {data: 'date', name: 'date'},
                {data: 'date', name: 'date'},
                {data: 'date', name: 'date'},
            ],
            columnDefs: [
                {
                    "render": function (data, type, row) {
                        return ' <a class="btn btn-block btn-primary">Edit</a> ';
                    },
                    "targets": -1,
                    "orderable": false,
                    "searchable": false
                }
            ]
        });
    });
</script>