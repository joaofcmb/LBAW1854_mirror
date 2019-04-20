@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('register') }}">
    {{ csrf_field() }}

    <label for="name">Name</label>
    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
    @if ($errors->has('name'))
      <span class="error">
          {{ $errors->first('name') }}
      </span>
    @endif

    <label for="email">E-Mail Address</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" required>
    @if ($errors->has('email'))
      <span class="error">
          {{ $errors->first('email') }}
      </span>
    @endif

    <label for="password">Password</label>
    <input id="password" type="password" name="password" required>
    @if ($errors->has('password'))
      <span class="error">
          {{ $errors->first('password') }}
      </span>
    @endif

    <label for="password-confirm">Confirm Password</label>
    <input id="password-confirm" type="password" name="password_confirmation" required>

    <button type="submit">
      Register
    </button>
    <a class="button button-outline" href="{{ route('login') }}">Login</a>
</form>
@endsection

@section('css')
    <link href="{{ asset('css/authentication.css') }}" rel="stylesheet">
@endsection

@section('title')
    <title>EPMA - Register</title>
@endsection

@section('body')
    <body>
    <nav class="navbar sticky-top navbar-expand-lg navbar-dark">
        <a id="nav-logo" class="navbar-brand" href="{{ route('index') }}">
            <img src="{{ asset('img/logo.png') }}" width="40" height="80" class="d-inline-block align-self-center" alt="Website Logo">
            EPMA
        </a>

        <a id="authentication" class="nav-item ml-auto pl-3" href="{{ route('index') }}">
            <i class="fas fa-chevron-circle-left"></i>
        </a>
    </nav>
    <div id="home" class="container">
        <div id="auth-zone" class="container">
            <div class="row align-items-center mx-3">
                <div class="col-lg-7 mx-auto mt-3">
                    <div id="auth-title" class="row justify-content-center text-center">
                        <p>Register & <span>Make Us Proud</span></p>
                    </div>
                    <form id="register-form" method="post" action="{{ route('register-action') }}">
                        {{ csrf_field() }}
                        <div class="form-group row justify-content-center">
                            <div class="col-sm-8 pt-3">
                                <input name="username" type="text" class="form-control" placeholder="Username" value="John" required>
                            </div>
                        </div>
                        <div class="form-group row justify-content-center">
                            <div class="col-sm-8">
                                <input name="first_name" type="text" class="form-control" placeholder="First Name" value="John" required>
                            </div>
                        </div>
                        <div class="form-group row justify-content-center">
                            <div class="col-sm-8">
                                <input name="last_name" type="text" class="form-control" placeholder="Last Name" value="Doe" required>
                            </div>
                        </div>
                        <div class="form-group row justify-content-center">
                            <div class="col-sm-8">
                                <input name="email" type="email" class="form-control" placeholder="Email" value="johndoe@gmail.com"required>
                            </div>
                        </div>
                        <div class="form-group row justify-content-center">
                            <div class="col-sm-8">
                                <input name="password" type="password" class="form-control" placeholder="Password" value="1234"required>
                            </div>
                        </div>
                        <div class="form-group row justify-content-center">
                            <div class="col-sm-8">
                                <input name="password_confirmation" type="password" class="form-control" placeholder="Confirm Password" value="1234" required>
                            </div>
                            @if ($errors->has('username'))
                                <span class="error mt-3 mx-auto" style="color: red;">
                                {{ $errors->first('username') }}
                            </span>
                            @endif
                            @if ($errors->has('password'))
                                <span class="error mt-3 mx-auto" style="color: red;">
                                {{ $errors->first('password') }}
                            </span>
                            @endif
                        </div>
                        <div id="search" class="form-group row justify-content-center">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" form="register-form" type="submit" id="search-button">
                                    <a> Register </a>
                                </button>
                            </div>
                        </div>
                    </form>
                    <div id="register" class="row justify-content-center pt-2">
                        <p>Have an account ? <a href="{{ route('login') }}"><span>Sign In</span></a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="fixed-bottom pt-1 pl-2">
        <span id="copyright">COPYRIGHT © EPMA 2019</span>
    </footer>
    </body>
@endsection