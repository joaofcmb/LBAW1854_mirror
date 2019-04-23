@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/authentication.css') }}" rel="stylesheet">
@endsection

@section('title', 'Register')

@section('body')
    <body>
    @include('partials.main-navbar', [
        'items' => [],
        'auth' => 'index'
    ])
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
                                <input name="username" type="text" class="form-control" placeholder="Username" required>
                            </div>
                        </div>
                        <div class="form-group row justify-content-center">
                            <div class="col-sm-8">
                                <input name="first_name" type="text" class="form-control" placeholder="First Name" required>
                            </div>
                        </div>
                        <div class="form-group row justify-content-center">
                            <div class="col-sm-8">
                                <input name="last_name" type="text" class="form-control" placeholder="Last Name" required>
                            </div>
                        </div>
                        <div class="form-group row justify-content-center">
                            <div class="col-sm-8">
                                <input name="email" type="email" class="form-control" placeholder="Email" required>
                            </div>
                        </div>
                        <div class="form-group row justify-content-center">
                            <div class="col-sm-8">
                                <input name="password" type="password" class="form-control" placeholder="Password" required>
                            </div>
                        </div>
                        <div class="form-group row justify-content-center">
                            <div class="col-sm-8">
                                <input name="password_confirmation" type="password" class="form-control" placeholder="Confirm Password" required>
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
                        <div id="brand-btn" class="form-group row justify-content-center">
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