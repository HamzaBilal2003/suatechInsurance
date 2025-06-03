@extends('layout.layout')
@section('content')
    <h1>Broker</h1>
    <div class="my-4 card shadow">
        <form action="{{route('broker.index',Auth::user()->username)}}" class="filter">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <label for="broker_name">Broker Name</label>
                        <input type="text" name="broker_name"  value="{{ request('broker_name') }}"  id="broker_name" class="form-control"
                            placeholder="Enter Broker Name">
                    </div>
                    <div class="col-md-4">
                        <label for="broker_email">Broker Email</label>
                        <input type="email" name="broker_email" value="{{ request('broker_email') }}"  id="broker_email" class="form-control"
                            placeholder="Enter Broker Email">
                    </div>
                    <div class="col-md-4">
                        <label for="broker_phone">Broker Phone</label>
                        <input type="text" name="broker_phone" value="{{ request('broker_phone') }}"  id="broker_phone" class="form-control"
                            placeholder="Enter Broker Phone">
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary mt-3">Filter</button>
                </div>
            </div>
        </form>
    </div>
    <div class="my-4 card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Brokers List</h3>
            <button id="add-borker" class="btn btn-primary">Add Broker</button>
        </div>
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
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr class="thead-dark">
                        <th>Broker Logo</th>
                        <th>Broker Name</th>
                        <th>Broker Phone</th>
                        <th>Broker Email</th>
                        <th>Broker Address</th>
                        <th>Broker assign to</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($brokers as $broker)
                        <tr>
                            <td>
                                <img src="{{asset($broker->logo_path)}}" alt="{{$broker->broker_name}}"
                                    style="width: 100px;height:100px;">
                            </td>
                            <td>{{ $broker->broker_name }}</td>
                            <td>{{ $broker->contact_person }}</td>
                            <td>{{ $broker->user ? $broker->user->email : 'N/A' }}</td>
                            <td>{{ $broker->address }}</td>
                            <td>{{ $broker->user ? $broker->user->name : 'Not Assigned' }}</td>
                            <td>
                                <button data-id="{{$broker->id}}" class="edit-broker btn btn-warning">Edit</button>
                                <form
                                    action="{{ route('broker.destroy', ['username' => Auth::user()->username, 'id' => $broker->id]) }}"
                                    method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty

                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{-- model here --}}
    <div class="modal fade" id="brokerModal" tabindex="-1" role="dialog" aria-labelledby="brokerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>

    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            $('#add-borker').on('click', function () {
                $.ajax({
                    url: "{{ route('broker.create', Auth::user()->username) }}",
                    type: "GET",
                    success: function (response) {
                        $('#brokerModal .modal-content').html(response);
                        $('#brokerModal').modal('show');
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to load form. Please try again.',
                        });
                    }
                });
            });
            $(document).on('click', '.edit-broker', function () {
                let brokerId = $(this).data('id');
                $.ajax({
                    url: `/{{Auth::user()->username}}/brokers/edit/${brokerId}`,
                    type: "GET",
                    success: function (response) {
                        $('#brokerModal .modal-content').html(response);
                        $('#brokerModal').modal('show');
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to load form. Please try again.',
                        });
                    }
                });
            });
        });
    </script>
@endsection