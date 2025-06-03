@extends('layout.layout')

@section('content')
<div class=" d-flex justify-content-between align-items-center">
    <h1>Edit Asset  {{ $client->name }}</h1>
        @include('layout.backbtn')
    </div>
    <div class="card shadow">
            <div class="card-header">
                <h3 class="card-title text-center">Edit Asset</h3>
            </div>
        <form id="assetForm" method="POST" action="{{ route('assets.update', [$client->id, $asset->id]) }}"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body row">
                <div class="form-group col-md-6">
                    <label for="type">Asset Type</label>
                    <input type="text" name="type" id="type" class="form-control" value="{{ $asset->type }}" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="make">Make</label>
                    <input type="text" name="make" id="make" class="form-control" value="{{ $asset->make }}" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="model">Model</label>
                    <input type="text" name="model" id="model" class="form-control" value="{{ $asset->model }}" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="serial_number">Serial Number</label>
                    <input type="text" name="serial_number" id="serial_number" class="form-control"
                        value="{{ $asset->serial_number }}">
                </div>
                <div class="form-group col-md-6">
                    <label for="purchase_price">Purchase Price</label>
                    <input type="number" name="purchase_price" id="purchase_price" class="form-control"
                        value="{{ $asset->purchase_price }}" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="supplier_name">Supplier Name</label>
                    <input type="text" name="supplier_name" id="supplier_name" class="form-control"
                        value="{{ $asset->supplier_name }}">
                </div>
                <div class="form-group col-md-6">
                    <label for="purchase_date">Purchase Date</label>
                    <input type="date" name="purchase_date" id="purchase_date" class="form-control"
                        value="{{ $asset->purchase_date }}" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="attached_files">Attach Files</label>
                    <input type="file" name="attached_files[]" id="attached_files" class="form-control-file" multiple>
                    @error('attached_files')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group col-md-12">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-control"
                        required>{{ $asset->description }}</textarea>
                </div>
                <div class="form-group col-md-12">
                    <label>Existing Files</label>
                    <ul id="existingFiles">
                        @php
                            $files = $asset->attached_files ? json_decode($asset->attached_files, true) : [];
                        @endphp
                        @foreach($files as $file)
                            <li>
                                @if(Str::endsWith(strtolower($file), ['.jpg', '.jpeg', '.png', '.gif', '.bmp', '.webp']))
                                    <img src="{{ asset('clients/assets/' . $file) }}" alt="attachment"
                                        style="width:50px;height:50px;object-fit:contain;">
                                @elseif(Str::endsWith(strtolower($file), ['.pdf']))
                                    <a href="{{ asset('clients/assets/' . $file) }}" target="_blank">
                                        <i class="fa fa-file-pdf-o" style="font-size:24px;color:red"></i> View PDF
                                    </a>
                                @else
                                    <a href="{{ asset('clients/assets/' . $file) }}" target="_blank">
                                        <i class="fa fa-file" style="font-size:24px;"></i> Download File
                                    </a>
                                @endif
                                <button type="button" class="btn btn-danger btn-sm removeFileBtn"
                                    data-file="{{ $file }}">Remove</button>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <button type="submit" class="btn btn-primary mt-3 col-md-12">Update</button>
            </div>
        </form>
    </div>


@endsection
@section('js')
    <script>
        $(document).ready(function () {
            // Handle file removal
            $(document).on('click', '.removeFileBtn', function () {
                let filePath = $(this).data('file');
                let assetId = "{{ $asset->id }}";

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, remove it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/clients/{{ $client->id }}/assets/${assetId}/remove-file`,
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                file: filePath
                            },
                            success: function (response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'File Removed Successfully',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                window.location.reload();
                            },
                            error: function (response) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.responseJSON.message,
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection