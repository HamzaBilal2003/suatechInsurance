@extends('layout.layout')
@section('content')
    <h1>Users</h1>
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
                        <label for="name">Name</label>
                        <input type="text" name="name" value="{{ request('name') }}" id="client_name" class="form-control"
                            placeholder="Enter Name">
                    </div>
                    <div class="col-md-4">
                        <label for="email">Email</label>
                        <input type="email" name="email" value="{{ request('email') }}" id="client_email"
                            class="form-control" placeholder="Enter Email">
                    </div>
                    <div class="col-md-4">
                        <label for="phone">Phone</label>
                        <input type="text" name="phone" value="{{ request('phone') }}" id="client_phone"
                            class="form-control" placeholder="Enter Phone">
                    </div>
                    @if (Auth::user()->role != 'broker')
                        <div class="col-md-4">
                            <label for="role">Role</label>
                            <input type="text" name="role" value="{{ request('role') }}" id="role" class="form-control"
                                placeholder="Enter Role">
                        </div>
                    @endif
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary mt-3">Filter</button>
                </div>
            </div>
        </form>
    </div>
    <div class="my-4 card shadow">
        <div class="d-flex justify-content-between align-items-center card-header">
            <h3>Users List</h3>
            <button class="btn btn-dark" id="add-client">Add User</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr class="thead-dark">
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="client-list">
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role }}</td>
                                <td>
                                    <button class="btn btn-primary edit-client" data-id="{{ $user->id }}">Edit</button>
                                    <button class="btn btn-danger delete-client" data-id="{{ $user->id }}">Delete</button>
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
                    url: "{{ route('users.create', Auth::user()->username) }}",
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
                    url: `/{{Auth::user()->username}}/users/edit/${brokerId}`,
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