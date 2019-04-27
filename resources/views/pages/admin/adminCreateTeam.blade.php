@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/admin-teams.css') }}" rel="stylesheet">
@endsection

@section('title', 'Admin - Create Team')

@section('body')
    <div class="navbar-dark sticky-top">
        @include('partials.main-navbar', [
            'active' => 'teams',
            'auth' => 'admin'
        ])
    </div>

    <div id="menu-option" class="container-fluid mx-auto py-4">
        <div class="row justify-content-start">
            <div class="col-sm-4 ml-2">
                <a href="{{ route('admin-teams') }}"><i class="fas fa-chevron-circle-left mx-2"></i>Back</a>
            </div>
        </div>
    </div>

    <div id="search-content" class="container-fluid px-3">
        <div class="row">
            <div class="col-lg-6 px-sm-3 px-lg-5">
                <div class="card my-2">
                    <div class="card-header">
                        <h4>Users</h4>
                    </div>
                    <div class="card-body">
                        <div id="search" class="">
                            <div class="input-group">
                                <input type="text" class="form-control p-2" placeholder="Users ..."
                                       aria-label="Users..." aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary py-0" type="button" id="search-button">
                                        <a href=""> <i class="fa fa-search mr-1" aria-hidden="true"></i>Search</a>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @foreach($users as $user)
                            <div class="profile card my-3">
                                <div class="card-body p-2">
                                    <a href="{{ route('profile', ['id' => $user->id]) }}">
                                        <img src="{{ asset('img/profile.png') }}" width="50" height="50"
                                             class="d-inline-block rounded-circle align-self-center my-auto"
                                             alt="User photo">
                                        <span class="pl-4">{{ $user->username }}</span>
                                    </a>
                                    <a href="" class="float-right pt-2 pr-2">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-lg-6 px-sm-3 px-lg-5">
                <div class="card my-2">
                    <div class="card-header">
                        <h4>Team</h4>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="form-group">
                                <input type="text" class="form-control" id="teamName" placeholder="Name">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="teamSkill" placeholder="Skill">
                            </div>
                        </form>
                        <div class="text-center">
                            <h5>Leader</h5>
                        </div>
                        <div class="text-center">
                            <h5>Members</h5>
                        </div>
                        <div id="action-button" class="text-center">
                            <a href="" class="btn mt-3" role="button">Create</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="fixed-bottom pl-2">
        COPYRIGHT © EPMA 2019
    </footer>
@endsection