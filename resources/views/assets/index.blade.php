@extends('layout.layout')

@section('content')
    <div class="d-flex align-items-center justify-content-between">
        <h1> Assets {{ $client->name }}</h1>
        @include('layout.backbtn')
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
    <div class='d-flex justify-content-end my-4'>
        <div class='btn-group'>
            <a href="{{ route('assets.export', [$client, 'pdf']) }}" class="btn btn-secondary">Export to PDF</a>
            <a href="{{ route('assets.export', [$client, 'csv']) }}" class="btn btn-secondary">Export to CSV</a>
            <a href="{{ route('assets.emailRecords', $client) }}" class="btn btn-success">Email Records</a>
        </div>
    </div>

    <div class='card my-4 shadow'>
        <div class='card-header'>
            <div class='d-flex justify-content-between align-items-center'>
                <div class='card-title'>
                    <h1>Asset List</h1>
                </div>
                <a href="{{route('assets.create', $client->id)}}" class="btn btn-dark">Add Assets</a>
            </div>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr class="thead-dark">
                        <th>Attachments</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Make</th>
                        <th>Model</th>
                        <th>Serial Number</th>
                        <th>Purchase Price</th>
                        <th>Supplier Name</th>
                        <th>Purchase Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assets as $asset)
                        <tr>
                            <td>
                                @php
                                    $files = [];
                                    if ($asset->attached_files && is_string($asset->attached_files)) {
                                        $decoded = json_decode($asset->attached_files, true);
                                        if (is_array($decoded)) {
                                            $files = $decoded;
                                        }
                                    }
                                @endphp
                                @if(count($files) > 0)
                                    @foreach($files as $file)
                                        @if(Str::endsWith(strtolower($file), ['.jpg', '.jpeg', '.png', '.gif', '.bmp', '.webp']))
                                            <img src="{{ asset("clients/assets/{$file}") }}" alt="attachment"
                                                style="width:50px;height:50px;object-fit:contain;">
                                        @elseif(Str::endsWith(strtolower($file), ['.pdf']))
                                            <a href="{{ asset("clients/assets/{$file}") }}" target="_blank">
                                                <i class="fa fa-file-pdf-o" style="font-size:24px;color:red"></i> View PDF
                                            </a>
                                        @else
                                            <a href="{{ asset("clients/assets/{$file}") }}" target="_blank">
                                                <i class="fa fa-file" style="font-size:24px;"></i> Download File
                                            </a>
                                        @endif
                                    @endforeach
                                @else
                                    No attachments
                                @endif
                            </td>
                            <td>{{ $asset->type }}</td>
                            <td>{{ $asset->description }}</td>
                            <td>{{ $asset->make }}</td>
                            <td>{{ $asset->model }}</td>
                            <td>{{ $asset->serial_number }}</td>
                            <td>{{ $asset->purchase_price }}</td>
                            <td>{{ $asset->supplier_name }}</td>
                            <td>{{ $asset->purchase_date }}</td>
                            <td>
                                <a href="{{ route('assets.edit', [$client->id, $asset->id]) }}" class="btn btn-info">Edit</a>
                                <form action="{{ route('assets.destroy', [$client, $asset]) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection