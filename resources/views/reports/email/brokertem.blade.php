<!DOCTYPE html>
<html>

<head>
    <title>Broker Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
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
    <h1>Client Count Report - {{ \Carbon\Carbon::now()->format('F Y') }}</h1>
    <table>
        <thead>
            <tr>
                <th>Broker Name</th>
                <th>Broker phone</th>
                <th>Client Creadted this month</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $broker)
                <tr>
                    <td>{{ $broker['broker_name'] }}</td>
                    <td>{{ $broker['phone'] }}</td>
                    <td>{{ $broker['current_month_client_count'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>