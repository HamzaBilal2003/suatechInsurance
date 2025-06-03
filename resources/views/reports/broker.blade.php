@extends('layout.layout')
@section('content')
    <div class="card shadow my-4">
        <div class='card-body'>
            <form action="" method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <label for="broker_id" class="form-label">Broker</label>
                        <select name="broker_id" id="broker_id" class="form-control">
                            <option value="">Select Broker</option>
                            @foreach($brokers as $broker)
                                <option value="{{ $broker->id }}" {{ request('broker_id') == $broker->id ? 'selected' : '' }}>
                                    {{ $broker->broker_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="report_month" class="form-label">Report Month</label>
                        <input type="month" name="report_month" id="report_month" class="form-control"
                            value="{{ request('report_month') }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class='card shadow my-4'>
        <div class='card-header '>
            <div class='card-title'>
                <h4>List {{ $month}}</h4>
            </div>
        </div>
        <div class='card-body'>
            <div class='table-responsive'>
                <table class='table table-striped'>
                    <thead>
                        <tr class='thead-dark'>
                            <th>Broker Name</th>
                            <th>Broker Phone</th>
                            <th>Client added</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($BorkerReports as $broker)
                            <tr>
                                <td>{{ $broker['broker_name'] }}</td>
                                <td>{{ $broker['phone'] }}</td>
                                <td>{{ $broker['current_month_client_count'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection