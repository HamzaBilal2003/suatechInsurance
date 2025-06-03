@extends('layout.layout')
@section('content')
    <h1>Clients</h1>
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
    <div class="my-4 card shadow">
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
                    <div class="col-md-4">
                        <label for="client_phone">Client Phone</label>
                        <input type="text" name="client_phone" value="{{ request('client_phone') }}" id="client_phone"
                            class="form-control" placeholder="Enter Client Phone">
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary mt-3">Filter</button>
                </div>
            </div>
        </form>
    </div>
    <div class="my-4 card shadow">
        <div class="d-flex justify-content-between align-items-center card-header">
            <h3>Clients List </h3>
            <button class="btn btn-dark" id="add-client">Add Client</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr class="thead-dark">
                            <th>Client Name</th>
                            <th>Client Email</th>
                            <th>Client Phone</th>
                            <th>Linked To</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="client-list">
                        @foreach ($clients as $client)
                            <tr>
                                <td>{{ $client->client_name }}</td>
                                <td>{{ $client->email }}</td>
                                <td>{{ $client->cell_number }}</td>
                                <td>{{ $client->user ? $client->user->name : 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('clients.getclient', ['username' => Auth::user()->username, 'id' => $client->id]) }}"
                                        class="btn btn-warning">View</a>
                                    <button class="btn btn-primary edit-client" data-id="{{ $client->id }}">Edit</button>
                                    <button class="btn btn-danger delete-client" data-id="{{ $client->id }}">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="brokerModal" tabindex="-1" role="dialog" aria-labelledby="brokerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            $('#add-client').on('click', function () {
                $.ajax({
                    url: "{{ route('clients.create', Auth::user()->username) }}",
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
            $(document).on('click', '.edit-client', function () {
                let brokerId = $(this).data('id');
                $.ajax({
                    url: `/{{Auth::user()->username}}/clients/edit/${brokerId}`,
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
            $(document).on('click', '.delete-client', function () {
                let brokerId = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You won\'t be able to revert this!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log(brokerId);
                        $.ajax({
                            url: `/clients/${brokerId}/destory`,
                            type: "POST",
                            success: function (response) {
                                Swal.fire({
                                    title: 'Client',
                                    text: 'Deleted Successfully',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    window.location.reload();
                                });
                            },
                            error: function (xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Failed to load form. Please try again.',
                                });
                            }
                        });
                    }
                });

            });
        });
    </script>
@endsection