@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <nav class="navbar navbar-toggleable-md navbar-light bg-faded">
                    <a class="btn btn-primary" href="/generate">Сгенерировать ключ для API</a>
                    <a class="small" href="/api/get_users/xml/sad">Получить список пользователей XML</a>
                    <a class="small" href="/api/get_users/json/asd">Получить список пользователей JSON</a>
                </nav>

                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>E-mail</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{$user->id}}</td>
                                    <td>
                                        <a href="/{{$user->id}}">{{$user->name}}</a>
                                    </td>
                                    <td>{{$user->email}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--<div class="card-body alert alert-danger">Login or Register to view list.</div>-->
            </div>
        </div>
@endsection