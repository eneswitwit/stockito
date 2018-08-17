
<div class="content body">
    <div class="row  text-center"><button class="btn btn-primary _add_product">@lang('admin.add_product')</button></div>
    <div class="table_scroll">
        <div class="panel panel-default">
            <table class="table table-bordered" id="subscriptions-table" style="width: 100%">
                <thead>
                <tr>
                    <th>@lang('admin.product')</th>
                    <th>@lang('admin.storage')</th>
                    <th>@lang('admin.video_storage')</th>
                    <th>@lang('admin.annually_price')</th>
                    <th class="text-right">@lang('admin.action')</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="table_scroll">
        <div class="panel panel-default">
            <table class="table table-bordered" id="voucher-table" style="width: 100%">
                <thead>
                <tr>
                    <th>@lang('admin.vouchercodes')</th>
                    <th>@lang('admin.code')</th>
                    <th>@lang('admin.price_reduction')</th>
                    <th>@lang('admin.price_reduction')</th>
                    <th class="text-right">@lang('admin.action')</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="row  text-center">
        <button class="btn btn-primary _add_voucher">@lang('admin.add_vouchercode')</button>
    </div>
</div>
<div class="modal" tabindex="-1" role="dialog" id="AddProductModal">
    <div class="modal-dialog" role="document">
        <form class="form-horizontal"  name="AddProductForm" id="AddProductForm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('admin.add_product')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label">@lang('admin.name')</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="title">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">@lang('admin.storage')</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="storage">
                    </div>
                    <div class="col-sm-1">GB</div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">@lang('admin.video_storage')</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="video_storage">
                    </div>
                    <div class="col-sm-1">GB</div>
                </div>
                <div class="form-group">
                    <label  class="col-sm-2 control-label">@lang('admin.annually_price')</label>
                    <div class="col-sm-9">
                        <input type="number" class="form-control" name="price" step="0.01">
                    </div>
                    <div class="col-sm-1">$</div>
                </div>
                <div class="form-group">
                    <label  class="col-sm-2 control-label">@lang('admin.product_interval')</label>
                    <div class="col-sm-9">
                        <select class="form-control" name="interval" id="interval">
                            @foreach($product_interval as $option)
                            <option value="{{$option}}">{{$option}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">@lang('admin.add')</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('admin.close')</button>
            </div>
        </div>
        </form>
    </div>
</div>
<div class="modal" tabindex="-1" role="dialog" id="EditProductModal">
    <div class="modal-dialog" role="document">
        <form class="form-horizontal"  name="EditProductForm" id="EditProductForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('admin.edit_product')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">@lang('admin.name')</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="title">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">@lang('admin.storage')</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="storage">
                        </div>
                        <div class="col-sm-1">GB</div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">@lang('admin.video_storage')</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="video_storage">
                        </div>
                        <div class="col-sm-1">GB</div>
                    </div>
                    <div class="form-group">
                        <label  class="col-sm-2 control-label">@lang('admin.annually_price')</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" name="price" step="0.01">
                        </div>
                        <div class="col-sm-1">$</div>
                    </div>
                    <div class="form-group">
                        <label  class="col-sm-2 control-label">@lang('admin.product_interval')</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="interval" id="interval">
                                @foreach($product_interval as $option)
                                    <option value="{{$option}}">{{$option}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">@lang('admin.save')</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('admin.close')</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal" tabindex="-1" role="dialog" id="EditVoucherModal">
    <div class="modal-dialog" role="document">
        <form class="form-horizontal" name="EditVoucherForm" id="EditVoucherForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('admin.edit_voucher')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Type</label>
                        <div class="col-sm-10">
                            <input id="type-percent" type="radio" name="type" value="percent">
                            <label for="type-percent">Percent (%)</label>
                            <input id="type-amount" type="radio" name="type" value="amount">
                            <label for="type-amount">Amount ($)</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">@lang('admin.price_reduction')</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" name="sale">
                        </div>
                        <div class="col-sm-1">%</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">@lang('admin.save')</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('admin.close')</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal" tabindex="-1" role="dialog" id="AddVoucherModal">
    <div class="modal-dialog" role="document">
        <form class="form-horizontal" name="AddVoucherForm" id="AddVoucherForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('admin.edit_voucher')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Type</label>
                            <div class="col-sm-10">
                                <input id="type-percent" type="radio" name="type" value="percent" checked>
                                <label for="type-percent">Percent (%)</label>
                                <input id="type-amount" type="radio" name="type" value="amount">
                                <label for="type-amount">Amount ($)</label>
                            </div>
                        </div>
                        <label class="col-sm-2 control-label">@lang('admin.price_reduction')</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" name="sale">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">@lang('admin.code')</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="code" id="code">
                        </div>
                        <div class="col-sm-2">
                            <button  class="btn btn-primary" type="button" onclick="RandCode(); return;">
                                <i class="fa fa-refresh" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">@lang('admin.save')</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('admin.close')</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
        $(function() {
            var tableVoucher = $('#voucher-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('admin.voucher.data') !!}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'code', name: 'code' },
                    { data: 'sale', name: 'sale' },
                    { data: 'action', name: 'action' },
                ],
                columnDefs: [
                    {
                        "render": function ( data, type, row ) {
                            return '<a href="/admin/voucher/'+ row.id +'/delete"  ' +
                                'class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>';
                        },
                        "targets": -1,
                        "orderable": false,
                        "searchable": false
                    }
                ]
            });

            var tableSubscriptions = $('#subscriptions-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('admin.product.data') !!}',
                columns: [
                    { data: 'title', name: 'title' },
                    { data: 'storage', name: 'storage' },
                    { data: 'video_storage', name: 'video_storage' },
                    { data: 'price', name: 'price' },
                    { data: 'action', name: 'action' },
                ],
                columnDefs: [
                    {
                        "render": function ( data, type, row ) {
                            var info = (row.stripe_id == '') ? '<small class="label bg-red">deleted plan in stripe</small>':'';
                            return data + info;
                        },
                        "targets": 0,
                    },
                    {
                        "render": function ( data, type, row ) {
                           return '<a href="#" data-id="'+ row.id +'" ' +
                               'class="btn btn-xs btn-primary _edit_product"><i class="fa fa-pencil"></i></a>' +
                               '<a href="/admin/product/'+ row.id +'/delete" ' +
                                'class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>';
                        },
                        "targets": -1,
                        "orderable": false,
                        "searchable": false
                    },
                    {
                        "render": function ( data, type, row ) {
                            return data + ' GB';
                        },
                        "targets": 1,
                    },
                    {
                        "render": function ( data, type, row ) {
                            return data + ' GB';
                        },
                        "targets": 2,
                    },
                    {
                        "render": function ( data, type, row ) {
                            return data + ' $';
                        },
                        "targets": 3,
                    }
                ]
            });
            $("#EditProductForm").submit(function(e) {
                var id = $('#EditProductForm').data('id');

                $.ajax({
                    type: "POST",
                    url: '/admin/product/' + id,
                    data: $("#EditProductForm").serialize(),
                    success: function(data)
                    {
                        $('#EditProductModal').modal('hide');
                        if (data && data.success) {
                            Admin.Messages.success('@lang('admin.edit_product')', data.success);
                        } else {
                            Admin.Messages.error('@lang('admin.edit_product')', data.error);
                        }

                    }
                });

                e.preventDefault();
            });

            $("#EditVoucherForm").submit(function(e) {
                var id = $('#EditVoucherForm').data('id');

                $.ajax({
                    type: "POST",
                    url: '/admin/voucher/' + id,
                    data: $("#EditVoucherForm").serialize(),
                    success: function(data)
                    {
                        $('#EditVoucherModal').modal('hide');
                        if (data && data.success) {
                            Admin.Messages.success('@lang('admin.edit_voucher')', data.success);
                        } else {
                            Admin.Messages.error('@lang('admin.edit_voucher')', data.error);
                        }
                    }
                });

                e.preventDefault();
            });

            $("#AddProductForm").submit(function(e) {
                $.ajax({
                    type: "POST",
                    url: '/admin/product/new',
                    data: $("#AddProductForm").serialize(),
                    success: function(data)
                    {
                        $('#AddProductModal').modal('hide');
                        if (data && data.success) {
                            Admin.Messages.success('@lang('admin.edit_voucher')', data.success);
                        } else {
                            Admin.Messages.error('@lang('admin.edit_voucher')', data.error);
                        }
                    }
                });

                e.preventDefault();
            });
            $("#AddVoucherForm").submit(function(e) {
                  $.ajax({
                    type: "POST",
                    url: '/admin/voucher/new',
                    data: $("#AddVoucherForm").serialize(),
                    success: function(data)
                    {
                        $('#AddVoucherModal').modal('hide');
                        if (data && data.success) {
                            Admin.Messages.success('@lang('admin.new_voucher')', data.success);
                        } else {
                            Admin.Messages.error('@lang('admin.new_voucher')', data.error);
                        }
                    }
                });

                e.preventDefault();
            });
        });
        $(document).on('click', '._add_product', function () {
            $('#AddProductModal').modal('show');
        });

        $(document).on('click', '._edit_product', function () {
            var id = $(this).data('id');
            $('#EditProductForm').data('id', id);
            $.get('/admin/product/' + id, function (res) {
                if (res && res.id) {
                    $('#EditProductForm input[name=title]').val(res.title);
                    $('#EditProductForm input[name=storage]').val(res.storage);
                    $('#EditProductForm input[name=video_storage]').val(res.video_storage);
                    $('#EditProductForm input[name=price]').val(res.price);
                    $('#EditProductForm select[name=interval]').val(res.interval);
                    $('#EditProductModal').modal('show');
                }
            });
        });

        $(document).on('click', '._edit_voucher', function () {
            var id = $(this).data('id');
            $('#EditVoucherForm').data('id', id);
            $.get('/admin/voucher/' + id, function (res) {
                if (res && res.id) {
                    $('#EditVoucherForm input[name=sale]').val(res.sale);
                    $('#EditVoucherModal').modal('show');
                }
            });
        });
        $(document).on('click', '._add_voucher', function () {
            RandCode();
            $('#AddVoucherModal').modal('show');
        });

        function RandCode() {
            $('#code').val(Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15));
        }
    </script>