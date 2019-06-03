@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/authentication.css') }}" rel="stylesheet">
@endsection

@section('title', 'Reset Password')

@section('body')
    @include('partials.main-navbar', [
      'auth' => 'authentication'
   ])

    <div id="home" class="container">
        <div id="auth-zone" class="container">
            <div class="row align-items-center mx-3">
                <div class="col-lg-6 mx-auto">
                    <div id="auth-title" class="row justify-content-center">
                        <p>Reset <span>Password</span></p>
                    </div>
                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                    <div>
                    <form id="login-form" method="POST" action="{{ route('password.email') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">

                            <div class="form-group row justify-content-center">
                                <div class="col-sm-10">
                                    <input id="email" type="email" class="form-control" name="email" placeholder="E-Mail Address" value="{{ old('email') }}" required>
                                </div>
                                @if ($errors->has('email'))
                                    <span class="help-block pt-3" style="color: red;">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div id="brand-btn" class="form-group row justify-content-center">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" form="login-form" type="submit">
                                    <a> Send Password Reset Link </a>
                                </button>
                            </div>
                        </div>
                    </form>
                    <div id="register" class="row justify-content-center">
                        <p>Having second thoughts ? <a href="{{ route('login') }}"><span>Sign In</span></a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
