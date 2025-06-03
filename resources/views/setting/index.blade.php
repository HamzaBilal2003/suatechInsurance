@extends('layout.layout')
@section('content')
    <div class="my-4 card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Site Settings</h3>
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
            @forelse ($settings as $setting => $value)
                <form action="{{route('settings.update' ,Auth::user()->username)}}" method="POST" class="mb-3">
                    @csrf
                    <div class="d-flex align-content-center">
                        <h6 class="pr-2 text-capitalize text-nowrap">{{ str_replace('_', ' ', $value->name) }}</h6>
                        <input type="hidden" name="setting_id" value="{{$value->id}}">
                        <input type="text" class="form-control" name="value" value="{{$value->value}}" required>
                        <button type="submit" class="btn btn-primary ml-2">Update</button>
                    </div>
                </form>
            @empty
                <div class="alert alert-warning">
                    No settings found.
                </div>
            @endforelse
        </div>
    </div>
@endsection