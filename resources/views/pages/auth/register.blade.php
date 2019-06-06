@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/authentication.css') }}" rel="stylesheet">
@endsection

@section('title', 'Register')

@section('body')
    @include('partials.main-navbar', [
        'auth' => 'authentication'
    ])
    <div id="home" class="container">
        <div id="auth-zone" class="container">
            <div class="row align-items-center mx-3">
                <fieldset class="col-lg-7 mx-auto mt-3">
                    <legend id="auth-title" class="row justify-content-center text-center">
                        <p>Register & <span>Make Us Proud</span></p>
                    </legend>
                    <form id="register-form" method="post" action="{{ route('register-action') }}">
                        {{ csrf_field() }}
                        <div class="form-group row justify-content-center">
                            <label class="col-sm-8 pt-3">
                                <input id="register-form-username" name="username" type="text" class="form-control" placeholder="Username" required>
                            </label>
                            @if ($errors->has('username'))
                                <span class="error mt-3 mx-auto" style="color: red;">
                                    {{ $errors->first('username') }}
                                </span>
                            @endif
                        </div>
                        <div class="form-group row justify-content-center">
                            <label class="col-sm-8">
                                <input name="first_name" type="text" class="form-control" placeholder="First Name" required>
                            </label>
                        </div>
                        <div class="form-group row justify-content-center">
                            <label class="col-sm-8">
                                <input name="last_name" type="text" class="form-control" placeholder="Last Name" required>
                            </label>
                        </div>
                        <div class="form-group row justify-content-center">
                            <label class="col-sm-8">
                                <input name="email" type="email" class="form-control" placeholder="Email" required>
                            </label>
                        </div>
                        <div class="form-group row justify-content-center">
                            <label class="col-sm-8">
                                <input name="password" type="password" class="form-control" placeholder="Password" required>
                            </label>
                        </div>
                        <div class="form-group row justify-content-center">
                            <label class="col-sm-8">
                                <input name="password_confirmation" type="password" class="form-control" placeholder="Confirm Password" required>
                            </label>
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
                </fieldset>
            </div>
        </div>
    </div>
@endsection