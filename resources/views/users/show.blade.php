@extends('layouts.app')

@section('content')
    <div class="container">
    <div class="row justify-content-center text-center">
        <div class="col-7">
            <div class="card">
                <div class="card-header">
                    {{ $user->email}}
                </div>
                <div class="card-body">
                    <div class="card">
                        <div class="card-body">
                            <img src="/uploads/{{$user->photo}}" alt="avatar"
                                 width="100%" height="100%">
                            <div class="col-12 mt-4">
                                Name: {{$user->name}}
                            </div>
                            <div class="col-12">
                                Account Created: {{$user->created_at}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection