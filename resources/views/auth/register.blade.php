@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Register</div>
                    <div class="card-body">
                        <form method="POST" action="/register" enctype="multipart/form-data">
                            <!--csrf-->
                            <input type="hidden" value="{{ $csrf_token }}" name="csrf_token">

                            <?php $errors = $session->getFlashBag()->get('errors');?>
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>
                                <div class="col-md-6">
                                    <input id="name" type="text"
                                           class="form-control" name="name"
                                           value="@if(is_string($name = $session->getFlashBag()->get('name'))){{$name}}@endif" required autocomplete="name" autofocus>
                                    @if(array_key_exists('name', $errors))
                                        @foreach($errors['name'] as $error)
                                            <div class="alert alert-danger mt-3">{{$error}}</div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                           class="form-control" name="email"
                                           value="@if(is_string($email = $session->getFlashBag()->get('email'))){{$email}}@endif" required autocomplete="email">
                                    @if(array_key_exists('email', $errors))
                                        @foreach(($errors['email']) as $error)
                                            <div class="alert alert-danger mt-3">{{$error}}</div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                           class="form-control" name="password"
                                           required autocomplete="new-password">
                                    @if(array_key_exists('password', $errors))
                                        @foreach(($errors['password']) as $error)
                                            <div class="alert alert-danger mt-3">{{$error}}</div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="photo" class="col-md-4 col-form-label text-md-right">Photo</label>
                                <div class="col-md-6">
                                    <input name="photo" type="file" accept="jpg, .jpeg, .png" class="form-control-file"
                                           id="photo" required>
                                    @if(array_key_exists('photo', $errors))
                                        @foreach(($errors['photo']) as $error)
                                            <div class="alert alert-danger mt-3">{{$error}}</div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="captcha" class="col-md-4 col-form-label text-md-right">Captcha:</label>
                                <img class="img-fluid mb-2 ml-3" src="{{$builder->inline()}}" alt="captcha">
                                <input id="captcha" type="text" name="captcha" class="ml-2 form-control"
                                       autocomplete="off" style="width: 120px;" required>
                            </div>
                            <div class="form-group row justify-content-center">
                                @if(array_key_exists('captcha', $errors))
                                    @foreach(($errors['captcha']) as $error)
                                        <div class=" alert alert-danger col-3 mr-4">{{$error}}</div>
                                    @endforeach
                                @endif
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Register
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
