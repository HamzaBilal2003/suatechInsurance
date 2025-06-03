@extends('layout.layout')
@section('content')
    @if (Auth::user()->role != 'client')
        <h1>Clients :- {{ $client->client_name }}</h1>
    @endif
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div class="card my-4 shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            @if (Auth::user()->role != 'client')
                <h3 class="card-title">Client Details</h3>
                <a href="{{ route('clients.index', Auth::user()->username) }}" class="btn btn-secondary">Back to List</a>
            @else
                <h3 class="card-title">Profile</h3>
            @endif
        </div>
        <div class="card-body row">
            <p class="col-md-3"><strong>Client Name:</strong> {{ $client->client_name }}</p>
            <p class="col-md-3"><strong>Contact Name:</strong> {{ $client->contact_name }}</p>
            <p class="col-md-3"><strong>Address:</strong> {{ $client->address }}</p>
            <p class="col-md-3"><strong>Office Number:</strong> {{ $client->office_number }}</p>
            <p class="col-md-3"><strong>Cell Number:</strong> {{ $client->cell_number }}</p>
            <p class="col-md-3"><strong>Email:</strong> {{ $client->email }}</p>
            <p class="col-md-3"><strong>Assigned To:</strong> {{ $client->user ? $client->user->name : 'N/A' }}</p>
        </div>
        <div class="card-footer d-flex justify-content-end">
            @if (Auth::user()->role != 'client')
                <div class="btn-group">
                    <a href="{{ route('clients.getClientDocuments', ['username' => Auth::user()->username, 'id' => $client->id]) }}"
                        class="btn btn-danger">Docuemnts</a>
                    <a href="{{'/clients/' . $client->id . '/assets'}}" class="btn btn-warning">Assets</a>
                </div>
            @endif
        </div>
    </div>
    <div class="card my-4 shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Client Activity</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr class="thead-dark">
                            <th>Activity</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($activities ?? [] as $activity)
                            <tr>
                                <td>{{$activity->action}}</td>
                                <td>{{ $activity->created_at->format('d M Y, h:i A') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="py-2 text-center" colspan="2">No Activity</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection