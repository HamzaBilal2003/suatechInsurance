@extends('layout.layout')
@section('content')
    <div class="d-flex align-item-center justify-content-between">
        <h4>Reports</h4>
        <a href="{{route('broker.downloadMonthlyReportPdf',Auth::user()->username)}}" class="btn btn-dark">Export pdf</a>
    </div>
    {{-- <div class="d-flex align-item-center">
        <div class="card shadow mx-2">
            <div class="card-body">
                <h6>Total item</h6>
                <h3>{{ $TotalItems }}</h3>
            </div>
        </div>
        <div class="card shadow mx-2">
            <div class="card-body">
                <h6>Total Client Space</h6>
                <h3>{{ $reports->count() }}</h3>
            </div>
        </div>
    </div> --}}
    <div class="card shadow my-4">
        <div class="card-header">
            <h2>Filter</h2>
            <form action="" class="filter">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="client_name">Client Name</label>
                            <input type="text" name="client_name" value="{{ request('client_name') }}" id="client_name"
                                class="form-control" placeholder="Enter Client Name">
                        </div>
                        <div class="col-md-4">
                            <label for="client_email">Client Email</label>
                            <input type="email" name="client_email" value="{{ request('client_email') }}" id="client_email"
                                class="form-control" placeholder="Enter Client Email">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary mt-3">Filter</button>
                    </div>
                </div>
            </form>
        </div>
        <div class='card-body'>
            <div class="table-responsive">
                <table class="table table-hovered table-striped">
                    <thead>
                        <tr class="thead-dark">
                            <th>Client Name</th>
                            <th>Client email</th>
                            <th>Client Phone</th>
                            <th>Space</th>
                            <th>Total item</th>
                            <th>Total documents</th>
                            <th>Created date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="client-list">
                        @foreach ($reports as $client)
                            <tr>
                                <td>{{ $client->client_name }}</td>
                                <td>{{ $client->email }}</td>
                                <td>{{ $client->cell_number }}</td>
                                <td>{{ $client->space }}</td>
                                <td>{{ $client->assets->count() }}</td>
                                <td>{{ $client->documents->count() }}</td>
                                <td>{{ $client->created_at->format('d M Y, h:i A') }}</td>
                                <td>
                                    <a href="{{ route('clients.getclient', ['username' => Auth::user()->username, 'id' => $client->id]) }}"
                                        class="btn btn-warning">View Client</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection