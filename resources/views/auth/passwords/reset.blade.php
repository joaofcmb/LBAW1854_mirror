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
                    <form id="login-form" method="post" action="{{ route('password.request') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group row justify-content-center">
                            <div class="col-sm-10 pt-3">
                                <input id="email" type="email" class="form-control" placeholder="E-Mail Address" name="email" value="{{ $email or old('email') }}" required autofocus>
                            </div>
                            @if ($errors->has('email'))
                                <span class="help-block pt-2" style="color: red;">
                                    <strong>{{ $errors->first('email') }}
                                </span>
                            @endif
                        </div>
                        <div class="form-group row justify-content-center">
                            <div class="col-sm-10">
                                <input id="password" type="password" placeholder="Password" class="form-control" name="password" required>
                            </div>
                            @if ($errors->has('password'))
                                <span class="help-block pt-2" style="color: red;">
                                    {{ $errors->first('password') }}
                                </span>
                            @endif
                        </div>
                        <div class="form-group row justify-content-center">
                            <div class="col-sm-10">
                                <input id="password-confirm" type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation" required>
                            </div>
                            @if ($errors->has('password_confirmation'))
                                <span class="help-block pt-2" style="color: red;">
                                    {{ $errors->first('password_confirmation') }}
                                </span>
                            @endif
                        </div>
                        <div id="brand-btn" class="form-group row justify-content-center">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" form="login-form" type="submit">
                                    <a> Reset Password </a>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
