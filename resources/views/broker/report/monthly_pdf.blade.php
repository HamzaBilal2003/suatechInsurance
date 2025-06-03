<!DOCTYPE html>
<html>
<head>
    <title>Broker Export</title>
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
    <h1>Report list</h1>
    <table>
        <thead>
            <tr>
                <th>Year</th>
                <th>Month</th>
                <th>Total Clients</th>
                <th>Total assets</th>
                <th>Total Document</th>

            </tr>
        </thead>
        @php
        $month = ['jan','feb','march','april','may','june','july','aug','sept','oct','nov','dec']
        @endphp
        <tbody>
            @foreach($monthlyReports as $report)
                <tr>
                    <td>{{ $report['year'] }}</td>
                    <td>{{ $month[$report['month']] }}</td>
                    <td>{{ $report['total_clients'] }}</td>
                    <td>{{ $report['clients_with_assets'] }}</td>
                    <td>{{ $report['clients_with_documents'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>