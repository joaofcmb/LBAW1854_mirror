@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/profile.css') }}" rel="stylesheet">
@endsection

@section('title', 'Profile')

@section('body')
    <div class="navbar-dark sticky-top">
        @include('partials.main-navbar', [
            'active' => 'my profile',
            'owner' => true,
            'auth' => 'session'
        ])
        @include('partials.sub-navbar', [
            'active' => 'information',
            'owner' => true
        ])
    </div>
    <div id="main-content" class="row mx-auto align-items-center">
        <div class="col-lg-8 px-0 order-12 order-lg-1">
            <div id="content" class="container py-5 align-self-center justify-content-center">
                <div class="main-tab card border-left-0 border-right-0 rounded-0 p-2">
                    <h4>Information</h4>
                    <div id="edit-email" class="row justify-content-center text-center mt-2">
                        <div class="col-12">
                            <h6>Email</h6>
                        </div>
                        <div class="col-11 col-sm-8 col-xl-6">
                            <div class="form-group">
                                <input type="email" class="form-control text-center" name="email" id="email"
                                       aria-describedby="emailHelpId" value="{{ $user->email }}">
                            </div>
                        </div>
                    </div>
                    <div id="edit-password" class="row justify-content-center text-center mt-2">
                        <div class="col-12">
                            <h6>Password</h6>
                        </div>
                        <div class="col-11 col-sm-8 col-xl-6 pt-2">
                            <div class="form-group">
                                <input type="password" class="form-control text-center mb-2" name="old-password"
                                       id="old-password" placeholder="Old Password">
                                <input type="password" class="form-control text-center mb-2" name="new-password"
                                       id="new-password" placeholder="New Password">
                                <input type="password" class="form-control text-center" name="confirm-password"
                                       id="confirm-password" placeholder="Confirm Password">
                            </div>
                        </div>
                    </div>
                    <div id="brand-btn" class="edit-profile-info text-center">
                        <button id="{{ $user->id }}" class="btn btn-outline-secondary mx-2" type="button">
                            <a class="px-2" role="button">Save</a>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @include('partials.side-profile', [
            'user' => $user,
            'isInfo' => true
        ])
@endsection