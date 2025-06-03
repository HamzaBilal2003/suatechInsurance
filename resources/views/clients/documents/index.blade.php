@extends('layout.layout')

@section('content')
    <div class="card  shadow">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div class="card-title">
                    <h1>Documents</h1>
                </div>
                <div class="d-flex align-items-center">
                    <button class="btn btn-dark mr-2" id="add-document">
                        Add Documents
                    </button>
                    @if (Auth::user()->role != 'client')
                    @include('layout.backbtn')
                    @endif
                </div>
            </div>
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
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr class="thead-dark">
                            <th>Name</th>
                            <th>Document</th>
                            <th>Date Added</th>
                            <th>Actions</td>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($documents as $document)
                            <tr>
                                <td>{{$document->document_name}}</td>
                                <td>
                                    <a download href="{{asset($document->file_path)}}" class="btn btn-primary">Download</a>
                                </td>
                                <td>
                                    {{ $document->created_at->format('d M Y, h:i A') }}
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button data-id="{{$document->id}}" class="edit-document btn btn-warning">Edit</button>
                                        <button data-id="{{$document->id}}" class="delete-document btn btn-danger">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    No Docuemnts Found
                                </td>
                            </tr>
                        @endforelse
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
            $('#add-document').on('click', function () {
                $.ajax({
                    url: "{{ route('clients.createClientDocument', ['username' => Auth::user()->username, 'id' => $clientName->id]) }}",
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
            $(document).on('click', '.edit-document', function () {
                let brokerId = $(this).data('id');
                $.ajax({
                    url: `/{{Auth::user()->username}}/clients/view/documents/edit/${brokerId}`,
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
            $(document).on('click', '.delete-document', function () {
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
                            url: `/clients/view/documents/delete/${brokerId}`,
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