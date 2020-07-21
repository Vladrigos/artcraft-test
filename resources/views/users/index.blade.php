@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @if($auth->isAuth())
                    <nav class="navbar navbar-toggleable-md navbar-light bg-faded">
                        @if(!$auth->getUser()->api_token)<a class="btn btn-primary" href="/generateToken">Сгенерировать
                            ключ для API</a>
                        @else
                            Your token for API end : {{$auth->getUser()->token_end}}
                        @endif
                            @if($session->getFlashBag()->get('invalidToken'))
                                <div class="alert alert-danger">Error! Invalid Token!</div>
                            @endif

                        <a class="small" href="/api/get_users/xml/{{$auth->getUser()->api_token ?? "empty"}}">Получить список
                            пользователей XML</a>
                        <a class="small" href="/api/get_users/json/{{$auth->getUser()->api_token ?? "empty"}}">Получить список
                            пользователей JSON</a>
                    </nav>

                    <div class="card">
                        <div class="card-body">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th><a class="btn-link" href="?sort=name&order={{ $swappedOrder }}">Name</a></th>
                                    <th><a class="btn-link" href="?sort=email&order={{ $swappedOrder }}">E-mail</a></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{$user->id}}</td>
                                        <td>
                                            <a href="/users/{{$user->id}}">{{$user->name}}</a>
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
            @else
                <div class="text-center mt-5">
                    You must login to view this page!
                </div>
            @endif
        </div>
@endsection