@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/authentication.css') }}" rel="stylesheet">
@endsection

@section('title', Login)

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
                <div class="col-lg-6 mx-auto">
                    <div id="auth-title" class="row justify-content-center">
                        <p>Sign in & <span>Start Working</span></p>
                    </div>
                    <form id="login-form" method="post" action="{{ route('login-action') }}">
                        {{ csrf_field() }}
                        <div class="form-group row justify-content-center">
                            <div class="col-sm-10 pt-3">
                                <input name="username" type="text" class="form-control" placeholder="Username" required>
                            </div>
                        </div>
                        <div class="form-group row justify-content-center">
                            <div class="col-sm-10">
                                <input name="password" type="password" class="form-control" placeholder="Password" required>
                            </div>
                            @if ($errors->has('username'))
                                <span class="error mt-4 mx-auto" style="color: red;">
                                {{ $errors->first('username') }}
                            </span>
                            @endif
                        </div>
                        <div id="search" class="form-group row justify-content-center">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" form="login-form" type="submit" id="search-button">
                                    <a> Login </a>
                                </button>
                            </div>
                        </div>
                        <div class="sep-bar row justify-content-center">
                            <div class="col-3 col-sm-4 mx-3">
                            </div>
                            <div class="col-sm-">
                                or
                            </div>
                            <div class="col-3 col-sm-4 mx-3">
                            </div>
                        </div>
                        <div id="search" class="form-group row justify-content-center">
                            <div class="col-10 col-md-9 mt-3 px-3 text-center">
                                <button class="btn btn-outline-secondary mx-2" type="submit" id="search-button">
                                    <a href="#">
                                        <img src="{{ asset('img/github-logo.png') }}" width="25" height="25"
                                             class="d-inline-block align-self-center" alt="GitHub Logo">
                                        GitHub</a>
                                </button>
                                <button class="btn btn-outline-secondary mx-2" type="button" id="search-button">
                                    <a href="#">
                                        <img src="{{ asset('img/gitlab-logo.png') }}" width="25" height="25"
                                             class="d-inline-block align-self-center" alt="GitLab Logo">
                                        GitLab</a>
                                </button>
                            </div>
                        </div>
                    </form>
                    <div id="register" class="row justify-content-center pt-3">
                        <p>Don’t have an account ? <a href="{{ route('register') }}"><span>Sign Up</span></a></p>
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
