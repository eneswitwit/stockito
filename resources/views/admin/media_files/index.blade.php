<div class="table_scroll">
    <div class="panel panel-default">
        <table class="table table-bordered" id="media-table" style="width: 100%">
            <thead>
            <tr>
                <th>Image</th>
            </tr>
            </thead>
        </table>
    </div>
</div>
<script>
    $(function() {
        var tableMedia = $('#media-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.media.data') !!}',
            columns: [
                {data: 'id', name: 'id'},

            ],
            columnDefs: [
                {
                    "render": function (data, type, row) {

                        var tags = '';
                        if (row.tags.length) {
                            tags = row.tags.map(function(t) {
                                return t.title + ' ';
                            });
                        }

                        return '<div class="row">' +
                            '<div class="col-md-2"><img src="http://via.placeholder.com/150x150"></div>' +
                            '<div class="col-md-8"><dl class="dl-horizontal">' +
                            '<dt>Title</dt>' + '<dd>Sea shore</dd>' +
                            '<dt>Brand</dt>' + '<dd>' + row.brand +'</dd>' +
                            '<dt>Uploaded by</dt>' + '<dd>' + row.uploaded_by +'</dd>' +
                            '<dt>Uploaded at</dt>' + '<dd>' + row.updated_at + '</dd>' +
                            '<dt>Tags</dt>' + '<dd>' + tags + '</dd>' +
                            '</dl></div>' +
                            '<div class="col-md-2">' +
                            '<button type="button" class="btn btn-block btn-primary">Download</button>' +
                            '<button type="button" class="btn btn-block btn-primary">Delete</button>' +
                            '</div>' +
                            '</div>';
                    },
                    "targets": 0,
                    "orderable": false,
                    "searchable": false
                }
            ]
        });
    });
</script>
