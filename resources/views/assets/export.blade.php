<!DOCTYPE html>
<html>
<head>
    <title>Assets Export</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Assets List {{$client->client_name}}</h1>
    <table>
        <thead>
            <tr>
                <th>Client Name</th>
                <th>Type</th>
                <th>Description</th>
                <th>Make</th>
                <th>Model</th>
                <th>Serial Number</th>
                <th>Purchase Price</th>
                <th>Supplier Name</th>
                <th>Purchase Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assets as $asset)
                <tr>
                    <td>{{$client->client_name}}</td>
                    <td>{{ $asset->type }}</td>
                    <td>{{ $asset->description }}</td>
                    <td>{{ $asset->make }}</td>
                    <td>{{ $asset->model }}</td>
                    <td>{{ $asset->serial_number }}</td>
                    <td>{{ $asset->purchase_price }}</td>
                    <td>{{ $asset->supplier_name }}</td>
                    <td>{{ $asset->purchase_date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>