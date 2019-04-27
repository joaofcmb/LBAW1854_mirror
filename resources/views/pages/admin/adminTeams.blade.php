@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
@endsection

@section('title', 'Admin - Teams')

@section('body')
    <div class="navbar-dark sticky-top">
        @include('partials.main-navbar', [
            'active' => 'teams',
            'auth' => 'admin'
        ])
    </div>

    <div id="create" class="container-fluid mx-auto mt-4">
        <div class="row justify-content-center">
            <div class="col-sm- py-2 px-3">
                <a href="{{ route('admin-create-team') }}">
                    <span>Create Team</span>
                    <i class="fas fa-plus-circle"></i>
                </a>
            </div>
        </div>
    </div>

    <div id="content" class="container">
        <div class="row justify-content-center" id="search-bar">
            <div id="search" class="col-12 col-sm-10 col-md-8 pt-4">
                <div class="input-group ">
                    <input type="text" class="form-control" placeholder="Teams..." aria-label="Teams..."
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

    <div id="search-content" class="container">
        <div class="row justify-content-center">
            @foreach($teams as $team)
                <div class="col-sm-4 my-5">
                    <div class="card text-center">
                        <div class="card-header" style="clear: both;">
                            <p id="team-name" class="m-0" style="float: left;">{{ $team->name }}</p>
                            <p class="m-0" style="float: right;">{{ $team->skill == null ? '' : $team->skill }}</p>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('profile', ['id' => $team->leader->id]) }}">
                                <p style="font-weight: bold;">{{ $team->leader->username }}</p>
                            </a>
                            <div class="mt-3">
                            @foreach($team->members as $member)
                                <a href="{{ route('profile', ['id' => $member->id]) }}">
                                    <p>{{ $member->username }}</p>
                                </a>
                            @endforeach
                            </div>
                            <a id="edit-button" href="{{ route('admin-edit-team', ['id' => $team->id]) }}" class="btn mt-3" role="button">Edit</a>
                            <a id="edit-button" href="" class="btn mt-3" role="button">Remove</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <footer class="fixed-bottom pl-2">
        COPYRIGHT © EPMA 2019
    </footer>
@endsection