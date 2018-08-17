<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>{{ $brand->brand_name }} - Licenses</title>
</head>
<body>
<h1>{{ $brand->brand_name }} - Licenses</h1>
<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th scope="col">ID</th>
        <th scope="col">File</th>
        <th scope="col">License</th>
        <th scope="col">Added By</th>
        <th scope="col">Expiration Date</th>
        <th scope="col">Brand</th>
    </tr>
    </thead>
    <tbody>
    @foreach($licenses as $license)
        <tr>
            <th scope="row">{{ $license->id }}</th>
            <td>{{ $license->media->origin_name }}</td>
            <td>{{ $license->getLicenseTitle() }}</td>
            <td>{{ $license->createdBy->getType()->name }}</td>
            <td>{{ $license->expired_at }}</td>
            <td>{{ $brand->brand_name }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>