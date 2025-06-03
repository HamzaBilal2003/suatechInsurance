@extends('layout.layout')

@section('content')
    <h1>Add Asset for {{ $client->name }}</h1>
    <div class="card shadow">
        <div class=" d-flex justify-content-between align-items-center">
            <div class="card-header">
                <h3 class="card-title text-center">Add Asset</h3>
            </div>
            @include('layout.backbtn')
        </div>
        <form id="assetForm" method="POST" action="{{ route('assets.store', $client->id) }}" enctype="multipart/form-data">
            @csrf
            <div class="card-body row">
                <div class="form-group col-md-6">
                    <label for="type">Asset Type</label>
                    <input type="text" name="type" id="type" class="form-control" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="make">Make</label>
                    <input type="text" name="make" id="make" class="form-control" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="model">Model</label>
                    <input type="text" name="model" id="model" class="form-control" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="serial_number">Serial Number</label>
                    <input type="text" name="serial_number" id="serial_number" class="form-control">
                </div>
                <div class="form-group col-md-6">
                    <label for="purchase_price">Purchase Price</label>
                    <input type="number" name="purchase_price" id="purchase_price" class="form-control" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="supplier_name">Supplier Name</label>
                    <input type="text" name="supplier_name" id="supplier_name" class="form-control">
                </div>
                <div class="form-group col-md-6">
                    <label for="purchase_date">Purchase Date</label>
                    <input type="date" name="purchase_date" id="purchase_date" class="form-control" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="attached_files">Attach Files</label>
                    <input type="file" name="attached_files[]" id="attached_files" class="form-control-file" multiple>
                </div>
                <div class="form-group col-md-12">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-control" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary mt-3 col-md-12">Save</button>
            </div>
        </form>
    </div>
@endsection