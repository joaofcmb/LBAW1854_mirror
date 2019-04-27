@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
@endsection

@section('title', 'Admin - Users')

@section('body')
    <div class="navbar-dark sticky-top">
        @include('partials.main-navbar', [
            'active' => 'users',
            'auth' => 'admin'
        ])
    </div>

    <div id="content" class="container">
        <div class="row justify-content-center" id="search-bar">
            <div id="search" class="col-12 col-sm-10 col-md-7 py-4">
                <div class="input-group ">
                    <input type="text" class="form-control" placeholder="Users..." aria-label="Users or Projects ..."
                           aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="search-button">
                            <a href=""> <i class="fa fa-search" aria-hidden="true"></i> Search</a>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="search-content" class="container mt-3">
        @foreach($users as $user)
            <div class="row justify-content-center pb-4">
                <div class="col-11 col-md-8 ali">
                    @if(!$user->is_active)
                        <div class="restore card">
                    @else
                        <div class="card">
                    @endif
                        <div class="card-body p-2">
                            @if($user->is_active)
                                <a href="{{ route('profile', ['id' => $user->id_user]) }}">
                            @endif
                                <img src="{{ asset('img/profile.png') }}" width="50" height="50"
                                     class="d-inline-block rounded-circle align-self-center my-auto" alt="User photo">
                                <span class="pl-4">{{ $user->username }}</span>
                            @if($user->is_active)
                                </a>
                            @endif
                            <a href="" class="float-right pt-2 pr-2">
                                @if(!$user->is_active)
                                    <span>Restore</span>
                                    <i class="fas fa-trash-restore"></i>
                                @else
                                    <i class="fas fa-times"></i>
                                @endif
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <footer class="fixed-bottom pl-2">
        COPYRIGHT © EPMA 2019
    </footer>
@endsection